<?php
/**
 * H&T Ad Manager - Professional Ad Insertion System
 * 
 * Supports raw HTML ad codes (Google AdSense, GPT, Prebid, custom HTML).
 * Admin-only input — no content sanitization stripping.
 * 
 * Placement engine supports:
 *   - Single paragraph:   "3"       → after paragraph 3
 *   - CSV list:           "3,6,9"   → after paragraphs 3, 6, 9
 *   - Repeat pattern:     "%4"      → after every 4th paragraph
 *
 * @package HTG
 * @since 2.3.0
 * @updated 3.0.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =====================================================================
   CORE: Get / Display Ad Code
   ===================================================================== */

/**
 * Get ad code for a specific slot.
 *
 * Ad codes are entered by admins only — output unescaped so that
 * <script>, <ins>, <iframe>, GPT, Prebid, and arbitrary HTML all work.
 *
 * @param string $slot  Option key suffix (e.g. 'header_above').
 * @param bool   $wrap  Wrap in container div with optional label.
 * @return string       Raw HTML.
 */
function HTG_get_ad_code( $slot, $wrap = true ) {
	$ad_code = get_option( 'HTG_ad_' . $slot, '' );

	if ( empty( trim( $ad_code ) ) ) {
		return '';
	}

	// Global codes (head/footer) — raw, no wrapper
	if ( in_array( $slot, array( 'head_code', 'footer_code' ), true ) || ! $wrap ) {
		return $ad_code;
	}

	$output = '<div class="htg-ad htg-ad-' . esc_attr( $slot ) . '">';

	if ( (bool) get_option( 'HTG_ad_labels_enable', 1 ) ) {
		$output .= '<span class="htg-ad-label">' . esc_html__( 'Advertisement', 'adtech-pro' ) . '</span>';
	}

	$output .= '<div class="htg-ad-content">' . $ad_code . '</div></div>';

	return $output;
}

/**
 * Echo ad code for a slot.
 */
function HTG_display_ad( $slot ) {
	echo HTG_get_ad_code( $slot ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- admin-only ad HTML
}

/* =====================================================================
   HOOKS: Inject ads into template positions
   ===================================================================== */

/**
 * Head code — analytics, ad-network base tags, verification
 */
function HTG_inject_head_code() {
	$code = get_option( 'HTG_ad_head_code', '' );
	if ( ! empty( $code ) ) {
		echo "\n<!-- H&T Ad Manager: Head -->\n" . $code . "\n"; // phpcs:ignore
	}
}
add_action( 'wp_head', 'HTG_inject_head_code', 1 );

/**
 * Footer code — chat widgets, tracking pixels
 */
function HTG_inject_footer_code() {
	$code = get_option( 'HTG_ad_footer_code', '' );
	if ( ! empty( $code ) ) {
		echo "\n<!-- H&T Ad Manager: Footer -->\n" . $code . "\n"; // phpcs:ignore
	}
}
add_action( 'wp_footer', 'HTG_inject_footer_code', 99 );

/**
 * Header ads — above & below header
 */
function HTG_inject_header_above_ad() {
	if ( is_admin() ) return;
	HTG_display_ad( 'header_above' );
}
add_action( 'HTG_before_header', 'HTG_inject_header_above_ad' );

function HTG_inject_header_below_ad() {
	if ( is_admin() ) return;
	HTG_display_ad( 'header_below' );
}
add_action( 'HTG_after_header', 'HTG_inject_header_below_ad' );

/**
 * Before / After single post content
 */
function HTG_inject_before_content_ad() {
	if ( ! is_singular( 'post' ) ) return;
	HTG_display_ad( 'before_content' );
}
add_action( 'HTG_before_single_post_content', 'HTG_inject_before_content_ad' );

function HTG_inject_after_content_ad() {
	if ( ! is_singular( 'post' ) ) return;
	HTG_display_ad( 'after_content' );
}
add_action( 'HTG_after_single_post_content', 'HTG_inject_after_content_ad' );

/* =====================================================================
   IN-ARTICLE PLACEMENT ENGINE
   ===================================================================== */

/**
 * Parse a placement rule string into an array of paragraph positions.
 *
 * Syntax:
 *   "5"       → [5]
 *   "3,6,9"   → [3, 6, 9]
 *   "%4"      → [4, 8, 12, 16, ...] (expanded to total paragraphs)
 *
 * @param string $rule  Placement rule.
 * @param int    $total Total paragraphs in the post.
 * @return int[]        Sorted unique paragraph numbers (1-indexed).
 */
function HTG_parse_placement_rule( $rule, $total ) {
	$rule = trim( $rule );
	$positions = array();

	if ( empty( $rule ) || $total < 1 ) {
		return $positions;
	}

	// Repeat pattern: %X
	if ( strpos( $rule, '%' ) === 0 ) {
		$interval = absint( substr( $rule, 1 ) );
		if ( $interval < 1 ) {
			return $positions;
		}
		for ( $p = $interval; $p <= $total; $p += $interval ) {
			$positions[] = $p;
		}
		return $positions;
	}

	// CSV or single: "3" or "3,6,9"
	$parts = explode( ',', $rule );
	foreach ( $parts as $part ) {
		$num = absint( trim( $part ) );
		if ( $num >= 1 && $num <= $total ) {
			$positions[] = $num;
		}
	}

	$positions = array_unique( $positions );
	sort( $positions );
	return $positions;
}

/**
 * Auto-insert in-article ads into post content.
 *
 * Supports 3 independent ad slots, each with its own placement rule.
 *
 * @param string $content Post content HTML.
 * @return string         Modified content with ads inserted.
 */
function HTG_auto_insert_content_ads( $content ) {
	if ( ! is_singular( 'post' ) || ! is_main_query() || is_admin() ) {
		return $content;
	}

	// Split by closing </p> tags
	$paragraphs = explode( '</p>', $content );
	$total = count( $paragraphs );

	// Don't count the last empty fragment after the final </p>
	if ( isset( $paragraphs[ $total - 1 ] ) && trim( $paragraphs[ $total - 1 ] ) === '' ) {
		$total_real = $total - 1;
	} else {
		$total_real = $total;
	}

	if ( $total_real < 2 ) {
		return $content;
	}

	// Collect all insertions: position => array of ad HTML strings
	$insertions = array();

	for ( $slot = 1; $slot <= 3; $slot++ ) {
		$ad_code = get_option( 'HTG_ad_in_article_' . $slot, '' );
		$rule    = get_option( 'HTG_ad_in_article_' . $slot . '_position', '' );

		if ( empty( trim( $ad_code ) ) || empty( trim( $rule ) ) ) {
			continue;
		}

		$positions = HTG_parse_placement_rule( $rule, $total_real );

		// Build ad HTML
		$ad_html = '<div class="htg-ad htg-ad-in-article htg-ad-in-article-' . $slot . '">';
		if ( (bool) get_option( 'HTG_ad_labels_enable', 1 ) ) {
			$ad_html .= '<span class="htg-ad-label">' . esc_html__( 'Advertisement', 'adtech-pro' ) . '</span>';
		}
		$ad_html .= '<div class="htg-ad-content">' . $ad_code . '</div></div>';

		foreach ( $positions as $pos ) {
			if ( ! isset( $insertions[ $pos ] ) ) {
				$insertions[ $pos ] = array();
			}
			$insertions[ $pos ][] = $ad_html;
		}
	}

	if ( empty( $insertions ) ) {
		return $content;
	}

	// Rebuild content with ads injected
	$new_content = '';
	for ( $i = 0; $i < $total; $i++ ) {
		$new_content .= $paragraphs[ $i ];
		if ( $i < $total - 1 ) {
			$new_content .= '</p>';
		}

		// Paragraph number is 1-indexed: after paragraph ($i + 1)
		$para_num = $i + 1;
		if ( isset( $insertions[ $para_num ] ) ) {
			foreach ( $insertions[ $para_num ] as $ad ) {
				$new_content .= "\n" . $ad . "\n";
			}
		}
	}

	return $new_content;
}
add_filter( 'the_content', 'HTG_auto_insert_content_ads', 20 );

/* =====================================================================
   ADMIN: Menu & Settings Page
   ===================================================================== */

/**
 * Register admin submenu
 */
function HTG_ad_manager_admin_menu() {
	add_submenu_page(
		'HTG-dashboard',
		__( 'Ad Manager', 'adtech-pro' ),
		__( 'Ad Manager', 'adtech-pro' ),
		'manage_options',
		'HTG-simple-ads',
		'HTG_render_ad_manager_page'
	);
}
add_action( 'admin_menu', 'HTG_ad_manager_admin_menu', 20 );

/**
 * Render Ad Manager admin page
 */
function HTG_render_ad_manager_page() {

	// ── Save Handler ──
	if ( isset( $_POST['HTG_save_ads'] ) && check_admin_referer( 'HTG_ad_manager_nonce' ) && current_user_can( 'manage_options' ) ) {

		// Global settings
		update_option( 'HTG_ad_labels_enable', isset( $_POST['HTG_ad_labels_enable'] ) ? 1 : 0 );

		// All ad code fields — store raw HTML (admin-only, unslash to undo WP magic quotes)
		$text_fields = array(
			'HTG_ad_head_code',
			'HTG_ad_footer_code',
			'HTG_ad_header_above',
			'HTG_ad_header_below',
			'HTG_ad_before_content',
			'HTG_ad_after_content',
			'HTG_ad_sidebar_top',
			'HTG_ad_sidebar_sticky',
			'HTG_ad_homepage_top',
			'HTG_ad_before_footer',
		);

		foreach ( $text_fields as $field ) {
			update_option( $field, wp_unslash( $_POST[ $field ] ?? '' ) );
		}

		// In-article slots (1-3)
		for ( $s = 1; $s <= 3; $s++ ) {
			update_option( 'HTG_ad_in_article_' . $s, wp_unslash( $_POST[ 'HTG_ad_in_article_' . $s ] ?? '' ) );
			update_option(
				'HTG_ad_in_article_' . $s . '_position',
				sanitize_text_field( $_POST[ 'HTG_ad_in_article_' . $s . '_position' ] ?? '' )
			);
		}

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'adtech-pro' ) . '</p></div>';
	}

	// ── Read values ──
	$labels_enabled = get_option( 'HTG_ad_labels_enable', 1 );
	?>
	<div class="wrap">
		<h1 class="htg-page-title">
			<span class="dashicons dashicons-megaphone"></span>
			<?php esc_html_e( 'Ad Manager', 'adtech-pro' ); ?>
		</h1>
		<p class="htg-page-desc"><?php esc_html_e( 'Manage ad placements and tracking codes. All fields accept raw HTML — paste Google AdSense, GPT tags, Prebid, or any ad code.', 'adtech-pro' ); ?></p>

		<form method="post" action="">
			<?php wp_nonce_field( 'HTG_ad_manager_nonce' ); ?>

			<div class="htg-ad-layout">

				<!-- ═══════════════════ Global Settings ═══════════════════ -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2><?php esc_html_e( 'Settings', 'adtech-pro' ); ?></h2>
					</div>
					<div class="htg-section-body">
						<label class="htg-checkbox-row">
							<input type="checkbox" name="HTG_ad_labels_enable" value="1" <?php checked( $labels_enabled, 1 ); ?>>
							<span><?php esc_html_e( 'Show "Advertisement" label above ads', 'adtech-pro' ); ?></span>
						</label>
					</div>
				</div>

				<!-- ═══════════════════ Global Codes ═══════════════════ -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-editor-code"></span>
							<?php esc_html_e( 'Global Codes', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Scripts injected in header or footer (analytics, ad network base code, verification tags)', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body htg-two-col">
						<div class="htg-field">
							<label><?php esc_html_e( 'Header Code', 'adtech-pro' ); ?> <code>&lt;head&gt;</code></label>
							<textarea name="HTG_ad_head_code" rows="6" placeholder="<?php esc_attr_e( 'Google Analytics, AdSense auto ads, GPT base code...', 'adtech-pro' ); ?>"><?php echo esc_textarea( get_option( 'HTG_ad_head_code', '' ) ); ?></textarea>
						</div>
						<div class="htg-field">
							<label><?php esc_html_e( 'Footer Code', 'adtech-pro' ); ?> <code>&lt;/body&gt;</code></label>
							<textarea name="HTG_ad_footer_code" rows="6" placeholder="<?php esc_attr_e( 'Chat widgets, tracking pixels, Prebid config...', 'adtech-pro' ); ?>"><?php echo esc_textarea( get_option( 'HTG_ad_footer_code', '' ) ); ?></textarea>
						</div>
					</div>
				</div>

				<!-- ═══════════════════ Header Ads ═══════════════════ -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-arrow-up-alt"></span>
							<?php esc_html_e( 'Header Ads', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Ads displayed above and below the site header', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body htg-two-col">
						<div class="htg-field">
							<label><?php esc_html_e( 'Above Header', 'adtech-pro' ); ?> <span class="htg-size">728×90</span></label>
							<textarea name="HTG_ad_header_above" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_header_above', '' ) ); ?></textarea>
							<p class="htg-help"><?php esc_html_e( 'Hooks into HTG_before_header action.', 'adtech-pro' ); ?></p>
						</div>
						<div class="htg-field">
							<label><?php esc_html_e( 'Below Header', 'adtech-pro' ); ?> <span class="htg-size">728×90 / 970×90</span></label>
							<textarea name="HTG_ad_header_below" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_header_below', '' ) ); ?></textarea>
							<p class="htg-help"><?php esc_html_e( 'Hooks into HTG_after_header action.', 'adtech-pro' ); ?></p>
						</div>
					</div>
				</div>

				<!-- ═══════════════════ Article Ads ═══════════════════ -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-media-text"></span>
							<?php esc_html_e( 'Article Ads', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Ads displayed before, after, or within article content on single posts', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body">

						<!-- Before / After Article -->
						<div class="htg-two-col">
							<div class="htg-field">
								<label><?php esc_html_e( 'Before Article', 'adtech-pro' ); ?> <span class="htg-size">728×90</span></label>
								<textarea name="HTG_ad_before_content" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_before_content', '' ) ); ?></textarea>
								<p class="htg-help"><?php esc_html_e( 'Hooks into HTG_before_single_post_content action.', 'adtech-pro' ); ?></p>
							</div>
							<div class="htg-field">
								<label><?php esc_html_e( 'After Article', 'adtech-pro' ); ?> <span class="htg-size">728×90 / 336×280</span></label>
								<textarea name="HTG_ad_after_content" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_after_content', '' ) ); ?></textarea>
								<p class="htg-help"><?php esc_html_e( 'Hooks into HTG_after_single_post_content action.', 'adtech-pro' ); ?></p>
							</div>
						</div>

						<!-- In-Article Slots 1-3 -->
						<div class="htg-placement-info">
							<h3><?php esc_html_e( 'In-Article Ads (Auto-Inserted by Paragraph)', 'adtech-pro' ); ?></h3>
							<p><?php esc_html_e( 'Up to 3 independent ad slots that auto-insert into post content. Each slot has its own placement rule.', 'adtech-pro' ); ?></p>
							<div class="htg-placement-syntax">
								<strong><?php esc_html_e( 'Placement Syntax:', 'adtech-pro' ); ?></strong>
								<ul>
									<li><code>3</code> — <?php esc_html_e( 'Insert after paragraph 3', 'adtech-pro' ); ?></li>
									<li><code>3,6,9</code> — <?php esc_html_e( 'Insert after paragraphs 3, 6, and 9', 'adtech-pro' ); ?></li>
									<li><code>%4</code> — <?php esc_html_e( 'Repeat after every 4th paragraph (4, 8, 12, 16...)', 'adtech-pro' ); ?></li>
								</ul>
							</div>
						</div>

						<?php for ( $slot = 1; $slot <= 3; $slot++ ) :
							$ad_code  = get_option( 'HTG_ad_in_article_' . $slot, '' );
							$position = get_option( 'HTG_ad_in_article_' . $slot . '_position', '' );
							$slot_labels = array(
								1 => __( 'In-Article Slot 1', 'adtech-pro' ),
								2 => __( 'In-Article Slot 2', 'adtech-pro' ),
								3 => __( 'In-Article Slot 3', 'adtech-pro' ),
							);
							$slot_hints = array(
								1 => '3',
								2 => '%5',
								3 => '3,7,12',
							);
						?>
						<div class="htg-field htg-field-highlight htg-slot-<?php echo $slot; ?>">
							<label>
								<?php echo esc_html( $slot_labels[ $slot ] ); ?>
								<span class="htg-size"><?php esc_html_e( 'In-article / 336×280 / Responsive', 'adtech-pro' ); ?></span>
							</label>
							<div class="htg-inline-option">
								<span><?php esc_html_e( 'Placement rule:', 'adtech-pro' ); ?></span>
								<input type="text" name="HTG_ad_in_article_<?php echo $slot; ?>_position" value="<?php echo esc_attr( $position ); ?>" placeholder="<?php echo esc_attr( $slot_hints[ $slot ] ); ?>" style="width: 120px;" class="htg-placement-input">
								<span class="htg-placement-preview" data-slot="<?php echo $slot; ?>"></span>
							</div>
							<textarea name="HTG_ad_in_article_<?php echo $slot; ?>" rows="5" placeholder="<?php esc_attr_e( 'Paste ad code (HTML/JS) for this slot...', 'adtech-pro' ); ?>"><?php echo esc_textarea( $ad_code ); ?></textarea>
						</div>
						<?php endfor; ?>
					</div>
				</div>

				<!-- ═══════════════════ Sidebar Ads ═══════════════════ -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-align-right"></span>
							<?php esc_html_e( 'Sidebar Ads', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Ads displayed in the sidebar widget area', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body htg-two-col">
						<div class="htg-field">
							<label><?php esc_html_e( 'Sidebar Top', 'adtech-pro' ); ?> <span class="htg-size">300×250</span></label>
							<textarea name="HTG_ad_sidebar_top" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_sidebar_top', '' ) ); ?></textarea>
						</div>
						<div class="htg-field">
							<label><?php esc_html_e( 'Sidebar Sticky', 'adtech-pro' ); ?> <span class="htg-size">300×250 / 300×600</span></label>
							<textarea name="HTG_ad_sidebar_sticky" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_sidebar_sticky', '' ) ); ?></textarea>
							<p class="htg-help"><?php esc_html_e( 'This ad sticks to the viewport as users scroll.', 'adtech-pro' ); ?></p>
						</div>
					</div>
				</div>

				<!-- ═══════════════════ Homepage & Footer ═══════════════════ -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-admin-home"></span>
							<?php esc_html_e( 'Homepage & Footer', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Ads for homepage and footer areas', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body htg-two-col">
						<div class="htg-field">
							<label><?php esc_html_e( 'Homepage Top', 'adtech-pro' ); ?> <span class="htg-size">970×250 / 728×90</span></label>
							<textarea name="HTG_ad_homepage_top" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_homepage_top', '' ) ); ?></textarea>
							<p class="htg-help"><?php esc_html_e( 'Displays below the slider on homepage.', 'adtech-pro' ); ?></p>
						</div>
						<div class="htg-field">
							<label><?php esc_html_e( 'Before Footer', 'adtech-pro' ); ?> <span class="htg-size">728×90 / 970×90</span></label>
							<textarea name="HTG_ad_before_footer" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_before_footer', '' ) ); ?></textarea>
						</div>
					</div>
				</div>

				<!-- Save Button -->
				<div class="htg-save-bar">
					<button type="submit" name="HTG_save_ads" class="button button-primary button-hero">
						<?php esc_html_e( 'Save All Changes', 'adtech-pro' ); ?>
					</button>
				</div>

			</div>
		</form>
	</div>

	<style>
	.htg-page-title {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 28px;
		font-weight: 600;
		margin-bottom: 5px;
	}
	.htg-page-title .dashicons {
		font-size: 28px;
		width: 28px;
		height: 28px;
		color: #1a1f36;
	}
	.htg-page-desc {
		color: #64748b;
		font-size: 14px;
		margin: 0 0 25px;
	}

	.htg-ad-layout {
		max-width: 1000px;
	}

	.htg-ad-section {
		background: #fff;
		border: 1px solid #e2e8f0;
		border-radius: 8px;
		margin-bottom: 20px;
		overflow: hidden;
	}

	.htg-section-header {
		padding: 20px 25px;
		border-bottom: 1px solid #e2e8f0;
		background: #f8fafc;
	}
	.htg-section-header h2 {
		display: flex;
		align-items: center;
		gap: 10px;
		font-size: 16px;
		font-weight: 600;
		margin: 0 0 5px;
		color: #1e293b;
	}
	.htg-section-header h2 .dashicons {
		color: #64748b;
		font-size: 20px;
		width: 20px;
		height: 20px;
	}
	.htg-section-header p {
		margin: 0;
		color: #64748b;
		font-size: 13px;
	}

	.htg-section-body {
		padding: 25px;
	}

	.htg-two-col {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 25px;
	}

	.htg-field {
		margin-bottom: 20px;
	}
	.htg-field:last-child {
		margin-bottom: 0;
	}

	.htg-field label {
		display: flex;
		align-items: center;
		gap: 10px;
		font-weight: 600;
		font-size: 13px;
		color: #334155;
		margin-bottom: 8px;
	}
	.htg-field label code {
		background: #e2e8f0;
		padding: 2px 6px;
		border-radius: 3px;
		font-size: 11px;
	}

	.htg-size {
		font-weight: 400;
		color: #94a3b8;
		font-size: 11px;
		background: #f1f5f9;
		padding: 3px 8px;
		border-radius: 4px;
	}

	.htg-field textarea {
		width: 100%;
		font-family: 'SF Mono', Monaco, 'Courier New', monospace;
		font-size: 12px;
		line-height: 1.5;
		padding: 12px 15px;
		border: 1px solid #e2e8f0;
		border-radius: 6px;
		background: #f8fafc;
		resize: vertical;
		transition: all 0.2s;
	}
	.htg-field textarea:focus {
		outline: none;
		border-color: #00d4aa;
		background: #fff;
		box-shadow: 0 0 0 3px rgba(0,212,170,0.1);
	}

	/* ── In-Article Slot Highlight ── */
	.htg-field-highlight {
		padding: 20px;
		border-radius: 8px;
		margin-top: 20px;
	}
	.htg-slot-1 {
		background: #f0fdf4;
		border: 1px solid #bbf7d0;
	}
	.htg-slot-2 {
		background: #eff6ff;
		border: 1px solid #bfdbfe;
	}
	.htg-slot-3 {
		background: #fefce8;
		border: 1px solid #fde68a;
	}

	/* ── Placement Syntax Guide ── */
	.htg-placement-info {
		margin-top: 25px;
		padding: 20px;
		background: #1e293b;
		border-radius: 8px;
		color: #e2e8f0;
	}
	.htg-placement-info h3 {
		margin: 0 0 8px;
		font-size: 15px;
		font-weight: 600;
		color: #fff;
	}
	.htg-placement-info > p {
		margin: 0 0 12px;
		font-size: 13px;
		color: #94a3b8;
	}
	.htg-placement-syntax {
		font-size: 13px;
	}
	.htg-placement-syntax strong {
		color: #00d4aa;
	}
	.htg-placement-syntax ul {
		margin: 8px 0 0 0;
		padding: 0;
		list-style: none;
	}
	.htg-placement-syntax li {
		padding: 4px 0;
	}
	.htg-placement-syntax code {
		background: rgba(0,212,170,0.15);
		color: #00d4aa;
		padding: 2px 8px;
		border-radius: 4px;
		font-size: 13px;
		font-weight: 600;
	}

	.htg-inline-option {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 12px;
		font-size: 13px;
		color: #475569;
	}
	.htg-placement-input {
		padding: 6px 10px;
		border: 1px solid #d1d5db;
		border-radius: 4px;
		font-size: 14px;
		font-weight: 600;
		font-family: 'SF Mono', Monaco, 'Courier New', monospace;
	}

	.htg-help {
		margin: 8px 0 0;
		font-size: 12px;
		color: #64748b;
	}

	.htg-checkbox-row {
		display: flex;
		align-items: center;
		gap: 10px;
		cursor: pointer;
		font-size: 14px;
	}
	.htg-checkbox-row input[type="checkbox"] {
		width: 18px;
		height: 18px;
		accent-color: #00d4aa;
	}

	.htg-save-bar {
		padding: 20px 0;
	}
	.htg-save-bar .button-hero {
		padding: 12px 40px;
		height: auto;
		font-size: 14px;
	}

	@media (max-width: 782px) {
		.htg-two-col {
			grid-template-columns: 1fr;
		}
	}
	</style>
	<?php
}

/* =====================================================================
   FRONTEND: Ad container styles
   ===================================================================== */

/**
 * Output minimal frontend ad styles
 */
function HTG_ad_frontend_styles() {
	?>
	<style>
	.htg-ad {
		margin: 25px 0;
		text-align: center;
		clear: both;
	}
	.htg-ad-label {
		display: block;
		font-size: 10px;
		text-transform: uppercase;
		letter-spacing: 1px;
		color: #94a3b8;
		margin-bottom: 8px;
	}
	.htg-ad-content {
		display: flex;
		justify-content: center;
	}
	.htg-ad-in-article {
		margin: 30px auto;
		max-width: 100%;
	}
	</style>
	<?php
}
add_action( 'wp_head', 'HTG_ad_frontend_styles' );

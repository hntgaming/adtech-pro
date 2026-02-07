<?php
/**
 * H&T Ad Manager - Professional Ad Insertion System
 * 
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize ad code
 */
function HTG_sanitize_ad_code( $ad_code ) {
	// Always sanitize with ad-specific allowed HTML (never bypass)
	$allowed_html = array(
		'script' => array(
			'async' => true, 'src' => true, 'defer' => true,
			'data-ad-client' => true, 'data-ad-slot' => true,
			'data-ad-format' => true, 'data-full-width-responsive' => true,
			'crossorigin' => true, 'type' => true, 'id' => true, 'class' => true,
		),
		'ins' => array(
			'class' => true, 'style' => true,
			'data-ad-client' => true, 'data-ad-slot' => true,
			'data-ad-format' => true, 'data-ad-layout' => true,
			'data-ad-layout-key' => true, 'data-full-width-responsive' => true,
		),
		'div' => array( 'id' => true, 'class' => true, 'style' => true ),
		'iframe' => array(
			'src' => true, 'width' => true, 'height' => true,
			'frameborder' => true, 'scrolling' => true, 'style' => true,
			'allow' => true, 'loading' => true, 'title' => true,
		),
		'a' => array( 'href' => true, 'target' => true, 'rel' => true, 'class' => true ),
		'img' => array( 'src' => true, 'alt' => true, 'width' => true, 'height' => true, 'loading' => true ),
		'span' => array( 'class' => true, 'style' => true ),
		'noscript' => array(),
		'style' => array( 'type' => true ),
	);
	
	return wp_kses( $ad_code, $allowed_html );
}

/**
 * Get ad code for a specific slot
 */
function HTG_get_ad_code( $slot, $wrap = true ) {
	$ad_code = get_option( 'HTG_ad_' . $slot, '' );
	
	if ( empty( $ad_code ) ) {
		return '';
	}
	
	// Raw output for global codes
	if ( in_array( $slot, array( 'head_code', 'footer_code' ), true ) || ! $wrap ) {
		return HTG_sanitize_ad_code( $ad_code );
	}
	
	$output = '<div class="htg-ad htg-ad-' . esc_attr( $slot ) . '">';
	
	if ( get_option( 'HTG_ad_labels_enable', 1 ) ) {
		$output .= '<span class="htg-ad-label">' . esc_html__( 'Advertisement', 'adtech-pro' ) . '</span>';
	}
	
	$output .= '<div class="htg-ad-content">' . HTG_sanitize_ad_code( $ad_code ) . '</div></div>';
	
	return $output;
}

/**
 * Display ad
 */
function HTG_display_ad( $slot ) {
	echo HTG_get_ad_code( $slot );
}

/**
 * Inject head code
 */
function HTG_inject_head_code() {
	$code = get_option( 'HTG_ad_head_code', '' );
	if ( ! empty( $code ) ) {
		echo "\n<!-- H&T Ad Manager -->\n" . HTG_sanitize_ad_code( $code ) . "\n";
	}
}
add_action( 'wp_head', 'HTG_inject_head_code', 1 );

/**
 * Inject footer code
 */
function HTG_inject_footer_code() {
	$code = get_option( 'HTG_ad_footer_code', '' );
	if ( ! empty( $code ) ) {
		echo "\n<!-- H&T Ad Manager -->\n" . HTG_sanitize_ad_code( $code ) . "\n";
	}
}
add_action( 'wp_footer', 'HTG_inject_footer_code', 99 );

/**
 * Auto-insert ads into post content
 */
function HTG_auto_insert_content_ads( $content ) {
	if ( ! is_singular( 'post' ) || ! is_main_query() || is_admin() ) {
		return $content;
	}
	
	// Get in-article ad settings
	$ad_code = get_option( 'HTG_ad_in_article', '' );
	$insert_after = absint( get_option( 'HTG_ad_in_article_position', 3 ) );
	
	if ( empty( $ad_code ) || $insert_after < 1 ) {
		return $content;
	}
	
	// Split by paragraphs
	$paragraphs = explode( '</p>', $content );
	$total = count( $paragraphs );
	
	if ( $total < $insert_after + 1 ) {
		return $content;
	}
	
	// Build ad HTML
	$ad_html = '<div class="htg-ad htg-ad-in-article">';
	if ( get_option( 'HTG_ad_labels_enable', 1 ) ) {
		$ad_html .= '<span class="htg-ad-label">' . esc_html__( 'Advertisement', 'adtech-pro' ) . '</span>';
	}
	$ad_html .= '<div class="htg-ad-content">' . HTG_sanitize_ad_code( $ad_code ) . '</div></div>';
	
	// Insert after specified paragraph
	$new_content = '';
	for ( $i = 0; $i < $total; $i++ ) {
		$new_content .= $paragraphs[ $i ];
		if ( $i < $total - 1 ) {
			$new_content .= '</p>';
		}
		if ( $i + 1 === $insert_after ) {
			$new_content .= "\n" . $ad_html . "\n";
		}
	}
	
	return $new_content;
}
add_filter( 'the_content', 'HTG_auto_insert_content_ads', 20 );

/**
 * Register admin menu
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
 * Render Ad Manager page
 */
function HTG_render_ad_manager_page() {
	// Handle save
	if ( isset( $_POST['HTG_save_ads'] ) && check_admin_referer( 'HTG_ad_manager_nonce' ) ) {
		// Global settings
		update_option( 'HTG_ad_labels_enable', isset( $_POST['HTG_ad_labels_enable'] ) ? 1 : 0 );
		
		// Global codes - sanitize with wp_unslash before storing
		update_option( 'HTG_ad_head_code', wp_unslash( $_POST['HTG_ad_head_code'] ?? '' ) );
		update_option( 'HTG_ad_footer_code', wp_unslash( $_POST['HTG_ad_footer_code'] ?? '' ) );
		
		// Header ads
		update_option( 'HTG_ad_header_above', wp_unslash( $_POST['HTG_ad_header_above'] ?? '' ) );
		update_option( 'HTG_ad_header_below', wp_unslash( $_POST['HTG_ad_header_below'] ?? '' ) );
		
		// Content ads
		update_option( 'HTG_ad_before_content', wp_unslash( $_POST['HTG_ad_before_content'] ?? '' ) );
		update_option( 'HTG_ad_after_content', wp_unslash( $_POST['HTG_ad_after_content'] ?? '' ) );
		
		// In-article
		update_option( 'HTG_ad_in_article', wp_unslash( $_POST['HTG_ad_in_article'] ?? '' ) );
		update_option( 'HTG_ad_in_article_position', absint( $_POST['HTG_ad_in_article_position'] ?? 3 ) );
		
		// Sidebar
		update_option( 'HTG_ad_sidebar_top', wp_unslash( $_POST['HTG_ad_sidebar_top'] ?? '' ) );
		update_option( 'HTG_ad_sidebar_sticky', wp_unslash( $_POST['HTG_ad_sidebar_sticky'] ?? '' ) );
		
		// Homepage
		update_option( 'HTG_ad_homepage_top', wp_unslash( $_POST['HTG_ad_homepage_top'] ?? '' ) );
		
		// Footer
		update_option( 'HTG_ad_before_footer', wp_unslash( $_POST['HTG_ad_before_footer'] ?? '' ) );
		
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'adtech-pro' ) . '</p></div>';
	}
	
	// Get values
	$labels_enabled = get_option( 'HTG_ad_labels_enable', 1 );
	$in_article_position = get_option( 'HTG_ad_in_article_position', 3 );
	?>
	<div class="wrap">
		<h1 class="htg-page-title">
			<span class="dashicons dashicons-megaphone"></span>
			<?php esc_html_e( 'Ad Manager', 'adtech-pro' ); ?>
		</h1>
		<p class="htg-page-desc"><?php esc_html_e( 'Manage ad placements and tracking codes across your site.', 'adtech-pro' ); ?></p>

		<form method="post" action="">
			<?php wp_nonce_field( 'HTG_ad_manager_nonce' ); ?>
			
			<div class="htg-ad-layout">
				<!-- Global Settings -->
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

				<!-- Global Codes -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-editor-code"></span>
							<?php esc_html_e( 'Global Codes', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Scripts injected in header or footer (analytics, ad network base code)', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body htg-two-col">
						<div class="htg-field">
							<label><?php esc_html_e( 'Header Code', 'adtech-pro' ); ?> <code>&lt;head&gt;</code></label>
							<textarea name="HTG_ad_head_code" rows="6" placeholder="<?php esc_attr_e( 'Google Analytics, AdSense auto ads, verification tags...', 'adtech-pro' ); ?>"><?php echo esc_textarea( get_option( 'HTG_ad_head_code', '' ) ); ?></textarea>
						</div>
						<div class="htg-field">
							<label><?php esc_html_e( 'Footer Code', 'adtech-pro' ); ?> <code>&lt;/body&gt;</code></label>
							<textarea name="HTG_ad_footer_code" rows="6" placeholder="<?php esc_attr_e( 'Chat widgets, tracking pixels...', 'adtech-pro' ); ?>"><?php echo esc_textarea( get_option( 'HTG_ad_footer_code', '' ) ); ?></textarea>
						</div>
					</div>
				</div>

				<!-- Header Ads -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-arrow-up-alt"></span>
							<?php esc_html_e( 'Header Ads', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Ads displayed in the header area', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body htg-two-col">
						<div class="htg-field">
							<label><?php esc_html_e( 'Above Header', 'adtech-pro' ); ?> <span class="htg-size">728×90</span></label>
							<textarea name="HTG_ad_header_above" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_header_above', '' ) ); ?></textarea>
						</div>
						<div class="htg-field">
							<label><?php esc_html_e( 'Below Header', 'adtech-pro' ); ?> <span class="htg-size">728×90 / 970×90</span></label>
							<textarea name="HTG_ad_header_below" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_header_below', '' ) ); ?></textarea>
						</div>
					</div>
				</div>

				<!-- Content Ads -->
				<div class="htg-ad-section">
					<div class="htg-section-header">
						<h2>
							<span class="dashicons dashicons-media-text"></span>
							<?php esc_html_e( 'Article Ads', 'adtech-pro' ); ?>
						</h2>
						<p><?php esc_html_e( 'Ads displayed before, after, or within article content', 'adtech-pro' ); ?></p>
					</div>
					<div class="htg-section-body">
						<div class="htg-two-col">
							<div class="htg-field">
								<label><?php esc_html_e( 'Before Article', 'adtech-pro' ); ?> <span class="htg-size">728×90</span></label>
								<textarea name="HTG_ad_before_content" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_before_content', '' ) ); ?></textarea>
							</div>
							<div class="htg-field">
								<label><?php esc_html_e( 'After Article', 'adtech-pro' ); ?> <span class="htg-size">728×90 / 336×280</span></label>
								<textarea name="HTG_ad_after_content" rows="5"><?php echo esc_textarea( get_option( 'HTG_ad_after_content', '' ) ); ?></textarea>
							</div>
						</div>
						
						<div class="htg-field htg-field-highlight">
							<label>
								<?php esc_html_e( 'In-Article Ad', 'adtech-pro' ); ?> 
								<span class="htg-size"><?php esc_html_e( 'In-article / 336×280', 'adtech-pro' ); ?></span>
							</label>
							<div class="htg-inline-option">
								<span><?php esc_html_e( 'Insert after paragraph', 'adtech-pro' ); ?></span>
								<input type="number" name="HTG_ad_in_article_position" value="<?php echo esc_attr( $in_article_position ); ?>" min="1" max="20" style="width: 70px;">
							</div>
							<textarea name="HTG_ad_in_article" rows="5" placeholder="<?php esc_attr_e( 'Paste your in-article ad code here...', 'adtech-pro' ); ?>"><?php echo esc_textarea( get_option( 'HTG_ad_in_article', '' ) ); ?></textarea>
							<p class="htg-help"><?php esc_html_e( 'Ad will be automatically inserted after the specified paragraph number on single posts.', 'adtech-pro' ); ?></p>
						</div>
					</div>
				</div>

				<!-- Sidebar Ads -->
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
							<p class="htg-help"><?php esc_html_e( 'This ad will stick to the viewport as users scroll.', 'adtech-pro' ); ?></p>
						</div>
					</div>
				</div>

				<!-- Homepage & Footer -->
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
	
	.htg-field-highlight {
		background: #f0fdf4;
		padding: 20px;
		border-radius: 8px;
		border: 1px solid #bbf7d0;
		margin-top: 20px;
	}
	
	.htg-inline-option {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 12px;
		font-size: 13px;
		color: #475569;
	}
	
	.htg-inline-option input[type="number"] {
		padding: 6px 10px;
		border: 1px solid #d1d5db;
		border-radius: 4px;
		font-size: 14px;
		font-weight: 600;
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

/**
 * Output frontend ad styles
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

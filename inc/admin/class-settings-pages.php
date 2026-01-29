<?php
/**
 * H&T AdTech Pro - Admin Settings Pages
 * Enterprise-grade settings interface
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Settings_Pages {

	/**
	 * Initialize settings pages
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_pages' ), 6 );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_settings_assets' ) );
	}

	/**
	 * Add settings pages to admin menu
	 */
	public static function add_settings_pages() {
		// General Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'General Settings', 'HTG' ),
			__( 'General Settings', 'HTG' ),
			'manage_options',
			'HTG-general-settings',
			array( __CLASS__, 'render_general_page' )
		);

		// Appearance Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'Appearance', 'HTG' ),
			__( 'Appearance', 'HTG' ),
			'manage_options',
			'HTG-appearance',
			array( __CLASS__, 'render_appearance_page' )
		);

		// Magazine Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'Magazine', 'HTG' ),
			__( 'Magazine', 'HTG' ),
			'manage_options',
			'HTG-magazine-settings',
			array( __CLASS__, 'render_magazine_page' )
		);

		// Engagement Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'Engagement', 'HTG' ),
			__( 'Engagement', 'HTG' ),
			'manage_options',
			'HTG-engagement-settings',
			array( __CLASS__, 'render_engagement_page' )
		);
	}

	/**
	 * Enqueue settings assets
	 */
	public static function enqueue_settings_assets( $hook ) {
		if ( strpos( $hook, 'HTG' ) === false ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}

	/**
	 * Register all settings
	 */
	public static function register_settings() {
		// Appearance settings
		register_setting( 'HTG_appearance', 'HTG_primary_color' );
		register_setting( 'HTG_appearance', 'HTG_secondary_color' );
		register_setting( 'HTG_appearance', 'HTG_accent_color_1' );
		register_setting( 'HTG_appearance', 'HTG_accent_color_2' );
		register_setting( 'HTG_appearance', 'HTG_heading_font' );
		register_setting( 'HTG_appearance', 'HTG_body_font' );
		register_setting( 'HTG_appearance', 'HTG_font_size_scale' );

		// Magazine settings
		register_setting( 'HTG_magazine', 'HTG_enable_magazine_layout' );
		register_setting( 'HTG_magazine', 'HTG_featured_posts_count' );
		register_setting( 'HTG_magazine', 'HTG_excerpt_length' );
		for ( $i = 1; $i <= 4; $i++ ) {
			register_setting( 'HTG_magazine', 'HTG_category_section_' . $i );
			register_setting( 'HTG_magazine', 'HTG_category_section_' . $i . '_posts' );
		}

		// Engagement settings
		register_setting( 'HTG_engagement', 'HTG_newsletter_title' );
		register_setting( 'HTG_engagement', 'HTG_newsletter_description' );
		register_setting( 'HTG_engagement', 'HTG_newsletter_email' );
		register_setting( 'HTG_engagement', 'HTG_newsletter_auto_insert' );
		register_setting( 'HTG_engagement', 'HTG_author_box_enable' );
		register_setting( 'HTG_engagement', 'HTG_author_box_style' );
		register_setting( 'HTG_engagement', 'HTG_author_box_show_post_count' );
		register_setting( 'HTG_engagement', 'HTG_author_box_show_social' );
	}

	/**
	 * Render Appearance Page
	 */
	public static function render_appearance_page() {
		// Handle save
		if ( isset( $_POST['HTG_save_appearance'] ) && check_admin_referer( 'HTG_appearance_nonce' ) ) {
			update_option( 'HTG_primary_color', sanitize_hex_color( $_POST['HTG_primary_color'] ?? HTG_get_default( 'HTG_primary_color' ) ) );
			update_option( 'HTG_secondary_color', sanitize_hex_color( $_POST['HTG_secondary_color'] ?? HTG_get_default( 'HTG_secondary_color' ) ) );
			update_option( 'HTG_accent_color_1', sanitize_hex_color( $_POST['HTG_accent_color_1'] ?? HTG_get_default( 'HTG_accent_color_1' ) ) );
			update_option( 'HTG_accent_color_2', sanitize_hex_color( $_POST['HTG_accent_color_2'] ?? HTG_get_default( 'HTG_accent_color_2' ) ) );
			update_option( 'HTG_heading_font', sanitize_text_field( $_POST['HTG_heading_font'] ?? HTG_get_default( 'HTG_heading_font' ) ) );
			update_option( 'HTG_body_font', sanitize_text_field( $_POST['HTG_body_font'] ?? HTG_get_default( 'HTG_body_font' ) ) );
			update_option( 'HTG_font_size_base', absint( $_POST['HTG_font_size_base'] ?? HTG_get_default( 'HTG_font_size_base' ) ) );
			update_option( 'HTG_custom_css', wp_strip_all_tags( $_POST['HTG_custom_css'] ?? '' ) );
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Appearance settings saved!', 'HTG' ) . '</strong></p></div>';
		}

		// Get current values using centralized defaults
		$primary_color = get_option( 'HTG_primary_color', HTG_get_default( 'HTG_primary_color' ) );
		$secondary_color = get_option( 'HTG_secondary_color', HTG_get_default( 'HTG_secondary_color' ) );
		$accent_color_1 = get_option( 'HTG_accent_color_1', HTG_get_default( 'HTG_accent_color_1' ) );
		$accent_color_2 = get_option( 'HTG_accent_color_2', HTG_get_default( 'HTG_accent_color_2' ) );
		$heading_font = get_option( 'HTG_heading_font', HTG_get_default( 'HTG_heading_font' ) );
		$body_font = get_option( 'HTG_body_font', HTG_get_default( 'HTG_body_font' ) );
		$font_size_base = get_option( 'HTG_font_size_base', HTG_get_default( 'HTG_font_size_base' ) );
		$custom_css = get_option( 'HTG_custom_css', HTG_get_default( 'HTG_custom_css' ) );
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-admin-appearance"></span>
					<?php esc_html_e( 'Appearance Settings', 'HTG' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Customize colors, typography, and visual styling', 'HTG' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_appearance_nonce' ); ?>
					
					<div class="HTG-settings-tabs">
						<h2 class="nav-tab-wrapper">
							<a href="#colors" class="nav-tab nav-tab-active"><?php esc_html_e( 'Colors', 'HTG' ); ?></a>
							<a href="#typography" class="nav-tab"><?php esc_html_e( 'Typography', 'HTG' ); ?></a>
							<a href="#custom-css" class="nav-tab"><?php esc_html_e( 'Custom CSS', 'HTG' ); ?></a>
						</h2>
						
						<!-- Colors Tab -->
						<div id="colors" class="HTG-tab-content">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_primary_color"><?php esc_html_e( 'Primary Color', 'HTG' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_primary_color" id="HTG_primary_color" value="<?php echo esc_attr( $primary_color ); ?>" class="HTG-color-picker" data-default-color="#1a1f36" />
										<p class="description"><?php esc_html_e( 'Main brand color for headers, buttons, and links.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_secondary_color"><?php esc_html_e( 'Secondary Color', 'HTG' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_secondary_color" id="HTG_secondary_color" value="<?php echo esc_attr( $secondary_color ); ?>" class="HTG-color-picker" data-default-color="#00d4aa" />
										<p class="description"><?php esc_html_e( 'Accent color for highlights, badges, and CTAs.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_accent_color_1"><?php esc_html_e( 'Accent Color 1', 'HTG' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_accent_color_1" id="HTG_accent_color_1" value="<?php echo esc_attr( $accent_color_1 ); ?>" class="HTG-color-picker" data-default-color="#6c5ce7" />
										<p class="description"><?php esc_html_e( 'Additional accent for categories and badges.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_accent_color_2"><?php esc_html_e( 'Accent Color 2', 'HTG' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_accent_color_2" id="HTG_accent_color_2" value="<?php echo esc_attr( $accent_color_2 ); ?>" class="HTG-color-picker" data-default-color="#00b894" />
										<p class="description"><?php esc_html_e( 'Secondary accent for success states and highlights.', 'HTG' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Typography Tab -->
						<div id="typography" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_heading_font"><?php esc_html_e( 'Headings Font', 'HTG' ); ?></label>
									</th>
									<td>
										<select name="HTG_heading_font" id="HTG_heading_font" class="regular-text">
											<option value="Inter" <?php selected( $heading_font, 'Inter' ); ?>>Inter</option>
											<option value="Poppins" <?php selected( $heading_font, 'Poppins' ); ?>>Poppins</option>
											<option value="Montserrat" <?php selected( $heading_font, 'Montserrat' ); ?>>Montserrat</option>
											<option value="Roboto" <?php selected( $heading_font, 'Roboto' ); ?>>Roboto</option>
											<option value="Open Sans" <?php selected( $heading_font, 'Open Sans' ); ?>>Open Sans</option>
											<option value="Lato" <?php selected( $heading_font, 'Lato' ); ?>>Lato</option>
										</select>
										<p class="description"><?php esc_html_e( 'Font family for headings (H1-H6).', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_body_font"><?php esc_html_e( 'Body Font', 'HTG' ); ?></label>
									</th>
									<td>
										<select name="HTG_body_font" id="HTG_body_font" class="regular-text">
											<option value="Inter" <?php selected( $body_font, 'Inter' ); ?>>Inter</option>
											<option value="Open Sans" <?php selected( $body_font, 'Open Sans' ); ?>>Open Sans</option>
											<option value="Roboto" <?php selected( $body_font, 'Roboto' ); ?>>Roboto</option>
											<option value="Lato" <?php selected( $body_font, 'Lato' ); ?>>Lato</option>
											<option value="Source Sans Pro" <?php selected( $body_font, 'Source Sans Pro' ); ?>>Source Sans Pro</option>
										</select>
										<p class="description"><?php esc_html_e( 'Font family for body text and paragraphs.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_font_size_base"><?php esc_html_e( 'Base Font Size', 'HTG' ); ?></label>
									</th>
									<td>
										<input type="number" name="HTG_font_size_base" id="HTG_font_size_base" value="<?php echo esc_attr( $font_size_base ); ?>" min="12" max="24" step="1" class="small-text" />
										<span>px</span>
										<p class="description"><?php esc_html_e( 'Base font size for body text (default: 16px).', 'HTG' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Custom CSS Tab -->
						<div id="custom-css" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_custom_css"><?php esc_html_e( 'Additional CSS', 'HTG' ); ?></label>
									</th>
									<td>
										<textarea name="HTG_custom_css" id="HTG_custom_css" rows="15" class="large-text code" style="font-family: 'SF Mono', Monaco, monospace; font-size: 13px;"><?php echo esc_textarea( $custom_css ); ?></textarea>
										<p class="description"><?php esc_html_e( 'Add custom CSS for advanced customization. This will be added to the site header.', 'HTG' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<p class="submit" style="padding: 20px 40px;">
						<button type="submit" name="HTG_save_appearance" class="button button-primary button-hero">
							<span class="dashicons dashicons-saved"></span>
							<?php esc_html_e( 'Save Appearance', 'HTG' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>
		
		<script>
		jQuery(document).ready(function($){
			$('.nav-tab').on('click', function(e){
				e.preventDefault();
				var target = $(this).attr('href');
				$('.nav-tab').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
				$('.HTG-tab-content').hide();
				$(target).show();
			});
			if (typeof $.fn.wpColorPicker !== 'undefined') {
				$('.HTG-color-picker').wpColorPicker();
			}
		});
		</script>
		<?php
	}

	/**
	 * Render Magazine Page
	 */
	public static function render_magazine_page() {
		// Handle form submission
		if ( isset( $_POST['HTG_magazine_settings_nonce'] ) && wp_verify_nonce( $_POST['HTG_magazine_settings_nonce'], 'HTG_magazine_settings' ) ) {
			update_option( 'HTG_magazine_enable', isset( $_POST['HTG_magazine_enable'] ) ? 1 : 0 );
			update_option( 'HTG_magazine_posts_per_section', absint( $_POST['HTG_magazine_posts_per_section'] ) );
			update_option( 'HTG_magazine_layout_style', sanitize_text_field( $_POST['HTG_magazine_layout_style'] ) );
			update_option( 'HTG_magazine_show_badges', isset( $_POST['HTG_magazine_show_badges'] ) ? 1 : 0 );
			update_option( 'HTG_magazine_show_reading_time', isset( $_POST['HTG_magazine_show_reading_time'] ) ? 1 : 0 );
			
			for ( $i = 1; $i <= 4; $i++ ) {
				update_option( 'HTG_magazine_section_' . $i . '_category', absint( $_POST['HTG_magazine_section_' . $i . '_category'] ) );
			}
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Magazine settings saved!', 'HTG' ) . '</strong></p></div>';
		}
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-layout"></span>
					<?php esc_html_e( 'Magazine Settings', 'HTG' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Configure magazine layout, sections, and category grids', 'HTG' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_magazine_settings', 'HTG_magazine_settings_nonce' ); ?>
					
					<div class="HTG-admin-card" style="margin: 20px 40px;">
						<div class="HTG-card-header">
							<h2>
								<span class="dashicons dashicons-admin-generic"></span>
								<?php esc_html_e( 'Layout Options', 'HTG' ); ?>
							</h2>
						</div>
						<div class="HTG-card-body">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Magazine Layout', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_magazine_enable" value="1" <?php checked( get_option( 'HTG_magazine_enable', 1 ), 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable the magazine-style homepage layout.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Posts Per Section', 'HTG' ); ?></th>
									<td>
										<input type="number" name="HTG_magazine_posts_per_section" value="<?php echo esc_attr( get_option( 'HTG_magazine_posts_per_section', '6' ) ); ?>" min="3" max="12" class="small-text">
										<p class="description"><?php esc_html_e( 'Number of posts in each magazine section (3-12).', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Layout Style', 'HTG' ); ?></th>
									<td>
										<select name="HTG_magazine_layout_style" class="regular-text">
											<option value="grid" <?php selected( get_option( 'HTG_magazine_layout_style', 'grid' ), 'grid' ); ?>><?php esc_html_e( 'Grid Layout', 'HTG' ); ?></option>
											<option value="list" <?php selected( get_option( 'HTG_magazine_layout_style' ), 'list' ); ?>><?php esc_html_e( 'List Layout', 'HTG' ); ?></option>
											<option value="masonry" <?php selected( get_option( 'HTG_magazine_layout_style' ), 'masonry' ); ?>><?php esc_html_e( 'Masonry Layout', 'HTG' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Post Format Badges', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_magazine_show_badges" value="1" <?php checked( get_option( 'HTG_magazine_show_badges', 1 ), 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Display video, gallery, and audio badges on posts.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Reading Time', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_magazine_show_reading_time" value="1" <?php checked( get_option( 'HTG_magazine_show_reading_time', 1 ), 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show estimated reading time on posts.', 'HTG' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="HTG-admin-card" style="margin: 20px 40px;">
						<div class="HTG-card-header">
							<h2>
								<span class="dashicons dashicons-category"></span>
								<?php esc_html_e( 'Category Sections', 'HTG' ); ?>
							</h2>
						</div>
						<div class="HTG-card-body">
							<p class="description" style="margin-bottom: 20px;"><?php esc_html_e( 'Select categories for each homepage section. Leave empty to hide a section.', 'HTG' ); ?></p>
							
							<table class="form-table">
								<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
								<tr>
									<th scope="row"><?php printf( esc_html__( 'Section %d', 'HTG' ), $i ); ?></th>
									<td>
										<?php
										wp_dropdown_categories( array(
											'name'             => 'HTG_magazine_section_' . $i . '_category',
											'selected'         => get_option( 'HTG_magazine_section_' . $i . '_category', '' ),
											'show_option_none' => esc_html__( '— Select Category —', 'HTG' ),
											'option_none_value' => '0',
											'hide_empty'       => 0,
											'class'            => 'regular-text',
										) );
										?>
									</td>
								</tr>
								<?php endfor; ?>
							</table>
						</div>
					</div>

					<p class="submit" style="padding: 20px 40px;">
						<button type="submit" class="button button-primary button-hero">
							<span class="dashicons dashicons-saved"></span>
							<?php esc_html_e( 'Save Magazine Settings', 'HTG' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Engagement Page
	 */
	public static function render_engagement_page() {
		// Handle form submission
		if ( isset( $_POST['HTG_engagement_nonce'] ) && wp_verify_nonce( $_POST['HTG_engagement_nonce'], 'HTG_engagement_settings' ) ) {
			update_option( 'HTG_newsletter_enable', isset( $_POST['HTG_newsletter_enable'] ) ? 1 : 0 );
			update_option( 'HTG_newsletter_title', sanitize_text_field( $_POST['HTG_newsletter_title'] ?? '' ) );
			update_option( 'HTG_newsletter_description', sanitize_textarea_field( $_POST['HTG_newsletter_description'] ?? '' ) );
			update_option( 'HTG_social_share_enable', isset( $_POST['HTG_social_share_enable'] ) ? 1 : 0 );
			update_option( 'HTG_social_share_facebook', isset( $_POST['HTG_social_share_facebook'] ) ? 1 : 0 );
			update_option( 'HTG_social_share_twitter', isset( $_POST['HTG_social_share_twitter'] ) ? 1 : 0 );
			update_option( 'HTG_social_share_linkedin', isset( $_POST['HTG_social_share_linkedin'] ) ? 1 : 0 );
			update_option( 'HTG_social_share_whatsapp', isset( $_POST['HTG_social_share_whatsapp'] ) ? 1 : 0 );
			update_option( 'HTG_reading_time_enable', isset( $_POST['HTG_reading_time_enable'] ) ? 1 : 0 );
			update_option( 'HTG_progress_bar_enable', isset( $_POST['HTG_progress_bar_enable'] ) ? 1 : 0 );
			update_option( 'HTG_progress_bar_color', sanitize_hex_color( $_POST['HTG_progress_bar_color'] ?? '#00d4aa' ) );
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Engagement settings saved!', 'HTG' ) . '</strong></p></div>';
		}
		
		// Get current values
		$newsletter_enable = get_option( 'HTG_newsletter_enable', 1 );
		$newsletter_title = get_option( 'HTG_newsletter_title', 'Subscribe to Our Newsletter' );
		$newsletter_description = get_option( 'HTG_newsletter_description', 'Get the latest updates delivered to your inbox.' );
		$social_share_enable = get_option( 'HTG_social_share_enable', 1 );
		$reading_time_enable = get_option( 'HTG_reading_time_enable', 1 );
		$progress_bar_enable = get_option( 'HTG_progress_bar_enable', 1 );
		$progress_bar_color = get_option( 'HTG_progress_bar_color', '#00d4aa' );
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-awards"></span>
					<?php esc_html_e( 'Engagement Settings', 'HTG' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Configure reader engagement features and social tools', 'HTG' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_engagement_settings', 'HTG_engagement_nonce' ); ?>
					
					<div class="HTG-settings-tabs" style="margin: 20px 40px;">
						<h2 class="nav-tab-wrapper">
							<a href="#newsletter" class="nav-tab nav-tab-active"><?php esc_html_e( 'Newsletter', 'HTG' ); ?></a>
							<a href="#social-share" class="nav-tab"><?php esc_html_e( 'Social Share', 'HTG' ); ?></a>
							<a href="#reading" class="nav-tab"><?php esc_html_e( 'Reading Experience', 'HTG' ); ?></a>
						</h2>
						
						<!-- Newsletter Tab -->
						<div id="newsletter" class="HTG-tab-content">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Newsletter', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_newsletter_enable" value="1" <?php checked( $newsletter_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show newsletter subscription form.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Title', 'HTG' ); ?></th>
									<td>
										<input type="text" name="HTG_newsletter_title" value="<?php echo esc_attr( $newsletter_title ); ?>" class="regular-text" />
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Description', 'HTG' ); ?></th>
									<td>
										<textarea name="HTG_newsletter_description" rows="3" class="large-text"><?php echo esc_textarea( $newsletter_description ); ?></textarea>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Social Share Tab -->
						<div id="social-share" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Social Share', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_social_share_enable" value="1" <?php checked( $social_share_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show social share buttons on posts.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Networks', 'HTG' ); ?></th>
									<td>
										<label style="display: block; margin-bottom: 10px;">
											<input type="checkbox" name="HTG_social_share_facebook" value="1" <?php checked( get_option( 'HTG_social_share_facebook', 1 ), 1 ); ?>>
											<?php esc_html_e( 'Facebook', 'HTG' ); ?>
										</label>
										<label style="display: block; margin-bottom: 10px;">
											<input type="checkbox" name="HTG_social_share_twitter" value="1" <?php checked( get_option( 'HTG_social_share_twitter', 1 ), 1 ); ?>>
											<?php esc_html_e( 'X (Twitter)', 'HTG' ); ?>
										</label>
										<label style="display: block; margin-bottom: 10px;">
											<input type="checkbox" name="HTG_social_share_linkedin" value="1" <?php checked( get_option( 'HTG_social_share_linkedin', 1 ), 1 ); ?>>
											<?php esc_html_e( 'LinkedIn', 'HTG' ); ?>
										</label>
										<label style="display: block;">
											<input type="checkbox" name="HTG_social_share_whatsapp" value="1" <?php checked( get_option( 'HTG_social_share_whatsapp', 1 ), 1 ); ?>>
											<?php esc_html_e( 'WhatsApp', 'HTG' ); ?>
										</label>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Reading Experience Tab -->
						<div id="reading" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Reading Time', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_reading_time_enable" value="1" <?php checked( $reading_time_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show estimated reading time on posts.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Progress Bar', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_progress_bar_enable" value="1" <?php checked( $progress_bar_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show reading progress bar at top of posts.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Progress Bar Color', 'HTG' ); ?></th>
									<td>
										<input type="text" name="HTG_progress_bar_color" value="<?php echo esc_attr( $progress_bar_color ); ?>" class="HTG-color-picker" data-default-color="#00d4aa" />
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<p class="submit" style="padding: 20px 40px;">
						<button type="submit" class="button button-primary button-hero">
							<span class="dashicons dashicons-saved"></span>
							<?php esc_html_e( 'Save Engagement Settings', 'HTG' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>
		
		<script>
		jQuery(document).ready(function($){
			$('.nav-tab').on('click', function(e){
				e.preventDefault();
				var target = $(this).attr('href');
				$('.nav-tab').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
				$('.HTG-tab-content').hide();
				$(target).show();
			});
			if (typeof $.fn.wpColorPicker !== 'undefined') {
				$('.HTG-color-picker').wpColorPicker();
			}
		});
		</script>
		<?php
	}

	/**
	 * Render General Page
	 */
	public static function render_general_page() {
		// Handle save
		if ( isset( $_POST['HTG_save_all_general'] ) && check_admin_referer( 'HTG_all_general_nonce' ) ) {
			// Site Settings
			update_option( 'HTG_site_layout', sanitize_text_field( $_POST['HTG_site_layout'] ?? 'wide' ) );
			update_option( 'HTG_sidebar_position', sanitize_text_field( $_POST['HTG_sidebar_position'] ?? 'right' ) );
			update_option( 'HTG_container_width', absint( $_POST['HTG_container_width'] ?? 1200 ) );
			update_option( 'HTG_breadcrumbs_enable', isset( $_POST['HTG_breadcrumbs_enable'] ) ? 1 : 0 );
			update_option( 'HTG_footer_copyright', wp_kses_post( $_POST['HTG_footer_copyright'] ?? '' ) );
			
			// Header Settings
			update_option( 'HTG_header_layout', sanitize_text_field( $_POST['HTG_header_layout'] ?? 'default' ) );
			update_option( 'HTG_topbar_enable', isset( $_POST['HTG_topbar_enable'] ) ? 1 : 0 );
			update_option( 'HTG_sticky_header', isset( $_POST['HTG_sticky_header'] ) ? 1 : 0 );
			update_option( 'HTG_header_show_search', isset( $_POST['HTG_header_show_search'] ) ? 1 : 0 );
			
			// Slider Settings
			update_option( 'HTG_slider_enable', isset( $_POST['HTG_slider_enable'] ) ? 1 : 0 );
			update_option( 'HTG_slider_posts_count', absint( $_POST['HTG_slider_posts_count'] ?? 5 ) );
			update_option( 'HTG_slider_autoplay', isset( $_POST['HTG_slider_autoplay'] ) ? 1 : 0 );
			
			// Blog Settings
			$layout_map = array( 'grid' => 'th-grid-2', 'list' => 'th-list-posts', 'large' => 'th-large-posts' );
			$selected_layout = sanitize_text_field( $_POST['HTG_blog_layout'] ?? 'grid' );
			update_option( 'archive_content_layout', $layout_map[ $selected_layout ] ?? 'th-grid-2' );
			update_option( 'HTG_excerpt_length', absint( $_POST['HTG_excerpt_length'] ?? 30 ) );
			update_option( 'HTG_show_post_date', isset( $_POST['HTG_show_post_date'] ) ? 1 : 0 );
			update_option( 'HTG_show_author', isset( $_POST['HTG_show_author'] ) ? 1 : 0 );
			
			// Post Settings
			update_option( 'HTG_post_show_author_box', isset( $_POST['HTG_post_show_author_box'] ) ? 1 : 0 );
			update_option( 'HTG_post_show_related', isset( $_POST['HTG_post_show_related'] ) ? 1 : 0 );
			update_option( 'HTG_post_related_count', absint( $_POST['HTG_post_related_count'] ?? 6 ) );
			update_option( 'HTG_post_show_tags', isset( $_POST['HTG_post_show_tags'] ) ? 1 : 0 );
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved!', 'HTG' ) . '</strong></p></div>';
		}

		// Get values
		$site_layout = get_option( 'HTG_site_layout', 'wide' );
		$sidebar_position = get_option( 'HTG_sidebar_position', 'right' );
		$container_width = get_option( 'HTG_container_width', 1200 );
		$breadcrumbs_enable = get_option( 'HTG_breadcrumbs_enable', 1 );
		$footer_copyright = get_option( 'HTG_footer_copyright', '© ' . date( 'Y' ) . ' H&T GAMING. All rights reserved.' );
		$header_layout = get_option( 'HTG_header_layout', 'default' );
		$topbar_enable = get_option( 'HTG_topbar_enable', 1 );
		$sticky_header = get_option( 'HTG_sticky_header', 1 );
		$header_show_search = get_option( 'HTG_header_show_search', 1 );
		$slider_enable = get_option( 'HTG_slider_enable', 1 );
		$slider_posts_count = get_option( 'HTG_slider_posts_count', 5 );
		$slider_autoplay = get_option( 'HTG_slider_autoplay', 1 );
		$archive_layout = get_option( 'archive_content_layout', 'th-grid-2' );
		$layout_reverse_map = array( 'th-grid-2' => 'grid', 'th-list-posts' => 'list', 'th-large-posts' => 'large' );
		$blog_layout = $layout_reverse_map[ $archive_layout ] ?? 'grid';
		$excerpt_length = get_option( 'HTG_excerpt_length', 30 );
		$show_post_date = get_option( 'HTG_show_post_date', 1 );
		$show_author = get_option( 'HTG_show_author', 1 );
		$post_show_author_box = get_option( 'HTG_post_show_author_box', 1 );
		$post_show_related = get_option( 'HTG_post_show_related', 1 );
		$post_related_count = get_option( 'HTG_post_related_count', 6 );
		$post_show_tags = get_option( 'HTG_post_show_tags', 1 );
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-admin-generic"></span>
					<?php esc_html_e( 'General Settings', 'HTG' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Configure site layout, header, slider, blog, and post settings', 'HTG' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_all_general_nonce' ); ?>
					
					<div class="HTG-settings-tabs" style="margin: 20px 40px;">
						<h2 class="nav-tab-wrapper">
							<a href="#site" class="nav-tab nav-tab-active"><?php esc_html_e( 'Site', 'HTG' ); ?></a>
							<a href="#header" class="nav-tab"><?php esc_html_e( 'Header', 'HTG' ); ?></a>
							<a href="#slider" class="nav-tab"><?php esc_html_e( 'Slider', 'HTG' ); ?></a>
							<a href="#blog" class="nav-tab"><?php esc_html_e( 'Blog', 'HTG' ); ?></a>
							<a href="#post" class="nav-tab"><?php esc_html_e( 'Post', 'HTG' ); ?></a>
						</h2>
						
						<!-- Site Tab -->
						<div id="site" class="HTG-tab-content">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Site Layout', 'HTG' ); ?></th>
									<td>
										<select name="HTG_site_layout" class="regular-text">
											<option value="wide" <?php selected( $site_layout, 'wide' ); ?>><?php esc_html_e( 'Wide (Full Width)', 'HTG' ); ?></option>
											<option value="boxed" <?php selected( $site_layout, 'boxed' ); ?>><?php esc_html_e( 'Boxed (Centered)', 'HTG' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Sidebar Position', 'HTG' ); ?></th>
									<td>
										<select name="HTG_sidebar_position" class="regular-text">
											<option value="right" <?php selected( $sidebar_position, 'right' ); ?>><?php esc_html_e( 'Right', 'HTG' ); ?></option>
											<option value="left" <?php selected( $sidebar_position, 'left' ); ?>><?php esc_html_e( 'Left', 'HTG' ); ?></option>
											<option value="none" <?php selected( $sidebar_position, 'none' ); ?>><?php esc_html_e( 'None (Full Width)', 'HTG' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Container Width', 'HTG' ); ?></th>
									<td>
										<input type="number" name="HTG_container_width" value="<?php echo esc_attr( $container_width ); ?>" min="960" max="1920" class="small-text" /> px
										<p class="description"><?php esc_html_e( 'Default: 1200px (Range: 960-1920px)', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Breadcrumbs', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_breadcrumbs_enable" value="1" <?php checked( $breadcrumbs_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable breadcrumb navigation.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Footer Copyright', 'HTG' ); ?></th>
									<td>
										<textarea name="HTG_footer_copyright" rows="2" class="large-text"><?php echo esc_textarea( $footer_copyright ); ?></textarea>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Header Tab -->
						<div id="header" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Header Layout', 'HTG' ); ?></th>
									<td>
										<select name="HTG_header_layout" class="regular-text">
											<option value="default" <?php selected( $header_layout, 'default' ); ?>><?php esc_html_e( 'Default (Logo Left)', 'HTG' ); ?></option>
											<option value="centered" <?php selected( $header_layout, 'centered' ); ?>><?php esc_html_e( 'Centered', 'HTG' ); ?></option>
											<option value="minimal" <?php selected( $header_layout, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'HTG' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Top Bar', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_topbar_enable" value="1" <?php checked( $topbar_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable top bar above header.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Sticky Header', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_sticky_header" value="1" <?php checked( $sticky_header, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Make header sticky on scroll.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Search Icon', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_header_show_search" value="1" <?php checked( $header_show_search, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show search icon in navigation.', 'HTG' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Slider Tab -->
						<div id="slider" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Slider', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_slider_enable" value="1" <?php checked( $slider_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show featured slider on homepage.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Number of Posts', 'HTG' ); ?></th>
									<td>
										<input type="number" name="HTG_slider_posts_count" value="<?php echo esc_attr( $slider_posts_count ); ?>" min="3" max="10" class="small-text" />
										<p class="description"><?php esc_html_e( 'Posts to show in slider (3-10).', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Autoplay', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_slider_autoplay" value="1" <?php checked( $slider_autoplay, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable auto-rotation.', 'HTG' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Blog Tab -->
						<div id="blog" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Blog Layout', 'HTG' ); ?></th>
									<td>
										<select name="HTG_blog_layout" class="regular-text">
											<option value="grid" <?php selected( $blog_layout, 'grid' ); ?>><?php esc_html_e( 'Grid (2 Columns)', 'HTG' ); ?></option>
											<option value="list" <?php selected( $blog_layout, 'list' ); ?>><?php esc_html_e( 'List', 'HTG' ); ?></option>
											<option value="large" <?php selected( $blog_layout, 'large' ); ?>><?php esc_html_e( 'Large Featured', 'HTG' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Excerpt Length', 'HTG' ); ?></th>
									<td>
										<input type="number" name="HTG_excerpt_length" value="<?php echo esc_attr( $excerpt_length ); ?>" min="10" max="100" class="small-text" /> <?php esc_html_e( 'words', 'HTG' ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Show Post Date', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_show_post_date" value="1" <?php checked( $show_post_date, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Show Author', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_show_author" value="1" <?php checked( $show_author, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Post Tab -->
						<div id="post" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Author Box', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_post_show_author_box" value="1" <?php checked( $post_show_author_box, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show author bio box on posts.', 'HTG' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Related Posts', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_post_show_related" value="1" <?php checked( $post_show_related, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Related Posts Count', 'HTG' ); ?></th>
									<td>
										<input type="number" name="HTG_post_related_count" value="<?php echo esc_attr( $post_related_count ); ?>" min="3" max="12" class="small-text" />
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Show Tags', 'HTG' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_post_show_tags" value="1" <?php checked( $post_show_tags, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<p class="submit" style="padding: 20px 40px;">
						<button type="submit" name="HTG_save_all_general" class="button button-primary button-hero">
							<span class="dashicons dashicons-saved"></span>
							<?php esc_html_e( 'Save All Settings', 'HTG' ); ?>
						</button>
					</p>
				</form>
			</div>
		</div>
		
		<script>
		jQuery(document).ready(function($){
			$('.nav-tab').on('click', function(e){
				e.preventDefault();
				var target = $(this).attr('href');
				$('.nav-tab').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
				$('.HTG-tab-content').hide();
				$(target).show();
			});
			if (typeof $.fn.wpColorPicker !== 'undefined') {
				$('.HTG-color-picker').wpColorPicker();
			}
		});
		</script>
		<?php
	}
}

HTG_Settings_Pages::init();

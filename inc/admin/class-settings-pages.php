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
			__( 'General Settings', 'adtech-pro' ),
			__( 'General Settings', 'adtech-pro' ),
			'manage_options',
			'HTG-general-settings',
			array( __CLASS__, 'render_general_page' )
		);

		// Appearance Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'Appearance', 'adtech-pro' ),
			__( 'Appearance', 'adtech-pro' ),
			'manage_options',
			'HTG-appearance',
			array( __CLASS__, 'render_appearance_page' )
		);

		// Magazine Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'Magazine', 'adtech-pro' ),
			__( 'Magazine', 'adtech-pro' ),
			'manage_options',
			'HTG-magazine-settings',
			array( __CLASS__, 'render_magazine_page' )
		);

		// Engagement Settings
		add_submenu_page(
			'HTG-dashboard',
			__( 'Engagement', 'adtech-pro' ),
			__( 'Engagement', 'adtech-pro' ),
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
			
			// Logo settings
			update_option( 'HTG_logo_max_width', absint( $_POST['HTG_logo_max_width'] ?? 300 ) );
			update_option( 'HTG_logo_max_height', absint( $_POST['HTG_logo_max_height'] ?? 100 ) );
			update_option( 'HTG_logo_auto_resize', isset( $_POST['HTG_logo_auto_resize'] ) ? 1 : 0 );
			update_option( 'HTG_logo_remove_bg', isset( $_POST['HTG_logo_remove_bg'] ) ? 1 : 0 );
			update_option( 'HTG_logo_bg_color', sanitize_hex_color( $_POST['HTG_logo_bg_color'] ?? '#ffffff' ) );
			update_option( 'HTG_logo_bg_tolerance', absint( $_POST['HTG_logo_bg_tolerance'] ?? 30 ) );
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Appearance settings saved!', 'adtech-pro' ) . '</strong></p></div>';
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
					<?php esc_html_e( 'Appearance Settings', 'adtech-pro' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Customize colors, typography, and visual styling', 'adtech-pro' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_appearance_nonce' ); ?>
					
					<div class="HTG-settings-tabs">
						<h2 class="nav-tab-wrapper">
							<a href="#colors" class="nav-tab nav-tab-active"><?php esc_html_e( 'Colors', 'adtech-pro' ); ?></a>
							<a href="#typography" class="nav-tab"><?php esc_html_e( 'Typography', 'adtech-pro' ); ?></a>
							<a href="#logo" class="nav-tab"><?php esc_html_e( 'Logo', 'adtech-pro' ); ?></a>
							<a href="#custom-css" class="nav-tab"><?php esc_html_e( 'Custom CSS', 'adtech-pro' ); ?></a>
						</h2>
						
						<!-- Colors Tab -->
						<div id="colors" class="HTG-tab-content">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_primary_color"><?php esc_html_e( 'Primary Color', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_primary_color" id="HTG_primary_color" value="<?php echo esc_attr( $primary_color ); ?>" class="HTG-color-picker" data-default-color="#1a1f36" />
										<p class="description"><?php esc_html_e( 'Main brand color for headers, buttons, and links.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_secondary_color"><?php esc_html_e( 'Secondary Color', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_secondary_color" id="HTG_secondary_color" value="<?php echo esc_attr( $secondary_color ); ?>" class="HTG-color-picker" data-default-color="#00d4aa" />
										<p class="description"><?php esc_html_e( 'Accent color for highlights, badges, and CTAs.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_accent_color_1"><?php esc_html_e( 'Accent Color 1', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_accent_color_1" id="HTG_accent_color_1" value="<?php echo esc_attr( $accent_color_1 ); ?>" class="HTG-color-picker" data-default-color="#00d4aa" />
										<p class="description"><?php esc_html_e( 'Additional accent for categories and badges.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_accent_color_2"><?php esc_html_e( 'Accent Color 2', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="text" name="HTG_accent_color_2" id="HTG_accent_color_2" value="<?php echo esc_attr( $accent_color_2 ); ?>" class="HTG-color-picker" data-default-color="#00b894" />
										<p class="description"><?php esc_html_e( 'Secondary accent for success states and highlights.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Typography Tab -->
						<div id="typography" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_heading_font"><?php esc_html_e( 'Headings Font', 'adtech-pro' ); ?></label>
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
										<p class="description"><?php esc_html_e( 'Font family for headings (H1-H6).', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_body_font"><?php esc_html_e( 'Body Font', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<select name="HTG_body_font" id="HTG_body_font" class="regular-text">
											<option value="Inter" <?php selected( $body_font, 'Inter' ); ?>>Inter</option>
											<option value="Open Sans" <?php selected( $body_font, 'Open Sans' ); ?>>Open Sans</option>
											<option value="Roboto" <?php selected( $body_font, 'Roboto' ); ?>>Roboto</option>
											<option value="Lato" <?php selected( $body_font, 'Lato' ); ?>>Lato</option>
											<option value="Source Sans Pro" <?php selected( $body_font, 'Source Sans Pro' ); ?>>Source Sans Pro</option>
										</select>
										<p class="description"><?php esc_html_e( 'Font family for body text and paragraphs.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_font_size_base"><?php esc_html_e( 'Base Font Size', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="number" name="HTG_font_size_base" id="HTG_font_size_base" value="<?php echo esc_attr( $font_size_base ); ?>" min="12" max="24" step="1" class="small-text" />
										<span>px</span>
										<p class="description"><?php esc_html_e( 'Base font size for body text (default: 16px).', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Logo Tab -->
						<div id="logo" class="HTG-tab-content" style="display: none;">
							<?php
							$logo_max_width = get_option( 'HTG_logo_max_width', 300 );
							$logo_max_height = get_option( 'HTG_logo_max_height', 100 );
							$logo_auto_resize = get_option( 'HTG_logo_auto_resize', 1 );
							$logo_remove_bg = get_option( 'HTG_logo_remove_bg', 0 );
							$logo_bg_color = get_option( 'HTG_logo_bg_color', '#ffffff' );
							$logo_bg_tolerance = get_option( 'HTG_logo_bg_tolerance', 30 );
							?>
							<p class="description" style="margin-bottom: 20px; font-size: 14px;">
								<?php esc_html_e( 'Configure automatic logo resizing and optimization. Upload your logo via Customize → Site Identity.', 'adtech-pro' ); ?>
							</p>
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_logo_max_width"><?php esc_html_e( 'Max Logo Width', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="number" name="HTG_logo_max_width" id="HTG_logo_max_width" value="<?php echo esc_attr( $logo_max_width ); ?>" min="50" max="600" class="small-text" /> px
										<p class="description"><?php esc_html_e( 'Maximum display width for logo. Recommended: 200-400px', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label for="HTG_logo_max_height"><?php esc_html_e( 'Max Logo Height', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="number" name="HTG_logo_max_height" id="HTG_logo_max_height" value="<?php echo esc_attr( $logo_max_height ); ?>" min="30" max="200" class="small-text" /> px
										<p class="description"><?php esc_html_e( 'Maximum display height for logo. Recommended: 60-120px', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Auto Resize on Upload', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_logo_auto_resize" value="1" <?php checked( $logo_auto_resize, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Automatically resize large logos to optimal dimensions (2x for retina displays).', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Remove Background', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_logo_remove_bg" id="HTG_logo_remove_bg" value="1" <?php checked( $logo_remove_bg, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Convert to PNG and attempt to remove solid background. Works best with logos that have solid white or black backgrounds.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr class="htg-bg-removal-options" style="<?php echo $logo_remove_bg ? '' : 'display:none;'; ?>">
									<th scope="row">
										<label for="HTG_logo_bg_color"><?php esc_html_e( 'Background Color', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="color" name="HTG_logo_bg_color" id="HTG_logo_bg_color" value="<?php echo esc_attr( $logo_bg_color ); ?>" />
										<p class="description"><?php esc_html_e( 'Select the background color to remove from your logo.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr class="htg-bg-removal-options" style="<?php echo $logo_remove_bg ? '' : 'display:none;'; ?>">
									<th scope="row">
										<label for="HTG_logo_bg_tolerance"><?php esc_html_e( 'Color Tolerance', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<input type="range" name="HTG_logo_bg_tolerance" id="HTG_logo_bg_tolerance" value="<?php echo esc_attr( $logo_bg_tolerance ); ?>" min="5" max="80" style="width: 200px; vertical-align: middle;" />
										<span id="htg-tolerance-value" style="margin-left: 10px; font-weight: bold;"><?php echo esc_html( $logo_bg_tolerance ); ?></span>
										<p class="description"><?php esc_html_e( 'Higher values remove more colors similar to background. Default: 30', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
							<div class="htg-logo-notice" style="background: #1a1f36; border-left: 4px solid #00d4aa; padding: 12px 15px; margin-top: 20px; border-radius: 4px;">
								<p style="margin: 0; color: #a0a8c0;">
									<strong style="color: #fff;"><?php esc_html_e( 'Note:', 'adtech-pro' ); ?></strong>
									<?php esc_html_e( 'Background removal works best with solid color backgrounds. For logos with gradients or complex backgrounds, we recommend using a dedicated image editor for best results.', 'adtech-pro' ); ?>
								</p>
							</div>
						</div>
						
						<!-- Custom CSS Tab -->
						<div id="custom-css" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row">
										<label for="HTG_custom_css"><?php esc_html_e( 'Additional CSS', 'adtech-pro' ); ?></label>
									</th>
									<td>
										<textarea name="HTG_custom_css" id="HTG_custom_css" rows="15" class="large-text code" style="font-family: 'SF Mono', Monaco, monospace; font-size: 13px;"><?php echo esc_textarea( $custom_css ); ?></textarea>
										<p class="description"><?php esc_html_e( 'Add custom CSS for advanced customization. This will be added to the site header.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>
					
					<p class="submit" style="padding: 20px 40px;">
						<button type="submit" name="HTG_save_appearance" class="button button-primary button-hero">
							<span class="dashicons dashicons-saved"></span>
							<?php esc_html_e( 'Save Appearance', 'adtech-pro' ); ?>
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
			
			// Logo settings toggles
			$('#HTG_logo_remove_bg').on('change', function() {
				$('.htg-bg-removal-options').toggle(this.checked);
			});
			$('#HTG_logo_bg_tolerance').on('input', function() {
				$('#htg-tolerance-value').text(this.value);
			});
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
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Magazine settings saved!', 'adtech-pro' ) . '</strong></p></div>';
		}
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-layout"></span>
					<?php esc_html_e( 'Magazine Settings', 'adtech-pro' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Configure magazine layout, sections, and category grids', 'adtech-pro' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_magazine_settings', 'HTG_magazine_settings_nonce' ); ?>
					
					<div class="HTG-admin-card" style="margin: 20px 40px;">
						<div class="HTG-card-header">
							<h2>
								<span class="dashicons dashicons-admin-generic"></span>
								<?php esc_html_e( 'Layout Options', 'adtech-pro' ); ?>
							</h2>
						</div>
						<div class="HTG-card-body">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Magazine Layout', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_magazine_enable" value="1" <?php checked( get_option( 'HTG_magazine_enable', 1 ), 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable the magazine-style homepage layout.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Posts Per Section', 'adtech-pro' ); ?></th>
									<td>
										<input type="number" name="HTG_magazine_posts_per_section" value="<?php echo esc_attr( get_option( 'HTG_magazine_posts_per_section', '6' ) ); ?>" min="3" max="12" class="small-text">
										<p class="description"><?php esc_html_e( 'Number of posts in each magazine section (3-12).', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Layout Style', 'adtech-pro' ); ?></th>
									<td>
										<select name="HTG_magazine_layout_style" class="regular-text">
											<option value="grid" <?php selected( get_option( 'HTG_magazine_layout_style', 'grid' ), 'grid' ); ?>><?php esc_html_e( 'Grid Layout', 'adtech-pro' ); ?></option>
											<option value="list" <?php selected( get_option( 'HTG_magazine_layout_style' ), 'list' ); ?>><?php esc_html_e( 'List Layout', 'adtech-pro' ); ?></option>
											<option value="masonry" <?php selected( get_option( 'HTG_magazine_layout_style' ), 'masonry' ); ?>><?php esc_html_e( 'Masonry Layout', 'adtech-pro' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Post Format Badges', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_magazine_show_badges" value="1" <?php checked( get_option( 'HTG_magazine_show_badges', 1 ), 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Display video, gallery, and audio badges on posts.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Reading Time', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_magazine_show_reading_time" value="1" <?php checked( get_option( 'HTG_magazine_show_reading_time', 1 ), 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show estimated reading time on posts.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="HTG-admin-card" style="margin: 20px 40px;">
						<div class="HTG-card-header">
							<h2>
								<span class="dashicons dashicons-category"></span>
								<?php esc_html_e( 'Category Sections', 'adtech-pro' ); ?>
							</h2>
						</div>
						<div class="HTG-card-body">
							<p class="description" style="margin-bottom: 20px;"><?php esc_html_e( 'Select categories for each homepage section. Leave empty to hide a section.', 'adtech-pro' ); ?></p>
							
							<table class="form-table">
								<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
								<tr>
									<th scope="row"><?php printf( esc_html__( 'Section %d', 'adtech-pro' ), $i ); ?></th>
									<td>
										<?php
										wp_dropdown_categories( array(
											'name'             => 'HTG_magazine_section_' . $i . '_category',
											'selected'         => get_option( 'HTG_magazine_section_' . $i . '_category', '' ),
											'show_option_none' => esc_html__( '— Select Category —', 'adtech-pro' ),
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
							<?php esc_html_e( 'Save Magazine Settings', 'adtech-pro' ); ?>
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
			update_option( 'HTG_reading_time_enable', isset( $_POST['HTG_reading_time_enable'] ) ? 1 : 0 );
			update_option( 'HTG_progress_bar_enable', isset( $_POST['HTG_progress_bar_enable'] ) ? 1 : 0 );
			update_option( 'HTG_progress_bar_color', sanitize_hex_color( $_POST['HTG_progress_bar_color'] ?? '#00d4aa' ) );
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Engagement settings saved!', 'adtech-pro' ) . '</strong></p></div>';
		}
		
		// Get current values
		$newsletter_enable = get_option( 'HTG_newsletter_enable', 1 );
		$newsletter_title = get_option( 'HTG_newsletter_title', 'Subscribe to Our Newsletter' );
		$newsletter_description = get_option( 'HTG_newsletter_description', 'Get the latest updates delivered to your inbox.' );
		$reading_time_enable = get_option( 'HTG_reading_time_enable', 1 );
		$progress_bar_enable = get_option( 'HTG_progress_bar_enable', 1 );
		$progress_bar_color = get_option( 'HTG_progress_bar_color', '#00d4aa' );
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-awards"></span>
					<?php esc_html_e( 'Engagement Settings', 'adtech-pro' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Configure reader engagement features', 'adtech-pro' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_engagement_settings', 'HTG_engagement_nonce' ); ?>
					
					<div class="HTG-settings-tabs" style="margin: 20px 40px;">
						<h2 class="nav-tab-wrapper">
							<a href="#newsletter" class="nav-tab nav-tab-active"><?php esc_html_e( 'Newsletter', 'adtech-pro' ); ?></a>
							<a href="#reading" class="nav-tab"><?php esc_html_e( 'Reading Experience', 'adtech-pro' ); ?></a>
						</h2>
						
						<!-- Newsletter Tab -->
						<div id="newsletter" class="HTG-tab-content">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Newsletter', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_newsletter_enable" value="1" <?php checked( $newsletter_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show newsletter subscription form.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Title', 'adtech-pro' ); ?></th>
									<td>
										<input type="text" name="HTG_newsletter_title" value="<?php echo esc_attr( $newsletter_title ); ?>" class="regular-text" />
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Description', 'adtech-pro' ); ?></th>
									<td>
										<textarea name="HTG_newsletter_description" rows="3" class="large-text"><?php echo esc_textarea( $newsletter_description ); ?></textarea>
									</td>
								</tr>
							</table>
						</div>
						
						
						<!-- Reading Experience Tab -->
						<div id="reading" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Reading Time', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_reading_time_enable" value="1" <?php checked( $reading_time_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show estimated reading time on posts.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Progress Bar', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_progress_bar_enable" value="1" <?php checked( $progress_bar_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show reading progress bar at top of posts.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Progress Bar Color', 'adtech-pro' ); ?></th>
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
							<?php esc_html_e( 'Save Engagement Settings', 'adtech-pro' ); ?>
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
			update_option( 'HTG_container_width', absint( $_POST['HTG_container_width'] ?? 1920 ) );
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
			
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved!', 'adtech-pro' ) . '</strong></p></div>';
		}

		// Get values
		$site_layout = get_option( 'HTG_site_layout', 'wide' );
		$sidebar_position = get_option( 'HTG_sidebar_position', 'right' );
		$container_width = get_option( 'HTG_container_width', 1920 );
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
					<?php esc_html_e( 'General Settings', 'adtech-pro' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Configure site layout, header, slider, blog, and post settings', 'adtech-pro' ); ?></p>
			</div>
			
			<div class="HTG-admin-row">
				<form method="post" action="" style="width: 100%;">
					<?php wp_nonce_field( 'HTG_all_general_nonce' ); ?>
					
					<div class="HTG-settings-tabs" style="margin: 20px 40px;">
						<h2 class="nav-tab-wrapper">
							<a href="#site" class="nav-tab nav-tab-active"><?php esc_html_e( 'Site', 'adtech-pro' ); ?></a>
							<a href="#header" class="nav-tab"><?php esc_html_e( 'Header', 'adtech-pro' ); ?></a>
							<a href="#slider" class="nav-tab"><?php esc_html_e( 'Slider', 'adtech-pro' ); ?></a>
							<a href="#blog" class="nav-tab"><?php esc_html_e( 'Blog', 'adtech-pro' ); ?></a>
							<a href="#post" class="nav-tab"><?php esc_html_e( 'Post', 'adtech-pro' ); ?></a>
						</h2>
						
						<!-- Site Tab -->
						<div id="site" class="HTG-tab-content">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Site Layout', 'adtech-pro' ); ?></th>
									<td>
										<select name="HTG_site_layout" class="regular-text">
											<option value="wide" <?php selected( $site_layout, 'wide' ); ?>><?php esc_html_e( 'Wide (Full Width)', 'adtech-pro' ); ?></option>
											<option value="boxed" <?php selected( $site_layout, 'boxed' ); ?>><?php esc_html_e( 'Boxed (Centered)', 'adtech-pro' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Sidebar Position', 'adtech-pro' ); ?></th>
									<td>
										<select name="HTG_sidebar_position" class="regular-text">
											<option value="right" <?php selected( $sidebar_position, 'right' ); ?>><?php esc_html_e( 'Right', 'adtech-pro' ); ?></option>
											<option value="left" <?php selected( $sidebar_position, 'left' ); ?>><?php esc_html_e( 'Left', 'adtech-pro' ); ?></option>
											<option value="none" <?php selected( $sidebar_position, 'none' ); ?>><?php esc_html_e( 'None (Full Width)', 'adtech-pro' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Container Width', 'adtech-pro' ); ?></th>
									<td>
										<input type="number" name="HTG_container_width" value="<?php echo esc_attr( $container_width ); ?>" min="960" max="2560" class="small-text" /> px
										<p class="description"><?php esc_html_e( 'Default: 1920px. Container auto-adjusts for smaller screens.', 'adtech-pro' ); ?></p>
										<p class="description"><?php esc_html_e( 'Default: 1200px (Range: 960-1920px)', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Breadcrumbs', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_breadcrumbs_enable" value="1" <?php checked( $breadcrumbs_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable breadcrumb navigation.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Footer Copyright', 'adtech-pro' ); ?></th>
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
									<th scope="row"><?php esc_html_e( 'Header Layout', 'adtech-pro' ); ?></th>
									<td>
										<select name="HTG_header_layout" class="regular-text">
											<option value="default" <?php selected( $header_layout, 'default' ); ?>><?php esc_html_e( 'Default (Logo Left)', 'adtech-pro' ); ?></option>
											<option value="centered" <?php selected( $header_layout, 'centered' ); ?>><?php esc_html_e( 'Centered', 'adtech-pro' ); ?></option>
											<option value="minimal" <?php selected( $header_layout, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'adtech-pro' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Top Bar', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_topbar_enable" value="1" <?php checked( $topbar_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable top bar above header.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Sticky Header', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_sticky_header" value="1" <?php checked( $sticky_header, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Make header sticky on scroll.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Search Icon', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_header_show_search" value="1" <?php checked( $header_show_search, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show search icon in navigation.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Slider Tab -->
						<div id="slider" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Enable Slider', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_slider_enable" value="1" <?php checked( $slider_enable, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show featured slider on homepage.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Number of Posts', 'adtech-pro' ); ?></th>
									<td>
										<input type="number" name="HTG_slider_posts_count" value="<?php echo esc_attr( $slider_posts_count ); ?>" min="3" max="10" class="small-text" />
										<p class="description"><?php esc_html_e( 'Posts to show in slider (3-10).', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Autoplay', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_slider_autoplay" value="1" <?php checked( $slider_autoplay, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Enable auto-rotation.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
							</table>
						</div>
						
						<!-- Blog Tab -->
						<div id="blog" class="HTG-tab-content" style="display: none;">
							<table class="form-table">
								<tr>
									<th scope="row"><?php esc_html_e( 'Blog Layout', 'adtech-pro' ); ?></th>
									<td>
										<select name="HTG_blog_layout" class="regular-text">
											<option value="grid" <?php selected( $blog_layout, 'grid' ); ?>><?php esc_html_e( 'Grid (2 Columns)', 'adtech-pro' ); ?></option>
											<option value="list" <?php selected( $blog_layout, 'list' ); ?>><?php esc_html_e( 'List', 'adtech-pro' ); ?></option>
											<option value="large" <?php selected( $blog_layout, 'large' ); ?>><?php esc_html_e( 'Large Featured', 'adtech-pro' ); ?></option>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Excerpt Length', 'adtech-pro' ); ?></th>
									<td>
										<input type="number" name="HTG_excerpt_length" value="<?php echo esc_attr( $excerpt_length ); ?>" min="10" max="100" class="small-text" /> <?php esc_html_e( 'words', 'adtech-pro' ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Show Post Date', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_show_post_date" value="1" <?php checked( $show_post_date, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Show Author', 'adtech-pro' ); ?></th>
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
									<th scope="row"><?php esc_html_e( 'Author Box', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_post_show_author_box" value="1" <?php checked( $post_show_author_box, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
										<p class="description"><?php esc_html_e( 'Show author bio box on posts.', 'adtech-pro' ); ?></p>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Related Posts', 'adtech-pro' ); ?></th>
									<td>
										<label class="HTG-toggle">
											<input type="checkbox" name="HTG_post_show_related" value="1" <?php checked( $post_show_related, 1 ); ?>>
											<span class="HTG-toggle-slider"></span>
										</label>
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Related Posts Count', 'adtech-pro' ); ?></th>
									<td>
										<input type="number" name="HTG_post_related_count" value="<?php echo esc_attr( $post_related_count ); ?>" min="3" max="12" class="small-text" />
									</td>
								</tr>
								<tr>
									<th scope="row"><?php esc_html_e( 'Show Tags', 'adtech-pro' ); ?></th>
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
							<?php esc_html_e( 'Save All Settings', 'adtech-pro' ); ?>
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

<?php
/**
 * HTG Theme Customizer - MINIMAL (Site Identity Only)
 * 
 * Customizer ONLY allows:
 * - Site Name
 * - Tagline
 * - Logo
 * - Site Icon
 * 
 * ALL other settings are in Admin Panel!
 *
 * @package HTG_AdTech_Pro
 * @since 2.1.0
 */

/**
 * Custom Control for Admin Panel Redirect Notice
 * Must be defined BEFORE customize_register action
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class HTG_Redirect_Notice_Control extends WP_Customize_Control {
		public $type = 'redirect_notice';

		public function render_content() {
			?>
			<div class="HTG-customizer-notice" style="background: linear-gradient(135deg, #240b50 0%, #3d1a75 100%); color: #fff; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
				<h3 style="color: #fff; margin-top: 0; font-size: 16px;">
					<span class="dashicons dashicons-admin-generic" style="font-size: 20px; vertical-align: middle;"></span>
					<?php esc_html_e( 'All Theme Settings in Admin Panel!', 'adtech-pro' ); ?>
				</h3>
				<p style="margin: 10px 0; line-height: 1.6; font-size: 14px;">
					<?php esc_html_e( 'All theme customization options are available in the professional Admin Panel.', 'adtech-pro' ); ?>
				</p>
				<p style="margin: 10px 0; line-height: 1.6; font-size: 14px;">
					<strong><?php esc_html_e( 'Configure:', 'adtech-pro' ); ?></strong><br>
					• <?php esc_html_e( 'Colors, Fonts, Layout', 'adtech-pro' ); ?><br>
					• <?php esc_html_e( 'Header, Footer, Slider', 'adtech-pro' ); ?><br>
					• <?php esc_html_e( 'Blog, Posts, Pages', 'adtech-pro' ); ?><br>
					• <?php esc_html_e( 'Ads, Magazine, Engagement', 'adtech-pro' ); ?>
				</p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" 
				   class="button button-primary button-hero" 
				   style="background: #80d3f5; border-color: #80d3f5; color: #240b50; text-shadow: none; margin-top: 10px; font-weight: 600;">
					<span class="dashicons dashicons-admin-settings" style="vertical-align: middle;"></span>
					<?php esc_html_e( 'Go to Admin Panel Settings', 'adtech-pro' ); ?>
				</a>
			</div>
			<div style="padding: 15px; background: #f9f9f9; border-left: 4px solid #80d3f5; margin-top: 15px;">
				<p style="margin: 0; font-size: 13px; color: #666;">
					<strong><?php esc_html_e( 'Note:', 'adtech-pro' ); ?></strong>
					<?php esc_html_e( 'Use this Customizer only for Site Name, Tagline, Logo, and Site Icon. All other settings are in H&T AdTech Admin Panel.', 'adtech-pro' ); ?>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Customize Register - Keep ONLY Site Identity
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function HTG_sections_register( $wp_customize ) {

	// Enable postMessage for live preview (Site Identity only)
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// Remove ALL other panels and sections
	// We only keep: Site Identity (blogname, blogdescription, custom_logo, site_icon)

	// Remove default sections that we don't want
	$wp_customize->remove_section( 'colors' );
	$wp_customize->remove_section( 'header_image' );
	$wp_customize->remove_section( 'background_image' );
	$wp_customize->remove_section( 'static_front_page' );

	// NOTE: Keep nav_menus panel for menu management
	// DO NOT remove nav_menus - it breaks menu updates!

	// Add admin panel redirect notice (only if custom control class exists)
	if ( class_exists( 'HTG_Redirect_Notice_Control' ) ) {
		$wp_customize->add_section( 'HTG_admin_redirect', array(
			'title'    => esc_html__( 'Theme Settings', 'adtech-pro' ),
			'priority' => 1,
		) );

		$wp_customize->add_setting( 'HTG_redirect_notice', array(
			'default'   => '',
			'transport' => 'postMessage',
		) );

		$wp_customize->add_control( new HTG_Redirect_Notice_Control( 
			$wp_customize,
			'HTG_redirect_notice',
			array(
				'section'  => 'HTG_admin_redirect',
				'settings' => 'HTG_redirect_notice',
			)
		) );
	}
}
add_action( 'customize_register', 'HTG_sections_register' );

/**
 * Remove Kirki fields (we don't use them anymore)
 */
function HTG_kirki_fields( $fields ) {
	// Return empty array - all settings now in Admin Panel
	return array();
}
add_filter( 'kirki/fields', 'HTG_kirki_fields' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Only for Site Identity (blogname, blogdescription, logo, icon)
 */
function HTG_customize_preview_js() {
	wp_enqueue_script( 'HTG_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), time(), true );
}
add_action( 'customize_preview_init', 'HTG_customize_preview_js' );

/**
 * Enqueue the customizer stylesheet.
 */
function HTG_enqueue_customizer_stylesheets() {
	wp_register_style( 'HTG-customizer-css', get_template_directory_uri() . '/css/customizer.css', NULL, NULL, 'all' );
	wp_enqueue_style( 'HTG-customizer-css' );
}
add_action( 'customize_controls_print_styles', 'HTG_enqueue_customizer_stylesheets' );

/**
 * Sanitize hex color
 */
function HTG_sanitize_hex_color( $hex_color, $setting ) {
	$hex_color = sanitize_hex_color( $hex_color );
	return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
}

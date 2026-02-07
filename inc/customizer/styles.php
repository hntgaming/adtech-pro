<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Legacy custom styles - DISABLED
 * 
 * Color output is now handled by the unified palette system in color-palettes.php.
 * All color rules use CSS variables from the master design tokens in style.css.
 * 
 * This function only handles the header background image (non-color) functionality.
 */
function HTG_custom_styles() {
	$HTG_custom_styles = "";

	// Header background image support (not color-related, still needed)
	if ( get_theme_mod( 'header_image_position', 'after-site-title' ) == 'header-background' ) {
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) {
			$HTG_custom_styles .= '
				.hm-header-bg-holder {
					background-image: url(' . esc_url( $header_image ) . ');
					background-size: cover;
					background-repeat: no-repeat;
				}
			';
		}
	}

	if ( ! empty( $HTG_custom_styles ) ) { ?>
		<style type="text/css">
			<?php echo $HTG_custom_styles; ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'HTG_custom_styles' );
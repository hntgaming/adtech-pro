<?php
/**
 * Dark Theme - Default and Only Mode
 * This theme is designed exclusively for dark backgrounds
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Always add dark mode body class
 * Theme is dark by design, no toggle needed
 */
function HTG_dark_mode_body_class( $classes ) {
	$classes[] = 'htg-dark-theme';
	return $classes;
}
add_filter( 'body_class', 'HTG_dark_mode_body_class' );

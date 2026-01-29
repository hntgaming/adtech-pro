<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package HTG_AdTech_Pro
 */

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 */
function HTG_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'HTG_infinite_scroll_render',
		'footer'    => 'page',
	) );

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );
}
add_action( 'after_setup_theme', 'HTG_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function HTG_infinite_scroll_render() {

	$archive_content_layout = get_option( 'archive_content_layout', 'th-grid-2' );
	echo '<div class="posts-wrap ' . esc_attr( $archive_content_layout ) . '">';	

	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_format() );
		endif;
	}

	echo '</div>';
}

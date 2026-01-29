<?php
/**
 * Breadcrumb System
 * SEO-friendly breadcrumb navigation
 *
 * @package HTG
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display breadcrumbs
 */
function HTG_breadcrumbs() {
	// Check if breadcrumbs are enabled
	if ( ! get_theme_mod( 'HTG_enable_breadcrumbs', true ) ) {
		return;
	}

	// Don't show on front page
	if ( is_front_page() ) {
		return;
	}

	$separator = ' <span class="breadcrumb-separator">/</span> ';
	$home_title = __( 'Home', 'HTG' );

	// Start breadcrumb
	echo '<nav class="hm-breadcrumb-wrap" aria-label="' . esc_attr__( 'Breadcrumb', 'HTG' ) . '">';
	echo '<div class="breadcrumb-trail">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="breadcrumb-home">' . esc_html( $home_title ) . '</a>';

	if ( is_category() || is_single() ) {
		$category = get_the_category();
		
		if ( $category ) {
			// Get the first category
			$cat = $category[0];
			
			// Get category ancestors if any
			if ( $cat->parent != 0 ) {
				$ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
				foreach ( $ancestors as $ancestor ) {
					echo $separator;
					echo '<a href="' . esc_url( get_category_link( $ancestor ) ) . '">' . esc_html( get_cat_name( $ancestor ) ) . '</a>';
				}
			}
			
			echo $separator;
			echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
		}
		
		if ( is_single() ) {
			echo $separator;
			echo '<span class="breadcrumb-current">' . esc_html( get_the_title() ) . '</span>';
		}
	} elseif ( is_tag() ) {
		echo $separator;
		echo '<span class="breadcrumb-current">' . esc_html__( 'Tag: ', 'HTG' ) . esc_html( single_tag_title( '', false ) ) . '</span>';
	} elseif ( is_author() ) {
		echo $separator;
		echo '<span class="breadcrumb-current">' . esc_html__( 'Author: ', 'HTG' ) . esc_html( get_the_author() ) . '</span>';
	} elseif ( is_date() ) {
		echo $separator;
		if ( is_day() ) {
			echo '<span class="breadcrumb-current">' . esc_html( get_the_date() ) . '</span>';
		} elseif ( is_month() ) {
			echo '<span class="breadcrumb-current">' . esc_html( get_the_date( 'F Y' ) ) . '</span>';
		} elseif ( is_year() ) {
			echo '<span class="breadcrumb-current">' . esc_html( get_the_date( 'Y' ) ) . '</span>';
		}
	} elseif ( is_search() ) {
		echo $separator;
		echo '<span class="breadcrumb-current">' . esc_html__( 'Search results for: ', 'HTG' ) . esc_html( get_search_query() ) . '</span>';
	} elseif ( is_404() ) {
		echo $separator;
		echo '<span class="breadcrumb-current">' . esc_html__( '404 - Page Not Found', 'HTG' ) . '</span>';
	} elseif ( is_page() ) {
		global $post;
		
		// Show parent pages if any
		if ( $post->post_parent ) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			
			while ( $parent_id ) {
				$page = get_post( $parent_id );
				$breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
				$parent_id = $page->post_parent;
			}
			
			$breadcrumbs = array_reverse( $breadcrumbs );
			
			foreach ( $breadcrumbs as $breadcrumb ) {
				echo $separator . $breadcrumb;
			}
		}
		
		echo $separator;
		echo '<span class="breadcrumb-current">' . esc_html( get_the_title() ) . '</span>';
	} elseif ( is_archive() ) {
		echo $separator;
		echo '<span class="breadcrumb-current">' . esc_html( get_the_archive_title() ) . '</span>';
	}

	echo '</div>';
	echo '</nav>';
}


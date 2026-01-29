<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package HTG_AdTech_Pro
 */

if ( ! function_exists( 'HTG_posted_datetime' ) ) :

function HTG_posted_datetime() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	return $time_string;
}

endif; 

if ( ! function_exists( 'HTG_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function HTG_posted_on() {

	if ( is_single() ) {
		$meta_display = get_theme_mod( 'single_post_meta', array( 'categories', 'date', 'author', 'comments' ) );
	} elseif ( is_page_template() ) {
		$meta_display = array( 'categories', 'date', 'author', 'comments' );
	} else {
		$meta_display = get_theme_mod( 'blog_post_meta', array( 'categories', 'date', 'author', 'comments' ) );
	}

	// Return if the $meta_display array is empty.
	if ( empty( $meta_display ) ) {
		return;
	}

	$HTG_meta = array();

	if ( in_array( 'date', $meta_display ) ) {

		$time_string = HTG_posted_datetime();

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';	

		$HTG_meta[] = '<span class="posted-on">' . $posted_on . '</span>';

	}
	
	if ( in_array( 'author', $meta_display ) ) {

		$byline = sprintf(
			esc_html__( 'by %s', 'HTG' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		$HTG_meta[] = '<span class="byline"> ' . $byline . '</span>';

	}

	if ( in_array( 'comments', $meta_display ) ) {

		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {

			$num_comments = esc_attr( get_comments_number() );

			if ( $num_comments == 0 ) {
				$comments_txt = esc_html__( 'Leave a Comment', 'HTG' );
			} elseif ( $num_comments > 1 ) {
				/* translators: %d: number of comments */
				$comments_txt = sprintf( esc_html__( '%d Comments.', 'HTG' ), $num_comments );
			} else {
				$comments_txt = esc_html__( '1 Comment', 'HTG' );
			}

			$HTG_meta[] = '<span class="comments-link"><a href="' . esc_url( get_comments_link() ).'">' . $comments_txt . '</a></span>';
		}			

	}

	if ( ! empty( $HTG_meta ) ) {
		$HTG_meta_str = implode( '<span class="meta-sep"> - </span>', $HTG_meta );
		echo $HTG_meta_str;
	}

}
endif;

if ( ! function_exists( 'HTG_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function HTG_entry_footer() {
	
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		if ( true == get_theme_mod( 'taglist_sw' , true ) ) :
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list();
			if ( $tags_list ) {
				echo '<span class="hm-tags-links">';
					echo '<span class="hm-tagged">';
						_e( 'Tagged', 'HTG' );
					echo '</span>';
					echo $tags_list;
				echo '</span>';
			}
		endif;
	}
	
	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'HTG' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="hm-edit-link">',
		'</span>'
	);
}
endif;

if ( ! function_exists( 'HTG_category_list' ) ) :
/**
 * Prints categories list.
 */
function HTG_category_list() {

	if ( 'post' === get_post_type() ) {

		if ( is_single() ) {
			$meta_display = get_theme_mod( 'single_post_meta', array( 'categories', 'date', 'author', 'comments' ) );
		} elseif ( is_page_template() ) {
			$meta_display = array( 'categories', 'date', 'author', 'comments' );
		} else {
			$meta_display = get_theme_mod( 'blog_post_meta', array( 'categories', 'date', 'author', 'comments' ) );
		}

		if( in_array( 'categories', $meta_display ) ) {

			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ' / ', 'HTG' ) );
			if ( $categories_list && HTG_categorized_blog() ) {
				echo '<div class="cat-links">' . $categories_list . '</div>';
			}	

		}
	}
	
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function HTG_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'HTG_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'HTG_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so HTG_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so HTG_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in HTG_categorized_blog.
 */
function HTG_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'HTG_categories' );
}
add_action( 'edit_category', 'HTG_category_transient_flusher' );
add_action( 'save_post',     'HTG_category_transient_flusher' );

/**
 * Returns different size featured images based on the layout.
 *
 * @return string
 */
function HTG_thumb_size() {

	$archive_sidebar_align = get_option( 'archive_sidebar_align', 'th-right-sidebar' );
	$archive_content_layout = get_option( 'archive_content_layout', 'th-grid-2' );

	$thumb_size = 'HTG-grid';

	if ( $archive_sidebar_align == 'th-no-sidebar' ) {
		if ( $archive_content_layout == 'th-grid-2' ) {
			return $thumb_size = 'HTG-featured';
		} elseif ( $archive_content_layout == 'th-large-posts' ) {
			return $thumb_size = 'HTG-landscape';
		} elseif ( $archive_content_layout == 'th-list-posts' ) {
			return $thumb_size = 'HTG-grid';
		}
	}

	if ( $archive_content_layout == 'th-list-posts' ) {
		return $thumb_size = 'HTG-list';
	}

	if ( $archive_content_layout == 'th-large-posts' ) {
		return $thumb_size = 'HTG-featured';
	}

	return $thumb_size;

}

/**
 * Displays the featured image in single post.
 */
function HTG_single_post_thumbnail() {

	global $post;

	if ( false == get_theme_mod( 'single_thumbnail_sw', true ) ) {
		return;
	}

	$HTG_layout = HTG_get_layout();
	$thumb_size = 'HTG-featured';

	if ( $HTG_layout == 'th-no-sidebar' ) {
		$thumb_size = 'HTG-landscape';
	} 
	
	$link_to_original = get_theme_mod( 'thumb_link_to_original', true );
	
	if ( $link_to_original == true ) {  
		if( true == get_theme_mod( 'use_lightbox', true ) ) {
			$link_class = "image-link";
		} else {
			$link_class = '';
		}
		echo '<a class="' . esc_attr( $link_class ) . '" href="' . esc_url ( get_the_post_thumbnail_url( $post->ID, 'full' ) ) . '">'; 
	}
		
		the_post_thumbnail( $thumb_size );

	if ( $link_to_original == true ) { echo '</a>'; }

}

/**
 * Removes the archive title label for category.
 */
function HTG_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    }
  
    return $title;
}
add_filter( 'get_the_archive_title', 'HTG_archive_title' );

if ( ! function_exists( 'HTG_get_selected_breadcrumb' ) ) {
	/**
	 * Get the user selected breadcrumb
	 * 
	 * @since 1.4.0
	 */
	function HTG_get_selected_breadcrumb() {
		$breadcrumb_source = get_theme_mod( 'HTG_breadcrumb_source', 'none' );

		if ( 'yoast' === $breadcrumb_source ) {
			if ( function_exists( 'yoast_breadcrumb' ) ) {
				yoast_breadcrumb( '<div id="hm-yoast-breadcrumbs">', '</div>' );
			}
		} elseif ( 'navxt' === $breadcrumb_source ) {
			if ( function_exists( 'bcn_display' ) ) {
				bcn_display();
			}
		} elseif ( 'rankmath' === $breadcrumb_source ) {
			if ( function_exists( 'rank_math_the_breadcrumbs') ) {
				rank_math_the_breadcrumbs();
			}
		}
	}

}

if ( ! function_exists( 'HTG_breadcrumb_template' ) ) {
	/**
	 * Adds the breadcrumb to the selected location
	 * 
	 * @since 1.4.0
	 */
	function HTG_breadcrumb_template() {

		if ( 'none' === get_theme_mod( 'HTG_breadcrumb_source', 'none' ) ) {
			return;
		}

		if ( 'before-entry-header' === get_theme_mod( 'HTG_breadcrumb_location', 'before-entry-header' ) ) {
			if ( is_archive() || is_search() ) {
				add_action( 'HTG_before_main_content', 'HTG_hook_breadcrumb_location', 15 );
			} elseif ( is_singular() ) {
				add_action( 'HTG_before_entry_header', 'HTG_hook_breadcrumb_location', 15 );
			}
		} else {
			add_action( 'HTG_after_header', 'HTG_hook_breadcrumb_location', 15 );
		}

	}
	add_action( 'wp', 'HTG_breadcrumb_template' );
}

if ( ! function_exists( 'HTG_hook_breadcrumb_location' ) ) {
	/**
	 * Hook breadcrumb template to the selected location.
	 * 
	 * @since 1.4.0
	 */
	function HTG_hook_breadcrumb_location() {
		$breadcrumb_location = get_theme_mod( 'HTG_breadcrumb_location', 'before-entry-header' );

		if ( 'after-site-header' === $breadcrumb_location ) {
			echo '<div class="hm-header-bar hm-header-breadcrumb">
					<div class="hm-container">';
		}

		echo '<div class="hm-breadcrumb-wrap">';
		
		HTG_get_selected_breadcrumb();

		echo '</div>';

		if ( 'after-site-header' === $breadcrumb_location ) {
			echo '</div>
				</div>';
		}

	}

}
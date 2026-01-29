<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package HTG
 */

get_header(); ?>

	<?php do_action( 'HTG_before_content' ); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php

		do_action( 'HTG_before_main_content' );
		
		// Ad Slot: Above Post Title (shows in content-single.php)
		
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );
			
			// Ad Slot: Post Bottom
			HTG_display_ad( 'post_bottom' );

			if ( true == get_theme_mod( 'relatedposts_sw', true ) ) :
				get_template_part( 'template-parts/related-posts' );
			endif;

			if ( true == get_theme_mod( 'postsnav_sw', true ) ) :
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next Article', 'HTG' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Previous Article', 'HTG' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );
			endif;

			if ( true == get_theme_mod( 'authorbox_sw', true) ) :
				get_template_part( 'template-parts/authorbox' );
			endif;

			// Note: Above Comments slot removed (not in simple ad system)
			// Use post_bottom instead

			do_action( 'HTG_before_comments_template' );
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
				do_action( 'HTG_after_comments_template' );
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();

do_action( 'HTG_after_content' );

get_footer();
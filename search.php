<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package HTG_AdTech_Pro
 */

get_header(); ?>

	<?php do_action( 'HTG_before_content' ); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		
		do_action( 'HTG_before_main_content' );

		if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title srch-page-title"><?php printf( esc_html__( 'Search Results for: %s', 'HTG' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php 

			$archive_content_layout = get_option( 'archive_content_layout', 'th-grid-2' );
			echo '<div class="posts-wrap ' . esc_attr( $archive_content_layout ) . '">';

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			echo '</div><!-- .posts-wrap -->';

			the_posts_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>
		
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_sidebar();

do_action( 'HTG_after_content' );

get_footer();

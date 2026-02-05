<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package HTG_AdTech_Pro
 */

get_header(); ?>

<?php do_action( 'HTG_before_content' ); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'adtech-pro' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'adtech-pro' ); ?></p>

					<?php get_search_form(); ?>
					
					<div class="error-404-suggestions">
						<h2><?php esc_html_e( 'Helpful Links', 'adtech-pro' ); ?></h2>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Homepage', 'adtech-pro' ); ?></a></li>
							<?php 
							// Show recent posts
							$recent_posts = wp_get_recent_posts( array(
								'numberposts' => 5,
								'post_status' => 'publish'
							) );
							
							if ( ! empty( $recent_posts ) ) :
								foreach ( $recent_posts as $post ) : ?>
									<li>
										<a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
											<?php echo esc_html( $post['post_title'] ); ?>
										</a>
									</li>
								<?php endforeach;
							endif;
							?>
						</ul>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();

do_action( 'HTG_after_content' );

get_footer();
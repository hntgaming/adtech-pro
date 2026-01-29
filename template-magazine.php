<?php
/**
 * Template Name: Magazine Homepage
 *
 * Displays the Magazine Template with configurable category sections
 * Settings are controlled from H&T AdTech > Magazine in admin panel
 * 
 * @package HTG_AdTech_Pro
 */

get_header(); ?>

<?php do_action( 'HTG_before_content' ); ?>

<?php do_action( 'HTG_magazine_top' ); ?>

<div id="primary" class="content-area magazine-layout">
	<main id="main" class="site-main" role="main">

		<?php
		// Featured Slider
		do_action( 'HTG_before_magazine_slider' );
		get_template_part( 'template-parts/featured-slider' );
		do_action( 'HTG_after_magazine_slider' );

		// Ad Slot: Homepage Top
		if ( function_exists( 'HTG_display_ad' ) ) {
			HTG_display_ad( 'homepage_top' );
		}

		// Get magazine settings from admin panel
		$magazine_enabled = get_option( 'HTG_magazine_enable', 1 );
		$posts_per_section = absint( get_option( 'HTG_magazine_posts_per_section', 4 ) );
		$layout_style = get_option( 'HTG_magazine_layout_style', 'grid' );
		$show_badges = get_option( 'HTG_magazine_show_badges', 1 );
		$show_reading_time = get_option( 'HTG_magazine_show_reading_time', 1 );

		// Check if magazine is enabled
		if ( ! $magazine_enabled ) :
			?>
			<div class="magazine-disabled-notice">
				<p><?php esc_html_e( 'Magazine layout is disabled. Enable it from H&T AdTech → Magazine settings.', 'HTG' ); ?></p>
			</div>
			<?php
		else :

			// Get configured categories from admin settings
			$configured_categories = array();
			for ( $i = 1; $i <= 4; $i++ ) {
				$cat_id = absint( get_option( 'HTG_magazine_section_' . $i . '_category', 0 ) );
				if ( $cat_id > 0 ) {
					$configured_categories[] = $cat_id;
				}
			}

			// If no categories configured, fall back to top 4 categories by post count
			if ( empty( $configured_categories ) ) {
				$categories = get_categories( array(
					'orderby'    => 'count',
					'order'      => 'DESC',
					'number'     => 4,
					'exclude'    => array( 1 ), // Exclude "Uncategorized"
					'hide_empty' => true,
				) );
				$configured_categories = wp_list_pluck( $categories, 'term_id' );
			}

			if ( ! empty( $configured_categories ) ) :
				$section_count = 0;
				
				foreach ( $configured_categories as $category_id ) :
					$category = get_category( $category_id );
					
					if ( ! $category || is_wp_error( $category ) ) {
						continue;
					}
					
					$section_count++;
					
					// Query posts from this category
					$cat_query = new WP_Query( array(
						'cat'            => $category->term_id,
						'posts_per_page' => $posts_per_section,
						'post_status'    => 'publish',
						'no_found_rows'  => true, // Performance optimization
					) );

					if ( $cat_query->have_posts() ) : ?>

						<section class="magazine-section magazine-section-<?php echo esc_attr( $section_count ); ?> magazine-layout-<?php echo esc_attr( $layout_style ); ?>">
							<div class="magazine-section-header">
								<h2 class="section-title">
									<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
										<?php echo esc_html( $category->name ); ?>
									</a>
								</h2>
								<span class="section-count"><?php echo esc_html( $category->count ); ?> <?php esc_html_e( 'Posts', 'HTG' ); ?></span>
							</div>

							<div class="magazine-posts-grid magazine-grid-<?php echo esc_attr( $layout_style ); ?>">
								<?php
								$post_index = 0;
								while ( $cat_query->have_posts() ) : $cat_query->the_post();
									$post_index++;
									$post_class = ( $post_index === 1 ) ? 'magazine-post magazine-post-featured' : 'magazine-post magazine-post-small';
									?>

									<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
										<?php if ( has_post_thumbnail() ) : ?>
											<div class="post-thumbnail">
												<a href="<?php the_permalink(); ?>">
													<?php
													if ( $post_index === 1 ) {
														the_post_thumbnail( 'large' );
													} else {
														the_post_thumbnail( 'medium' );
													}
													?>
												</a>
												<?php if ( $show_badges && get_post_format() ) : ?>
													<span class="post-format-badge post-format-<?php echo esc_attr( get_post_format() ); ?>">
														<?php
														$format = get_post_format();
														$format_icons = array(
															'video'   => '🎬',
															'audio'   => '🎵',
															'gallery' => '🖼️',
															'quote'   => '💬',
															'link'    => '🔗',
														);
														echo isset( $format_icons[ $format ] ) ? $format_icons[ $format ] : '';
														?>
													</span>
												<?php endif; ?>
											</div>
										<?php endif; ?>

										<div class="post-content-wrapper">
											<header class="entry-header">
												<div class="entry-meta">
													<?php
													$categories_list = get_the_category_list( ', ' );
													if ( $categories_list ) {
														echo '<span class="cat-links">' . $categories_list . '</span>';
													}
													?>
												</div>

												<?php
												if ( $post_index === 1 ) {
													the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
												} else {
													the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' );
												}
												?>

												<div class="entry-meta-bottom">
													<span class="posted-on">
														<i class="far fa-calendar"></i>
														<?php echo get_the_date(); ?>
													</span>
													<span class="posted-by">
														<i class="far fa-user"></i>
														<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
															<?php echo get_the_author(); ?>
														</a>
													</span>
													<?php if ( $show_reading_time && function_exists( 'HTG_reading_time' ) ) : ?>
														<span class="reading-time">
															<i class="far fa-clock"></i>
															<?php printf( esc_html__( '%d min read', 'HTG' ), HTG_reading_time() ); ?>
														</span>
													<?php endif; ?>
												</div>
											</header>

											<?php if ( $post_index === 1 ) : ?>
												<div class="entry-summary">
													<?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
												</div>
												<a href="<?php the_permalink(); ?>" class="read-more-btn">
													<?php esc_html_e( 'Read More', 'HTG' ); ?> <i class="fas fa-arrow-right"></i>
												</a>
											<?php endif; ?>
										</div>
									</article>

								<?php endwhile; ?>
							</div><!-- .magazine-posts-grid -->

							<div class="section-view-all">
								<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="view-all-btn">
									<?php esc_html_e( 'View All', 'HTG' ); ?> <?php echo esc_html( $category->name ); ?> <i class="fas fa-arrow-right"></i>
								</a>
							</div>
						</section>

					<?php
					endif;
					wp_reset_postdata();

				endforeach;

			else : ?>

				<div class="magazine-empty-state">
					<div class="empty-icon">
						<i class="fas fa-newspaper fa-3x"></i>
					</div>
					<h2><?php esc_html_e( 'No Categories Configured', 'HTG' ); ?></h2>
					<p><?php esc_html_e( 'Go to H&T AdTech → Magazine to select categories for your homepage sections.', 'HTG' ); ?></p>
					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-magazine-settings' ) ); ?>" class="button button-primary">
							<?php esc_html_e( 'Configure Magazine', 'HTG' ); ?>
						</a>
					<?php endif; ?>
				</div>

			<?php endif; ?>

		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();

do_action( 'HTG_magazine_bottom' );

do_action( 'HTG_after_content' );

get_footer();

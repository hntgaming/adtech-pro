<?php
/**
 * AJAX Post Loading System
 * Load more posts without page refresh (like innovation4world.com)
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Ajax_Posts {

	/**
	 * Initialize AJAX posts system
	 */
	public static function init() {
		add_action( 'wp_ajax_HTG_load_more_posts', array( __CLASS__, 'load_more_posts' ) );
		add_action( 'wp_ajax_nopriv_HTG_load_more_posts', array( __CLASS__, 'load_more_posts' ) );
		add_action( 'wp_ajax_HTG_filter_posts', array( __CLASS__, 'filter_posts' ) );
		add_action( 'wp_ajax_nopriv_HTG_filter_posts', array( __CLASS__, 'filter_posts' ) );
		add_shortcode( 'HTG_post_grid', array( __CLASS__, 'render_post_grid_shortcode' ) );
	}

	/**
	 * Load more posts via AJAX
	 */
	public static function load_more_posts() {
		check_ajax_referer( 'HTG_ajax_nonce', 'nonce' );

		$page = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
		$posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 9;
		$category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'paged'          => $page,
		);

		if ( ! empty( $category ) && 'all' !== $category ) {
			$args['cat'] = intval( $category );
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			ob_start();
			
			while ( $query->have_posts() ) : $query->the_post();
				self::render_post_card();
			endwhile;
			
			$html = ob_get_clean();
			
			wp_send_json_success( array(
				'html'      => $html,
				'has_more'  => $page < $query->max_num_pages,
				'max_pages' => $query->max_num_pages,
			) );
		} else {
			wp_send_json_error( array(
				'message' => __( 'No more posts to load.', 'adtech-pro' ),
			) );
		}

		wp_reset_postdata();
	}

	/**
	 * Filter posts by category
	 */
	public static function filter_posts() {
		check_ajax_referer( 'HTG_ajax_nonce', 'nonce' );

		$category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
		$posts_per_page = isset( $_POST['posts_per_page'] ) ? intval( $_POST['posts_per_page'] ) : 9;

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $posts_per_page,
			'paged'          => 1,
		);

		if ( ! empty( $category ) && 'all' !== $category ) {
			$args['cat'] = intval( $category );
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			ob_start();
			
			while ( $query->have_posts() ) : $query->the_post();
				self::render_post_card();
			endwhile;
			
			$html = ob_get_clean();
			
			wp_send_json_success( array(
				'html'      => $html,
				'has_more'  => $query->max_num_pages > 1,
				'max_pages' => $query->max_num_pages,
				'count'     => $query->found_posts,
			) );
		} else {
			wp_send_json_error( array(
				'message' => __( 'No posts found in this category.', 'adtech-pro' ),
			) );
		}

		wp_reset_postdata();
	}

	/**
	 * Render single post card
	 */
	private static function render_post_card() {
		?>
		<article <?php post_class( 'HTG-ajax-post-card' ); ?> data-post-id="<?php the_ID(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="HTG-ajax-post-thumbnail">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'HTG-medium-thumb' ); ?>
					</a>
					
					<?php if ( get_post_format() ) : ?>
						<span class="HTG-post-format-badge">
							<span class="dashicons <?php echo esc_attr( HTG_get_post_format_icon() ); ?>"></span>
						</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="HTG-ajax-post-content">
				<?php HTG_category_list(); ?>
				
				<h3 class="HTG-ajax-post-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>

				<div class="HTG-ajax-post-meta">
					<span class="HTG-ajax-post-date">
						<span class="dashicons dashicons-calendar-alt"></span>
						<?php echo get_the_date(); ?>
					</span>
					<span class="HTG-ajax-post-author">
						<span class="dashicons dashicons-admin-users"></span>
						<?php the_author(); ?>
					</span>
					<?php if ( function_exists( 'HTG_reading_time' ) ) : ?>
						<span class="HTG-ajax-post-reading-time">
							<span class="dashicons dashicons-clock"></span>
							<?php echo esc_html( HTG_reading_time() ); ?> <?php esc_html_e( 'min read', 'adtech-pro' ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div class="HTG-ajax-post-excerpt">
					<?php the_excerpt(); ?>
				</div>

				<a href="<?php the_permalink(); ?>" class="HTG-ajax-post-link">
					<?php esc_html_e( 'Read More', 'adtech-pro' ); ?>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</a>
			</div>
		</article>
		<?php
	}

	/**
	 * Render post grid with filters
	 */
	public static function render_post_grid( $atts = array() ) {
		$atts = shortcode_atts( array(
			'posts_per_page' => 9,
			'category'       => '',
			'show_filters'   => 'yes',
			'columns'        => 3,
		), $atts );

		$categories = get_categories( array(
			'orderby' => 'count',
			'order'   => 'DESC',
			'number'  => 10,
		) );

		ob_start();
		?>
		<div class="HTG-ajax-post-grid-container" data-posts-per-page="<?php echo esc_attr( $atts['posts_per_page'] ); ?>" data-columns="<?php echo esc_attr( $atts['columns'] ); ?>">
			
			<?php if ( 'yes' === $atts['show_filters'] && ! empty( $categories ) ) : ?>
				<div class="HTG-post-filters">
					<h2 class="HTG-filters-title"><?php esc_html_e( 'Explore by Category', 'adtech-pro' ); ?></h2>
					
					<div class="HTG-filter-buttons">
						<button class="HTG-filter-btn active" data-category="all">
							<?php esc_html_e( 'All', 'adtech-pro' ); ?>
						</button>
						<?php foreach ( $categories as $category ) : ?>
							<button class="HTG-filter-btn" data-category="<?php echo esc_attr( $category->term_id ); ?>">
								<?php echo esc_html( $category->name ); ?>
								<span class="HTG-filter-count">(<?php echo esc_html( $category->count ); ?>)</span>
							</button>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="HTG-ajax-posts-grid HTG-grid-columns-<?php echo esc_attr( $atts['columns'] ); ?>">
				<?php
				$query_args = array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => $atts['posts_per_page'],
					'paged'          => 1,
				);

				if ( ! empty( $atts['category'] ) ) {
					$query_args['cat'] = intval( $atts['category'] );
				}

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) : $query->the_post();
						self::render_post_card();
					endwhile;
				else :
					?>
					<div class="HTG-no-posts">
						<p><?php esc_html_e( 'No posts found.', 'adtech-pro' ); ?></p>
					</div>
					<?php
				endif;

				wp_reset_postdata();
				?>
			</div>

			<?php if ( $query->max_num_pages > 1 ) : ?>
				<div class="HTG-load-more-container">
					<button class="HTG-load-more-btn" data-page="1" data-max-pages="<?php echo esc_attr( $query->max_num_pages ); ?>">
						<span class="HTG-load-more-text"><?php esc_html_e( 'Load More Posts', 'adtech-pro' ); ?></span>
						<span class="HTG-load-more-loading" style="display: none;">
							<?php esc_html_e( 'Loading...', 'adtech-pro' ); ?>
							<span class="HTG-spinner"></span>
						</span>
					</button>
				</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render post grid shortcode
	 */
	public static function render_post_grid_shortcode( $atts ) {
		return self::render_post_grid( $atts );
	}
}

// Initialize AJAX posts system
HTG_Ajax_Posts::init();


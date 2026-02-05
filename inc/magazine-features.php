<?php
/**
 * Magazine Homepage Features
 * Advanced Pro features from HTG Pro
 *
 * @package HTG
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add post format support
 */
function HTG_add_post_formats() {
	add_theme_support( 'post-formats', array(
		'video',
		'audio',
		'gallery',
		'quote',
		'link',
		'status',
		'aside',
	) );
}
add_action( 'after_setup_theme', 'HTG_add_post_formats' );

/**
 * Add custom image sizes for magazine layouts
 */
function HTG_add_custom_image_sizes() {
	add_image_size( 'HTG-hero', 1200, 600, true );
	add_image_size( 'HTG-large-thumb', 800, 500, true );
	add_image_size( 'HTG-medium-thumb', 600, 400, true );
	add_image_size( 'HTG-small-thumb', 400, 250, true );
	add_image_size( 'HTG-square', 400, 400, true );
}
add_action( 'after_setup_theme', 'HTG_add_custom_image_sizes' );

/**
 * Get post format icon
 */
function HTG_get_post_format_icon( $format = '' ) {
	if ( empty( $format ) ) {
		$format = get_post_format();
	}

	$icons = array(
		'video'   => 'dashicons-video-alt3',
		'audio'   => 'dashicons-format-audio',
		'gallery' => 'dashicons-format-gallery',
		'quote'   => 'dashicons-format-quote',
		'link'    => 'dashicons-admin-links',
		'status'  => 'dashicons-format-status',
		'aside'   => 'dashicons-format-aside',
	);

	return isset( $icons[ $format ] ) ? $icons[ $format ] : 'dashicons-admin-post';
}

/**
 * Add magazine customizer settings
 */
function HTG_magazine_customizer( $wp_customize ) {
	// Magazine Section
	$wp_customize->add_section( 'HTG_magazine', array(
		'title'    => __( 'Magazine Homepage', 'adtech-pro' ),
		'priority' => 35,
	) );

	// Enable Magazine Layout
	$wp_customize->add_setting( 'HTG_enable_magazine_layout', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	) );

	$wp_customize->add_control( 'HTG_enable_magazine_layout', array(
		'label'       => __( 'Enable Magazine Layout', 'adtech-pro' ),
		'description' => __( 'Show magazine-style sections on homepage', 'adtech-pro' ),
		'section'     => 'HTG_magazine',
		'type'        => 'checkbox',
	) );

	// Featured Posts Count
	$wp_customize->add_setting( 'HTG_featured_posts_count', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'HTG_featured_posts_count', array(
		'label'       => __( 'Featured Posts Count', 'adtech-pro' ),
		'description' => __( 'Number of posts in slider', 'adtech-pro' ),
		'section'     => 'HTG_magazine',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 3,
			'max'  => 10,
			'step' => 1,
		),
	) );

	// Category Sections
	for ( $i = 1; $i <= 4; $i++ ) {
		$wp_customize->add_setting( 'HTG_category_section_' . $i, array(
			'default'           => '',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( 'HTG_category_section_' . $i, array(
			'label'       => sprintf( __( 'Category Section %d', 'adtech-pro' ), $i ),
			'description' => __( 'Choose category to display', 'adtech-pro' ),
			'section'     => 'HTG_magazine',
			'type'        => 'select',
			'choices'     => HTG_get_categories_list(),
		) );

		$wp_customize->add_setting( 'HTG_category_section_' . $i . '_posts', array(
			'default'           => 4,
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( 'HTG_category_section_' . $i . '_posts', array(
			'label'       => sprintf( __( 'Category Section %d - Posts Count', 'adtech-pro' ), $i ),
			'section'     => 'HTG_magazine',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 3,
				'max'  => 12,
				'step' => 1,
			),
		) );
	}

	// Excerpt Length
	$wp_customize->add_setting( 'HTG_excerpt_length', array(
		'default'           => 30,
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( 'HTG_excerpt_length', array(
		'label'       => __( 'Excerpt Length', 'adtech-pro' ),
		'description' => __( 'Number of words in excerpt', 'adtech-pro' ),
		'section'     => 'HTG_magazine',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 10,
			'max'  => 100,
			'step' => 5,
		),
	) );
}
add_action( 'customize_register', 'HTG_magazine_customizer' );

/**
 * Get categories list for customizer
 */
function HTG_get_categories_list() {
	$categories = get_categories( array(
		'orderby' => 'name',
		'order'   => 'ASC',
	) );

	$choices = array( '' => __( '-- Select Category --', 'adtech-pro' ) );

	foreach ( $categories as $category ) {
		$choices[ $category->term_id ] = $category->name;
	}

	return $choices;
}

/**
 * Render magazine homepage sections
 * Uses admin panel settings (get_option) for configuration
 */
function HTG_render_magazine_sections() {
	// Check admin panel setting first, fall back to theme_mod for backward compatibility
	$magazine_enabled = get_option( 'HTG_magazine_enable', null );
	if ( $magazine_enabled === null ) {
		$magazine_enabled = get_theme_mod( 'HTG_enable_magazine_layout', true );
	}
	
	if ( ! $magazine_enabled ) {
		return;
	}

	$posts_per_section = absint( get_option( 'HTG_magazine_posts_per_section', 4 ) );

	// Render 4 category sections using admin panel settings
	for ( $i = 1; $i <= 4; $i++ ) {
		// Try admin panel option first, then fall back to theme_mod
		$category_id = absint( get_option( 'HTG_magazine_section_' . $i . '_category', 0 ) );
		if ( ! $category_id ) {
			$category_id = get_theme_mod( 'HTG_category_section_' . $i, '' );
		}
		
		$posts_count = get_option( 'HTG_magazine_posts_per_section', null );
		if ( $posts_count === null ) {
			$posts_count = get_theme_mod( 'HTG_category_section_' . $i . '_posts', 4 );
		}

		if ( empty( $category_id ) ) {
			continue;
		}

		HTG_render_category_section( $category_id, absint( $posts_count ), $i );
	}
}

/**
 * Render individual category section
 */
function HTG_render_category_section( $category_id, $posts_count, $section_number ) {
	$category = get_category( $category_id );
	
	if ( ! $category || is_wp_error( $category ) ) {
		return;
	}

	$args = array(
		'cat'            => $category_id,
		'posts_per_page' => $posts_count,
		'post_status'    => 'publish',
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return;
	}

	// Determine layout style (alternate between styles)
	$style = ( $section_number % 2 === 1 ) ? 'grid' : 'list';
	?>
	<div class="HTG-magazine-section HTG-section-<?php echo esc_attr( $style ); ?>" data-section="<?php echo esc_attr( $section_number ); ?>">
		<div class="HTG-section-header">
			<h2 class="HTG-section-title">
				<?php echo esc_html( $category->name ); ?>
			</h2>
			<a href="<?php echo esc_url( get_category_link( $category_id ) ); ?>" class="HTG-view-all">
				<?php esc_html_e( 'View All', 'adtech-pro' ); ?>
				<span class="dashicons dashicons-arrow-right-alt2"></span>
			</a>
		</div>

		<div class="HTG-section-posts HTG-posts-<?php echo esc_attr( $style ); ?>">
			<?php
			while ( $query->have_posts() ) : $query->the_post();
				?>
				<article <?php post_class( 'HTG-magazine-post' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="HTG-post-thumbnail">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'grid' === $style ? 'HTG-medium-thumb' : 'HTG-small-thumb' ); ?>
							</a>
							<?php if ( get_post_format() ) : ?>
								<span class="HTG-post-format-icon">
									<span class="dashicons <?php echo esc_attr( HTG_get_post_format_icon() ); ?>"></span>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="HTG-post-content">
						<?php HTG_category_list(); ?>
						
						<h3 class="HTG-post-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<div class="HTG-post-meta">
							<span class="HTG-post-date"><?php echo get_the_date(); ?></span>
							<span class="HTG-post-author"><?php esc_html_e( 'by', 'adtech-pro' ); ?> <?php the_author(); ?></span>
						</div>

						<?php if ( 'list' === $style ) : ?>
							<div class="HTG-post-excerpt">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
}

/**
 * Add magazine homepage CSS
 * Loads on front page or when using Magazine Homepage template
 */
function HTG_magazine_styles() {
	// Check if magazine is enabled from admin panel or theme_mod
	$magazine_enabled = get_option( 'HTG_magazine_enable', null );
	if ( $magazine_enabled === null ) {
		$magazine_enabled = get_theme_mod( 'HTG_enable_magazine_layout', true );
	}
	
	// Load CSS on front page or magazine template pages
	$is_magazine_template = is_page_template( 'template-magazine.php' );
	
	if ( ( is_front_page() && $magazine_enabled ) || $is_magazine_template ) {
		wp_enqueue_style( 'HTG-magazine', get_template_directory_uri() . '/css/magazine.css', array( 'HTG-style' ), '2.0.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'HTG_magazine_styles' );

/**
 * Advanced post views counter
 */
function HTG_set_post_views() {
	if ( is_single() ) {
		global $post;
		$post_id = $post->ID;
		$count_key = 'HTG_post_views_count';
		$count = get_post_meta( $post_id, $count_key, true );
		
		if ( empty( $count ) ) {
			$count = 0;
			delete_post_meta( $post_id, $count_key );
			add_post_meta( $post_id, $count_key, '0' );
		} else {
			$count++;
			update_post_meta( $post_id, $count_key, $count );
		}
	}
}
add_action( 'wp_head', 'HTG_set_post_views' );

/**
 * Get post views count
 */
function HTG_get_post_views( $post_id ) {
	$count_key = 'HTG_post_views_count';
	$count = get_post_meta( $post_id, $count_key, true );
	
	if ( empty( $count ) ) {
		return '0';
	}
	
	return number_format_i18n( $count );
}

/**
 * Add reading time to posts
 */
function HTG_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Average reading speed: 200 words/minute
	
	return $reading_time;
}

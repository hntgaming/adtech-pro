<?php
/**
 * Apply Admin Panel Settings to Frontend
 * Makes all admin settings actually work on the live site
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output custom colors from Appearance settings
 * NOTE: This function is now handled by color-palettes.php
 * which includes all 11 colors and uses get_theme_mod()
 * Keeping this comment for reference only.
 */

/**
 * Apply General Settings
 */
function HTG_apply_general_settings() {
	$site_layout = get_option( 'HTG_site_layout', HTG_get_default( 'HTG_site_layout' ) );
	$sidebar_position = get_option( 'HTG_sidebar_position', HTG_get_default( 'HTG_sidebar_position' ) );
	$container_width = get_option( 'HTG_container_width', HTG_get_default( 'HTG_container_width' ) );
	
	?>
	<style id="HTG-general-settings">
		/* Site Layout */
		<?php if ( $site_layout === 'boxed' ) : ?>
		.site-content {
			max-width: <?php echo esc_attr( $container_width ); ?>px;
			margin: 0 auto;
			padding: 0 20px;
		}
		<?php endif; ?>
		
		/* Container Width */
		.container,
		.site-content {
			max-width: <?php echo esc_attr( $container_width ); ?>px;
		}
		
		/* Sidebar Position */
		<?php if ( $sidebar_position === 'left' ) : ?>
		.content-area {
			order: 2;
		}
		.widget-area {
			order: 1;
		}
		<?php elseif ( $sidebar_position === 'none' ) : ?>
		.widget-area {
			display: none;
		}
		.content-area {
			width: 100%;
			max-width: 100%;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'HTG_apply_general_settings', 101 );

/**
 * Apply Header Settings
 */
function HTG_apply_header_settings() {
	$header_bg = get_option( 'HTG_header_bg_color', HTG_get_default( 'HTG_header_bg_color' ) );
	$sticky_header = get_option( 'HTG_sticky_header', HTG_get_default( 'HTG_sticky_header' ) );
	$topbar_enable = get_option( 'HTG_topbar_enable', HTG_get_default( 'HTG_topbar_enable' ) );
	
	?>
	<style id="HTG-header-settings">
		/* Header Background */
		.site-header {
			background-color: <?php echo esc_attr( $header_bg ); ?>;
		}
		
		/* Sticky Header */
		<?php if ( $sticky_header ) : ?>
		.site-header.sticky {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			z-index: 999;
			box-shadow: 0 2px 5px rgba(0,0,0,0.1);
		}
		<?php endif; ?>
		
		/* Top Bar */
		<?php if ( ! $topbar_enable ) : ?>
		.top-bar {
			display: none;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'HTG_apply_header_settings', 102 );

/**
 * Modify Footer Copyright Text
 */
function HTG_footer_copyright() {
	$copyright = get_option( 'HTG_footer_copyright', HTG_get_default( 'HTG_footer_copyright' ) );
	return wp_kses_post( $copyright );
}
add_filter( 'HTG_footer_text', 'HTG_footer_copyright' );

/**
 * Apply Breadcrumbs Settings
 */
function HTG_show_breadcrumbs() {
	$breadcrumbs_enable = get_option( 'HTG_breadcrumbs_enable', HTG_get_default( 'HTG_breadcrumbs_enable' ) );
	return (bool) $breadcrumbs_enable;
}
add_filter( 'HTG_display_breadcrumbs', 'HTG_show_breadcrumbs' );

/**
 * Breadcrumbs Separator
 */
function HTG_breadcrumb_separator() {
	$separator = get_option( 'HTG_breadcrumbs_separator', HTG_get_default( 'HTG_breadcrumbs_separator' ) );
	return sanitize_text_field( $separator );
}
add_filter( 'HTG_breadcrumb_separator', 'HTG_breadcrumb_separator' );

/**
 * Apply Typography Settings
 */
function HTG_apply_typography() {
	$heading_font = get_option( 'HTG_heading_font', HTG_get_default( 'HTG_heading_font' ) );
	$body_font = get_option( 'HTG_body_font', HTG_get_default( 'HTG_body_font' ) );
	$font_size_base = get_option( 'HTG_font_size_base', HTG_get_default( 'HTG_font_size_base' ) );
	
	// Load Google Fonts
	wp_enqueue_style( 
		'HTG-google-fonts', 
		'https://fonts.googleapis.com/css2?family=' . str_replace( ' ', '+', $heading_font ) . ':wght@400;700&family=' . str_replace( ' ', '+', $body_font ) . ':wght@400;600&display=swap',
		array(), 
		null 
	);
	
	?>
	<style id="HTG-typography">
		/* Typography from Admin Settings */
		body {
			font-family: '<?php echo esc_attr( $body_font ); ?>', sans-serif;
			font-size: <?php echo esc_attr( $font_size_base ); ?>px;
		}
		
		h1, h2, h3, h4, h5, h6,
		.site-title,
		.entry-title {
			font-family: '<?php echo esc_attr( $heading_font ); ?>', sans-serif;
		}
	</style>
	<?php
}
add_action( 'wp_enqueue_scripts', 'HTG_apply_typography', 1 );
add_action( 'wp_head', 'HTG_apply_typography', 103 );

/**
 * Apply Blog Layout Settings
 */
function HTG_blog_layout_class( $classes ) {
	if ( is_home() || is_archive() ) {
		$blog_layout = get_option( 'HTG_blog_layout', HTG_get_default( 'HTG_blog_layout' ) );
		$classes[] = 'blog-layout-' . sanitize_html_class( $blog_layout );
	}
	return $classes;
}
add_filter( 'body_class', 'HTG_blog_layout_class' );

/**
 * Posts Per Page
 */
function HTG_posts_per_page( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ( is_home() || is_archive() ) ) {
		$posts_per_page = get_option( 'HTG_posts_per_page', HTG_get_default( 'HTG_posts_per_page' ) );
		$query->set( 'posts_per_page', absint( $posts_per_page ) );
	}
}
add_action( 'pre_get_posts', 'HTG_posts_per_page' );

/**
 * Excerpt Length - Update existing function via filter
 */
function HTG_update_excerpt_length( $length ) {
	$custom_length = get_option( 'HTG_excerpt_length', HTG_get_default( 'HTG_excerpt_length' ) );
	if ( $custom_length ) {
		return absint( $custom_length );
	}
	return $length;
}
add_filter( 'excerpt_length', 'HTG_update_excerpt_length', 9999 );

/**
 * Show/Hide Featured Images
 */
function HTG_show_featured_image() {
	return (bool) get_option( 'HTG_show_featured_image', HTG_get_default( 'HTG_show_featured_image' ) );
}

/**
 * Show/Hide Author Box
 */
function HTG_show_author_box() {
	return (bool) get_option( 'HTG_post_show_author_box', HTG_get_default( 'HTG_post_show_author_box' ) );
}

/**
 * Show/Hide Related Posts
 */
function HTG_show_related_posts() {
	return (bool) get_option( 'HTG_post_show_related', HTG_get_default( 'HTG_post_show_related' ) );
}

/**
 * Related Posts Count
 */
function HTG_related_posts_count() {
	return absint( get_option( 'HTG_post_related_count', HTG_get_default( 'HTG_post_related_count' ) ) );
}

/**
 * Apply Slider Settings
 */
function HTG_slider_settings() {
	return array(
		'enable' => (bool) get_option( 'HTG_slider_enable', HTG_get_default( 'HTG_slider_enable' ) ),
		'count' => absint( get_option( 'HTG_slider_posts_count', HTG_get_default( 'HTG_slider_posts_count' ) ) ),
		'autoplay' => (bool) get_option( 'HTG_slider_autoplay', HTG_get_default( 'HTG_slider_autoplay' ) ),
		'speed' => absint( get_option( 'HTG_slider_speed', HTG_get_default( 'HTG_slider_speed' ) ) ),
		'navigation' => (bool) get_option( 'HTG_slider_show_nav', HTG_get_default( 'HTG_slider_show_nav' ) ),
		'pagination' => (bool) get_option( 'HTG_slider_show_pagination', HTG_get_default( 'HTG_slider_show_pagination' ) ),
	);
}

/**
 * Output Slider JS Config
 */
function HTG_slider_config() {
	if ( ! is_front_page() ) {
		return;
	}
	
	$settings = HTG_slider_settings();
	?>
	<script>
	var HTG_slider_config = <?php echo json_encode( $settings ); ?>;
	</script>
	<?php
}
add_action( 'wp_footer', 'HTG_slider_config' );

/**
 * Reading Progress Bar
 */
function HTG_reading_progress_bar() {
	if ( ! is_single() ) {
		return;
	}
	
	$show_progress = get_option( 'HTG_post_reading_progress', HTG_get_default( 'HTG_post_reading_progress' ) );
	
	if ( $show_progress ) {
		$progress_color = get_option( 'HTG_progress_bar_color', HTG_get_default( 'HTG_progress_bar_color' ) );
		?>
		<div class="reading-progress-bar">
			<div class="reading-progress-fill"></div>
		</div>
		<style>
		.reading-progress-bar {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 3px;
			background: rgba(0,0,0,0.1);
			z-index: 9999;
		}
		.reading-progress-fill {
			height: 100%;
			background: <?php echo esc_attr( $progress_color ); ?>;
			width: 0%;
			transition: width 0.2s ease;
		}
		</style>
		<script>
		jQuery(window).on('scroll', function() {
			var s = jQuery(window).scrollTop(),
				d = jQuery(document).height() - jQuery(window).height(),
				c = (s / d) * 100;
			jQuery('.reading-progress-fill').css('width', c + '%');
		});
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'HTG_reading_progress_bar' );

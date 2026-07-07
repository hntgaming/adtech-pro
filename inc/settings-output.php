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
	$container_width = absint( get_option( 'HTG_container_width', HTG_get_default( 'HTG_container_width' ) ) );
	// Clamp to safe range
	$container_width = max( 960, min( 2560, $container_width ) );

	?>
	<style id="HTG-general-settings">
		/* Container Width - Auto Responsive */
		.hm-container,
		.container,
		.site-content,
		.HTG-wrapper {
			max-width: <?php echo absint( $container_width ); ?>px;
			width: 100%;
			margin-left: auto;
			margin-right: auto;
			padding-left: 20px;
			padding-right: 20px;
			box-sizing: border-box;
		}

		/* Full width inner elements */
		.hm-nav-container,
		.site-header .hm-container,
		.header-main-area .hm-container {
			max-width: <?php echo absint( $container_width ); ?>px;
		}

		@media screen and (max-width: 1400px) {
			.hm-container,
			.container,
			.site-content {
				padding-left: 30px;
				padding-right: 30px;
			}
		}

		@media screen and (max-width: 991px) {
			.hm-container,
			.container,
			.site-content {
				padding-left: 20px;
				padding-right: 20px;
			}
		}
		
		@media screen and (max-width: 576px) {
			.hm-container,
			.container,
			.site-content {
				padding-left: 15px;
				padding-right: 15px;
			}
		}
		
		/* Site Layout - Boxed */
		<?php if ( $site_layout === 'boxed' ) : ?>
		.site-content {
			max-width: <?php echo esc_attr( $container_width ); ?>px;
			margin: 0 auto;
			background: var(--htg-content-bg, #0a0a0a);
			box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
		}
		<?php endif; ?>
		
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
	$header_bg = sanitize_hex_color( get_option( 'HTG_header_bg_color', HTG_get_default( 'HTG_header_bg_color' ) ) );
	if ( ! $header_bg ) {
		$header_bg = HTG_get_default( 'HTG_header_bg_color' );
	}
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
		.hm-top-bar {
			display: none;
		}
		<?php endif; ?>
	</style>
	<?php
}
add_action( 'wp_head', 'HTG_apply_header_settings', 102 );

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
	$allowed_fonts = array( 'Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato', 'Ubuntu', 'Montserrat' );
	$heading_font = get_option( 'HTG_heading_font', HTG_get_default( 'HTG_heading_font' ) );
	$body_font    = get_option( 'HTG_body_font', HTG_get_default( 'HTG_body_font' ) );

	if ( ! in_array( $heading_font, $allowed_fonts, true ) ) {
		$heading_font = HTG_get_default( 'HTG_heading_font' );
	}
	if ( ! in_array( $body_font, $allowed_fonts, true ) ) {
		$body_font = HTG_get_default( 'HTG_body_font' );
	}

	// Load Google Fonts (typography CSS rules are handled by HTG_dynamic_css in functions.php)
	if ( 'system' !== $heading_font || 'system' !== $body_font ) {
		$families = array();
		if ( 'system' !== $heading_font ) {
			$families[] = str_replace( ' ', '+', $heading_font ) . ':wght@400;700';
		}
		if ( 'system' !== $body_font && $body_font !== $heading_font ) {
			$families[] = str_replace( ' ', '+', $body_font ) . ':wght@400;600';
		}
		if ( ! empty( $families ) ) {
			$url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $families ) . '&display=swap';
			wp_enqueue_style( 'HTG-google-fonts', esc_url( $url ), array(), null );
		}
	}
	// Note: font-family and font-size CSS rules are output by HTG_dynamic_css() in functions.php
}
add_action( 'wp_enqueue_scripts', 'HTG_apply_typography', 1 );

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
	if ( ! is_admin() && $query->is_main_query() && ( is_home() || is_category() || is_tag() ) ) {
		$posts_per_page = absint( get_option( 'HTG_posts_per_page', HTG_get_default( 'HTG_posts_per_page' ) ) );
		$query->set( 'posts_per_page', max( 1, min( 50, $posts_per_page ) ) );
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
	var HTG_slider_config = <?php echo wp_json_encode( $settings ); ?>;
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
		$progress_color = sanitize_hex_color( get_option( 'HTG_progress_bar_color', HTG_get_default( 'HTG_progress_bar_color' ) ) );
		if ( ! $progress_color ) {
			$progress_color = HTG_get_default( 'HTG_progress_bar_color' );
		}
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
				c = (d > 0) ? (s / d) * 100 : 0;
			jQuery('.reading-progress-fill').css('width', c + '%');
		});
		</script>
		<?php
	}
}
add_action( 'wp_footer', 'HTG_reading_progress_bar' );

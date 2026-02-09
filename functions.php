<?php
/**
 * HTG functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package HTG
 */

if ( ! function_exists( 'HTG_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function HTG_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on HTG, use a find and replace
	 * to change 'adtech-pro' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'adtech-pro', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );
	if ( true == get_theme_mod( 'HTG_use_high_res_images', false ) ) {
		add_image_size( 'HTG-landscape',	1500, 9999, false ); // No crop - full width
		add_image_size( 'HTG-featured', 1000, 9999, false );    // No crop - full image
		add_image_size( 'HTG-grid', 696, 9999, false );         // No crop
		add_image_size( 'HTG-list', 580, 9999, false );         // No crop
		add_image_size( 'HTG-thumbnail', 270, 186, true );      // Thumbnails can crop
	} else {
		add_image_size( 'HTG-landscape',	1120, 9999, false ); // No crop - full width
		add_image_size( 'HTG-featured', 735, 9999, false );     // No crop - full image
		add_image_size( 'HTG-grid', 348, 9999, false );         // No crop
		add_image_size( 'HTG-list', 290, 9999, false );         // No crop
		add_image_size( 'HTG-thumbnail', 135, 93, true );       // Thumbnails can crop
	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' 		=> esc_html__( 'Main Menu', 'adtech-pro' ),
		'menu-2' 		=> esc_html__( 'Top Menu', 'adtech-pro' ),
		'menu-social'  	=> esc_html__( 'Social Media Menu', 'adtech-pro' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for custom logo upload.
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 380,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );	

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'HTG_custom_background_args', array(
		'default-color' => 'dddddd',
		'default-image' => '',
	) ) );

	// Declare WooCommerce support.
	add_theme_support( 'woocommerce' );	
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Selective refresh widgets removed - we use Admin Panel instead
	// add_theme_support( 'customize-selective-refresh-widgets' );

	// Add editor style.
	add_editor_style( array( 'css/editor-style.css', HTG_fonts_url() ) );

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

 	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets' => array(
			
			'sidebar-1' => array(
				'search',
				'text_about',
				'custom_popular_widget' => array( 'HTG_tabbed_widget', array(
						'nop'	=> 5,
						'noc'	=> 5
					)	 
				),
				'recent-posts'
			),

			'magazine' => array(
				'magazine_posts_style_1' => array( 'HTG_single_category_posts', array(
						'title' => 'Fashion'
					)
				),
				'magazine_posts_style_2' => array( 'HTG_dual_category_posts', array(
						'title1' 		=> 'Lifestyle',
						'number_posts1' => 4,
						'title2' 		=> 'Technology',
						'number_posts2' => 4
					)
				),
				'magazine_posts_style_3' => array( 'HTG_grid_category_posts', array(
						'title' 		=> 'Sports',
						'number_posts' => 6
					)
				)
			),

			'footer-left' => array(
				'text_business_info',
			),
			
			'footer-mid' => array(
				'text_about',
			),

			'footer-right' => array(
				'recent-posts',
				'search',
			),

		),

		'posts' => array(
			// Add pages.
			'home' => array(
				'template' => 'template-magazine.php'
			),
			'blog'		
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set up nav menus for each of the three areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "main menu" location.
			'menu-1' => array(
				'name' => esc_html__( 'Main Menu', 'adtech-pro' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_blog',
				),
			),

			// Assign a menu to the "top menu" location.
			'menu-2' => array(
				'name' => esc_html__( 'Top Menu', 'adtech-pro' ),
				'items' => array(
					'link_home',
					'page_blog',
				),
			),

			// Assign a menu to the "menu-social" location.
			'menu-social' => array(
				'name' => esc_html__( 'Social Links Menu', 'adtech-pro' ),
				'items' => array(
					'link_facebook',
					'link_twitter',
					'link_instagram',
				),
			),
		),

	);

	$starter_content = apply_filters( 'HTG_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );	

}
endif;
add_action( 'after_setup_theme', 'HTG_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function HTG_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'HTG_content_width', 735 );
}
add_action( 'after_setup_theme', 'HTG_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function HTG_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'adtech-pro' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'adtech-pro' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Magazine Homepage', 'adtech-pro' ),
		'id'            => 'magazine',
		'description'   => esc_html__( 'Appears on Magazine Homepage template only. Add magazine posts widgets to this widget area.', 'adtech-pro' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Header Advertisement Area', 'adtech-pro' ),
		'id'            => 'sidebar-header',
		'description'   => esc_html__( 'You can add advertisement widget to this widget area.', 'adtech-pro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Left Sidebar', 'adtech-pro' ),
		'id'            => 'footer-left',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );	
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Mid Sidebar', 'adtech-pro' ),
		'id'            => 'footer-mid',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );	
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Right Sidebar', 'adtech-pro' ),
		'id'            => 'footer-right',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	) );	
}
add_action( 'widgets_init', 'HTG_widgets_init' );

/**
 * Load Google Fonts
 */
function HTG_fonts_url() {
    $fonts_url = get_theme_file_uri( '/css/fonts.css' );
    return $fonts_url;
}
/**
* Enqueue Google fonts.
*/
function HTG_font_styles() {
    wp_enqueue_style( 'HTG-fonts', HTG_fonts_url(), array(), null );
}
add_action( 'wp_enqueue_scripts', 'HTG_font_styles' );

/**
 * Resource Hints — Preconnect / DNS-Prefetch / Preload for AdTech & Analytics
 *
 * Strategy rationale:
 * - preconnect  = DNS + TCP + TLS handshake (saves ~100-300 ms per origin on first request)
 * - dns-prefetch = DNS-only fallback for browsers that don't support preconnect
 * - preload     = fetch the resource immediately at highest priority (used for GPT script)
 *
 * Origins:
 * - cdn.hntgaming.me                → H&T Gaming CDN (ad loader, site.js, configs)
 * - securepubads.g.doubleclick.net  → GPT ad tag
 * - pagead2.googlesyndication.com   → AdSense / GPT impl / ad creatives
 * - googleads.g.doubleclick.net     → Ad serving (creatives, tracking)
 * - tpc.googlesyndication.com       → Third-party cookie sync
 * - www.googletagmanager.com        → Google Analytics (GA4 via gtag.js)
 * - www.google-analytics.com        → Google Analytics legacy / Measurement Protocol
 * - adservice.google.com            → Ad click handling
 *
 * @since 3.0.0
 */
function HTG_resource_hints() {
	// Only on frontend, not in admin or customizer preview
	if ( is_admin() ) {
		return;
	}

	// ─────────────────────────────────────────────────────────────
	// H&T Gaming CDN — NO crossorigin (referrer-restricted access)
	// The CDN validates Referer header; crossorigin sends anonymous
	// CORS requests that strip Referer, causing the CDN to block.
	// ─────────────────────────────────────────────────────────────
	echo '<link rel="preconnect" href="https://cdn.hntgaming.me">' . "\n";
	echo '<link rel="dns-prefetch" href="//cdn.hntgaming.me">' . "\n";

	// ─────────────────────────────────────────────────────────────
	// Google origins — crossorigin is correct here (CORS-enabled CDNs)
	// ─────────────────────────────────────────────────────────────
	$google_preconnect = array(
		'https://securepubads.g.doubleclick.net',  // GPT ad tag
		'https://pagead2.googlesyndication.com',   // AdSense / GPT impl
		'https://googleads.g.doubleclick.net',     // Ad creatives & tracking
		'https://www.googletagmanager.com',        // GA4 via gtag.js
		'https://www.google-analytics.com',        // Analytics measurement
	);

	foreach ( $google_preconnect as $origin ) {
		printf(
			'<link rel="preconnect" href="%s" crossorigin>' . "\n",
			esc_url( $origin )
		);
	}

	// ── DNS-prefetch fallback for all origins + extra ad-serving domains ──
	$dns_prefetch = array(
		'//securepubads.g.doubleclick.net',
		'//pagead2.googlesyndication.com',
		'//googleads.g.doubleclick.net',
		'//www.googletagmanager.com',
		'//www.google-analytics.com',
		'//adservice.google.com',
		'//tpc.googlesyndication.com',
		'//adsrvr.org',
		'//cdn.ampproject.org',
	);

	foreach ( $dns_prefetch as $origin ) {
		printf(
			'<link rel="dns-prefetch" href="%s">' . "\n",
			esc_attr( $origin )
		);
	}

	// ─────────────────────────────────────────────────────────────
	// Preload — only GPT (fixed URL, always loaded on ad-tech sites)
	//
	// NOT preloading:
	// - cdn.hntgaming.me scripts: filename is per-publisher (e.g.
	//   bdresultguru.js, site.js) and CDN rejects crossorigin.
	//   Preconnect above is sufficient — connection is warm by the
	//   time the <script> tag fires from the ad loader.
	// - gtag.js: requires ?id=G-XXXXX parameter, bare URL won't
	//   match the actual request → "preloaded but not used" warning.
	// ─────────────────────────────────────────────────────────────
	echo '<link rel="preload" href="https://securepubads.g.doubleclick.net/tag/js/gpt.js" as="script" crossorigin>' . "\n";
}
add_action( 'wp_head', 'HTG_resource_hints', 1 );

/**
 * Filter WordPress core resource hints to avoid duplicate dns-prefetch
 * WordPress core adds some by default; we deduplicate here.
 *
 * @since 3.0.0
 */
function HTG_filter_resource_hints( $urls, $relation_type ) {
	if ( $relation_type === 'dns-prefetch' ) {
		// Remove WordPress default s.w.org dns-prefetch (emoji CDN, usually not needed)
		$urls = array_filter( $urls, function( $url ) {
			if ( is_array( $url ) ) {
				return ! isset( $url['href'] ) || strpos( $url['href'], 's.w.org' ) === false;
			}
			return strpos( $url, 's.w.org' ) === false;
		} );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'HTG_filter_resource_hints', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function HTG_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	// Font Awesome (icons)
	wp_enqueue_style( 'HTG-font-awesome', get_template_directory_uri() . '/css/all.min.css', array(), '6.5.1' );
	
	// Main theme stylesheet (WordPress standard)
	wp_enqueue_style( 'HTG-style', get_stylesheet_uri(), array( 'HTG-font-awesome' ), $theme_version );

	wp_enqueue_script( 'HTG-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), $theme_version, true );

	// skip-link-focus-fix.js removed — was IE-only, no longer needed

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_page_template( 'template-magazine.php' ) && ( true == get_theme_mod( 'show_slider', true ) ) ) {
		wp_enqueue_style( 'HTG-swiper', get_template_directory_uri() . '/css/swiper-bundle.min.css', '', '11.0.5', 'screen' );
		wp_enqueue_script( 'HTG-swiper', get_template_directory_uri() . '/js/swiper-bundle.min.js', '', '11.0.5', true );
	}

	wp_enqueue_script( 'HTG-scripts', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), $theme_version, true );	

	if ( ! is_front_page() && is_singular() && ( true == get_theme_mod( 'use_lightbox', true ) ) ) {
		wp_enqueue_script( 'jquery-magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '', true );
		wp_enqueue_style( 'jquery-magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css', array(), '' );
	}

	// html5shiv removed — IE < 9 is no longer supported
}
add_action( 'wp_enqueue_scripts', 'HTG_scripts' );

/**
 * Enqueue editor styles for Gutenberg
 *
 * @since HTG 1.3.1
 */
function HTG_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'HTG-block-editor-style', get_template_directory_uri() . '/css/editor-blocks.css' );
	// Add custom fonts.
	wp_enqueue_style( 'HTG-fonts', HTG_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'HTG_block_editor_styles' );

/**
 * custom logo.
 */
function HTG_the_custom_logo() {
	if ( function_exists( 'the_custom_logo' ) ) {
		the_custom_logo();
	}
}

/**
 * Theme defaults - MUST be loaded first
 * Centralized default values for all settings
 */
require get_template_directory() . '/inc/theme-defaults.php';

/**
 * Embed kirki plugin.
 */
if ( ! class_exists( 'Kirki' ) ) {
	include_once( get_template_directory() . '/inc/kirki/kirki.php' );
}
require get_template_directory() . '/inc/customizer/kirki-config.php';
require get_template_directory() . '/inc/customizer/styles.php';

// Removed: HTG Pro upsell (no longer needed for H&T AdTech Pro)
// require_once( trailingslashit( get_template_directory() ) . '/inc/customizer/custom-controls/class-upsell-customize.php' );

/**
 * Custom block styles.
 */
require get_template_directory() . '/inc/block-styles.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Custom meta boxes.
 */
require get_template_directory() . '/inc/class-meta-boxes.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Logo handler - auto-resize and optimization
 */
require get_template_directory() . '/inc/logo-handler.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load all widgets.
 */
require get_template_directory() . '/inc/widgets/block-posts-single.php';
require get_template_directory() . '/inc/widgets/block-posts-dual.php';
require get_template_directory() . '/inc/widgets/block-posts-grid.php';
require get_template_directory() . '/inc/widgets/sidebar-posts.php';
require get_template_directory() . '/inc/widgets/popular-tags-comments.php';
require get_template_directory() . '/inc/widgets/popular-posts.php';


/**
 * Theme Info Page.
 */
require get_template_directory() . '/inc/dashboard/theme-info.php';

/**
 * ===========================================
 * AdTech & Monetization Features (v2.0.0)
 * ===========================================
 */

// Simple Ad Management (Google AdSense/AdManager Compliant - Better Ads Standard)
require get_template_directory() . '/inc/simple-ads.php';

// Color Palette System with H&T Brand Colors
require get_template_directory() . '/inc/customizer/color-palettes.php';

// Breadcrumb System
require get_template_directory() . '/inc/breadcrumbs.php';

// Dark Mode Support
require get_template_directory() . '/inc/dark-mode.php';

// Enterprise Admin Dashboard
require get_template_directory() . '/inc/admin/class-admin-dashboard.php';

// Direct Admin Settings Pages (no customizer!)
require get_template_directory() . '/inc/admin/class-settings-pages.php';

// Apply Admin Settings to Frontend (makes settings actually work!)
require get_template_directory() . '/inc/settings-output.php';

// Disable Customizer & Widgets (Enterprise Admin Panel Only!)
require get_template_directory() . '/inc/disable-customizer-widgets.php';

// Sync Theme Settings (Make theme_mod use admin panel options!)
require get_template_directory() . '/inc/sync-theme-settings.php';

// Magazine Features (Pro features for free)
require get_template_directory() . '/inc/magazine-features.php';

// Engagement Features (Publisher retention tools)
require get_template_directory() . '/inc/engagement/class-quiz-system.php';
require get_template_directory() . '/inc/engagement/class-accordion-system.php';
require get_template_directory() . '/inc/engagement/class-newsletter-system.php';
require get_template_directory() . '/inc/engagement/class-author-box.php';

// AJAX Post Loading (innovation4world.com inspired)
require get_template_directory() . '/inc/ajax-posts.php';

// Publisher Legal Pages (AdSense compliance)
require get_template_directory() . '/inc/publisher-legal.php';

// GitHub Auto-Updater (checks for updates from GitHub releases)
require get_template_directory() . '/inc/class-github-updater.php';

/**
 * Enqueue AdTech styles and scripts
 */
function HTG_simple_ads_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );
	
	// Simple ads CSS (Clean, policy-compliant)
	wp_enqueue_style( 'HTG-simple-ads', get_template_directory_uri() . '/css/simple-ads.css', array( 'HTG-style' ), $theme_version );

	// Engagement CSS
	wp_enqueue_style( 'HTG-engagement', get_template_directory_uri() . '/css/engagement.css', array( 'HTG-style' ), $theme_version );

	// Engagement JavaScript
	wp_enqueue_script( 'HTG-engagement', get_template_directory_uri() . '/js/engagement.js', array(), $theme_version, true );

	// Localize engagement script
	wp_localize_script( 'HTG-engagement', 'HTG_engagement', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'HTG_engagement_nonce' ),
		'post_id'  => get_the_ID(),
	) );

	// Localize quiz script
	wp_localize_script( 'HTG-engagement', 'HTG_quiz', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'HTG_quiz_nonce' ),
	) );

	// Localize AJAX posts script
	wp_localize_script( 'HTG-engagement', 'HTG_ajax', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'HTG_ajax_nonce' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'HTG_simple_ads_assets', 20 );

/**
 * Generate dynamic CSS — Typography only
 * 
 * Color output is handled by the unified palette system in color-palettes.php.
 * This function only outputs typography settings (fonts, font-size scale).
 */
function HTG_dynamic_css() {
	// Typography
	$heading_font = get_theme_mod( 'HTG_heading_font', 'Ubuntu' );
	$body_font = get_theme_mod( 'HTG_body_font', 'Lato' );
	$font_size_scale = get_theme_mod( 'HTG_font_size_scale', 100 );
	
	$css = "
	/* Typography Scale */
	html {
		font-size: {$font_size_scale}%;
	}
	";
	
	if ( 'system' !== $heading_font ) {
		$css .= "
		h1, h2, h3, h4, h5, h6,
		.site-title,
		.widget-title,
		.footer-widget-title,
		.arc-page-title,
		.entry-title {
			font-family: '{$heading_font}', sans-serif;
		}
		";
	}
	
	if ( 'system' !== $body_font ) {
		$css .= "
		body,
		button,
		input,
		select,
		textarea {
			font-family: '{$body_font}', sans-serif;
		}
		";
	}
	
	wp_add_inline_style( 'HTG-style', $css );
}
add_action( 'wp_enqueue_scripts', 'HTG_dynamic_css' );

/**
 * Auto-setup Magazine Homepage
 * Creates a Home page with Magazine Homepage template and sets it as front page
 */
function HTG_auto_setup_magazine_homepage() {
	// Only run once (check option)
	if ( get_option( 'HTG_magazine_setup_complete' ) ) {
		return;
	}
	
	// Only run for admins
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	
	// Check if magazine is enabled
	$magazine_enabled = get_option( 'HTG_magazine_enable', 1 );
	if ( ! $magazine_enabled ) {
		return;
	}
	
	// Check if we already have a page with Magazine Homepage template
	$existing_pages = get_posts( array(
		'post_type'   => 'page',
		'meta_key'    => '_wp_page_template',
		'meta_value'  => 'template-magazine.php',
		'numberposts' => 1,
		'post_status' => 'any',
	) );
	
	$home_page_id = 0;
	
	if ( ! empty( $existing_pages ) ) {
		$home_page_id = $existing_pages[0]->ID;
		// Make sure it's published
		if ( get_post_status( $home_page_id ) !== 'publish' ) {
			wp_update_post( array(
				'ID'          => $home_page_id,
				'post_status' => 'publish',
			) );
		}
	} else {
		// Create the Magazine Homepage page
		$home_page_id = wp_insert_post( array(
			'post_title'   => 'Home',
			'post_name'    => 'home',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
			'page_template' => 'template-magazine.php',
		) );
		
		if ( $home_page_id && ! is_wp_error( $home_page_id ) ) {
			update_post_meta( $home_page_id, '_wp_page_template', 'template-magazine.php' );
		}
	}
	
	// Set as static front page
	if ( $home_page_id && ! is_wp_error( $home_page_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page_id );
		
		// Mark setup as complete
		update_option( 'HTG_magazine_setup_complete', 1 );
	}
}
add_action( 'admin_init', 'HTG_auto_setup_magazine_homepage' );

/**
 * Reset magazine setup option when theme is reactivated
 * This allows the setup to run again if needed
 */
function HTG_theme_activation_setup() {
	delete_option( 'HTG_magazine_setup_complete' );
}
add_action( 'after_switch_theme', 'HTG_theme_activation_setup' );
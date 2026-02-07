<?php
/**
 * H&T AdTech Pro - Centralized Theme Defaults
 * Single source of truth for all default values
 *
 * All theme files should use these functions to get default values.
 * This ensures consistency across Admin Panel, Customizer, and Frontend.
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all theme default values
 * Centralized configuration for consistency
 *
 * @return array All default values
 */
function HTG_get_theme_defaults() {
	static $defaults = null;
	if ( $defaults !== null ) {
		return $defaults;
	}
	$defaults = array(
		// ===========================================
		// COLOR SETTINGS (Dark Theme Optimized)
		// ===========================================
		'HTG_primary_color'           => '#1a1f36',     // Dark navy - headers, buttons
		'HTG_secondary_color'         => '#00d4aa',     // Teal - accents, links
		'HTG_accent_color_1'          => '#00d4aa',     // Teal - gradients, badges
		'HTG_accent_color_2'          => '#00b894',     // Green - success states
		'HTG_heading_color'           => '#ffffff',     // White headings for dark theme
		'HTG_body_text_color'         => '#e0e0e0',     // Light gray body text
		'HTG_link_color'              => '#00d4aa',     // Teal links
		'HTG_link_hover_color'        => '#ffffff',     // White hover (brightens on dark)
		'HTG_body_background_color'   => '#0a0a0a',     // Near black background
		'HTG_content_background_color'=> '#121212',     // Dark card background
		'HTG_footer_background_color' => '#000000',     // Black footer
		
		// ===========================================
		// TYPOGRAPHY SETTINGS
		// ===========================================
		'HTG_heading_font'            => 'Inter',       // Modern sans-serif
		'HTG_body_font'               => 'Inter',       // Consistent with headings
		'HTG_font_size_base'          => 16,            // Standard 16px
		'HTG_font_size_scale'         => 100,           // 100% scale
		'HTG_custom_css'              => '',            // No custom CSS by default
		
		// ===========================================
		// SITE LAYOUT SETTINGS
		// ===========================================
		'HTG_site_layout'             => 'wide',        // Full width layout
		'HTG_sidebar_position'        => 'right',       // Right sidebar
		'HTG_container_width'         => 1920,          // 1920px container (auto-responsive)
		'HTG_breadcrumbs_enable'      => 1,             // Breadcrumbs enabled
		'HTG_breadcrumbs_separator'   => '›',           // Chevron separator
		'HTG_footer_copyright'        => '© ' . date('Y') . ' H&T GAMING. All rights reserved.',
		
		// ===========================================
		// HEADER SETTINGS
		// ===========================================
		'HTG_header_layout'           => 'default',     // Logo left layout
		'HTG_header_bg_color'         => '#0a0a0a',     // Dark header
		'HTG_topbar_enable'           => 1,             // Top bar enabled
		'HTG_topbar_show_date'        => 1,             // Show date
		'HTG_sticky_header'           => 1,             // Sticky header enabled
		'HTG_header_show_search'      => 1,             // Search icon shown
		
		// ===========================================
		// SLIDER SETTINGS
		// ===========================================
		'HTG_slider_enable'           => 1,             // Slider enabled
		'HTG_slider_posts_count'      => 5,             // 5 posts in slider
		'HTG_slider_autoplay'         => 1,             // Autoplay enabled
		'HTG_slider_speed'            => 5000,          // 5 second interval
		'HTG_slider_show_nav'         => 1,             // Navigation arrows
		'HTG_slider_show_pagination'  => 1,             // Pagination dots
		
		// ===========================================
		// BLOG/ARCHIVE SETTINGS
		// ===========================================
		'archive_content_layout'      => 'th-grid-2',   // Grid layout (2 columns)
		'HTG_blog_layout'             => 'grid',        // Grid style
		'HTG_posts_per_page'          => 12,            // 12 posts per page
		'HTG_excerpt_length'          => 25,            // 25 words excerpt
		'HTG_blog_show_excerpt'       => 1,             // Show excerpt
		'HTG_blog_show_full_content'  => 0,             // Don't show full content
		'HTG_blog_show_readmore'      => 1,             // Show read more button
		'HTG_blog_readmore_text'      => 'Read More',   // Button text
		'HTG_show_featured_image'     => 1,             // Show thumbnails
		'HTG_show_post_date'          => 1,             // Show date
		'HTG_show_author'             => 1,             // Show author
		
		// ===========================================
		// SINGLE POST SETTINGS
		// ===========================================
		'HTG_post_show_author_box'    => 1,             // Author box enabled
		'HTG_post_show_related'       => 1,             // Related posts enabled
		'HTG_post_related_count'      => 6,             // 6 related posts
		'HTG_post_show_tags'          => 1,             // Show tags
		'HTG_post_reading_progress'   => 1,             // Progress bar enabled
		
		// ===========================================
		// MAGAZINE SETTINGS
		// ===========================================
		'HTG_magazine_enable'         => 1,             // Magazine layout enabled
		'HTG_magazine_posts_per_section' => 6,          // 6 posts per section
		'HTG_magazine_layout_style'   => 'grid',        // Grid layout
		'HTG_magazine_show_badges'    => 1,             // Post format badges
		'HTG_magazine_show_reading_time' => 1,          // Reading time shown
		'HTG_magazine_section_1_category' => 0,         // No category selected
		'HTG_magazine_section_2_category' => 0,
		'HTG_magazine_section_3_category' => 0,
		'HTG_magazine_section_4_category' => 0,
		
		// ===========================================
		// ENGAGEMENT SETTINGS
		// ===========================================
		'HTG_newsletter_enable'       => 1,             // Newsletter enabled
		'HTG_newsletter_title'        => 'Subscribe to Our Newsletter',
		'HTG_newsletter_description'  => 'Get the latest updates delivered to your inbox.',
		'HTG_reading_time_enable'     => 1,             // Reading time enabled
		'HTG_progress_bar_enable'     => 1,             // Progress bar enabled
		'HTG_progress_bar_color'      => '#00d4aa',     // Teal progress bar
		
		// ===========================================
		// AUTHOR BOX SETTINGS
		// ===========================================
		'HTG_author_box_enable'       => 1,
		'HTG_author_box_style'        => 'modern',      // Modern style
		'HTG_author_box_show_post_count' => 1,
		'HTG_author_box_show_social'  => 1,
		
		// ===========================================
		// AD MANAGEMENT SETTINGS
		// ===========================================
		'HTG_ad_labels_enable'        => 1,             // Ad labels shown
		
		// Global codes
		'HTG_ad_head_code'            => '',            // Header scripts
		'HTG_ad_footer_code'          => '',            // Footer scripts
		
		// Header ads
		'HTG_ad_header_above'         => '',
		'HTG_ad_header_below'         => '',
		
		// Article ads
		'HTG_ad_before_content'       => '',
		'HTG_ad_after_content'        => '',
		'HTG_ad_in_article'           => '',
		'HTG_ad_in_article_position'  => 3,             // After 3rd paragraph
		
		// Sidebar ads
		'HTG_ad_sidebar_top'          => '',
		'HTG_ad_sidebar_sticky'       => '',
		
		// Homepage & Footer
		'HTG_ad_homepage_top'         => '',
		'HTG_ad_before_footer'        => '',
		
		// ===========================================
		// THEME MODE (Dark Only)
		// ===========================================
		// Theme is dark by design - no toggle needed
		
		// ===========================================
		// SIDEBAR ALIGNMENT (Legacy)
		// ===========================================
		'archive_sidebar_align'       => 'th-right-sidebar',
		'post_sidebar_align'          => 'th-right-sidebar',
		'page_sidebar_align'          => 'th-right-sidebar',
		
		// ===========================================
		// COLOR SCHEME
		// ===========================================
		'HTG_color_scheme'            => 'htg_dark',    // Default color scheme
	);
	return $defaults;
}

/**
 * Get a single default value
 *
 * @param string $key Setting key
 * @param mixed $fallback Fallback if key doesn't exist
 * @return mixed Default value
 */
function HTG_get_default( $key, $fallback = null ) {
	$defaults = HTG_get_theme_defaults();
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : $fallback;
}

/**
 * Get option with theme default
 *
 * @param string $option Option name
 * @return mixed Option value or default
 */
function HTG_get_option( $option ) {
	$default = HTG_get_default( $option );
	return get_option( $option, $default );
}

/**
 * Get theme mod with theme default
 *
 * @param string $mod Theme mod name
 * @return mixed Theme mod value or default
 */
function HTG_get_mod( $mod ) {
	$default = HTG_get_default( $mod );
	return get_theme_mod( $mod, $default );
}

/**
 * Initialize default options on theme activation
 * Only sets defaults if option doesn't already exist
 */
function HTG_set_default_options() {
	$defaults = HTG_get_theme_defaults();
	
	foreach ( $defaults as $key => $value ) {
		// Only set if option doesn't exist
		if ( false === get_option( $key, false ) ) {
			// Skip empty strings and arrays
			if ( $value !== '' && ! is_array( $value ) ) {
				add_option( $key, $value );
			}
		}
	}
}
add_action( 'after_switch_theme', 'HTG_set_default_options' );

/**
 * One-time migration for v2.3.1
 * Updates container width from old default (1200) to new default (1920)
 */
function HTG_migrate_v231() {
	$migrated = get_option( 'HTG_migrated_v231', false );
	
	if ( $migrated ) {
		return; // Already migrated
	}
	
	// Update container width if it's still at old default
	$current_width = get_option( 'HTG_container_width', false );
	if ( $current_width === false || $current_width == 1200 ) {
		update_option( 'HTG_container_width', 1920 );
	}
	
	// Mark as migrated
	update_option( 'HTG_migrated_v231', true );
}
add_action( 'init', 'HTG_migrate_v231' );

/**
 * Reset options to defaults (admin function)
 * Only call this from admin panel reset button
 */
function HTG_reset_to_defaults() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}
	
	$defaults = HTG_get_theme_defaults();
	
	foreach ( $defaults as $key => $value ) {
		if ( strpos( $key, 'HTG_' ) === 0 ) {
			update_option( $key, $value );
		}
	}
	
	return true;
}

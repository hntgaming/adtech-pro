<?php
/**
 * Sync Theme Settings - Bidirectional Sync between Admin Panel and Customizer
 * 
 * This file ensures settings work correctly whether changed from:
 * - Admin Panel (H&T AdTech settings pages)
 * - WordPress Customizer
 * 
 * Priority: Admin Panel options take precedence, but changes from either location
 * are synced to both storage locations.
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Map - Maps theme_mod names to option names
 * Used for bidirectional sync between Customizer and Admin Panel
 * Defaults are pulled from centralized theme-defaults.php
 */
function HTG_get_settings_map() {
	return array(
		// Colors
		'HTG_primary_color'    => array( 'option' => 'HTG_primary_color', 'default' => HTG_get_default( 'HTG_primary_color' ) ),
		'HTG_secondary_color'  => array( 'option' => 'HTG_secondary_color', 'default' => HTG_get_default( 'HTG_secondary_color' ) ),
		'HTG_accent_color_1'   => array( 'option' => 'HTG_accent_color_1', 'default' => HTG_get_default( 'HTG_accent_color_1' ) ),
		'HTG_accent_color_2'   => array( 'option' => 'HTG_accent_color_2', 'default' => HTG_get_default( 'HTG_accent_color_2' ) ),
		
		// Typography
		'HTG_heading_font'     => array( 'option' => 'HTG_heading_font', 'default' => HTG_get_default( 'HTG_heading_font' ) ),
		'HTG_body_font'        => array( 'option' => 'HTG_body_font', 'default' => HTG_get_default( 'HTG_body_font' ) ),
		
		// Header
		'display_topbar'       => array( 'option' => 'HTG_topbar_enable', 'default' => HTG_get_default( 'HTG_topbar_enable' ), 'bool_to_string' => true ),
		'show_topbar_date'     => array( 'option' => 'HTG_topbar_show_date', 'default' => HTG_get_default( 'HTG_topbar_show_date' ), 'bool_to_string' => true ),
		'show_nav_search'      => array( 'option' => 'HTG_header_show_search', 'default' => HTG_get_default( 'HTG_header_show_search' ) ),
		
		// Footer
		'footer_copyright_text' => array( 'option' => 'HTG_footer_copyright', 'default' => HTG_get_default( 'HTG_footer_copyright' ) ),
		
		// Blog/Archive
		'excerpt_display'       => array( 'option' => 'HTG_blog_show_excerpt', 'default' => HTG_get_default( 'HTG_blog_show_excerpt' ) ),
		'archive_show_content'  => array( 'option' => 'HTG_blog_show_full_content', 'default' => HTG_get_default( 'HTG_blog_show_full_content' ) ),
		'show_readmore'         => array( 'option' => 'HTG_blog_show_readmore', 'default' => HTG_get_default( 'HTG_blog_show_readmore' ) ),
		'readmore_text'         => array( 'option' => 'HTG_blog_readmore_text', 'default' => HTG_get_default( 'HTG_blog_readmore_text' ) ),
		
		// Slider
		'show_slider'           => array( 'option' => 'HTG_slider_enable', 'default' => HTG_get_default( 'HTG_slider_enable' ) ),
		
		// Magazine
		'HTG_enable_magazine_layout' => array( 'option' => 'HTG_magazine_enable', 'default' => HTG_get_default( 'HTG_magazine_enable' ) ),
	);
}

/**
 * Get synced setting value
 * Checks both option and theme_mod, prioritizing option
 */
function HTG_get_synced_setting( $theme_mod_name, $default = null ) {
	$map = HTG_get_settings_map();
	
	if ( isset( $map[ $theme_mod_name ] ) ) {
		$config = $map[ $theme_mod_name ];
		$option_name = $config['option'];
		$default = $default !== null ? $default : $config['default'];
		
		// Get from options (Admin Panel)
		$value = get_option( $option_name, null );
		
		// If option exists, use it
		if ( $value !== null && $value !== false ) {
			// Convert bool to string if needed (for display_topbar, show_topbar_date)
			if ( isset( $config['bool_to_string'] ) && $config['bool_to_string'] ) {
				return $value ? 'true' : 'false';
			}
			return $value;
		}
		
		return $default;
	}
	
	return $default;
}

/**
 * Override theme_mod values to use admin panel options
 * This ensures get_theme_mod() returns values from admin panel
 */
foreach ( HTG_get_settings_map() as $theme_mod => $config ) {
	add_filter( 'theme_mod_' . $theme_mod, function( $value ) use ( $theme_mod, $config ) {
		$option_value = get_option( $config['option'], null );
		
		if ( $option_value !== null && $option_value !== false ) {
			// Convert bool to string if needed
			if ( isset( $config['bool_to_string'] ) && $config['bool_to_string'] ) {
				return $option_value ? 'true' : 'false';
			}
			return $option_value;
		}
		
		return $value ?: $config['default'];
	});
}

/**
 * Sync Customizer changes to Admin Panel options
 * When a theme_mod is updated via Customizer, also update the corresponding option
 * 
 * Note: update_option action signature is ($option, $old_value, $value)
 */
function HTG_sync_customizer_to_options( $option_name, $old_value, $new_value ) {
	// Ensure option_name is a string
	if ( ! is_string( $option_name ) ) {
		return;
	}
	
	// This runs when theme_mods are updated
	if ( strpos( $option_name, 'theme_mods_' ) !== 0 ) {
		return;
	}
	
	if ( ! is_array( $new_value ) ) {
		return;
	}
	
	$map = HTG_get_settings_map();
	
	foreach ( $map as $theme_mod => $config ) {
		if ( isset( $new_value[ $theme_mod ] ) ) {
			$value = $new_value[ $theme_mod ];
			
			// Convert 'true'/'false' strings back to boolean for storage
			if ( isset( $config['bool_to_string'] ) && $config['bool_to_string'] ) {
				$value = ( $value === 'true' || $value === true || $value === 1 || $value === '1' ) ? 1 : 0;
			}
			
			// Update the option (silent - no action hooks)
			update_option( $config['option'], $value );
		}
	}
}
add_action( 'update_option', 'HTG_sync_customizer_to_options', 10, 3 );

/**
 * Sync Admin Panel options to Customizer theme_mods
 * When an HTG_* option is updated, also update the corresponding theme_mod
 */
function HTG_sync_options_to_customizer( $option_name, $old_value, $new_value ) {
	// Only process HTG_ options
	if ( strpos( $option_name, 'HTG_' ) !== 0 ) {
		return;
	}
	
	$map = HTG_get_settings_map();
	
	// Find the theme_mod that corresponds to this option
	foreach ( $map as $theme_mod => $config ) {
		if ( $config['option'] === $option_name ) {
			$value = $new_value;
			
			// Convert boolean to 'true'/'false' string if needed
			if ( isset( $config['bool_to_string'] ) && $config['bool_to_string'] ) {
				$value = $new_value ? 'true' : 'false';
			}
			
			// Update theme_mod (remove action first to prevent infinite loop)
			remove_action( 'update_option', 'HTG_sync_customizer_to_options', 10 );
			set_theme_mod( $theme_mod, $value );
			add_action( 'update_option', 'HTG_sync_customizer_to_options', 10, 3 );
			
			break;
		}
	}
}
add_action( 'updated_option', 'HTG_sync_options_to_customizer', 10, 3 );
add_action( 'added_option', function( $option_name, $value ) {
	HTG_sync_options_to_customizer( $option_name, null, $value );
}, 10, 2 );

/**
 * Override ad-related theme_mods to use options
 * Register individual theme_mod filters for each ad-related setting
 */
function HTG_register_ad_theme_mod_overrides() {
	$ad_options = array(
		'HTG_ad_head_code', 'HTG_ad_footer_code', 'HTG_ad_header_above',
		'HTG_ad_header_below', 'HTG_ad_before_content', 'HTG_ad_after_content',
		'HTG_ad_in_article', 'HTG_ad_in_article_position', 'HTG_ad_sidebar_top',
		'HTG_ad_sidebar_sticky', 'HTG_ad_homepage_top', 'HTG_ad_before_footer',
		'HTG_ad_labels_enable',
	);
	
	foreach ( $ad_options as $option_name ) {
		add_filter( 'theme_mod_' . $option_name, function( $value ) use ( $option_name ) {
			$option_value = get_option( $option_name, null );
			if ( $option_value !== null && $option_value !== false ) {
				return $option_value;
			}
			return $value;
		});
	}
}
HTG_register_ad_theme_mod_overrides();

/**
 * Helper function to get any theme setting (from either admin panel or customizer)
 * Use this throughout the theme instead of get_theme_mod() or get_option()
 */
function HTG_get_setting( $name, $default = null ) {
	$map = HTG_get_settings_map();
	
	// Check if this is a mapped setting
	if ( isset( $map[ $name ] ) ) {
		return HTG_get_synced_setting( $name, $default );
	}
	
	// Check if it's an option name (reverse lookup)
	foreach ( $map as $theme_mod => $config ) {
		if ( $config['option'] === $name ) {
			return HTG_get_synced_setting( $theme_mod, $default );
		}
	}
	
	// Fallback: try option first, then theme_mod
	$value = get_option( $name, null );
	if ( $value !== null && $value !== false ) {
		return $value;
	}
	
	return get_theme_mod( $name, $default );
}

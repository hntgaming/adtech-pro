<?php
/**
 * Color Palette System - Distinct Dark Themes
 * Each palette is a completely different visual style
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get color palettes - Each is a unique visual identity
 */
function HTG_get_color_palettes() {
	return array(
		
		// =============================================
		// HTG DARK (DEFAULT) - Clean teal on dark gray
		// Matches the master design token system in style.css
		// Professional, modern, readable
		// =============================================
		'htg_dark' => array(
			'name'        => __( 'HTG Dark', 'adtech-pro' ),
			'description' => __( 'Default professional dark theme', 'adtech-pro' ),
			// Accents - Teal spectrum
			'primary'     => '#00d4aa',  // Teal (--htg-accent)
			'secondary'   => '#00b894',  // Deep teal (--htg-accent-hover)
			'accent1'     => '#1a1f36',  // Dark navy (--htg-navy)
			'accent2'     => '#00b894',  // Green teal
			// Text - High readability
			'heading'     => '#ffffff',
			'body_text'   => '#e0e0e0',
			'link'        => '#00d4aa',
			'link_hover'  => '#00b894',
			// Backgrounds - Dark grays
			'body_bg'     => '#0a0a0a',
			'content_bg'  => '#121212',
			'footer_bg'   => '#000000',
			'card_bg'     => '#1a1a1a',
			'border'      => '#2a2a2a',
		),
		
		// =============================================
		// NEON CYBER - Vibrant teal/purple on pure black
		// Modern, gaming, tech startups
		// =============================================
		'neon_cyber' => array(
			'name'        => __( 'Neon Cyber', 'adtech-pro' ),
			'description' => __( 'Vibrant neon on pure black', 'adtech-pro' ),
			// Accents - High contrast neon
			'primary'     => '#00ffd5',  // Bright cyan
			'secondary'   => '#bf00ff',  // Neon purple
			'accent1'     => '#ff00a0',  // Hot pink
			'accent2'     => '#00ff88',  // Neon green
			// Text - High contrast
			'heading'     => '#ffffff',
			'body_text'   => '#c0c0c0',
			'link'        => '#00ffd5',
			'link_hover'  => '#bf00ff',
			// Backgrounds - Pure black
			'body_bg'     => '#000000',
			'content_bg'  => '#0a0a0a',
			'footer_bg'   => '#000000',
			'card_bg'     => '#111111',
			'border'      => '#1a1a1a',
		),
		
		// =============================================
		// MIDNIGHT OCEAN - Deep blue tones throughout
		// News, business, corporate, professional
		// =============================================
		'midnight_ocean' => array(
			'name'        => __( 'Midnight Ocean', 'adtech-pro' ),
			'description' => __( 'Deep blue professional look', 'adtech-pro' ),
			// Accents - Blue spectrum
			'primary'     => '#0ea5e9',  // Sky blue
			'secondary'   => '#38bdf8',  // Light sky
			'accent1'     => '#0284c7',  // Darker blue
			'accent2'     => '#7dd3fc',  // Pale blue
			// Text - Slightly blue-tinted
			'heading'     => '#f0f9ff',  // Blue-white
			'body_text'   => '#94a3b8',  // Slate
			'link'        => '#0ea5e9',
			'link_hover'  => '#38bdf8',
			// Backgrounds - Navy/slate
			'body_bg'     => '#0c1222',  // Deep navy
			'content_bg'  => '#151d2e',  // Dark slate
			'footer_bg'   => '#070b14',  // Darker navy
			'card_bg'     => '#1a2435',
			'border'      => '#1e3a5f',
		),
		
		// =============================================
		// CRIMSON NIGHT - Red accents on warm dark
		// Entertainment, news, sports, media
		// =============================================
		'crimson_night' => array(
			'name'        => __( 'Crimson Night', 'adtech-pro' ),
			'description' => __( 'Bold red on warm charcoal', 'adtech-pro' ),
			// Accents - Red/orange warmth
			'primary'     => '#ef4444',  // Red
			'secondary'   => '#f97316',  // Orange
			'accent1'     => '#dc2626',  // Dark red
			'accent2'     => '#fbbf24',  // Amber
			// Text - Warm tones
			'heading'     => '#fef2f2',  // Red-tinted white
			'body_text'   => '#a8a29e',  // Warm gray
			'link'        => '#ef4444',
			'link_hover'  => '#f97316',
			// Backgrounds - Warm charcoal
			'body_bg'     => '#1c1917',  // Warm black
			'content_bg'  => '#292524',  // Stone
			'footer_bg'   => '#0c0a09',  // Dark stone
			'card_bg'     => '#2d2926',
			'border'      => '#44403c',
		),
		
		// =============================================
		// FOREST DEPTHS - Green/teal on deep forest
		// Tech, eco, lifestyle, nature, health
		// =============================================
		'forest_depths' => array(
			'name'        => __( 'Forest Depths', 'adtech-pro' ),
			'description' => __( 'Fresh green on forest dark', 'adtech-pro' ),
			// Accents - Nature greens
			'primary'     => '#10b981',  // Emerald
			'secondary'   => '#06b6d4',  // Cyan
			'accent1'     => '#059669',  // Dark emerald
			'accent2'     => '#34d399',  // Light emerald
			// Text - Nature tinted
			'heading'     => '#ecfdf5',  // Green-white
			'body_text'   => '#9ca3af',
			'link'        => '#10b981',
			'link_hover'  => '#06b6d4',
			// Backgrounds - Forest tones
			'body_bg'     => '#0a0f0d',  // Dark forest
			'content_bg'  => '#111916',  // Forest green-black
			'footer_bg'   => '#050807',
			'card_bg'     => '#162019',
			'border'      => '#1f352b',
		),
		
		// =============================================
		// GOLDEN DUSK - Amber/gold on rich brown-black
		// Creative, premium, luxury, gaming
		// =============================================
		'golden_dusk' => array(
			'name'        => __( 'Golden Dusk', 'adtech-pro' ),
			'description' => __( 'Luxury gold on rich dark', 'adtech-pro' ),
			// Accents - Gold spectrum
			'primary'     => '#f59e0b',  // Amber
			'secondary'   => '#eab308',  // Yellow
			'accent1'     => '#d97706',  // Dark amber
			'accent2'     => '#fcd34d',  // Light yellow
			// Text - Warm cream
			'heading'     => '#fffbeb',  // Cream
			'body_text'   => '#a1a1aa',
			'link'        => '#f59e0b',
			'link_hover'  => '#eab308',
			// Backgrounds - Rich brown-black
			'body_bg'     => '#0f0d09',  // Dark brown-black
			'content_bg'  => '#1a1713',  // Brown tint
			'footer_bg'   => '#080704',
			'card_bg'     => '#211e18',
			'border'      => '#3d3730',
		),
		
		// =============================================
		// ROYAL VIOLET - Purple luxury on deep plum
		// Creative, design, art, premium
		// =============================================
		'royal_violet' => array(
			'name'        => __( 'Royal Violet', 'adtech-pro' ),
			'description' => __( 'Rich purple on deep plum', 'adtech-pro' ),
			// Accents - Purple spectrum
			'primary'     => '#a855f7',  // Purple
			'secondary'   => '#ec4899',  // Pink
			'accent1'     => '#9333ea',  // Dark purple
			'accent2'     => '#c084fc',  // Light purple
			// Text - Purple tinted
			'heading'     => '#faf5ff',  // Purple-white
			'body_text'   => '#a1a1aa',
			'link'        => '#a855f7',
			'link_hover'  => '#ec4899',
			// Backgrounds - Deep plum
			'body_bg'     => '#0f0a15',  // Dark plum
			'content_bg'  => '#1a1225',  // Plum
			'footer_bg'   => '#08050c',
			'card_bg'     => '#1f1529',
			'border'      => '#3b2d4d',
		),
		
		// =============================================
		// SLATE MINIMAL - Clean gray, subtle blue accent
		// Professional, minimal, corporate, portfolio
		// =============================================
		'slate_minimal' => array(
			'name'        => __( 'Slate Minimal', 'adtech-pro' ),
			'description' => __( 'Clean minimal gray tones', 'adtech-pro' ),
			// Accents - Subtle blue-gray
			'primary'     => '#64748b',  // Slate
			'secondary'   => '#3b82f6',  // Blue pop
			'accent1'     => '#475569',  // Dark slate
			'accent2'     => '#94a3b8',  // Light slate
			// Text - Pure neutrals
			'heading'     => '#f8fafc',
			'body_text'   => '#94a3b8',
			'link'        => '#3b82f6',
			'link_hover'  => '#60a5fa',
			// Backgrounds - Pure grays
			'body_bg'     => '#0f1115',
			'content_bg'  => '#171b21',
			'footer_bg'   => '#0a0c0f',
			'card_bg'     => '#1e2329',
			'border'      => '#2d333b',
		),
	);
}

/**
 * Sanitize color
 */
function HTG_sanitize_color_or_transparent( $color ) {
	if ( 'transparent' === $color ) {
		return 'transparent';
	}
	return sanitize_hex_color( $color );
}

/**
 * Add customizer settings
 */
function HTG_color_palette_customizer( $wp_customize ) {

	// Panel
	$wp_customize->add_panel( 'HTG_colors_panel', array(
		'title'       => __( 'Colors & Branding', 'adtech-pro' ),
		'description' => __( 'Choose a visual style for your site', 'adtech-pro' ),
		'priority'    => 30,
	) );

	// Section: Color Scheme
	$wp_customize->add_section( 'HTG_color_scheme_section', array(
		'title'       => __( 'Color Scheme', 'adtech-pro' ),
		'panel'       => 'HTG_colors_panel',
		'priority'    => 10,
	) );

	// Scheme selector
	$wp_customize->add_setting( 'HTG_color_scheme', array(
		'default'           => 'htg_dark',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	$palettes = HTG_get_color_palettes();
	$choices = array();
	foreach ( $palettes as $key => $palette ) {
		$choices[ $key ] = $palette['name'] . ' — ' . $palette['description'];
	}
	$choices['custom'] = __( 'Custom — Define your own colors', 'adtech-pro' );

	$wp_customize->add_control( 'HTG_color_scheme', array(
		'label'       => __( 'Visual Style', 'adtech-pro' ),
		'description' => __( 'Each style transforms the entire site look', 'adtech-pro' ),
		'section'     => 'HTG_color_scheme_section',
		'type'        => 'select',
		'choices'     => $choices,
	) );

	// Section: Custom Colors (only for custom scheme)
	$wp_customize->add_section( 'HTG_custom_colors', array(
		'title'       => __( 'Custom Colors', 'adtech-pro' ),
		'description' => __( 'Only used when "Custom" scheme is selected', 'adtech-pro' ),
		'panel'       => 'HTG_colors_panel',
		'priority'    => 20,
	) );

	// Primary Color
	$wp_customize->add_setting( 'HTG_custom_primary', array(
		'default'           => '#00d4aa',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'HTG_custom_primary', array(
		'label'   => __( 'Primary Accent', 'adtech-pro' ),
		'section' => 'HTG_custom_colors',
	) ) );

	// Secondary Color
	$wp_customize->add_setting( 'HTG_custom_secondary', array(
		'default'           => '#00b894',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'HTG_custom_secondary', array(
		'label'   => __( 'Secondary Accent', 'adtech-pro' ),
		'section' => 'HTG_custom_colors',
	) ) );

	// Background
	$wp_customize->add_setting( 'HTG_custom_body_bg', array(
		'default'           => '#0a0a0a',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'HTG_custom_body_bg', array(
		'label'   => __( 'Site Background', 'adtech-pro' ),
		'section' => 'HTG_custom_colors',
	) ) );

	// Content Background
	$wp_customize->add_setting( 'HTG_custom_content_bg', array(
		'default'           => '#121212',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'HTG_custom_content_bg', array(
		'label'   => __( 'Content Background', 'adtech-pro' ),
		'section' => 'HTG_custom_colors',
	) ) );

	// Widget / Card Background
	$wp_customize->add_setting( 'HTG_custom_card_bg', array(
		'default'           => '#1a1a1a',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'HTG_custom_card_bg', array(
		'label'       => __( 'Widget / Card Background', 'adtech-pro' ),
		'description' => __( 'Background for widgets, cards, and elevated surfaces', 'adtech-pro' ),
		'section'     => 'HTG_custom_colors',
	) ) );

	// Typography Section
	$wp_customize->add_section( 'HTG_typography_section', array(
		'title'    => __( 'Typography', 'adtech-pro' ),
		'panel'    => 'HTG_colors_panel',
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'HTG_heading_font', array(
		'default'           => 'Inter',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'HTG_heading_font', array(
		'label'   => __( 'Heading Font', 'adtech-pro' ),
		'section' => 'HTG_typography_section',
		'type'    => 'select',
		'choices' => array(
			'Inter'      => 'Inter',
			'Roboto'     => 'Roboto',
			'Poppins'    => 'Poppins',
			'Montserrat' => 'Montserrat',
			'Open Sans'  => 'Open Sans',
		),
	) );

	$wp_customize->add_setting( 'HTG_body_font', array(
		'default'           => 'Inter',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'HTG_body_font', array(
		'label'   => __( 'Body Font', 'adtech-pro' ),
		'section' => 'HTG_typography_section',
		'type'    => 'select',
		'choices' => array(
			'Inter'      => 'Inter',
			'Roboto'     => 'Roboto',
			'Open Sans'  => 'Open Sans',
			'Lato'       => 'Lato',
		),
	) );
}
add_action( 'customize_register', 'HTG_color_palette_customizer' );

/**
 * Get current colors based on selected scheme
 */
function HTG_get_current_colors() {
	$scheme = get_theme_mod( 'HTG_color_scheme', 'htg_dark' );
	$palettes = HTG_get_color_palettes();
	
	if ( 'custom' === $scheme ) {
		return array(
			'primary'     => get_theme_mod( 'HTG_custom_primary', '#00d4aa' ),
			'secondary'   => get_theme_mod( 'HTG_custom_secondary', '#00b894' ),
			'accent1'     => '#1a1f36',
			'accent2'     => get_theme_mod( 'HTG_custom_secondary', '#00b894' ),
			'heading'     => '#ffffff',
			'body_text'   => '#e0e0e0',
			'link'        => get_theme_mod( 'HTG_custom_primary', '#00d4aa' ),
			'link_hover'  => get_theme_mod( 'HTG_custom_secondary', '#00b894' ),
			'body_bg'     => get_theme_mod( 'HTG_custom_body_bg', '#0a0a0a' ),
			'content_bg'  => get_theme_mod( 'HTG_custom_content_bg', '#121212' ),
			'footer_bg'   => get_theme_mod( 'HTG_custom_body_bg', '#0a0a0a' ),
			'card_bg'     => get_theme_mod( 'HTG_custom_card_bg', '#1a1a1a' ),
			'border'      => '#2a2a2a',
		);
	}
	
	if ( isset( $palettes[ $scheme ] ) ) {
		return $palettes[ $scheme ];
	}
	
	return $palettes['htg_dark'];
}

/**
 * Output CSS variables that bridge palette colors to style.css design tokens.
 * 
 * This is the SINGLE source of truth for runtime color variables.
 * style.css uses these variables throughout — no hardcoded rules needed here.
 */
function HTG_output_color_scheme() {
	$c = HTG_get_current_colors();
	?>
	<style id="htg-color-scheme">
	/* === Design Token Bridge ===
	   Maps active palette colors to the master design tokens used in style.css.
	   This block overrides the :root defaults in style.css with the active palette. */
	:root {
		/* --- Accent Colors --- */
		--htg-accent: <?php echo esc_attr( $c['primary'] ); ?>;
		--htg-accent-hover: <?php echo esc_attr( $c['secondary'] ); ?>;
		--htg-navy: <?php echo esc_attr( $c['accent1'] ); ?>;

		/* --- Text Colors --- */
		--htg-text-primary: <?php echo esc_attr( $c['heading'] ); ?>;
		--htg-text-body: <?php echo esc_attr( $c['body_text'] ); ?>;

		/* --- Background Colors --- */
		--htg-bg-primary: <?php echo esc_attr( $c['body_bg'] ); ?>;
		--htg-bg-card: <?php echo esc_attr( $c['content_bg'] ); ?>;
		--htg-bg-elevated: <?php echo esc_attr( $c['card_bg'] ); ?>;

		/* --- Border --- */
		--htg-border: <?php echo esc_attr( $c['border'] ); ?>;

		/* --- Legacy variable aliases (backward compat) --- */
		--htg-primary: <?php echo esc_attr( $c['primary'] ); ?>;
		--htg-secondary: <?php echo esc_attr( $c['secondary'] ); ?>;
		--htg-accent-1: <?php echo esc_attr( $c['accent1'] ); ?>;
		--htg-accent-2: <?php echo esc_attr( $c['accent2'] ); ?>;
		--htg-heading: <?php echo esc_attr( $c['heading'] ); ?>;
		--htg-body-text: <?php echo esc_attr( $c['body_text'] ); ?>;
		--htg-link: <?php echo esc_attr( $c['link'] ); ?>;
		--htg-link-hover: <?php echo esc_attr( $c['link_hover'] ); ?>;
		--htg-body-bg: <?php echo esc_attr( $c['body_bg'] ); ?>;
		--htg-content-bg: <?php echo esc_attr( $c['content_bg'] ); ?>;
		--htg-footer-bg: <?php echo esc_attr( $c['footer_bg'] ); ?>;
		--htg-card-bg: <?php echo esc_attr( $c['card_bg'] ); ?>;
	}

	/* === Critical Overrides ===
	   Minimal set of rules that MUST use !important to override
	   third-party plugins and WordPress core inline styles. */

	/* Force dark backgrounds on structural wrappers */
	body, html {
		background-color: var(--htg-bg-primary) !important;
		color: var(--htg-text-body);
	}

	#page, .site, .site-content, .hm-container, #content {
		background-color: var(--htg-bg-primary) !important;
	}

	/* Header & navigation backgrounds */
	.site-header, #masthead {
		background-color: var(--htg-bg-primary) !important;
	}

	.main-navigation, .hm-main-navigation, #site-navigation {
		background-color: var(--htg-bg-card) !important;
	}

	/* Footer backgrounds */
	.site-footer, #colophon, .footer-widget-area {
		background-color: <?php echo esc_attr( $c['footer_bg'] ); ?> !important;
	}

	/* Scrollbar */
	::-webkit-scrollbar { width: 10px; height: 10px; background: var(--htg-bg-primary); }
	::-webkit-scrollbar-track { background: var(--htg-bg-primary); }
	::-webkit-scrollbar-thumb { background: var(--htg-border); border-radius: 5px; }
	::-webkit-scrollbar-thumb:hover { background: var(--htg-accent); }

	/* Selection */
	::selection { background-color: var(--htg-accent); color: #000; }
	::-moz-selection { background-color: var(--htg-accent); color: #000; }
	</style>
	<?php
}
add_action( 'wp_head', 'HTG_output_color_scheme', 100 );

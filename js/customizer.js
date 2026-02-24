/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 * Palette definitions mirror color-palettes.php for live preview.
 *
 * @package HTG_AdTech_Pro
 * @since 2.3.0
 */

( function( $ ) {

	// Palette definitions — must stay in sync with HTG_get_color_palettes() in PHP
	var colorPalettes = {
		htg_dark: {
			primary: '#00d4aa',
			secondary: '#00b894',
			accent1: '#1a1f36',
			heading: '#ffffff',
			body_text: '#e0e0e0',
			link: '#00d4aa',
			link_hover: '#00b894',
			body_bg: '#0a0a0a',
			content_bg: '#121212',
			footer_bg: '#000000',
			card_bg: '#1a1a1a',
			border: '#2a2a2a'
		},
		neon_cyber: {
			primary: '#00ffd5',
			secondary: '#bf00ff',
			accent1: '#ff00a0',
			heading: '#ffffff',
			body_text: '#c0c0c0',
			link: '#00ffd5',
			link_hover: '#bf00ff',
			body_bg: '#000000',
			content_bg: '#0a0a0a',
			footer_bg: '#000000',
			card_bg: '#111111',
			border: '#1a1a1a'
		},
		midnight_ocean: {
			primary: '#0ea5e9',
			secondary: '#38bdf8',
			accent1: '#0284c7',
			heading: '#f0f9ff',
			body_text: '#94a3b8',
			link: '#0ea5e9',
			link_hover: '#38bdf8',
			body_bg: '#0c1222',
			content_bg: '#151d2e',
			footer_bg: '#070b14',
			card_bg: '#1a2435',
			border: '#1e3a5f'
		},
		crimson_night: {
			primary: '#ef4444',
			secondary: '#f97316',
			accent1: '#dc2626',
			heading: '#fef2f2',
			body_text: '#a8a29e',
			link: '#ef4444',
			link_hover: '#f97316',
			body_bg: '#1c1917',
			content_bg: '#292524',
			footer_bg: '#0c0a09',
			card_bg: '#2d2926',
			border: '#44403c'
		},
		forest_depths: {
			primary: '#10b981',
			secondary: '#06b6d4',
			accent1: '#059669',
			heading: '#ecfdf5',
			body_text: '#9ca3af',
			link: '#10b981',
			link_hover: '#06b6d4',
			body_bg: '#0a0f0d',
			content_bg: '#111916',
			footer_bg: '#050807',
			card_bg: '#162019',
			border: '#1f352b'
		},
		golden_dusk: {
			primary: '#f59e0b',
			secondary: '#eab308',
			accent1: '#d97706',
			heading: '#fffbeb',
			body_text: '#a1a1aa',
			link: '#f59e0b',
			link_hover: '#eab308',
			body_bg: '#0f0d09',
			content_bg: '#1a1713',
			footer_bg: '#080704',
			card_bg: '#211e18',
			border: '#3d3730'
		},
		royal_violet: {
			primary: '#a855f7',
			secondary: '#ec4899',
			accent1: '#9333ea',
			heading: '#faf5ff',
			body_text: '#a1a1aa',
			link: '#a855f7',
			link_hover: '#ec4899',
			body_bg: '#0f0a15',
			content_bg: '#1a1225',
			footer_bg: '#08050c',
			card_bg: '#1f1529',
			border: '#3b2d4d'
		},
		slate_minimal: {
			primary: '#64748b',
			secondary: '#3b82f6',
			accent1: '#475569',
			heading: '#f8fafc',
			body_text: '#94a3b8',
			link: '#3b82f6',
			link_hover: '#60a5fa',
			body_bg: '#0f1115',
			content_bg: '#171b21',
			footer_bg: '#0a0c0f',
			card_bg: '#1e2329',
			border: '#2d333b'
		}
	};

	function isValidCSSColor( val ) {
		return typeof val === 'string' && /^#[0-9A-Fa-f]{3,8}$/.test( val );
	}

	function safeColor( val, fallback ) {
		return isValidCSSColor( val ) ? val : ( fallback || '#000000' );
	}

	function applyColors( palette ) {
		$( '#HTG-custom-colors-preview' ).remove();

		var p = {
			primary:    safeColor( palette.primary ),
			secondary:  safeColor( palette.secondary ),
			accent1:    safeColor( palette.accent1 ),
			heading:    safeColor( palette.heading, '#ffffff' ),
			body_text:  safeColor( palette.body_text, '#cccccc' ),
			body_bg:    safeColor( palette.body_bg, '#0a0a0a' ),
			content_bg: safeColor( palette.content_bg, '#111111' ),
			card_bg:    safeColor( palette.card_bg, '#1a1a1a' ),
			border:     safeColor( palette.border, '#333333' ),
			footer_bg:  safeColor( palette.footer_bg, '#0a0a0a' )
		};

		var css = '<style id="HTG-custom-colors-preview">';
		css += ':root {';
		css += '--htg-accent: ' + p.primary + ';';
		css += '--htg-accent-hover: ' + p.secondary + ';';
		css += '--htg-navy: ' + p.accent1 + ';';
		css += '--htg-text-primary: ' + p.heading + ';';
		css += '--htg-text-body: ' + p.body_text + ';';
		css += '--htg-bg-primary: ' + p.body_bg + ';';
		css += '--htg-bg-card: ' + p.content_bg + ';';
		css += '--htg-bg-elevated: ' + p.card_bg + ';';
		css += '--htg-border: ' + p.border + ';';
		css += '}';

		css += 'body, html { background-color: ' + p.body_bg + ' !important; color: ' + p.body_text + '; }';
		css += '#page, .site, .site-content { background-color: ' + p.body_bg + ' !important; }';
		css += '.site-footer, .footer-widget-area { background-color: ' + p.footer_bg + ' !important; }';
		css += '</style>';

		$( 'head' ).append( css );
	}

	// ===================================
	// SITE IDENTITY (postMessage transport)
	// ===================================
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
					color: to
				} );
			}
		} );
	} );

	// ===================================
	// COLOR SCHEME HANDLER
	// ===================================
	wp.customize( 'HTG_color_scheme', function( value ) {
		value.bind( function( scheme ) {
			if ( scheme === 'custom' ) {
				$( '#HTG-custom-colors-preview' ).remove();
				return;
			}

			var palette = colorPalettes[ scheme ];
			if ( palette ) {
				applyColors( palette );
			}
		} );
	} );

	// ===================================
	// CUSTOM COLOR HANDLERS (for Custom scheme)
	// ===================================
	wp.customize( 'HTG_custom_primary', function( value ) {
		value.bind( function( to ) {
			if ( isValidCSSColor( to ) ) document.documentElement.style.setProperty( '--htg-accent', to );
		} );
	} );

	wp.customize( 'HTG_custom_secondary', function( value ) {
		value.bind( function( to ) {
			if ( isValidCSSColor( to ) ) document.documentElement.style.setProperty( '--htg-accent-hover', to );
		} );
	} );

	wp.customize( 'HTG_custom_body_bg', function( value ) {
		value.bind( function( to ) {
			if ( !isValidCSSColor( to ) ) return;
			document.documentElement.style.setProperty( '--htg-bg-primary', to );
			$( 'body' ).css( 'background-color', to );
		} );
	} );

	wp.customize( 'HTG_custom_content_bg', function( value ) {
		value.bind( function( to ) {
			if ( isValidCSSColor( to ) ) document.documentElement.style.setProperty( '--htg-bg-card', to );
		} );
	} );

	wp.customize( 'HTG_custom_card_bg', function( value ) {
		value.bind( function( to ) {
			if ( isValidCSSColor( to ) ) document.documentElement.style.setProperty( '--htg-bg-elevated', to );
		} );
	} );

} )( jQuery );

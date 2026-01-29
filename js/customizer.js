/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Define color palettes
	var colorPalettes = {
		ht_gaming: {
			primary: '#240b50',
			secondary: '#80d3f5',
			accent1: '#faa98f',
			accent2: '#b9eacc',
			heading: '#222222',
			body_text: '#404040',
			link: '#80d3f5',
			link_hover: '#240b50',
			body_bg: '#ffffff',
			content_bg: '#ffffff',
			footer_bg: '#240b50'
		},
		news_red: {
			primary: '#e74c3c',
			secondary: '#c0392b',
			accent1: '#ff6b6b',
			accent2: '#ffa07a',
			heading: '#2c3e50',
			body_text: '#34495e',
			link: '#e74c3c',
			link_hover: '#c0392b',
			body_bg: '#f8f9fa',
			content_bg: '#ffffff',
			footer_bg: '#2c3e50'
		},
		tech_blue: {
			primary: '#3498db',
			secondary: '#2980b9',
			accent1: '#5dade2',
			accent2: '#85c1e9',
			heading: '#1a1a1a',
			body_text: '#333333',
			link: '#3498db',
			link_hover: '#2980b9',
			body_bg: '#ffffff',
			content_bg: '#ffffff',
			footer_bg: '#1a1a1a'
		},
		business: {
			primary: '#2c3e50',
			secondary: '#34495e',
			accent1: '#95a5a6',
			accent2: '#bdc3c7',
			heading: '#2c3e50',
			body_text: '#34495e',
			link: '#3498db',
			link_hover: '#2c3e50',
			body_bg: '#ecf0f1',
			content_bg: '#ffffff',
			footer_bg: '#2c3e50'
		},
		dark_mode: {
			primary: '#bb86fc',
			secondary: '#03dac6',
			accent1: '#cf6679',
			accent2: '#03dac6',
			heading: '#ffffff',
			body_text: '#e0e0e0',
			link: '#bb86fc',
			link_hover: '#03dac6',
			body_bg: '#121212',
			content_bg: '#1e1e1e',
			footer_bg: '#000000'
		}
	};

	// Function to apply colors
	function applyColors(palette) {
		// Remove existing custom style
		$('#HTG-custom-colors-preview').remove();

		// Build CSS
		var css = '<style id="HTG-custom-colors-preview">';
		css += ':root {';
		css += '--htg-primary: ' + palette.primary + ';';
		css += '--htg-secondary: ' + palette.secondary + ';';
		css += '--htg-accent-1: ' + palette.accent1 + ';';
		css += '--htg-accent-2: ' + palette.accent2 + ';';
		css += '}';
		
		// Apply colors
		css += 'body { color: ' + palette.body_text + ' !important; background-color: ' + palette.body_bg + ' !important; }';
		css += 'h1, h2, h3, h4, h5, h6, .entry-title, .entry-title a, .widget-title { color: ' + palette.heading + ' !important; }';
		css += 'a { color: ' + palette.link + ' !important; }';
		css += 'a:hover { color: ' + palette.link_hover + ' !important; }';
		css += '.site-title a, button, input[type="button"], input[type="reset"], input[type="submit"], .cat-links a, .search-form .search-submit { background-color: ' + palette.primary + ' !important; }';
		css += '.site-footer, .widget-title, .footer-widget-title { border-color: ' + palette.primary + ' !important; }';
		css += '.footer-widget-area a:hover, .site-info a { color: ' + palette.secondary + ' !important; }';
		css += '.site-content { background-color: ' + palette.content_bg + ' !important; }';
		css += '</style>';

		$('head').append(css);
	}

	// Site title and description.
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

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
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
				// Remove preview styles for custom
				$('#HTG-custom-colors-preview').remove();
				return;
			}

			// Get palette and apply
			var palette = colorPalettes[scheme];
			if ( palette ) {
				applyColors(palette);
				
				// Also update the individual color controls
				wp.customize('HTG_primary_color').set(palette.primary);
				wp.customize('HTG_secondary_color').set(palette.secondary);
				wp.customize('HTG_accent_color_1').set(palette.accent1);
				wp.customize('HTG_accent_color_2').set(palette.accent2);
				wp.customize('HTG_heading_color').set(palette.heading);
				wp.customize('HTG_body_text_color').set(palette.body_text);
				wp.customize('HTG_link_color').set(palette.link);
				wp.customize('HTG_link_hover_color').set(palette.link_hover);
				wp.customize('HTG_body_background_color').set(palette.body_bg);
				wp.customize('HTG_content_background_color').set(palette.content_bg);
				wp.customize('HTG_footer_background_color').set(palette.footer_bg);
			}
		} );
	} );

	// ===================================
	// INDIVIDUAL COLOR HANDLERS
	// ===================================
	
	// Primary color
	wp.customize( 'HTG_primary_color', function( value ) {
		value.bind( function( to ) {
			$('<style id="HTG-primary-preview">' +
				'.site-title a, button, input[type="button"], input[type="reset"], input[type="submit"], ' +
				'.cat-links a, .search-form .search-submit, .nav-links .current, .widget_tag_cloud .tagcloud a ' +
				'{ background-color: ' + to + ' !important; }' +
				'.site-footer, .widget-title, .footer-widget-title { border-color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
			$('#HTG-primary-preview').remove();
			$('<style id="HTG-primary-preview">' +
				'.site-title a, button, input[type="button"], input[type="reset"], input[type="submit"], ' +
				'.cat-links a, .search-form .search-submit, .nav-links .current, .widget_tag_cloud .tagcloud a ' +
				'{ background-color: ' + to + ' !important; }' +
				'.site-footer, .widget-title, .footer-widget-title { border-color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
		} );
	} );

	// Secondary color
	wp.customize( 'HTG_secondary_color', function( value ) {
		value.bind( function( to ) {
			$('#HTG-secondary-preview').remove();
			$('<style id="HTG-secondary-preview">' +
				'.footer-widget-area a:hover, .site-info a { color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
		} );
	} );

	// Heading color
	wp.customize( 'HTG_heading_color', function( value ) {
		value.bind( function( to ) {
			$('#HTG-heading-preview').remove();
			$('<style id="HTG-heading-preview">' +
				'h1, h2, h3, h4, h5, h6, .entry-title, .entry-title a, .widget-title { color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
		} );
	} );

	// Body text color
	wp.customize( 'HTG_body_text_color', function( value ) {
		value.bind( function( to ) {
			$('#HTG-body-text-preview').remove();
			$('<style id="HTG-body-text-preview">' +
				'body { color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
		} );
	} );

	// Link color
	wp.customize( 'HTG_link_color', function( value ) {
		value.bind( function( to ) {
			$('#HTG-link-preview').remove();
			$('<style id="HTG-link-preview">' +
				'a { color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
		} );
	} );

	// Link hover color
	wp.customize( 'HTG_link_hover_color', function( value ) {
		value.bind( function( to ) {
			$('#HTG-link-hover-preview').remove();
			$('<style id="HTG-link-hover-preview">' +
				'a:hover, a:focus, a:active { color: ' + to + ' !important; }' +
			'</style>').appendTo('head');
		} );
	} );

	// Background colors
	wp.customize( 'HTG_body_background_color', function( value ) {
		value.bind( function( to ) {
			$('body').css('background-color', to);
		} );
	} );

	wp.customize( 'HTG_content_background_color', function( value ) {
		value.bind( function( to ) {
			$('.site-content').css('background-color', to);
		} );
	} );

	wp.customize( 'HTG_footer_background_color', function( value ) {
		value.bind( function( to ) {
			$('.site-footer').css('background-color', to);
		} );
	} );

} )( jQuery );
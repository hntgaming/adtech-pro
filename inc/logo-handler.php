<?php
/**
 * H&T AdTech Pro - Logo Handler
 * 
 * Auto-resize and optimize site logo for best display
 * Optional PNG conversion with background removal
 *
 * @package HTG
 * @since 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get logo settings with defaults
 */
function HTG_get_logo_settings() {
	return array(
		'max_width'           => get_option( 'HTG_logo_max_width', 300 ),
		'max_height'          => get_option( 'HTG_logo_max_height', 100 ),
		'auto_resize'         => get_option( 'HTG_logo_auto_resize', 1 ),
		'remove_background'   => get_option( 'HTG_logo_remove_bg', 0 ),
		'background_color'    => get_option( 'HTG_logo_bg_color', '#ffffff' ),
		'tolerance'           => get_option( 'HTG_logo_bg_tolerance', 30 ),
	);
}

/**
 * Process logo when set as custom logo
 * Triggers on customize_save_after to catch logo changes
 */
function HTG_process_custom_logo() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( ! $custom_logo_id ) {
		return;
	}

	// Check if we've already processed this logo
	$processed = get_post_meta( $custom_logo_id, '_htg_logo_processed', true );
	if ( $processed ) {
		return;
	}

	$settings = HTG_get_logo_settings();
	
	if ( ! $settings['auto_resize'] && ! $settings['remove_background'] ) {
		return;
	}

	$file_path = get_attached_file( $custom_logo_id );
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return;
	}

	// Check if GD library is available
	if ( ! function_exists( 'imagecreatetruecolor' ) ) {
		return;
	}

	$image_info = getimagesize( $file_path );
	if ( ! $image_info ) {
		return;
	}

	$width = $image_info[0];
	$height = $image_info[1];
	$mime_type = $image_info['mime'];

	// Check if resize is needed
	$max_width = $settings['max_width'] * 2; // 2x for retina
	$max_height = $settings['max_height'] * 2;
	
	$needs_resize = ( $width > $max_width || $height > $max_height ) && $settings['auto_resize'];
	$needs_bg_removal = $settings['remove_background'];

	if ( ! $needs_resize && ! $needs_bg_removal ) {
		update_post_meta( $custom_logo_id, '_htg_logo_processed', 1 );
		return;
	}

	// Load image based on type
	$image = null;
	switch ( $mime_type ) {
		case 'image/jpeg':
			$image = @imagecreatefromjpeg( $file_path );
			break;
		case 'image/png':
			$image = @imagecreatefrompng( $file_path );
			break;
		case 'image/gif':
			$image = @imagecreatefromgif( $file_path );
			break;
		case 'image/webp':
			if ( function_exists( 'imagecreatefromwebp' ) ) {
				$image = @imagecreatefromwebp( $file_path );
			}
			break;
	}

	if ( ! $image ) {
		update_post_meta( $custom_logo_id, '_htg_logo_processed', 1 );
		return;
	}

	// Calculate new dimensions if resizing
	if ( $needs_resize ) {
		$ratio = min( $max_width / $width, $max_height / $height );
		$new_width = round( $width * $ratio );
		$new_height = round( $height * $ratio );
	} else {
		$new_width = $width;
		$new_height = $height;
	}

	// Create output image
	$output = imagecreatetruecolor( $new_width, $new_height );
	
	// Preserve transparency
	imagealphablending( $output, false );
	imagesavealpha( $output, true );
	$transparent = imagecolorallocatealpha( $output, 0, 0, 0, 127 );
	imagefill( $output, 0, 0, $transparent );

	// Resize or copy
	if ( $needs_resize ) {
		imagecopyresampled( $output, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
	} else {
		imagecopy( $output, $image, 0, 0, 0, 0, $width, $height );
	}

	// Remove background if enabled
	if ( $needs_bg_removal ) {
		$output = HTG_remove_logo_background( $output, $settings['background_color'], $settings['tolerance'] );
	}

	// Always save as PNG for transparency support
	$path_info = pathinfo( $file_path );
	$new_file_path = $path_info['dirname'] . '/' . $path_info['filename'] . '-optimized.png';
	
	imagepng( $output, $new_file_path, 9 );

	// Update attachment
	update_attached_file( $custom_logo_id, $new_file_path );
	
	// Regenerate metadata
	$metadata = wp_generate_attachment_metadata( $custom_logo_id, $new_file_path );
	wp_update_attachment_metadata( $custom_logo_id, $metadata );
	
	// Update mime type
	wp_update_post( array(
		'ID' => $custom_logo_id,
		'post_mime_type' => 'image/png',
	) );

	// Mark as processed
	update_post_meta( $custom_logo_id, '_htg_logo_processed', 1 );

	// Cleanup
	imagedestroy( $image );
	imagedestroy( $output );
}
add_action( 'customize_save_after', 'HTG_process_custom_logo', 20 );

/**
 * Also process logo when theme mod changes
 */
function HTG_on_logo_change( $value, $old_value ) {
	if ( $value !== $old_value && $value ) {
		// Clear processed flag so it gets reprocessed
		delete_post_meta( $value, '_htg_logo_processed' );
	}
	return $value;
}
add_filter( 'pre_set_theme_mod_custom_logo', 'HTG_on_logo_change', 10, 2 );

/**
 * Remove background from image (simple color-based removal)
 * Works best with solid color backgrounds (white, black, etc.)
 */
function HTG_remove_logo_background( $image, $bg_color = '#ffffff', $tolerance = 30 ) {
	$width = imagesx( $image );
	$height = imagesy( $image );

	// Parse background color
	$bg_color = ltrim( $bg_color, '#' );
	$bg_r = hexdec( substr( $bg_color, 0, 2 ) );
	$bg_g = hexdec( substr( $bg_color, 2, 2 ) );
	$bg_b = hexdec( substr( $bg_color, 4, 2 ) );

	// Create new image with alpha channel
	$new_image = imagecreatetruecolor( $width, $height );
	imagealphablending( $new_image, false );
	imagesavealpha( $new_image, true );

	for ( $x = 0; $x < $width; $x++ ) {
		for ( $y = 0; $y < $height; $y++ ) {
			$rgba = imagecolorat( $image, $x, $y );
			$r = ( $rgba >> 16 ) & 0xFF;
			$g = ( $rgba >> 8 ) & 0xFF;
			$b = $rgba & 0xFF;
			$a = ( $rgba >> 24 ) & 0x7F; // Existing alpha

			// Skip if already transparent
			if ( $a == 127 ) {
				$color = imagecolorallocatealpha( $new_image, 0, 0, 0, 127 );
				imagesetpixel( $new_image, $x, $y, $color );
				continue;
			}

			// Calculate color difference
			$diff = abs( $r - $bg_r ) + abs( $g - $bg_g ) + abs( $b - $bg_b );
			
			if ( $diff < $tolerance * 3 ) {
				// Make transparent (gradual based on difference)
				$alpha = min( 127, (int) ( 127 * ( 1 - $diff / ( $tolerance * 3 ) ) ) );
				$color = imagecolorallocatealpha( $new_image, $r, $g, $b, $alpha );
			} else {
				// Keep original color with original alpha
				$color = imagecolorallocatealpha( $new_image, $r, $g, $b, $a );
			}
			
			imagesetpixel( $new_image, $x, $y, $color );
		}
	}

	imagedestroy( $image );
	return $new_image;
}

/**
 * Filter custom logo HTML to add optimal sizing
 */
function HTG_filter_custom_logo( $html, $blog_id ) {
	if ( empty( $html ) ) {
		return $html;
	}

	$settings = HTG_get_logo_settings();
	
	// Add max dimensions via inline style
	$style = sprintf(
		'max-width: %dpx; max-height: %dpx; width: auto; height: auto;',
		$settings['max_width'],
		$settings['max_height']
	);

	// Check if style already exists
	if ( strpos( $html, 'style=' ) !== false ) {
		// Append to existing style
		$html = preg_replace(
			'/style=["\']([^"\']*)["\']/',
			'style="$1 ' . esc_attr( $style ) . '"',
			$html
		);
	} else {
		// Add new style attribute
		$html = preg_replace(
			'/<img([^>]+)>/i',
			'<img$1 style="' . esc_attr( $style ) . '">',
			$html
		);
	}

	return $html;
}
add_filter( 'get_custom_logo', 'HTG_filter_custom_logo', 10, 2 );

/**
 * Add logo CSS to frontend
 */
function HTG_logo_styles() {
	$settings = HTG_get_logo_settings();
	?>
	<style id="htg-logo-styles">
		/* Logo Auto-Sizing */
		.custom-logo-link {
			display: inline-block;
			vertical-align: middle;
		}
		.custom-logo-link img,
		.custom-logo {
			max-width: <?php echo esc_attr( $settings['max_width'] ); ?>px;
			max-height: <?php echo esc_attr( $settings['max_height'] ); ?>px;
			width: auto;
			height: auto;
			object-fit: contain;
		}
		.hm-logo {
			display: flex;
			align-items: center;
		}
		.hm-logo img {
			max-width: <?php echo esc_attr( $settings['max_width'] ); ?>px;
			max-height: <?php echo esc_attr( $settings['max_height'] ); ?>px;
			width: auto;
			height: auto;
		}
		/* Responsive logo sizing */
		@media screen and (max-width: 768px) {
			.custom-logo-link img,
			.custom-logo,
			.hm-logo img {
				max-width: <?php echo esc_attr( min( $settings['max_width'], 200 ) ); ?>px;
				max-height: <?php echo esc_attr( min( $settings['max_height'], 60 ) ); ?>px;
			}
		}
		@media screen and (max-width: 480px) {
			.custom-logo-link img,
			.custom-logo,
			.hm-logo img {
				max-width: <?php echo esc_attr( min( $settings['max_width'], 150 ) ); ?>px;
				max-height: <?php echo esc_attr( min( $settings['max_height'], 50 ) ); ?>px;
			}
		}
	</style>
	<?php
}
add_action( 'wp_head', 'HTG_logo_styles', 20 );

/**
 * Add button to manually re-process logo in admin
 */
function HTG_add_logo_reprocess_button() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->base !== 'toplevel_page_HTG-dashboard' ) {
		// Check if we're on the appearance page
		if ( ! $screen || strpos( $screen->id, 'HTG-appearance' ) === false ) {
			return;
		}
	}
	
	if ( isset( $_GET['htg_reprocess_logo'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'htg_reprocess_logo' ) ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			delete_post_meta( $custom_logo_id, '_htg_logo_processed' );
			HTG_process_custom_logo();
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Logo has been reprocessed.', 'adtech-pro' ) . '</p></div>';
			} );
		}
	}
}
add_action( 'admin_init', 'HTG_add_logo_reprocess_button' );

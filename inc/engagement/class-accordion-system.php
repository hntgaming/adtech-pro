<?php
/**
 * Content Accordion System
 * Organize long articles for better readability
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Accordion_System {

	/**
	 * Initialize accordion system
	 */
	public static function init() {
		add_shortcode( 'HTG_accordion', array( __CLASS__, 'render_accordion_shortcode' ) );
		add_shortcode( 'HTG_accordion_item', array( __CLASS__, 'render_accordion_item_shortcode' ) );
		add_action( 'wp_ajax_HTG_accordion_track', array( __CLASS__, 'track_accordion_interaction' ) );
		add_action( 'wp_ajax_nopriv_HTG_accordion_track', array( __CLASS__, 'track_accordion_interaction' ) );
	}

	/**
	 * Render accordion container
	 */
	public static function render_accordion_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'style' => 'default', // default, minimal, bordered
			'id'    => 'accordion-' . uniqid(),
		), $atts );

		$classes = array(
			'HTG-accordion',
			'HTG-accordion-' . esc_attr( $atts['style'] ),
		);

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" id="<?php echo esc_attr( $atts['id'] ); ?>" data-accordion-id="<?php echo esc_attr( $atts['id'] ); ?>">
			<?php echo do_shortcode( $content ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render accordion item
	 */
	public static function render_accordion_item_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'title' => '',
			'open'  => 'false',
			'icon'  => 'chevron', // chevron, plus, arrow
		), $atts );

		$item_id = 'accordion-item-' . uniqid();
		$is_open = 'true' === $atts['open'];

		$icon_html = self::get_icon_html( $atts['icon'] );

		ob_start();
		?>
		<div class="HTG-accordion-item <?php echo $is_open ? 'is-open' : ''; ?>" id="<?php echo esc_attr( $item_id ); ?>">
			<button class="HTG-accordion-header" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $item_id ); ?>-content">
				<span class="HTG-accordion-title"><?php echo esc_html( $atts['title'] ); ?></span>
				<span class="HTG-accordion-icon" data-icon-type="<?php echo esc_attr( $atts['icon'] ); ?>">
					<?php echo $icon_html; ?>
				</span>
			</button>
			<div class="HTG-accordion-content" id="<?php echo esc_attr( $item_id ); ?>-content" style="<?php echo $is_open ? '' : 'display: none;'; ?>">
				<div class="HTG-accordion-body">
					<?php echo do_shortcode( wpautop( $content ) ); ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get icon HTML based on type
	 */
	private static function get_icon_html( $type ) {
		switch ( $type ) {
			case 'plus':
				return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 5v10M5 10h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';
			
			case 'arrow':
				return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M5 7.5l5 5 5-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
			
			default: // chevron
				return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M6 8l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		}
	}

	/**
	 * Track accordion interactions
	 */
	public static function track_accordion_interaction() {
		check_ajax_referer( 'HTG_engagement_nonce', 'nonce' );

		$accordion_id = isset( $_POST['accordion_id'] ) ? sanitize_text_field( $_POST['accordion_id'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$action_type = isset( $_POST['action_type'] ) ? sanitize_text_field( $_POST['action_type'] ) : '';

		if ( ! $accordion_id || ! $post_id ) {
			wp_send_json_error();
		}

		// Track accordion opens
		$meta_key = '_HTG_accordion_opens';
		$opens = get_post_meta( $post_id, $meta_key, true );
		
		if ( ! is_array( $opens ) ) {
			$opens = array();
		}

		if ( ! isset( $opens[ $accordion_id ] ) ) {
			$opens[ $accordion_id ] = 0;
		}

		if ( 'open' === $action_type ) {
			$opens[ $accordion_id ]++;
			update_post_meta( $post_id, $meta_key, $opens );
		}

		wp_send_json_success( array(
			'opens' => $opens[ $accordion_id ],
		) );
	}
}

// Initialize accordion system
HTG_Accordion_System::init();


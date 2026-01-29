<?php
/**
 * Block Styles
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package WordPress
 * @subpackage HTG
 * @since HTG 1.3.0
 */

if ( function_exists( 'register_block_style' ) ) {

	/**
	 * Register block styles.
	 *
	 * @since HTG 1.3.0
	 *
	 * @return void
	 */
    function HTG_register_block_styles() {
        register_block_style(
			'core/heading',
			array(
				'name'  => 'HTG-widget-title',
				'label' => esc_html__( 'Widget title style', 'HTG' ),
			)
		);
    }
    add_action( 'init', 'HTG_register_block_styles' );

}


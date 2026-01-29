<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HTG
 */

$HTG_sidebar_layout = HTG_get_layout();

if ( $HTG_sidebar_layout == 'th-content-centered' || $HTG_sidebar_layout == 'th-no-sidebar' ) {
	return;
}

?>

<aside id="secondary" class="widget-area" role="complementary">

	<?php do_action( 'HTG_before_sidebar' ); ?>

	<?php 
	// Ad Slot: Sidebar Top
	HTG_display_ad( 'sidebar_top' ); 
	?>

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

	<?php 
	// Ad Slot: Sidebar Middle
	HTG_display_ad( 'sidebar_middle' ); 
	?>

	<?php do_action( 'HTG_after_sidebar' ); ?>

	<?php 
	// Note: Sidebar Bottom slot removed (not in simple ad system)
	// Use sidebar_top or sidebar_middle instead
	?>

</aside><!-- #secondary -->
<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HTG
 */

?>
	</div><!-- .hm-container -->
	</div><!-- #content -->

	<?php do_action( 'HTG_before_footer' ); ?>

	<?php 
	// Ad Slot: Footer Top
	HTG_display_ad( 'footer_top' ); 
	?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="hm-container">

			<?php do_action( 'HTG_before_footer_widget_area' ); ?>

			<div class="footer-widget-area">
				<div class="footer-sidebar" role="complementary">
					<?php if ( ! dynamic_sidebar( 'footer-left' ) ) : ?>
						
					<?php endif; // end sidebar widget area ?>
				</div><!-- .footer-sidebar -->
		
				<div class="footer-sidebar" role="complementary">
					<?php if ( ! dynamic_sidebar( 'footer-mid' ) ) : ?>

					<?php endif; // end sidebar widget area ?>
				</div><!-- .footer-sidebar -->		

				<div class="footer-sidebar" role="complementary">
					<?php if ( ! dynamic_sidebar( 'footer-right' ) ) : ?>

					<?php endif; // end sidebar widget area ?>
				</div><!-- .footer-sidebar -->			
			</div><!-- .footer-widget-area -->

			<?php do_action( 'HTG_after_footer_widget_area' ); ?>

		</div><!-- .hm-container -->

		<div class="site-info">
			<div class="hm-container">
				<div class="site-info-owner">
					<?php
						$footer_copyright_text = get_theme_mod( 'footer_copyright_text', '' );

						if ( ! empty ( $footer_copyright_text ) ) {
							echo wp_kses_post( $footer_copyright_text );
						} else {
							$site_link = '<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" >' . esc_attr( get_bloginfo( 'name' ) ) . '</a>';
							printf( esc_html__( 'Copyright &#169; %1$s %2$s.', 'HTG' ), date_i18n( 'Y' ), $site_link );
						}		
					?>
				</div>			
				<div class="site-info-designer">
					<?php
						printf( esc_html__( 'Powered by %1$s | AdTech Pro by %2$s', 'HTG' ),
							'<a href="https://wordpress.org" target="_blank" title="WordPress">WordPress</a>',
							'<a href="https://hntgaming.me" target="_blank" title="H&T GAMING">H&T GAMING</a>'
						); 
					?>
				</div>
			</div><!-- .hm-container -->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

	<?php do_action( 'HTG_after_footer' ); ?>

</div><!-- #page -->


<?php wp_footer(); ?>
</body>
</html>
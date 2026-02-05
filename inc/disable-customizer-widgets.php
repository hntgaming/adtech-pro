<?php
/**
 * Customizer Access Control
 * Customizer is ACCESSIBLE but MINIMAL (Site Identity only)
 * All theme settings are in Admin Panel
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add admin notice - Customizer is for Site Identity only
 */
function HTG_customizer_notice() {
	$screen = get_current_screen();
	
	if ( 'themes' === $screen->id || ( isset( $_GET['page'] ) && $_GET['page'] === 'HTG-general-settings' ) ) {
		?>
		<div class="notice notice-info">
			<p>
				<strong><?php esc_html_e( '🎨 Customizer Available!', 'adtech-pro' ); ?></strong>
				<?php esc_html_e( 'Use Customizer for Site Name, Tagline, Logo, and Icon. All theme settings are in the Admin Panel.', 'adtech-pro' ); ?>
				<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button" style="margin-left: 10px;">
					<?php esc_html_e( 'Site Identity', 'adtech-pro' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="button button-primary" style="margin-left: 5px;">
					<?php esc_html_e( 'Theme Settings', 'adtech-pro' ); ?>
				</a>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'HTG_customizer_notice' );

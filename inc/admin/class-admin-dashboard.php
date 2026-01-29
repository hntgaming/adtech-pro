<?php
/**
 * H&T AdTech Pro - Enterprise Admin Dashboard
 * Production-ready admin interface
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Admin_Dashboard {

	/**
	 * Theme version
	 */
	const VERSION = '2.3.0';

	/**
	 * Initialize the admin dashboard
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
	}

	/**
	 * Add admin menu
	 */
	public static function add_admin_menu() {
		add_menu_page(
			__( 'H&T AdTech', 'HTG' ),
			__( 'H&T AdTech', 'HTG' ),
			'manage_options',
			'HTG-dashboard',
			array( __CLASS__, 'render_dashboard' ),
			'dashicons-chart-area',
			3
		);
	}

	/**
	 * Enqueue admin assets
	 */
	public static function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, 'HTG' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'HTG-admin',
			get_template_directory_uri() . '/inc/admin/assets/admin.css',
			array(),
			self::VERSION
		);

		wp_enqueue_script(
			'HTG-admin',
			get_template_directory_uri() . '/inc/admin/assets/admin.js',
			array( 'jquery' ),
			self::VERSION,
			true
		);
	}

	/**
	 * Render main dashboard
	 */
	public static function render_dashboard() {
		$theme = wp_get_theme();
		?>
		<div class="wrap HTG-admin-wrap">
			<!-- Header -->
			<div class="HTG-admin-header">
				<h1>
					<?php esc_html_e( 'H&T AdTech Pro', 'HTG' ); ?>
					<span class="HTG-admin-badge"><?php echo esc_html( 'v' . self::VERSION ); ?></span>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Enterprise Publishing Platform for High-Traffic Publishers', 'HTG' ); ?></p>
			</div>

			<!-- Quick Stats Row -->
			<div class="HTG-admin-row HTG-admin-cols-4">
				<div class="HTG-stat-card">
					<div class="HTG-stat-icon">
						<span class="dashicons dashicons-admin-post"></span>
					</div>
					<div class="HTG-stat-content">
						<div class="HTG-stat-value"><?php echo esc_html( number_format( wp_count_posts()->publish ) ); ?></div>
						<div class="HTG-stat-label"><?php esc_html_e( 'Published Posts', 'HTG' ); ?></div>
					</div>
				</div>

				<div class="HTG-stat-card">
					<div class="HTG-stat-icon">
						<span class="dashicons dashicons-category"></span>
					</div>
					<div class="HTG-stat-content">
						<div class="HTG-stat-value"><?php echo esc_html( number_format( wp_count_terms( 'category' ) ) ); ?></div>
						<div class="HTG-stat-label"><?php esc_html_e( 'Categories', 'HTG' ); ?></div>
					</div>
				</div>

				<div class="HTG-stat-card">
					<div class="HTG-stat-icon">
						<span class="dashicons dashicons-admin-comments"></span>
					</div>
					<div class="HTG-stat-content">
						<div class="HTG-stat-value"><?php echo esc_html( number_format( wp_count_comments()->approved ) ); ?></div>
						<div class="HTG-stat-label"><?php esc_html_e( 'Comments', 'HTG' ); ?></div>
					</div>
				</div>

				<div class="HTG-stat-card">
					<div class="HTG-stat-icon">
						<span class="dashicons dashicons-admin-users"></span>
					</div>
					<div class="HTG-stat-content">
						<?php
						$user_count = count_users();
						?>
						<div class="HTG-stat-value"><?php echo esc_html( number_format( $user_count['total_users'] ) ); ?></div>
						<div class="HTG-stat-label"><?php esc_html_e( 'Total Users', 'HTG' ); ?></div>
					</div>
				</div>
			</div>

			<!-- Quick Actions & Ad Slots -->
			<div class="HTG-admin-row HTG-admin-cols-2">
				<!-- Quick Actions -->
				<div class="HTG-admin-card">
					<div class="HTG-card-header">
						<h2>
							<span class="dashicons dashicons-dashboard"></span>
							<?php esc_html_e( 'Quick Actions', 'HTG' ); ?>
						</h2>
					</div>
					<div class="HTG-card-body">
						<div class="HTG-quick-actions">
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-admin-generic"></span>
								<span><?php esc_html_e( 'General Settings', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-megaphone"></span>
								<span><?php esc_html_e( 'Ad Management', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-appearance' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-admin-appearance"></span>
								<span><?php esc_html_e( 'Appearance', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-magazine-settings' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-layout"></span>
								<span><?php esc_html_e( 'Magazine', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-engagement-settings' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-awards"></span>
								<span><?php esc_html_e( 'Engagement', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-legal-pages' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-shield"></span>
								<span><?php esc_html_e( 'Legal Pages', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="HTG-quick-action">
								<span class="dashicons dashicons-plus-alt"></span>
								<span><?php esc_html_e( 'New Post', 'HTG' ); ?></span>
							</a>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="HTG-quick-action" target="_blank">
								<span class="dashicons dashicons-external"></span>
								<span><?php esc_html_e( 'View Site', 'HTG' ); ?></span>
							</a>
						</div>
					</div>
				</div>

				<!-- Active Ad Slots -->
				<div class="HTG-admin-card">
					<div class="HTG-card-header">
						<h2>
							<span class="dashicons dashicons-megaphone"></span>
							<?php esc_html_e( 'Ad Slots Overview', 'HTG' ); ?>
						</h2>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button button-small">
							<?php esc_html_e( 'Manage', 'HTG' ); ?>
						</a>
					</div>
					<div class="HTG-card-body">
						<?php self::render_active_ad_slots(); ?>
					</div>
				</div>
			</div>

			<!-- Site Info & Environment -->
			<div class="HTG-admin-row HTG-admin-cols-2">
				<!-- Site Info -->
				<div class="HTG-admin-card">
					<div class="HTG-card-header">
						<h2>
							<span class="dashicons dashicons-info-outline"></span>
							<?php esc_html_e( 'Site Information', 'HTG' ); ?>
						</h2>
					</div>
					<div class="HTG-card-body">
						<?php self::render_site_info(); ?>
					</div>
				</div>

				<!-- Environment Status -->
				<div class="HTG-admin-card">
					<div class="HTG-card-header">
						<h2>
							<span class="dashicons dashicons-performance"></span>
							<?php esc_html_e( 'Environment Status', 'HTG' ); ?>
						</h2>
					</div>
					<div class="HTG-card-body">
						<?php self::render_environment_status(); ?>
					</div>
				</div>
			</div>

			<!-- Getting Started Guide -->
			<div class="HTG-admin-row">
				<div class="HTG-admin-card HTG-card-info">
					<div class="HTG-card-header">
						<h2>
							<span class="dashicons dashicons-welcome-learn-more"></span>
							<?php esc_html_e( 'Getting Started', 'HTG' ); ?>
						</h2>
					</div>
					<div class="HTG-card-body">
						<div class="HTG-info-grid">
							<div class="HTG-info-item">
								<h3><?php esc_html_e( '1. Configure Settings', 'HTG' ); ?></h3>
								<p><?php esc_html_e( 'Set up your site layout, header, and display preferences in General Settings.', 'HTG' ); ?></p>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="button button-primary">
									<?php esc_html_e( 'General Settings', 'HTG' ); ?>
								</a>
							</div>

							<div class="HTG-info-item">
								<h3><?php esc_html_e( '2. Add Your Ads', 'HTG' ); ?></h3>
								<p><?php esc_html_e( 'Paste your Google AdSense or Ad Manager codes in the ad slots.', 'HTG' ); ?></p>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button">
									<?php esc_html_e( 'Ad Management', 'HTG' ); ?>
								</a>
							</div>

							<div class="HTG-info-item">
								<h3><?php esc_html_e( '3. Customize Design', 'HTG' ); ?></h3>
								<p><?php esc_html_e( 'Adjust colors, fonts, and styling to match your brand identity.', 'HTG' ); ?></p>
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-appearance' ) ); ?>" class="button">
									<?php esc_html_e( 'Appearance', 'HTG' ); ?>
								</a>
							</div>

							<div class="HTG-info-item">
								<h3><?php esc_html_e( '4. Create Content', 'HTG' ); ?></h3>
								<p><?php esc_html_e( 'Start publishing quality content to grow your audience and revenue.', 'HTG' ); ?></p>
								<a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>" class="button">
									<?php esc_html_e( 'New Post', 'HTG' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Footer -->
			<div class="HTG-admin-footer">
				<p>
					<?php esc_html_e( 'H&T AdTech Pro', 'HTG' ); ?> <?php echo esc_html( self::VERSION ); ?> &mdash;
					<?php
					printf(
						/* translators: %s: H&T GAMING website URL */
						esc_html__( 'Built by %s', 'HTG' ),
						'<a href="https://hntgaming.me" target="_blank" rel="noopener">H&T GAMING</a>'
					);
					?>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Render active ad slots
	 */
	private static function render_active_ad_slots() {
		if ( ! function_exists( 'HTG_get_ad_slots' ) ) {
			echo '<p class="HTG-no-data">' . esc_html__( 'Ad system not available.', 'HTG' ) . '</p>';
			return;
		}

		$ad_slots = HTG_get_ad_slots();
		$total_slots = count( $ad_slots );
		$active_count = 0;
		$slots_html = '';

		foreach ( $ad_slots as $slot_id => $slot_config ) {
			$ad_code = get_option( 'HTG_ad_' . $slot_id, '' );
			$is_active = ! empty( $ad_code );
			
			if ( $is_active ) {
				$active_count++;
			}
			
			$slots_html .= '<div class="HTG-ad-slot-item">';
			$slots_html .= '<div class="HTG-ad-slot-status ' . ( $is_active ? 'active' : 'inactive' ) . '"></div>';
			$slots_html .= '<div class="HTG-ad-slot-info">';
			$slots_html .= '<strong>' . esc_html( $slot_config['name'] ) . '</strong>';
			$slots_html .= '<span class="HTG-ad-slot-location">' . esc_html( $slot_config['recommended_size'] ) . '</span>';
			$slots_html .= '</div>';
			$slots_html .= '</div>';
		}

		echo '<div class="HTG-ad-slots-list">';
		echo $slots_html;
		echo '</div>';
		
		echo '<div class="HTG-ad-slots-summary">';
		echo '<strong>' . esc_html( $active_count ) . '</strong> / ' . esc_html( $total_slots ) . ' ' . esc_html__( 'ad slots configured', 'HTG' );
		echo '</div>';
	}

	/**
	 * Render site information
	 */
	private static function render_site_info() {
		$info_items = array(
			array(
				'label' => __( 'Site Name', 'HTG' ),
				'value' => get_bloginfo( 'name' ),
				'icon'  => 'admin-home',
			),
			array(
				'label' => __( 'Site URL', 'HTG' ),
				'value' => home_url(),
				'icon'  => 'admin-links',
			),
			array(
				'label' => __( 'Theme Version', 'HTG' ),
				'value' => self::VERSION,
				'icon'  => 'admin-appearance',
			),
			array(
				'label' => __( 'WordPress', 'HTG' ),
				'value' => get_bloginfo( 'version' ),
				'icon'  => 'wordpress-alt',
			),
		);

		echo '<div class="HTG-info-list">';
		foreach ( $info_items as $item ) {
			?>
			<div class="HTG-info-row">
				<span class="dashicons dashicons-<?php echo esc_attr( $item['icon'] ); ?>"></span>
				<div class="HTG-info-details">
					<div class="HTG-info-label"><?php echo esc_html( $item['label'] ); ?></div>
					<div class="HTG-info-value"><?php echo esc_html( $item['value'] ); ?></div>
				</div>
			</div>
			<?php
		}
		echo '</div>';
	}

	/**
	 * Render environment status
	 */
	private static function render_environment_status() {
		$php_version = PHP_VERSION;
		$php_status = version_compare( $php_version, '7.4', '>=' ) ? 'good' : 'warning';
		
		$memory_limit = ini_get( 'memory_limit' );
		$memory_value = intval( $memory_limit );
		$memory_status = $memory_value >= 128 ? 'good' : 'warning';
		
		$max_execution = ini_get( 'max_execution_time' );
		$exec_status = intval( $max_execution ) >= 30 ? 'good' : 'warning';
		
		$upload_size = ini_get( 'upload_max_filesize' );
		
		$status_items = array(
			array(
				'label'  => __( 'PHP Version', 'HTG' ),
				'value'  => $php_version,
				'status' => $php_status,
				'icon'   => 'editor-code',
			),
			array(
				'label'  => __( 'Memory Limit', 'HTG' ),
				'value'  => $memory_limit,
				'status' => $memory_status,
				'icon'   => 'performance',
			),
			array(
				'label'  => __( 'Max Execution', 'HTG' ),
				'value'  => $max_execution . 's',
				'status' => $exec_status,
				'icon'   => 'clock',
			),
			array(
				'label'  => __( 'Max Upload', 'HTG' ),
				'value'  => $upload_size,
				'status' => 'good',
				'icon'   => 'upload',
			),
		);

		echo '<div class="HTG-info-list">';
		foreach ( $status_items as $item ) {
			$status_class = 'good' === $item['status'] ? 'active' : 'inactive';
			?>
			<div class="HTG-info-row">
				<span class="dashicons dashicons-<?php echo esc_attr( $item['icon'] ); ?>"></span>
				<div class="HTG-info-details">
					<div class="HTG-info-label"><?php echo esc_html( $item['label'] ); ?></div>
					<div class="HTG-info-value"><?php echo esc_html( $item['value'] ); ?></div>
				</div>
				<div class="HTG-ad-slot-status <?php echo esc_attr( $status_class ); ?>"></div>
			</div>
			<?php
		}
		echo '</div>';
	}
}

HTG_Admin_Dashboard::init();

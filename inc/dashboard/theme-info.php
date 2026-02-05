<?php
/**
 * H&T AdTech Pro - Theme Info Page
 * Enterprise-grade theme information and getting started guide
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue admin scripts for theme info page
 */
function HTG_enqueue_theme_info_scripts( $hook ) {
	if ( 'appearance_page_about-HTG-theme' !== $hook ) {
		return;
	}
	
	wp_enqueue_style(
		'HTG-theme-info-css',
		get_template_directory_uri() . '/inc/dashboard/css/admin.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'admin_enqueue_scripts', 'HTG_enqueue_theme_info_scripts' );

/**
 * Add admin notice on theme activation
 */
function HTG_activation_notice() {
	?>
	<div class="notice notice-success is-dismissible HTG-activation-notice">
		<div class="HTG-notice-content">
			<div class="HTG-notice-icon">
				<span class="dashicons dashicons-yes-alt"></span>
			</div>
			<div class="HTG-notice-text">
				<h2><?php esc_html_e( 'H&T AdTech Pro Activated!', 'adtech-pro' ); ?></h2>
				<p><?php esc_html_e( 'Thank you for choosing H&T AdTech Pro! Your new theme is ready. Set up your ad placements, customize your design, and start publishing.', 'adtech-pro' ); ?></p>
			</div>
		</div>
		<div class="HTG-notice-actions">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-dashboard' ) ); ?>" class="button button-primary">
				<span class="dashicons dashicons-dashboard"></span>
				<?php esc_html_e( 'Open Dashboard', 'adtech-pro' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button">
				<?php esc_html_e( 'Configure Ads', 'adtech-pro' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme' ) ); ?>" class="button">
				<?php esc_html_e( 'Getting Started', 'adtech-pro' ); ?>
			</a>
		</div>
	</div>
	<style>
	.HTG-activation-notice {
		padding: 20px;
		border-left-color: #00d4aa;
	}
	.HTG-notice-content {
		display: flex;
		align-items: flex-start;
		gap: 15px;
		margin-bottom: 15px;
	}
	.HTG-notice-icon .dashicons {
		font-size: 40px;
		width: 40px;
		height: 40px;
		color: #00d4aa;
	}
	.HTG-notice-text h2 {
		margin: 0 0 5px;
		font-size: 18px;
	}
	.HTG-notice-text p {
		margin: 0;
		color: #646970;
	}
	.HTG-notice-actions {
		display: flex;
		gap: 10px;
	}
	.HTG-notice-actions .button-primary {
		background: #1a1f36;
		border-color: #1a1f36;
	}
	.HTG-notice-actions .dashicons {
		font-size: 16px;
		width: 16px;
		height: 16px;
		margin-right: 5px;
		vertical-align: text-bottom;
	}
	</style>
	<?php
}

function HTG_check_activation_notice() {
	global $pagenow;
	if ( is_admin() && 'themes.php' === $pagenow && isset( $_GET['activated'] ) ) {
		add_action( 'admin_notices', 'HTG_activation_notice' );
	}
}
add_action( 'load-themes.php', 'HTG_check_activation_notice' );

/**
 * Add theme info page to admin menu
 */
function HTG_add_theme_info_page() {
	add_theme_page(
		esc_html__( 'H&T AdTech Pro', 'adtech-pro' ),
		esc_html__( 'H&T AdTech Pro', 'adtech-pro' ),
		'edit_theme_options',
		'about-HTG-theme',
		'HTG_render_theme_info_page'
	);
}
add_action( 'admin_menu', 'HTG_add_theme_info_page' );

/**
 * Render theme info page
 */
function HTG_render_theme_info_page() {
	$theme = wp_get_theme();
	$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'welcome';
	?>
	<div class="wrap HTG-theme-info-wrap">
		<!-- Header -->
		<div class="HTG-theme-header">
			<div class="HTG-theme-header-content">
				<div class="HTG-theme-logo">
					<span class="dashicons dashicons-chart-area"></span>
				</div>
				<div class="HTG-theme-header-text">
					<h1><?php esc_html_e( 'H&T AdTech Pro', 'adtech-pro' ); ?> <span class="HTG-version"><?php echo esc_html( $theme->get( 'Version' ) ); ?></span></h1>
					<p><?php esc_html_e( 'A modern dark-themed magazine theme built for bloggers, news sites, and content publishers who want to monetize with ads.', 'adtech-pro' ); ?></p>
				</div>
			</div>
			<div class="HTG-theme-header-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-dashboard' ) ); ?>" class="HTG-header-btn HTG-btn-primary">
					<span class="dashicons dashicons-dashboard"></span>
					<?php esc_html_e( 'Open Dashboard', 'adtech-pro' ); ?>
				</a>
				<a href="https://hntgaming.me/" target="_blank" class="HTG-header-btn">
					<span class="dashicons dashicons-external"></span>
					<?php esc_html_e( 'H&T GAMING', 'adtech-pro' ); ?>
				</a>
			</div>
		</div>

		<!-- Navigation Tabs -->
		<nav class="HTG-theme-nav">
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme' ) ); ?>" class="HTG-nav-tab <?php echo 'welcome' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-admin-home"></span>
				<?php esc_html_e( 'Welcome', 'adtech-pro' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme&tab=features' ) ); ?>" class="HTG-nav-tab <?php echo 'features' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-star-filled"></span>
				<?php esc_html_e( 'Features', 'adtech-pro' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme&tab=setup' ) ); ?>" class="HTG-nav-tab <?php echo 'setup' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-admin-tools"></span>
				<?php esc_html_e( 'Setup Guide', 'adtech-pro' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme&tab=changelog' ) ); ?>" class="HTG-nav-tab <?php echo 'changelog' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-backup"></span>
				<?php esc_html_e( 'Changelog', 'adtech-pro' ); ?>
			</a>
		</nav>

		<!-- Content -->
		<div class="HTG-theme-content">
			<?php
			switch ( $current_tab ) {
				case 'features':
					HTG_render_features_tab();
					break;
				case 'setup':
					HTG_render_setup_tab();
					break;
				case 'changelog':
					HTG_render_changelog_tab();
					break;
				default:
					HTG_render_welcome_tab();
					break;
			}
			?>
		</div>

		<!-- Footer -->
		<div class="HTG-theme-footer">
			<p>
				<?php esc_html_e( 'H&T AdTech Pro', 'adtech-pro' ); ?> &mdash;
				<?php
				printf(
					esc_html__( 'Built by %s', 'adtech-pro' ),
					'<a href="https://hntgaming.me" target="_blank">H&T GAMING</a>'
				);
				?>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Render welcome tab content
 */
function HTG_render_welcome_tab() {
	?>
	<!-- Quick Start Cards -->
	<div class="HTG-quick-start">
		<h2><?php esc_html_e( 'Get Started in Minutes', 'adtech-pro' ); ?></h2>
		<p class="HTG-section-desc"><?php esc_html_e( 'Follow these steps to set up your site. Most settings come pre-configured with recommended defaults.', 'adtech-pro' ); ?></p>
		<div class="HTG-start-grid">
			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-admin-generic"></span>
				</div>
				<h3><?php esc_html_e( 'General Settings', 'adtech-pro' ); ?></h3>
				<p><?php esc_html_e( 'Choose your layout style, sidebar position, and configure header and footer options.', 'adtech-pro' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="button">
					<?php esc_html_e( 'Configure', 'adtech-pro' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-megaphone"></span>
				</div>
				<h3><?php esc_html_e( 'Ad Placements', 'adtech-pro' ); ?></h3>
				<p><?php esc_html_e( 'Paste your AdSense, Ad Manager, or any ad network code into ready-made ad slots.', 'adtech-pro' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button">
					<?php esc_html_e( 'Add Ads', 'adtech-pro' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-admin-appearance"></span>
				</div>
				<h3><?php esc_html_e( 'Colors & Fonts', 'adtech-pro' ); ?></h3>
				<p><?php esc_html_e( 'Change the color scheme, pick your fonts, or add custom CSS to match your brand.', 'adtech-pro' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-appearance' ) ); ?>" class="button">
					<?php esc_html_e( 'Customize', 'adtech-pro' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-layout"></span>
				</div>
				<h3><?php esc_html_e( 'Homepage Layout', 'adtech-pro' ); ?></h3>
				<p><?php esc_html_e( 'Use the magazine template to showcase posts by category with a featured slider.', 'adtech-pro' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-magazine-settings' ) ); ?>" class="button">
					<?php esc_html_e( 'Set Up', 'adtech-pro' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-share"></span>
				</div>
				<h3><?php esc_html_e( 'Newsletter', 'adtech-pro' ); ?></h3>
				<p><?php esc_html_e( 'Collect email subscribers with beautiful newsletter forms.', 'adtech-pro' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-engagement-settings' ) ); ?>" class="button">
					<?php esc_html_e( 'Enable', 'adtech-pro' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-media-text"></span>
				</div>
				<h3><?php esc_html_e( 'Legal Pages', 'adtech-pro' ); ?></h3>
				<p><?php esc_html_e( 'Auto-generate Privacy Policy, Terms of Service, and other required pages.', 'adtech-pro' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-legal-pages' ) ); ?>" class="button">
					<?php esc_html_e( 'Create', 'adtech-pro' ); ?>
				</a>
			</div>
		</div>
	</div>

	<!-- Theme Info -->
	<div class="HTG-info-section">
		<div class="HTG-info-card">
			<h3><?php esc_html_e( 'Theme Information', 'adtech-pro' ); ?></h3>
			<table class="HTG-info-table">
				<tr>
					<td><?php esc_html_e( 'Theme', 'adtech-pro' ); ?></td>
					<td><strong>H&T AdTech Pro</strong></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Version', 'adtech-pro' ); ?></td>
					<td><?php echo esc_html( wp_get_theme()->get( 'Version' ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Update Status', 'adtech-pro' ); ?></td>
					<td>
						<?php
						if ( function_exists( 'htg_github_updater' ) ) {
							$updater = htg_github_updater();
							$update = $updater->is_update_available();
							if ( $update ) {
								echo '<span style="color: #f0ad4e;"><span class="dashicons dashicons-update" style="font-size: 16px; width: 16px; height: 16px;"></span> ';
								printf(
									/* translators: %s: New version number */
									esc_html__( 'Version %s available', 'adtech-pro' ),
									esc_html( $update['version'] )
								);
								echo '</span> ';
								echo '<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '" class="button button-small">' . esc_html__( 'Update Now', 'adtech-pro' ) . '</a>';
							} else {
								echo '<span style="color: #46b450;"><span class="dashicons dashicons-yes" style="font-size: 16px; width: 16px; height: 16px;"></span> ' . esc_html__( 'Up to date', 'adtech-pro' ) . '</span>';
							}
						} else {
							echo '<span style="color: #999;">' . esc_html__( 'Update checker not loaded', 'adtech-pro' ) . '</span>';
						}
						?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Author', 'adtech-pro' ); ?></td>
					<td><a href="https://hntgaming.me" target="_blank">H&T GAMING</a></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Requires WordPress', 'adtech-pro' ); ?></td>
					<td>6.0+</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Requires PHP', 'adtech-pro' ); ?></td>
					<td>7.4+</td>
				</tr>
			</table>
		</div>

		<div class="HTG-info-card">
			<h3><?php esc_html_e( 'Support & Resources', 'adtech-pro' ); ?></h3>
			<ul class="HTG-resource-list">
				<li>
					<a href="https://hntgaming.me/docs" target="_blank">
						<span class="dashicons dashicons-book"></span>
						<?php esc_html_e( 'Documentation', 'adtech-pro' ); ?>
					</a>
				</li>
				<li>
					<a href="https://hntgaming.me/support" target="_blank">
						<span class="dashicons dashicons-sos"></span>
						<?php esc_html_e( 'Support Center', 'adtech-pro' ); ?>
					</a>
				</li>
				<li>
					<a href="https://github.com/hntgaming/adtech-pro" target="_blank">
						<span class="dashicons dashicons-github"></span>
						<?php esc_html_e( 'GitHub Repository', 'adtech-pro' ); ?>
					</a>
				</li>
				<li>
					<a href="https://github.com/hntgaming/adtech-pro/releases" target="_blank">
						<span class="dashicons dashicons-download"></span>
						<?php esc_html_e( 'All Releases', 'adtech-pro' ); ?>
					</a>
				</li>
				<li>
					<a href="https://hntgaming.me/changelog" target="_blank">
						<span class="dashicons dashicons-backup"></span>
						<?php esc_html_e( 'Changelog', 'adtech-pro' ); ?>
					</a>
				</li>
				<li>
					<a href="https://hntgaming.me" target="_blank">
						<span class="dashicons dashicons-admin-site-alt3"></span>
						<?php esc_html_e( 'H&T GAMING Website', 'adtech-pro' ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Render features tab content
 */
function HTG_render_features_tab() {
	?>
	<div class="HTG-features-section">
		<h2><?php esc_html_e( 'What\'s Included', 'adtech-pro' ); ?></h2>
		<p class="HTG-section-desc"><?php esc_html_e( 'Everything you need to run a professional blog or news site with ad monetization.', 'adtech-pro' ); ?></p>
		
		<div class="HTG-features-grid">
			<div class="HTG-feature-card">
				<div class="HTG-feature-icon ad">
					<span class="dashicons dashicons-megaphone"></span>
				</div>
				<h3><?php esc_html_e( 'Simple Ad Management', 'adtech-pro' ); ?></h3>
				<ul>
					<li><?php esc_html_e( '8 ad placement areas', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Works with AdSense, Ezoic, Mediavine', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Header, sidebar, in-content ads', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Mobile-responsive ad slots', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Optional "Advertisement" labels', 'adtech-pro' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon design">
					<span class="dashicons dashicons-admin-appearance"></span>
				</div>
				<h3><?php esc_html_e( 'Modern Design', 'adtech-pro' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Clean dark theme by default', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Magazine-style homepage', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Grid and list post layouts', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Featured post slider', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Category sections on homepage', 'adtech-pro' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon performance">
					<span class="dashicons dashicons-performance"></span>
				</div>
				<h3><?php esc_html_e( 'Fast & Lightweight', 'adtech-pro' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Optimized for page speed', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Lazy loading for images', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Clean, minimal code', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Works great with caching plugins', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Mobile-first responsive design', 'adtech-pro' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon engagement">
					<span class="dashicons dashicons-share"></span>
				</div>
				<h3><?php esc_html_e( 'Reader Engagement', 'adtech-pro' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Email newsletter signup form', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Reading progress indicator', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Estimated reading time', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Related posts section', 'adtech-pro' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon seo">
					<span class="dashicons dashicons-search"></span>
				</div>
				<h3><?php esc_html_e( 'SEO Ready', 'adtech-pro' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Clean semantic HTML', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Schema markup included', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Social media meta tags', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Breadcrumb navigation', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Compatible with Yoast, RankMath', 'adtech-pro' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon legal">
					<span class="dashicons dashicons-edit-page"></span>
				</div>
				<h3><?php esc_html_e( 'Legal Page Generator', 'adtech-pro' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Privacy Policy template', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Terms of Service template', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Affiliate Disclosure page', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'Disclaimer page', 'adtech-pro' ); ?></li>
					<li><?php esc_html_e( 'One-click page creation', 'adtech-pro' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render setup tab content
 */
function HTG_render_setup_tab() {
	?>
	<div class="HTG-setup-section">
		<h2><?php esc_html_e( 'Setup Guide', 'adtech-pro' ); ?></h2>
		
		<div class="HTG-setup-steps">
			<div class="HTG-setup-step">
				<div class="HTG-step-number">1</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Configure General Settings', 'adtech-pro' ); ?></h3>
					<p><?php esc_html_e( 'Set up your site layout, sidebar position, header style, and footer copyright.', 'adtech-pro' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'General Settings', 'adtech-pro' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">2</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Set Up Magazine Homepage', 'adtech-pro' ); ?></h3>
					<p><?php esc_html_e( 'Create a page with the "Magazine Homepage" template and set it as your front page in Settings → Reading.', 'adtech-pro' ); ?></p>
					<?php
					$show_on_front = get_option( 'show_on_front' );
					$page_on_front = get_option( 'page_on_front' );
					$template = $page_on_front ? get_page_template_slug( $page_on_front ) : '';
					
					if ( 'page' === $show_on_front && 'template-magazine.php' === $template ) :
					?>
						<span class="HTG-step-complete">
							<span class="dashicons dashicons-yes-alt"></span>
							<?php esc_html_e( 'Magazine homepage is active!', 'adtech-pro' ); ?>
						</span>
					<?php else : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-magazine-settings' ) ); ?>" class="button">
							<?php esc_html_e( 'Magazine Settings', 'adtech-pro' ); ?>
						</a>
						<a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>" class="button">
							<?php esc_html_e( 'Reading Settings', 'adtech-pro' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">3</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Add Your Ad Codes', 'adtech-pro' ); ?></h3>
					<p><?php esc_html_e( 'Paste your Google AdSense or Ad Manager codes into the ad slots.', 'adtech-pro' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'Ad Management', 'adtech-pro' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">4</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Customize Appearance', 'adtech-pro' ); ?></h3>
					<p><?php esc_html_e( 'Adjust colors, fonts, and add custom CSS to match your brand.', 'adtech-pro' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-appearance' ) ); ?>" class="button">
						<?php esc_html_e( 'Appearance', 'adtech-pro' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">5</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Generate Legal Pages', 'adtech-pro' ); ?></h3>
					<p><?php esc_html_e( 'Create required legal pages for AdSense compliance: Privacy Policy, Terms, Disclaimer, Disclosure.', 'adtech-pro' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-legal-pages' ) ); ?>" class="button">
						<?php esc_html_e( 'Legal Pages', 'adtech-pro' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">6</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Configure Menus', 'adtech-pro' ); ?></h3>
					<p><?php esc_html_e( 'Set up your navigation menus: Top Menu, Main Menu, and Footer Menu.', 'adtech-pro' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button">
						<?php esc_html_e( 'Menus', 'adtech-pro' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render changelog tab content
 */
function HTG_render_changelog_tab() {
	?>
	<div class="HTG-changelog-section">
		<h2><?php esc_html_e( 'Changelog', 'adtech-pro' ); ?></h2>
		
		<div class="HTG-changelog-list">
			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.5.2</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'adtech-pro' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Comprehensive CSS Audit & Uniform Styling', 'adtech-pro' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Fixed light backgrounds (#ECF0F1) in navigation and tab widgets', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed invisible borders (#1a1f36) to visible teal accent', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed WooCommerce prices, labels, and links for dark theme', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed light borders to dark theme compatible colors', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed tab widget text and thumb swiper background', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Consistent color palette site-wide (22+ issues resolved)', 'adtech-pro' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.5.1</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'adtech-pro' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Dark Theme Hover Style Consistency', 'adtech-pro' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Fixed post navigation hover - proper anchor-based hover behavior', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed related posts hover consistency with teal accent color', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed 15+ dark theme hover colors from invisible to visible', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed comment section dark theme colors and borders', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed widget titles with white text and teal accent border', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Added smooth 0.2s transitions to all hover effects', 'adtech-pro' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.5.0</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'adtech-pro' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Production Cleanup & WordPress.org Compliance', 'adtech-pro' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Fixed text domain inconsistency - 657 occurrences updated for translation compliance', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed footer ad slot mismatch with Ad Manager settings', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed dark mode CSS class mismatch - styles now properly applied', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Removed dead code - deleted unused dark mode toggle JS', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Removed legacy FontAwesome v4 fonts (~1.08 MB saved)', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Cleaner, production-ready codebase', 'adtech-pro' ); ?></li>
					</ul>
				</div>
			</div>
			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.3.0</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'adtech-pro' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Major Admin Panel Redesign', 'adtech-pro' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Complete admin panel UI overhaul with enterprise-grade design', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'New unified CSS design system with custom properties', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Improved dashboard with quick stats and environment status', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Enhanced settings pages with toggle switches and tabs', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Removed redundant Ad Settings page', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed broken quick action links', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'New theme info page with setup guide', 'adtech-pro' ); ?></li>
					</ul>
				</div>
			</div>

			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.2.0</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'adtech-pro' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Magazine & Sync Improvements', 'adtech-pro' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Magazine homepage dark theme styling', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed Admin Panel and Customizer sync', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Auto-setup magazine homepage on theme activation', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Fixed menu location persistence issue', 'adtech-pro' ); ?></li>
					</ul>
				</div>
			</div>

			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.1.0</span>
					<span class="date"><?php esc_html_e( 'December 2025', 'adtech-pro' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Enterprise Features', 'adtech-pro' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Simple Ad Management system', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Legal Pages generator', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Engagement tools (newsletter, reading progress)', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Magazine homepage template', 'adtech-pro' ); ?></li>
						<li><?php esc_html_e( 'Dark mode optimization', 'adtech-pro' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
}

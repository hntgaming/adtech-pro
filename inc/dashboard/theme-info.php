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
		'2.3.0'
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
				<h2><?php esc_html_e( 'H&T AdTech Pro Activated!', 'HTG' ); ?></h2>
				<p><?php esc_html_e( 'Thank you for choosing H&T AdTech Pro! Your new theme is ready. Set up your ad placements, customize your design, and start publishing.', 'HTG' ); ?></p>
			</div>
		</div>
		<div class="HTG-notice-actions">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-dashboard' ) ); ?>" class="button button-primary">
				<span class="dashicons dashicons-dashboard"></span>
				<?php esc_html_e( 'Open Dashboard', 'HTG' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button">
				<?php esc_html_e( 'Configure Ads', 'HTG' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme' ) ); ?>" class="button">
				<?php esc_html_e( 'Getting Started', 'HTG' ); ?>
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
		esc_html__( 'H&T AdTech Pro', 'HTG' ),
		esc_html__( 'H&T AdTech Pro', 'HTG' ),
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
					<h1><?php esc_html_e( 'H&T AdTech Pro', 'HTG' ); ?> <span class="HTG-version"><?php echo esc_html( $theme->get( 'Version' ) ); ?></span></h1>
					<p><?php esc_html_e( 'A modern dark-themed magazine theme built for bloggers, news sites, and content publishers who want to monetize with ads.', 'HTG' ); ?></p>
				</div>
			</div>
			<div class="HTG-theme-header-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-dashboard' ) ); ?>" class="HTG-header-btn HTG-btn-primary">
					<span class="dashicons dashicons-dashboard"></span>
					<?php esc_html_e( 'Open Dashboard', 'HTG' ); ?>
				</a>
				<a href="https://hntgaming.me/" target="_blank" class="HTG-header-btn">
					<span class="dashicons dashicons-external"></span>
					<?php esc_html_e( 'H&T GAMING', 'HTG' ); ?>
				</a>
			</div>
		</div>

		<!-- Navigation Tabs -->
		<nav class="HTG-theme-nav">
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme' ) ); ?>" class="HTG-nav-tab <?php echo 'welcome' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-admin-home"></span>
				<?php esc_html_e( 'Welcome', 'HTG' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme&tab=features' ) ); ?>" class="HTG-nav-tab <?php echo 'features' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-star-filled"></span>
				<?php esc_html_e( 'Features', 'HTG' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme&tab=setup' ) ); ?>" class="HTG-nav-tab <?php echo 'setup' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-admin-tools"></span>
				<?php esc_html_e( 'Setup Guide', 'HTG' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'themes.php?page=about-HTG-theme&tab=changelog' ) ); ?>" class="HTG-nav-tab <?php echo 'changelog' === $current_tab ? 'active' : ''; ?>">
				<span class="dashicons dashicons-backup"></span>
				<?php esc_html_e( 'Changelog', 'HTG' ); ?>
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
				<?php esc_html_e( 'H&T AdTech Pro', 'HTG' ); ?> &mdash;
				<?php
				printf(
					esc_html__( 'Built by %s', 'HTG' ),
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
		<h2><?php esc_html_e( 'Get Started in Minutes', 'HTG' ); ?></h2>
		<p class="HTG-section-desc"><?php esc_html_e( 'Follow these steps to set up your site. Most settings come pre-configured with recommended defaults.', 'HTG' ); ?></p>
		<div class="HTG-start-grid">
			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-admin-generic"></span>
				</div>
				<h3><?php esc_html_e( 'General Settings', 'HTG' ); ?></h3>
				<p><?php esc_html_e( 'Choose your layout style, sidebar position, and configure header and footer options.', 'HTG' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="button">
					<?php esc_html_e( 'Configure', 'HTG' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-megaphone"></span>
				</div>
				<h3><?php esc_html_e( 'Ad Placements', 'HTG' ); ?></h3>
				<p><?php esc_html_e( 'Paste your AdSense, Ad Manager, or any ad network code into ready-made ad slots.', 'HTG' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button">
					<?php esc_html_e( 'Add Ads', 'HTG' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-admin-appearance"></span>
				</div>
				<h3><?php esc_html_e( 'Colors & Fonts', 'HTG' ); ?></h3>
				<p><?php esc_html_e( 'Change the color scheme, pick your fonts, or add custom CSS to match your brand.', 'HTG' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-appearance' ) ); ?>" class="button">
					<?php esc_html_e( 'Customize', 'HTG' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-layout"></span>
				</div>
				<h3><?php esc_html_e( 'Homepage Layout', 'HTG' ); ?></h3>
				<p><?php esc_html_e( 'Use the magazine template to showcase posts by category with a featured slider.', 'HTG' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-magazine-settings' ) ); ?>" class="button">
					<?php esc_html_e( 'Set Up', 'HTG' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-share"></span>
				</div>
				<h3><?php esc_html_e( 'Social & Newsletter', 'HTG' ); ?></h3>
				<p><?php esc_html_e( 'Add social share buttons to posts and collect email subscribers.', 'HTG' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-engagement-settings' ) ); ?>" class="button">
					<?php esc_html_e( 'Enable', 'HTG' ); ?>
				</a>
			</div>

			<div class="HTG-start-card">
				<div class="HTG-start-icon">
					<span class="dashicons dashicons-media-text"></span>
				</div>
				<h3><?php esc_html_e( 'Legal Pages', 'HTG' ); ?></h3>
				<p><?php esc_html_e( 'Auto-generate Privacy Policy, Terms of Service, and other required pages.', 'HTG' ); ?></p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-legal-pages' ) ); ?>" class="button">
					<?php esc_html_e( 'Create', 'HTG' ); ?>
				</a>
			</div>
		</div>
	</div>

	<!-- Theme Info -->
	<div class="HTG-info-section">
		<div class="HTG-info-card">
			<h3><?php esc_html_e( 'Theme Information', 'HTG' ); ?></h3>
			<table class="HTG-info-table">
				<tr>
					<td><?php esc_html_e( 'Theme', 'HTG' ); ?></td>
					<td><strong>H&T AdTech Pro</strong></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Version', 'HTG' ); ?></td>
					<td><?php echo esc_html( wp_get_theme()->get( 'Version' ) ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Update Status', 'HTG' ); ?></td>
					<td>
						<?php
						if ( function_exists( 'htg_github_updater' ) ) {
							$updater = htg_github_updater();
							$update = $updater->is_update_available();
							if ( $update ) {
								echo '<span style="color: #f0ad4e;"><span class="dashicons dashicons-update" style="font-size: 16px; width: 16px; height: 16px;"></span> ';
								printf(
									/* translators: %s: New version number */
									esc_html__( 'Version %s available', 'HTG' ),
									esc_html( $update['version'] )
								);
								echo '</span> ';
								echo '<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '" class="button button-small">' . esc_html__( 'Update Now', 'HTG' ) . '</a>';
							} else {
								echo '<span style="color: #46b450;"><span class="dashicons dashicons-yes" style="font-size: 16px; width: 16px; height: 16px;"></span> ' . esc_html__( 'Up to date', 'HTG' ) . '</span>';
							}
						} else {
							echo '<span style="color: #999;">' . esc_html__( 'Update checker not loaded', 'HTG' ) . '</span>';
						}
						?>
					</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Author', 'HTG' ); ?></td>
					<td><a href="https://hntgaming.me" target="_blank">H&T GAMING</a></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Requires WordPress', 'HTG' ); ?></td>
					<td>6.0+</td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Requires PHP', 'HTG' ); ?></td>
					<td>7.4+</td>
				</tr>
			</table>
		</div>

		<div class="HTG-info-card">
			<h3><?php esc_html_e( 'Support & Resources', 'HTG' ); ?></h3>
			<ul class="HTG-resource-list">
				<li>
					<a href="https://hntgaming.me/docs" target="_blank">
						<span class="dashicons dashicons-book"></span>
						<?php esc_html_e( 'Documentation', 'HTG' ); ?>
					</a>
				</li>
				<li>
					<a href="https://hntgaming.me/support" target="_blank">
						<span class="dashicons dashicons-sos"></span>
						<?php esc_html_e( 'Support Center', 'HTG' ); ?>
					</a>
				</li>
				<li>
					<a href="https://github.com/hntgaming/adtech-pro" target="_blank">
						<span class="dashicons dashicons-github"></span>
						<?php esc_html_e( 'GitHub Repository', 'HTG' ); ?>
					</a>
				</li>
				<li>
					<a href="https://github.com/hntgaming/adtech-pro/releases" target="_blank">
						<span class="dashicons dashicons-download"></span>
						<?php esc_html_e( 'All Releases', 'HTG' ); ?>
					</a>
				</li>
				<li>
					<a href="https://hntgaming.me/changelog" target="_blank">
						<span class="dashicons dashicons-backup"></span>
						<?php esc_html_e( 'Changelog', 'HTG' ); ?>
					</a>
				</li>
				<li>
					<a href="https://hntgaming.me" target="_blank">
						<span class="dashicons dashicons-admin-site-alt3"></span>
						<?php esc_html_e( 'H&T GAMING Website', 'HTG' ); ?>
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
		<h2><?php esc_html_e( 'What\'s Included', 'HTG' ); ?></h2>
		<p class="HTG-section-desc"><?php esc_html_e( 'Everything you need to run a professional blog or news site with ad monetization.', 'HTG' ); ?></p>
		
		<div class="HTG-features-grid">
			<div class="HTG-feature-card">
				<div class="HTG-feature-icon ad">
					<span class="dashicons dashicons-megaphone"></span>
				</div>
				<h3><?php esc_html_e( 'Simple Ad Management', 'HTG' ); ?></h3>
				<ul>
					<li><?php esc_html_e( '8 ad placement areas', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Works with AdSense, Ezoic, Mediavine', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Header, sidebar, in-content ads', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Mobile-responsive ad slots', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Optional "Advertisement" labels', 'HTG' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon design">
					<span class="dashicons dashicons-admin-appearance"></span>
				</div>
				<h3><?php esc_html_e( 'Modern Design', 'HTG' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Clean dark theme by default', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Magazine-style homepage', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Grid and list post layouts', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Featured post slider', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Category sections on homepage', 'HTG' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon performance">
					<span class="dashicons dashicons-performance"></span>
				</div>
				<h3><?php esc_html_e( 'Fast & Lightweight', 'HTG' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Optimized for page speed', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Lazy loading for images', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Clean, minimal code', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Works great with caching plugins', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Mobile-first responsive design', 'HTG' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon engagement">
					<span class="dashicons dashicons-share"></span>
				</div>
				<h3><?php esc_html_e( 'Reader Engagement', 'HTG' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Email newsletter signup form', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Social share buttons on posts', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Reading progress indicator', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Estimated reading time', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Related posts section', 'HTG' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon seo">
					<span class="dashicons dashicons-search"></span>
				</div>
				<h3><?php esc_html_e( 'SEO Ready', 'HTG' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Clean semantic HTML', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Schema markup included', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Social media meta tags', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Breadcrumb navigation', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Compatible with Yoast, RankMath', 'HTG' ); ?></li>
				</ul>
			</div>

			<div class="HTG-feature-card">
				<div class="HTG-feature-icon legal">
					<span class="dashicons dashicons-edit-page"></span>
				</div>
				<h3><?php esc_html_e( 'Legal Page Generator', 'HTG' ); ?></h3>
				<ul>
					<li><?php esc_html_e( 'Privacy Policy template', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Terms of Service template', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Affiliate Disclosure page', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'Disclaimer page', 'HTG' ); ?></li>
					<li><?php esc_html_e( 'One-click page creation', 'HTG' ); ?></li>
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
		<h2><?php esc_html_e( 'Setup Guide', 'HTG' ); ?></h2>
		
		<div class="HTG-setup-steps">
			<div class="HTG-setup-step">
				<div class="HTG-step-number">1</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Configure General Settings', 'HTG' ); ?></h3>
					<p><?php esc_html_e( 'Set up your site layout, sidebar position, header style, and footer copyright.', 'HTG' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-general-settings' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'General Settings', 'HTG' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">2</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Set Up Magazine Homepage', 'HTG' ); ?></h3>
					<p><?php esc_html_e( 'Create a page with the "Magazine Homepage" template and set it as your front page in Settings → Reading.', 'HTG' ); ?></p>
					<?php
					$show_on_front = get_option( 'show_on_front' );
					$page_on_front = get_option( 'page_on_front' );
					$template = $page_on_front ? get_page_template_slug( $page_on_front ) : '';
					
					if ( 'page' === $show_on_front && 'template-magazine.php' === $template ) :
					?>
						<span class="HTG-step-complete">
							<span class="dashicons dashicons-yes-alt"></span>
							<?php esc_html_e( 'Magazine homepage is active!', 'HTG' ); ?>
						</span>
					<?php else : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-magazine-settings' ) ); ?>" class="button">
							<?php esc_html_e( 'Magazine Settings', 'HTG' ); ?>
						</a>
						<a href="<?php echo esc_url( admin_url( 'options-reading.php' ) ); ?>" class="button">
							<?php esc_html_e( 'Reading Settings', 'HTG' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">3</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Add Your Ad Codes', 'HTG' ); ?></h3>
					<p><?php esc_html_e( 'Paste your Google AdSense or Ad Manager codes into the ad slots.', 'HTG' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-simple-ads' ) ); ?>" class="button button-primary">
						<?php esc_html_e( 'Ad Management', 'HTG' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">4</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Customize Appearance', 'HTG' ); ?></h3>
					<p><?php esc_html_e( 'Adjust colors, fonts, and add custom CSS to match your brand.', 'HTG' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-appearance' ) ); ?>" class="button">
						<?php esc_html_e( 'Appearance', 'HTG' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">5</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Generate Legal Pages', 'HTG' ); ?></h3>
					<p><?php esc_html_e( 'Create required legal pages for AdSense compliance: Privacy Policy, Terms, Disclaimer, Disclosure.', 'HTG' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=HTG-legal-pages' ) ); ?>" class="button">
						<?php esc_html_e( 'Legal Pages', 'HTG' ); ?>
					</a>
				</div>
			</div>

			<div class="HTG-setup-step">
				<div class="HTG-step-number">6</div>
				<div class="HTG-step-content">
					<h3><?php esc_html_e( 'Configure Menus', 'HTG' ); ?></h3>
					<p><?php esc_html_e( 'Set up your navigation menus: Top Menu, Main Menu, and Footer Menu.', 'HTG' ); ?></p>
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button">
						<?php esc_html_e( 'Menus', 'HTG' ); ?>
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
		<h2><?php esc_html_e( 'Changelog', 'HTG' ); ?></h2>
		
		<div class="HTG-changelog-list">
			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.3.0</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'HTG' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Major Admin Panel Redesign', 'HTG' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Complete admin panel UI overhaul with enterprise-grade design', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'New unified CSS design system with custom properties', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Improved dashboard with quick stats and environment status', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Enhanced settings pages with toggle switches and tabs', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Removed redundant Ad Settings page', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Fixed broken quick action links', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'New theme info page with setup guide', 'HTG' ); ?></li>
					</ul>
				</div>
			</div>

			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.2.0</span>
					<span class="date"><?php esc_html_e( 'January 2026', 'HTG' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Magazine & Sync Improvements', 'HTG' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Magazine homepage dark theme styling', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Fixed Admin Panel and Customizer sync', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Auto-setup magazine homepage on theme activation', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Fixed menu location persistence issue', 'HTG' ); ?></li>
					</ul>
				</div>
			</div>

			<div class="HTG-changelog-item">
				<div class="HTG-changelog-version">
					<span class="version">2.1.0</span>
					<span class="date"><?php esc_html_e( 'December 2025', 'HTG' ); ?></span>
				</div>
				<div class="HTG-changelog-content">
					<h4><?php esc_html_e( 'Enterprise Features', 'HTG' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'Simple Ad Management system', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Legal Pages generator', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Engagement tools (newsletter, social share)', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Magazine homepage template', 'HTG' ); ?></li>
						<li><?php esc_html_e( 'Dark mode optimization', 'HTG' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
}

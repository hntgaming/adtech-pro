<?php
/**
 * GitHub Theme Updater
 *
 * Checks GitHub releases for theme updates and integrates with WordPress update system.
 *
 * @package HTG
 * @since 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GitHub Theme Updater Class
 */
class HTG_GitHub_Updater {

	/**
	 * GitHub username/organization
	 *
	 * @var string
	 */
	private $github_username = 'hntgaming';

	/**
	 * GitHub repository name
	 *
	 * @var string
	 */
	private $github_repo = 'adtech-pro';

	/**
	 * Theme slug (folder name)
	 *
	 * @var string
	 */
	private $theme_slug = 'adtech-pro';

	/**
	 * Theme display name
	 *
	 * @var string
	 */
	private $theme_name = 'H&T AdTech Pro';

	/**
	 * Current theme version
	 *
	 * @var string
	 */
	private $current_version = '2.4.3';

	/**
	 * GitHub API URL
	 *
	 * @var string
	 */
	private $api_url;

	/**
	 * Transient key for caching
	 *
	 * @var string
	 */
	private $transient_key = 'htg_github_update_check';

	/**
	 * Cache duration in seconds (1 hour - reduced for faster update detection)
	 *
	 * @var int
	 */
	private $cache_duration = 3600;

	/**
	 * GitHub access token for private repos (optional)
	 *
	 * @var string
	 */
	private $access_token = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Get version from style.css
		$theme = wp_get_theme( get_template() );
		if ( $theme->exists() ) {
			$this->current_version = $theme->get( 'Version' );
			$this->theme_name = $theme->get( 'Name' );
		}
		
		$this->api_url = "https://api.github.com/repos/{$this->github_username}/{$this->github_repo}/releases/latest";
		
		// Get access token from options (for private repos)
		$this->access_token = get_option( 'htg_github_token', '' );

		// Hook into WordPress update system
		add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
		add_filter( 'themes_api', array( $this, 'theme_info' ), 20, 3 );
		
		// Admin notice for updates
		add_action( 'admin_notices', array( $this, 'update_notice' ) );
		
		// Add settings for GitHub token (private repos)
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		
		// Clear cache on theme switch
		add_action( 'switch_theme', array( $this, 'clear_cache' ) );
		
		// Manual update check via admin
		add_action( 'admin_post_htg_check_updates', array( $this, 'manual_check' ) );
		
		// Auto-clear cache via URL parameter
		add_action( 'admin_init', array( $this, 'maybe_force_check' ) );
		
		// Add check for updates link in theme row
		add_filter( 'theme_action_links_' . $this->theme_slug, array( $this, 'add_check_updates_link' ) );
	}

	/**
	 * Get release info from GitHub
	 *
	 * @param bool $force_check Force fresh check, bypass cache.
	 * @return array|false Release data or false on failure.
	 */
	private function get_github_release( $force_check = false ) {
		// Check cache first
		if ( ! $force_check ) {
			$cached = get_transient( $this->transient_key );
			if ( false !== $cached ) {
				return $cached;
			}
		}

		// Build request args
		$args = array(
			'timeout'     => 10,
			'headers'     => array(
				'Accept'     => 'application/vnd.github.v3+json',
				'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
			),
		);

		// Add authorization for private repos
		if ( ! empty( $this->access_token ) ) {
			$args['headers']['Authorization'] = 'token ' . $this->access_token;
		}

		// Make API request
		$response = wp_remote_get( $this->api_url, $args );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $response_code ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		$release = json_decode( $body, true );

		if ( empty( $release ) || ! isset( $release['tag_name'] ) ) {
			return false;
		}

		// Parse release data
		$data = array(
			'version'      => ltrim( $release['tag_name'], 'vV' ),
			'tag_name'     => $release['tag_name'],
			'name'         => isset( $release['name'] ) ? $release['name'] : $release['tag_name'],
			'body'         => isset( $release['body'] ) ? $release['body'] : '',
			'published_at' => isset( $release['published_at'] ) ? $release['published_at'] : '',
			'html_url'     => isset( $release['html_url'] ) ? $release['html_url'] : '',
			'zipball_url'  => isset( $release['zipball_url'] ) ? $release['zipball_url'] : '',
			'download_url' => '',
		);

		// Find the ZIP asset (preferred) or use zipball
		if ( ! empty( $release['assets'] ) ) {
			foreach ( $release['assets'] as $asset ) {
				if ( 'application/zip' === $asset['content_type'] || 
				     substr( $asset['name'], -4 ) === '.zip' ) {
					$data['download_url'] = $asset['browser_download_url'];
					break;
				}
			}
		}

		// Fallback to zipball if no ZIP asset
		if ( empty( $data['download_url'] ) ) {
			$data['download_url'] = $data['zipball_url'];
		}

		// Cache the result
		set_transient( $this->transient_key, $data, $this->cache_duration );

		return $data;
	}

	/**
	 * Check for updates and inject into WordPress update system
	 *
	 * @param object $transient WordPress update transient.
	 * @return object Modified transient.
	 */
	public function check_for_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release = $this->get_github_release();

		if ( false === $release ) {
			return $transient;
		}

		// Compare versions
		if ( version_compare( $release['version'], $this->current_version, '>' ) ) {
			$transient->response[ $this->theme_slug ] = array(
				'theme'        => $this->theme_slug,
				'new_version'  => $release['version'],
				'url'          => $release['html_url'],
				'package'      => $release['download_url'],
				'requires'     => '6.0',
				'requires_php' => '7.4',
			);
		}

		return $transient;
	}

	/**
	 * Provide theme info for the WordPress updates UI
	 *
	 * @param false|object|array $result Result.
	 * @param string             $action API action.
	 * @param object             $args   Arguments.
	 * @return false|object Theme info or false.
	 */
	public function theme_info( $result, $action, $args ) {
		if ( 'theme_information' !== $action ) {
			return $result;
		}

		if ( ! isset( $args->slug ) || $args->slug !== $this->theme_slug ) {
			return $result;
		}

		$release = $this->get_github_release();

		if ( false === $release ) {
			return $result;
		}

		$theme = wp_get_theme( get_template() );
		$screenshot_url = get_template_directory_uri() . '/screenshot.png';

		return (object) array(
			'name'           => $this->theme_name,
			'slug'           => $this->theme_slug,
			'version'        => $release['version'],
			'author'         => '<a href="https://hntgaming.me">H&T GAMING</a>',
			'author_profile' => 'https://hntgaming.me',
			'requires'       => '6.0',
			'requires_php'   => '7.4',
			'homepage'       => 'https://hntgaming.me',
			'screenshot_url' => $screenshot_url,
			'sections'       => array(
				'description' => $theme->exists() ? $theme->get( 'Description' ) : 'A modern dark-themed magazine WordPress theme optimized for ad monetization.',
				'changelog'   => $this->format_changelog( $release['body'] ),
			),
			'download_link'  => $release['download_url'],
			'last_updated'   => $release['published_at'],
			'banners'        => array(
				'low'  => $screenshot_url,
				'high' => $screenshot_url,
			),
		);
	}

	/**
	 * Format changelog from GitHub release notes
	 *
	 * @param string $body Release body (markdown).
	 * @return string Formatted HTML.
	 */
	private function format_changelog( $body ) {
		if ( empty( $body ) ) {
			return '<p>No changelog available.</p>';
		}

		// Convert markdown to basic HTML
		$html = esc_html( $body );
		$html = nl2br( $html );
		
		// Convert markdown headers
		$html = preg_replace( '/^### (.+)$/m', '<h4>$1</h4>', $html );
		$html = preg_replace( '/^## (.+)$/m', '<h3>$1</h3>', $html );
		$html = preg_replace( '/^# (.+)$/m', '<h2>$1</h2>', $html );
		
		// Convert markdown lists
		$html = preg_replace( '/^[\-\*] (.+)$/m', '<li>$1</li>', $html );
		$html = preg_replace( '/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html );
		
		// Convert bold and italic
		$html = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html );
		$html = preg_replace( '/\*(.+?)\*/', '<em>$1</em>', $html );

		return $html;
	}

	/**
	 * Display admin notice when update is available
	 */
	public function update_notice() {
		// Only show to admins
		if ( ! current_user_can( 'update_themes' ) ) {
			return;
		}

		// Don't show on themes page (WordPress handles it there)
		$screen = get_current_screen();
		if ( 'themes' === $screen->id || 'update-core' === $screen->id ) {
			return;
		}

		$release = $this->get_github_release();

		if ( false === $release ) {
			return;
		}

		if ( version_compare( $release['version'], $this->current_version, '<=' ) ) {
			return;
		}

		// Check if user dismissed this version
		$dismissed = get_user_meta( get_current_user_id(), 'htg_dismissed_update', true );
		if ( $dismissed === $release['version'] ) {
			return;
		}

		$update_url = admin_url( 'update-core.php' );

		?>
		<div class="notice notice-warning is-dismissible htg-update-notice">
			<p>
				<strong><?php echo esc_html( $this->theme_name ); ?></strong>: 
				<?php
				printf(
					/* translators: 1: New version number, 2: Current version number */
					esc_html__( 'A new version %1$s is available. You are running version %2$s.', 'HTG' ),
					'<strong>' . esc_html( $release['version'] ) . '</strong>',
					'<strong>' . esc_html( $this->current_version ) . '</strong>'
				);
				?>
				<a href="<?php echo esc_url( $update_url ); ?>" class="button button-primary" style="margin-left: 10px;">
					<?php esc_html_e( 'Update Now', 'HTG' ); ?>
				</a>
				<a href="<?php echo esc_url( $release['html_url'] ); ?>" target="_blank" style="margin-left: 5px;">
					<?php esc_html_e( 'View Release Notes', 'HTG' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Register settings for GitHub token
	 */
	public function register_settings() {
		register_setting( 'htg_github_settings', 'htg_github_token', array(
			'sanitize_callback' => 'sanitize_text_field',
		) );
	}

	/**
	 * Clear update cache
	 */
	public function clear_cache() {
		delete_transient( $this->transient_key );
		delete_site_transient( 'update_themes' );
	}
	
	/**
	 * Force check via URL parameter (?htg_force_update_check=1)
	 */
	public function maybe_force_check() {
		if ( isset( $_GET['htg_force_update_check'] ) && current_user_can( 'update_themes' ) ) {
			$this->clear_cache();
			$this->get_github_release( true );
			
			// Redirect to remove query param
			$redirect = remove_query_arg( 'htg_force_update_check' );
			$redirect = add_query_arg( 'htg_cache_cleared', '1', $redirect );
			wp_safe_redirect( $redirect );
			exit;
		}
		
		// Show notice after cache clear
		if ( isset( $_GET['htg_cache_cleared'] ) ) {
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-success is-dismissible"><p><strong>H&T AdTech Pro:</strong> Update cache cleared. Refresh the page to see the latest version.</p></div>';
			} );
		}
	}
	
	/**
	 * Add "Check for Updates" link in theme actions
	 */
	public function add_check_updates_link( $links ) {
		$check_url = add_query_arg( 'htg_force_update_check', '1', admin_url( 'themes.php' ) );
		$links['check_updates'] = '<a href="' . esc_url( $check_url ) . '">Check for Updates</a>';
		return $links;
	}

	/**
	 * Manual update check (called via admin action)
	 */
	public function manual_check() {
		if ( ! current_user_can( 'update_themes' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'HTG' ) );
		}

		check_admin_referer( 'htg_check_updates' );

		$this->clear_cache();
		$release = $this->get_github_release( true );

		$redirect_url = add_query_arg(
			array(
				'htg_update_check' => $release ? 'success' : 'failed',
			),
			admin_url( 'themes.php' )
		);

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Check if update is available
	 *
	 * @return bool|array False if no update, release data if available.
	 */
	public function is_update_available() {
		$release = $this->get_github_release();

		if ( false === $release ) {
			return false;
		}

		if ( version_compare( $release['version'], $this->current_version, '>' ) ) {
			return $release;
		}

		return false;
	}

	/**
	 * Get current version
	 *
	 * @return string Current theme version.
	 */
	public function get_current_version() {
		return $this->current_version;
	}

	/**
	 * Get GitHub repo URL
	 *
	 * @return string Repository URL.
	 */
	public function get_repo_url() {
		return "https://github.com/{$this->github_username}/{$this->github_repo}";
	}
}

/**
 * Initialize the GitHub updater
 *
 * @return HTG_GitHub_Updater
 */
function htg_github_updater() {
	static $instance = null;

	if ( null === $instance ) {
		$instance = new HTG_GitHub_Updater();
	}

	return $instance;
}

// Initialize on admin
add_action( 'admin_init', 'htg_github_updater' );

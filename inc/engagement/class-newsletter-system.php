<?php
/**
 * Newsletter Signup System
 * Visual CTAs to grow email list
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Newsletter_System {

	/**
	 * Initialize newsletter system
	 */
	public static function init() {
		add_shortcode( 'HTG_newsletter', array( __CLASS__, 'render_newsletter_shortcode' ) );
		add_action( 'wp_ajax_HTG_newsletter_subscribe', array( __CLASS__, 'handle_subscription' ) );
		add_action( 'wp_ajax_nopriv_HTG_newsletter_subscribe', array( __CLASS__, 'handle_subscription' ) );
		add_action( 'customize_register', array( __CLASS__, 'add_customizer_settings' ) );
	}

	/**
	 * Add customizer settings
	 */
	public static function add_customizer_settings( $wp_customize ) {
		// Newsletter Section
		$wp_customize->add_section( 'HTG_newsletter', array(
			'title'    => __( 'Newsletter Settings', 'adtech-pro' ),
			'priority' => 130,
		) );

		// Newsletter Title
		$wp_customize->add_setting( 'HTG_newsletter_title', array(
			'default'           => __( 'Subscribe to Our Newsletter', 'adtech-pro' ),
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'HTG_newsletter_title', array(
			'label'   => __( 'Newsletter Title', 'adtech-pro' ),
			'section' => 'HTG_newsletter',
			'type'    => 'text',
		) );

		// Newsletter Description
		$wp_customize->add_setting( 'HTG_newsletter_description', array(
			'default'           => __( 'Get the latest stories and insights delivered to your inbox.', 'adtech-pro' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		) );

		$wp_customize->add_control( 'HTG_newsletter_description', array(
			'label'   => __( 'Newsletter Description', 'adtech-pro' ),
			'section' => 'HTG_newsletter',
			'type'    => 'textarea',
		) );

		// Newsletter Email (where submissions go)
		$wp_customize->add_setting( 'HTG_newsletter_email', array(
			'default'           => get_option( 'admin_email' ),
			'sanitize_callback' => 'sanitize_email',
		) );

		$wp_customize->add_control( 'HTG_newsletter_email', array(
			'label'       => __( 'Notification Email', 'adtech-pro' ),
			'description' => __( 'Where to send new subscriber notifications', 'adtech-pro' ),
			'section'     => 'HTG_newsletter',
			'type'        => 'email',
		) );

		// Auto-insert newsletter
		$wp_customize->add_setting( 'HTG_newsletter_auto_insert', array(
			'default'           => false,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control( 'HTG_newsletter_auto_insert', array(
			'label'       => __( 'Auto-Insert in Posts', 'adtech-pro' ),
			'description' => __( 'Automatically show newsletter signup after content', 'adtech-pro' ),
			'section'     => 'HTG_newsletter',
			'type'        => 'checkbox',
		) );
	}

	/**
	 * Render newsletter signup form
	 */
	public static function render_newsletter( $style = 'default' ) {
		$title = get_theme_mod( 'HTG_newsletter_title', __( 'Subscribe to Our Newsletter', 'adtech-pro' ) );
		$description = get_theme_mod( 'HTG_newsletter_description', __( 'Get the latest stories and insights delivered to your inbox.', 'adtech-pro' ) );

		$styles = array( 'default', 'minimal', 'boxed', 'gradient' );
		
		if ( ! in_array( $style, $styles, true ) ) {
			$style = 'default';
		}

		ob_start();
		?>
		<div class="HTG-newsletter-box HTG-newsletter-<?php echo esc_attr( $style ); ?>" data-style="<?php echo esc_attr( $style ); ?>">
			<div class="HTG-newsletter-icon">
				<svg width="48" height="48" viewBox="0 0 48 48" fill="none">
					<path d="M8 12h32v24H8V12z" stroke="currentColor" stroke-width="2" fill="none"/>
					<path d="M8 12l16 12 16-12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>

			<div class="HTG-newsletter-content">
				<h3 class="HTG-newsletter-title"><?php echo esc_html( $title ); ?></h3>
				<p class="HTG-newsletter-description"><?php echo esc_html( $description ); ?></p>

				<form class="HTG-newsletter-form" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>">
					<div class="HTG-newsletter-input-group">
						<input 
							type="email" 
							name="email" 
							class="HTG-newsletter-input" 
							placeholder="<?php esc_attr_e( 'Enter your email address', 'adtech-pro' ); ?>" 
							required
						>
						<button type="submit" class="HTG-newsletter-button">
							<span class="HTG-newsletter-button-text"><?php esc_html_e( 'Subscribe', 'adtech-pro' ); ?></span>
							<span class="HTG-newsletter-button-icon">→</span>
						</button>
					</div>

					<div class="HTG-newsletter-message" style="display: none;"></div>
					
					<p class="HTG-newsletter-privacy">
						<?php esc_html_e( 'We respect your privacy. Unsubscribe at any time.', 'adtech-pro' ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render newsletter shortcode
	 */
	public static function render_newsletter_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'style' => 'default',
		), $atts );

		return self::render_newsletter( $atts['style'] );
	}

	/**
	 * Handle newsletter subscription
	 */
	public static function handle_subscription() {
		check_ajax_referer( 'HTG_engagement_nonce', 'nonce' );

		$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;

		if ( ! is_email( $email ) ) {
			wp_send_json_error( array(
				'message' => __( 'Please enter a valid email address.', 'adtech-pro' ),
			) );
		}

		// Store subscriber
		global $wpdb;
		$table_name = $wpdb->prefix . 'HTG_subscribers';
		
		// Create table if it doesn't exist
		self::maybe_create_subscribers_table();

		// Check if already subscribed
		$existing = $wpdb->get_var( $wpdb->prepare(
			"SELECT id FROM $table_name WHERE email = %s",
			$email
		) );

		if ( $existing ) {
			wp_send_json_error( array(
				'message' => __( 'This email is already subscribed!', 'adtech-pro' ),
			) );
		}

		// Insert subscriber
		$inserted = $wpdb->insert(
			$table_name,
			array(
				'email'      => $email,
				'post_id'    => $post_id,
				'ip_address' => self::get_user_ip(),
				'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '',
				'created_at' => current_time( 'mysql' ),
			),
			array( '%s', '%d', '%s', '%s', '%s' )
		);

		if ( ! $inserted ) {
			wp_send_json_error( array(
				'message' => __( 'Something went wrong. Please try again.', 'adtech-pro' ),
			) );
		}

		// Send notification email
		self::send_notification_email( $email, $post_id );

		// Update subscriber count
		$count = get_option( 'HTG_subscriber_count', 0 );
		update_option( 'HTG_subscriber_count', $count + 1 );

		wp_send_json_success( array(
			'message' => __( '🎉 Success! Check your email to confirm your subscription.', 'adtech-pro' ),
		) );
	}

	/**
	 * Create subscribers table (cached check - only runs dbDelta once)
	 */
	private static function maybe_create_subscribers_table() {
		// Only create table once per version
		$db_version = '1.0.0';
		if ( get_option( 'HTG_subscribers_db_version' ) === $db_version ) {
			return;
		}
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'HTG_subscribers';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			email varchar(100) NOT NULL,
			post_id bigint(20) DEFAULT 0,
			ip_address varchar(45) DEFAULT '',
			user_agent text DEFAULT '',
			status varchar(20) DEFAULT 'pending',
			created_at datetime DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (id),
			UNIQUE KEY email (email)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		
		update_option( 'HTG_subscribers_db_version', $db_version );
	}

	/**
	 * Send notification email
	 */
	private static function send_notification_email( $email, $post_id ) {
		$to = get_theme_mod( 'HTG_newsletter_email', get_option( 'admin_email' ) );
		$subject = sprintf( __( 'New Newsletter Subscriber: %s', 'adtech-pro' ), $email );
		
		$post_title = $post_id ? get_the_title( $post_id ) : __( 'Unknown', 'adtech-pro' );
		$post_url = $post_id ? get_permalink( $post_id ) : '';
		
		$message = sprintf(
			__( "New newsletter subscription!\n\nEmail: %s\nSubscribed from: %s\nPost: %s\nTime: %s", 'adtech-pro' ),
			$email,
			$post_url,
			$post_title,
			current_time( 'mysql' )
		);

		wp_mail( $to, $subject, $message );
	}

	/**
	 * Get user IP address
	 */
	private static function get_user_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return sanitize_text_field( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return sanitize_text_field( $_SERVER['HTTP_X_FORWARDED_FOR'] );
		} else {
			return sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
		}
	}

	/**
	 * Auto-insert newsletter in content
	 */
	public static function auto_insert_newsletter( $content ) {
		if ( ! is_single() || ! get_theme_mod( 'HTG_newsletter_auto_insert', false ) ) {
			return $content;
		}

		$newsletter_html = self::render_newsletter( 'boxed' );
		return $content . $newsletter_html;
	}
}

// Initialize newsletter system
HTG_Newsletter_System::init();

// Auto-insert newsletter
add_filter( 'the_content', array( 'HTG_Newsletter_System', 'auto_insert_newsletter' ), 25 );


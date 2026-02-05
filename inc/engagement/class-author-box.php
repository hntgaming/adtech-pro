<?php
/**
 * Enhanced Author Box
 * Build authority and showcase writers
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Author_Box {

	/**
	 * Initialize author box
	 */
	public static function init() {
		add_shortcode( 'HTG_author_box', array( __CLASS__, 'render_author_box_shortcode' ) );
		add_action( 'show_user_profile', array( __CLASS__, 'add_social_fields' ) );
		add_action( 'edit_user_profile', array( __CLASS__, 'add_social_fields' ) );
		add_action( 'personal_options_update', array( __CLASS__, 'save_social_fields' ) );
		add_action( 'edit_user_profile_update', array( __CLASS__, 'save_social_fields' ) );
		add_action( 'customize_register', array( __CLASS__, 'add_customizer_settings' ) );
	}

	/**
	 * Add customizer settings
	 */
	public static function add_customizer_settings( $wp_customize ) {
		// Author Box Section
		$wp_customize->add_section( 'HTG_author_box', array(
			'title'    => __( 'Author Box Settings', 'adtech-pro' ),
			'priority' => 135,
		) );

		// Enable Author Box
		$wp_customize->add_setting( 'HTG_author_box_enable', array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control( 'HTG_author_box_enable', array(
			'label'       => __( 'Enable Author Box', 'adtech-pro' ),
			'description' => __( 'Show author bio box at the end of posts', 'adtech-pro' ),
			'section'     => 'HTG_author_box',
			'type'        => 'checkbox',
		) );

		// Author Box Style
		$wp_customize->add_setting( 'HTG_author_box_style', array(
			'default'           => 'default',
			'sanitize_callback' => 'sanitize_text_field',
		) );

		$wp_customize->add_control( 'HTG_author_box_style', array(
			'label'   => __( 'Author Box Style', 'adtech-pro' ),
			'section' => 'HTG_author_box',
			'type'    => 'select',
			'choices' => array(
				'default'  => __( 'Default', 'adtech-pro' ),
				'minimal'  => __( 'Minimal', 'adtech-pro' ),
				'card'     => __( 'Card Style', 'adtech-pro' ),
				'gradient' => __( 'Gradient Background', 'adtech-pro' ),
			),
		) );

		// Show Post Count
		$wp_customize->add_setting( 'HTG_author_box_show_post_count', array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control( 'HTG_author_box_show_post_count', array(
			'label'   => __( 'Show Post Count', 'adtech-pro' ),
			'section' => 'HTG_author_box',
			'type'    => 'checkbox',
		) );

		// Show Social Links
		$wp_customize->add_setting( 'HTG_author_box_show_social', array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control( 'HTG_author_box_show_social', array(
			'label'   => __( 'Show Social Links', 'adtech-pro' ),
			'section' => 'HTG_author_box',
			'type'    => 'checkbox',
		) );
	}

	/**
	 * Add social fields to user profile
	 */
	public static function add_social_fields( $user ) {
		$social_networks = self::get_social_networks();
		?>
		<h2><?php esc_html_e( 'Social Media Profiles', 'adtech-pro' ); ?></h2>
		<table class="form-table">
			<?php foreach ( $social_networks as $network => $label ) : ?>
				<tr>
					<th>
						<label for="<?php echo esc_attr( $network ); ?>">
							<?php echo esc_html( $label ); ?>
						</label>
					</th>
					<td>
						<input 
							type="url" 
							name="<?php echo esc_attr( $network ); ?>" 
							id="<?php echo esc_attr( $network ); ?>" 
							value="<?php echo esc_attr( get_the_author_meta( $network, $user->ID ) ); ?>" 
							class="regular-text" 
							placeholder="<?php echo esc_attr( sprintf( __( 'https://...', 'adtech-pro' ) ) ); ?>"
						>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>

		<h2><?php esc_html_e( 'Additional Information', 'adtech-pro' ); ?></h2>
		<table class="form-table">
			<tr>
				<th>
					<label for="job_title">
						<?php esc_html_e( 'Job Title', 'adtech-pro' ); ?>
					</label>
				</th>
				<td>
					<input 
						type="text" 
						name="job_title" 
						id="job_title" 
						value="<?php echo esc_attr( get_the_author_meta( 'job_title', $user->ID ) ); ?>" 
						class="regular-text" 
						placeholder="<?php esc_attr_e( 'e.g., Senior Writer, Editor', 'adtech-pro' ); ?>"
					>
				</td>
			</tr>
			<tr>
				<th>
					<label for="company">
						<?php esc_html_e( 'Company', 'adtech-pro' ); ?>
					</label>
				</th>
				<td>
					<input 
						type="text" 
						name="company" 
						id="company" 
						value="<?php echo esc_attr( get_the_author_meta( 'company', $user->ID ) ); ?>" 
						class="regular-text" 
						placeholder="<?php esc_attr_e( 'Your company name', 'adtech-pro' ); ?>"
					>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save social fields
	 */
	public static function save_social_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$social_networks = self::get_social_networks();
		
		foreach ( $social_networks as $network => $label ) {
			if ( isset( $_POST[ $network ] ) ) {
				update_user_meta( $user_id, $network, esc_url_raw( $_POST[ $network ] ) );
			}
		}

		// Save additional fields
		if ( isset( $_POST['job_title'] ) ) {
			update_user_meta( $user_id, 'job_title', sanitize_text_field( $_POST['job_title'] ) );
		}

		if ( isset( $_POST['company'] ) ) {
			update_user_meta( $user_id, 'company', sanitize_text_field( $_POST['company'] ) );
		}
	}

	/**
	 * Get social networks
	 */
	private static function get_social_networks() {
		return array(
			'twitter_url'    => __( 'Twitter/X URL', 'adtech-pro' ),
			'facebook_url'   => __( 'Facebook URL', 'adtech-pro' ),
			'linkedin_url'   => __( 'LinkedIn URL', 'adtech-pro' ),
			'instagram_url'  => __( 'Instagram URL', 'adtech-pro' ),
			'youtube_url'    => __( 'YouTube URL', 'adtech-pro' ),
			'github_url'     => __( 'GitHub URL', 'adtech-pro' ),
			'website_url'    => __( 'Website URL', 'adtech-pro' ),
		);
	}

	/**
	 * Render author box
	 */
	public static function render_author_box( $author_id = null, $style = null ) {
		if ( ! get_theme_mod( 'HTG_author_box_enable', true ) ) {
			return '';
		}

		if ( ! $author_id ) {
			$author_id = get_the_author_meta( 'ID' );
		}

		if ( ! $style ) {
			$style = get_theme_mod( 'HTG_author_box_style', 'default' );
		}

		$author_name = get_the_author_meta( 'display_name', $author_id );
		$author_bio = get_the_author_meta( 'description', $author_id );
		$author_url = get_author_posts_url( $author_id );
		$author_avatar = get_avatar( $author_id, 120 );
		$job_title = get_the_author_meta( 'job_title', $author_id );
		$company = get_the_author_meta( 'company', $author_id );
		$post_count = count_user_posts( $author_id, 'post', true );

		$show_post_count = get_theme_mod( 'HTG_author_box_show_post_count', true );
		$show_social = get_theme_mod( 'HTG_author_box_show_social', true );

		ob_start();
		?>
		<div class="HTG-author-box HTG-author-box-<?php echo esc_attr( $style ); ?>" itemscope itemtype="https://schema.org/Person">
			<div class="HTG-author-avatar">
				<a href="<?php echo esc_url( $author_url ); ?>" rel="author">
					<?php echo $author_avatar; ?>
				</a>
			</div>

			<div class="HTG-author-info">
				<div class="HTG-author-header">
					<div class="HTG-author-name-wrapper">
						<h3 class="HTG-author-name" itemprop="name">
							<a href="<?php echo esc_url( $author_url ); ?>" rel="author" itemprop="url">
								<?php echo esc_html( $author_name ); ?>
							</a>
						</h3>
						
						<?php if ( $job_title || $company ) : ?>
							<div class="HTG-author-title">
								<?php if ( $job_title ) : ?>
									<span class="HTG-author-job-title" itemprop="jobTitle"><?php echo esc_html( $job_title ); ?></span>
								<?php endif; ?>
								<?php if ( $job_title && $company ) : ?>
									<span class="HTG-author-separator"> @ </span>
								<?php endif; ?>
								<?php if ( $company ) : ?>
									<span class="HTG-author-company" itemprop="worksFor"><?php echo esc_html( $company ); ?></span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $show_post_count ) : ?>
						<div class="HTG-author-stats">
							<span class="HTG-author-post-count">
								<strong><?php echo esc_html( number_format_i18n( $post_count ) ); ?></strong>
								<?php echo esc_html( _n( 'Article', 'Articles', $post_count, 'adtech-pro' ) ); ?>
							</span>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $author_bio ) : ?>
					<div class="HTG-author-bio" itemprop="description">
						<?php echo wpautop( esc_html( $author_bio ) ); ?>
					</div>
				<?php endif; ?>

				<div class="HTG-author-footer">
					<?php if ( $show_social ) : ?>
						<div class="HTG-author-social">
							<?php echo self::render_social_links( $author_id ); ?>
						</div>
					<?php endif; ?>

					<a href="<?php echo esc_url( $author_url ); ?>" class="HTG-author-link">
						<?php esc_html_e( 'View all posts', 'adtech-pro' ); ?>
						<span class="HTG-author-link-arrow">→</span>
					</a>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render social links
	 */
	private static function render_social_links( $author_id ) {
		$social_networks = array(
			'twitter_url'   => array( 'label' => 'Twitter', 'icon' => '𝕏' ),
			'facebook_url'  => array( 'label' => 'Facebook', 'icon' => 'f' ),
			'linkedin_url'  => array( 'label' => 'LinkedIn', 'icon' => 'in' ),
			'instagram_url' => array( 'label' => 'Instagram', 'icon' => 'IG' ),
			'youtube_url'   => array( 'label' => 'YouTube', 'icon' => '▶' ),
			'github_url'    => array( 'label' => 'GitHub', 'icon' => 'GH' ),
			'website_url'   => array( 'label' => 'Website', 'icon' => '🌐' ),
		);

		$links = array();
		
		foreach ( $social_networks as $network => $data ) {
			$url = get_the_author_meta( $network, $author_id );
			
			if ( ! empty( $url ) ) {
				$links[] = sprintf(
					'<a href="%s" class="HTG-author-social-link HTG-social-%s" target="_blank" rel="nofollow noopener" title="%s" aria-label="%s">%s</a>',
					esc_url( $url ),
					esc_attr( str_replace( '_url', '', $network ) ),
					esc_attr( $data['label'] ),
					esc_attr( $data['label'] ),
					esc_html( $data['icon'] )
				);
			}
		}

		return ! empty( $links ) ? implode( '', $links ) : '';
	}

	/**
	 * Render author box shortcode
	 */
	public static function render_author_box_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'author_id' => get_the_author_meta( 'ID' ),
			'style'     => null,
		), $atts );

		return self::render_author_box( $atts['author_id'], $atts['style'] );
	}

	/**
	 * Auto-insert author box
	 */
	public static function auto_insert_author_box( $content ) {
		if ( ! is_single() || ! get_theme_mod( 'HTG_author_box_enable', true ) ) {
			return $content;
		}

		$author_box = self::render_author_box();
		return $content . $author_box;
	}
}

// Initialize author box
HTG_Author_Box::init();

// Auto-insert author box
add_filter( 'the_content', array( 'HTG_Author_Box', 'auto_insert_author_box' ), 30 );


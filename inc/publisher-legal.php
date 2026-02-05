<?php
/**
 * Publisher Legal Pages Generator
 * Auto-generate Disclaimer, Disclosure, Terms, Privacy Policy
 * Essential for AdSense & affiliate monetization compliance
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Publisher_Legal {

	/**
	 * Initialize legal pages
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_page_generation' ) );
	}

	/**
	 * Add admin menu
	 */
	public static function add_admin_menu() {
		add_submenu_page(
			'HTG-dashboard',
			__( 'Legal Pages', 'adtech-pro' ),
			__( 'Legal Pages', 'adtech-pro' ),
			'manage_options',
			'HTG-legal-pages',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Render admin page
	 */
	public static function render_admin_page() {
		// Check if pages already exist
		$disclaimer_page = get_page_by_path( 'disclaimer' );
		$disclosure_page = get_page_by_path( 'advertiser-disclosure' );
		$terms_page = get_page_by_path( 'terms-of-service' );
		$privacy_page = get_page_by_path( 'privacy-policy' );

		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-shield"></span>
					<?php esc_html_e( 'Legal Pages', 'adtech-pro' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Generate essential legal pages for AdSense compliance', 'adtech-pro' ); ?></p>
			</div>

			<div class="HTG-admin-row">
				<div style="width: 100%; padding: 0 40px;">
					<div class="HTG-legal-pages-grid">
						<!-- Disclaimer -->
						<div class="HTG-legal-page-card">
							<div class="HTG-legal-page-icon">
								<span class="dashicons dashicons-info-outline"></span>
							</div>
							<h3><?php esc_html_e( 'Disclaimer', 'adtech-pro' ); ?></h3>
							<p><?php esc_html_e( 'Protects you from liability for information published on your site.', 'adtech-pro' ); ?></p>
							
							<?php if ( $disclaimer_page ) : ?>
								<div class="HTG-page-exists">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Page exists', 'adtech-pro' ); ?>
									<div style="margin-top: 8px;">
										<a href="<?php echo esc_url( get_permalink( $disclaimer_page->ID ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'adtech-pro' ); ?></a>
										&nbsp;|&nbsp;
										<a href="<?php echo esc_url( get_edit_post_link( $disclaimer_page->ID ) ); ?>"><?php esc_html_e( 'Edit', 'adtech-pro' ); ?></a>
									</div>
								</div>
							<?php else : ?>
								<form method="post" style="margin-top: 15px;">
									<?php wp_nonce_field( 'HTG_generate_legal_page', 'HTG_legal_nonce' ); ?>
									<input type="hidden" name="page_type" value="disclaimer">
									<button type="submit" name="generate_page" class="button button-primary"><?php esc_html_e( 'Generate Page', 'adtech-pro' ); ?></button>
								</form>
							<?php endif; ?>
						</div>

						<!-- Disclosure -->
						<div class="HTG-legal-page-card">
							<div class="HTG-legal-page-icon">
								<span class="dashicons dashicons-megaphone"></span>
							</div>
							<h3><?php esc_html_e( 'Advertiser Disclosure', 'adtech-pro' ); ?></h3>
							<p><?php esc_html_e( 'Required for affiliate marketing and ad revenue transparency.', 'adtech-pro' ); ?></p>
							
							<?php if ( $disclosure_page ) : ?>
								<div class="HTG-page-exists">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Page exists', 'adtech-pro' ); ?>
									<div style="margin-top: 8px;">
										<a href="<?php echo esc_url( get_permalink( $disclosure_page->ID ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'adtech-pro' ); ?></a>
										&nbsp;|&nbsp;
										<a href="<?php echo esc_url( get_edit_post_link( $disclosure_page->ID ) ); ?>"><?php esc_html_e( 'Edit', 'adtech-pro' ); ?></a>
									</div>
								</div>
							<?php else : ?>
								<form method="post" style="margin-top: 15px;">
									<?php wp_nonce_field( 'HTG_generate_legal_page', 'HTG_legal_nonce' ); ?>
									<input type="hidden" name="page_type" value="disclosure">
									<button type="submit" name="generate_page" class="button button-primary"><?php esc_html_e( 'Generate Page', 'adtech-pro' ); ?></button>
								</form>
							<?php endif; ?>
						</div>

						<!-- Terms -->
						<div class="HTG-legal-page-card">
							<div class="HTG-legal-page-icon">
								<span class="dashicons dashicons-media-document"></span>
							</div>
							<h3><?php esc_html_e( 'Terms of Service', 'adtech-pro' ); ?></h3>
							<p><?php esc_html_e( 'Establishes rules for using your website and protects your content.', 'adtech-pro' ); ?></p>
							
							<?php if ( $terms_page ) : ?>
								<div class="HTG-page-exists">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Page exists', 'adtech-pro' ); ?>
									<div style="margin-top: 8px;">
										<a href="<?php echo esc_url( get_permalink( $terms_page->ID ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'adtech-pro' ); ?></a>
										&nbsp;|&nbsp;
										<a href="<?php echo esc_url( get_edit_post_link( $terms_page->ID ) ); ?>"><?php esc_html_e( 'Edit', 'adtech-pro' ); ?></a>
									</div>
								</div>
							<?php else : ?>
								<form method="post" style="margin-top: 15px;">
									<?php wp_nonce_field( 'HTG_generate_legal_page', 'HTG_legal_nonce' ); ?>
									<input type="hidden" name="page_type" value="terms">
									<button type="submit" name="generate_page" class="button button-primary"><?php esc_html_e( 'Generate Page', 'adtech-pro' ); ?></button>
								</form>
							<?php endif; ?>
						</div>

						<!-- Privacy Policy -->
						<div class="HTG-legal-page-card">
							<div class="HTG-legal-page-icon">
								<span class="dashicons dashicons-privacy"></span>
							</div>
							<h3><?php esc_html_e( 'Privacy Policy', 'adtech-pro' ); ?></h3>
							<p><?php esc_html_e( 'Required by GDPR, CCPA, and AdSense. Explains data collection.', 'adtech-pro' ); ?></p>
							
							<?php if ( $privacy_page ) : ?>
								<div class="HTG-page-exists">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Page exists', 'adtech-pro' ); ?>
									<div style="margin-top: 8px;">
										<a href="<?php echo esc_url( get_permalink( $privacy_page->ID ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'adtech-pro' ); ?></a>
										&nbsp;|&nbsp;
										<a href="<?php echo esc_url( get_edit_post_link( $privacy_page->ID ) ); ?>"><?php esc_html_e( 'Edit', 'adtech-pro' ); ?></a>
									</div>
								</div>
							<?php else : ?>
								<form method="post" style="margin-top: 15px;">
									<?php wp_nonce_field( 'HTG_generate_legal_page', 'HTG_legal_nonce' ); ?>
									<input type="hidden" name="page_type" value="privacy">
									<button type="submit" name="generate_page" class="button button-primary"><?php esc_html_e( 'Generate Page', 'adtech-pro' ); ?></button>
								</form>
							<?php endif; ?>
						</div>
					</div>

					<div class="HTG-legal-notice">
						<h3 style="display: flex; align-items: center; gap: 8px;">
							<span class="dashicons dashicons-warning" style="color: #f59e0b;"></span>
							<?php esc_html_e( 'Important Notice', 'adtech-pro' ); ?>
						</h3>
						<p><?php esc_html_e( 'These pages are templates. You MUST customize them with your actual business information, contact details, and specific practices. Consider consulting with a lawyer for your specific needs.', 'adtech-pro' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle page generation
	 */
	public static function handle_page_generation() {
		if ( ! isset( $_POST['generate_page'] ) || ! isset( $_POST['page_type'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['HTG_legal_nonce'], 'HTG_generate_legal_page' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page_type = sanitize_text_field( $_POST['page_type'] );
		$site_name = get_bloginfo( 'name' );
		$site_url = home_url();

		$pages_content = array(
			'disclaimer' => array(
				'title'   => 'Disclaimer',
				'content' => self::get_disclaimer_content( $site_name, $site_url ),
			),
			'disclosure' => array(
				'title'   => 'Advertiser Disclosure',
				'content' => self::get_disclosure_content( $site_name, $site_url ),
			),
			'terms' => array(
				'title'   => 'Terms of Service',
				'content' => self::get_terms_content( $site_name, $site_url ),
			),
			'privacy' => array(
				'title'   => 'Privacy Policy',
				'content' => self::get_privacy_content( $site_name, $site_url ),
			),
		);

		if ( ! isset( $pages_content[ $page_type ] ) ) {
			return;
		}

		$page_data = $pages_content[ $page_type ];

		$page_id = wp_insert_post( array(
			'post_title'   => $page_data['title'],
			'post_content' => $page_data['content'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => get_current_user_id(),
		) );

		if ( $page_id ) {
			wp_safe_redirect( admin_url( 'admin.php?page=HTG-legal-pages&generated=1' ) );
			exit;
		}
	}

	/**
	 * Get disclaimer content
	 */
	private static function get_disclaimer_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Last Updated:</strong> {$date}</p>

<h2>General Disclaimer</h2>
<p>The information provided by <strong>{$site_name}</strong> ("{$site_url}") is for general informational purposes only. All information on the Site is provided in good faith, however we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the Site.</p>

<p><strong>UNDER NO CIRCUMSTANCE SHALL WE HAVE ANY LIABILITY TO YOU FOR ANY LOSS OR DAMAGE OF ANY KIND INCURRED AS A RESULT OF THE USE OF THE SITE OR RELIANCE ON ANY INFORMATION PROVIDED ON THE SITE. YOUR USE OF THE SITE AND YOUR RELIANCE ON ANY INFORMATION ON THE SITE IS SOLELY AT YOUR OWN RISK.</strong></p>

<h2>No Professional Advice</h2>
<p>The Site cannot and does not contain professional advice. The information is provided for general informational and educational purposes only and is not a substitute for professional advice. Accordingly, before taking any actions based upon such information, we encourage you to consult with the appropriate professionals. We do not provide any kind of professional advice.</p>

<p>THE USE OR RELIANCE OF ANY INFORMATION CONTAINED ON THE SITE IS SOLELY AT YOUR OWN RISK.</p>

<h2>Third-Party Links</h2>
<p>The Site may contain links to third-party websites or content belonging to or originating from third parties. Such external links are not investigated, monitored, or checked for accuracy, adequacy, validity, reliability, availability or completeness by us.</p>

<p>WE DO NOT WARRANT, ENDORSE, GUARANTEE, OR ASSUME RESPONSIBILITY FOR THE ACCURACY OR RELIABILITY OF ANY INFORMATION OFFERED BY THIRD-PARTY WEBSITES LINKED THROUGH THE SITE OR ANY WEBSITE OR FEATURE LINKED IN ANY BANNER OR OTHER ADVERTISING.</p>

<h2>Errors and Omissions</h2>
<p>While we have made every attempt to ensure that the information contained in this site has been obtained from reliable sources, {$site_name} is not responsible for any errors or omissions or for the results obtained from the use of this information. All information in this site is provided "as is", with no guarantee of completeness, accuracy, timeliness or of the results obtained from the use of this information.</p>

<h2>Changes</h2>
<p>We reserve the right to modify this disclaimer at any time. Your continued use of the Site following any changes indicates your acceptance of the new terms.</p>

<h2>Contact Us</h2>
<p>If you have any questions about this Disclaimer, please contact us.</p>
HTML;
	}

	/**
	 * Get disclosure content
	 */
	private static function get_disclosure_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Last Updated:</strong> {$date}</p>

<h2>Overview</h2>
<p><strong>{$site_name}</strong> is an independent, objective, advertising-supported content publisher. To support our ability to provide free content to our users, the recommendations that appear on our site might be from companies from which we receive affiliate compensation. Such compensation may impact how, where and in what order offers appear on our site. Other factors such as our own proprietary website rules and whether a product is offered in your area or at your self-selected credit score range can also impact how and where products appear on this site.</p>

<p>While we strive to provide a wide range of offers, we do not include information about every product or service that may be available to you. Our goal is to keep information accurate and timely, but some information may not be current.</p>

<h2>Compensation Disclosure</h2>
<p>We may receive compensation from companies whose products or services are mentioned or advertised on {$site_name}. This compensation may be in the form of money, products, or services.</p>

<p>While we strive to maintain transparency and objectivity, please be aware that receiving compensation can create a conflict of interest. We are committed to providing honest, unbiased information regardless of any affiliate relationships.</p>

<h2>Affiliate Relationships</h2>
<p>{$site_name} participates in various affiliate marketing programs. This means we may get paid commissions on editorially chosen products purchased through our links to retailer sites.</p>

<p>Our affiliate partners include, but are not limited to:</p>
<ul>
<li>Amazon Associates Program</li>
<li>Google AdSense</li>
<li>Various Affiliate Networks</li>
</ul>

<h2>Editorial Independence</h2>
<p>Our editorial content is not influenced by advertisers or affiliate partnerships. Our team of writers and editors are dedicated to providing accurate, unbiased information to help our readers make informed decisions.</p>

<p>Products and services are reviewed independently, and our opinions are our own. Compensation received does not influence the content, topics, or posts made on this site.</p>

<h2>Advertising</h2>
<p>This website may contain advertisements. Advertisers and advertising networks place ads on this site. These companies may use information (not including your name, address, email address, or telephone number) about your visits to this and other websites in order to provide advertisements about goods and services of interest to you.</p>

<h2>Third-Party Links</h2>
<p>When you click on links on our site, they may direct you away from our site. We are not responsible for the content or privacy practices of these other sites.</p>

<h2>Product Reviews</h2>
<p>When we feature products on {$site_name}, we may receive compensation from the companies whose products we feature. Some product links are affiliate links which means if you buy something we'll receive a small commission.</p>

<h2>Disclaimer</h2>
<p>While we do our best to keep this information current, the terms shown may be outdated. Please visit the vendor's website to verify terms and conditions. We are an independent, advertising-supported comparison service. Our goal is to help you make smarter financial decisions by providing you with interactive tools and financial calculators, publishing original and objective content, by enabling you to conduct research and compare information for free.</p>

<h2>Questions</h2>
<p>If you have any questions regarding this disclosure policy, please contact us.</p>
HTML;
	}

	/**
	 * Get terms content
	 */
	private static function get_terms_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Last Updated:</strong> {$date}</p>

<p>Please read these Terms of Service carefully before using {$site_url} operated by <strong>{$site_name}</strong>.</p>

<h2>1. Agreement to Terms</h2>
<p>By accessing and using this Website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

<h2>2. Use License</h2>
<p>Permission is granted to temporarily download one copy of the materials (information or software) on {$site_name}'s website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>

<ul>
<li>Modify or copy the materials</li>
<li>Use the materials for any commercial purpose</li>
<li>Attempt to decompile or reverse engineer any software contained on {$site_name}'s website</li>
<li>Remove any copyright or other proprietary notations from the materials</li>
<li>Transfer the materials to another person or "mirror" the materials on any other server</li>
</ul>

<h2>3. Disclaimer</h2>
<p>The materials on {$site_name}'s website are provided on an 'as is' basis. {$site_name} makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>

<h2>4. Limitations</h2>
<p>In no event shall {$site_name} or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on {$site_name}'s website.</p>

<h2>5. Accuracy of Materials</h2>
<p>The materials appearing on {$site_name}'s website could include technical, typographical, or photographic errors. {$site_name} does not warrant that any of the materials on its website are accurate, complete or current.</p>

<h2>6. Links</h2>
<p>{$site_name} has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by {$site_name} of the site. Use of any such linked website is at the user's own risk.</p>

<h2>7. Modifications</h2>
<p>{$site_name} may revise these terms of service for its website at any time without notice. By using this website you are agreeing to be bound by the then current version of these terms of service.</p>

<h2>8. Governing Law</h2>
<p>These terms and conditions are governed by and construed in accordance with the laws and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>

<h2>9. User Comments</h2>
<p>Parts of this website offer an opportunity for users to post and exchange opinions and information. {$site_name} does not filter, edit, publish or review Comments prior to their presence on the website. Comments reflect the views of the person who post their views and do not reflect the views of {$site_name}.</p>

<h2>10. Your Privacy</h2>
<p>Please read our Privacy Policy.</p>

<h2>Contact Information</h2>
<p>If you have any questions about these Terms, please contact us.</p>
HTML;
	}

	/**
	 * Get privacy content
	 */
	private static function get_privacy_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Last Updated:</strong> {$date}</p>

<p>Your privacy is important to us. This Privacy Policy explains how <strong>{$site_name}</strong> collects, uses, and discloses information about you when you visit our website at {$site_url}.</p>

<h2>1. Information We Collect</h2>
<h3>Information You Provide to Us</h3>
<ul>
<li><strong>Contact Information:</strong> When you subscribe to our newsletter or contact us, we may collect your name and email address.</li>
<li><strong>Comments:</strong> When you leave a comment, we collect the data shown in the comments form.</li>
</ul>

<h3>Information We Collect Automatically</h3>
<ul>
<li><strong>Log Data:</strong> When you visit our website, our servers automatically record information, including your IP address, browser type, operating system, referring URLs, and page views.</li>
<li><strong>Cookies:</strong> We use cookies and similar tracking technologies to track activity on our website and hold certain information.</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use the information we collect to:</p>
<ul>
<li>Provide, maintain, and improve our services</li>
<li>Send you newsletters and marketing communications (if you've opted in)</li>
<li>Respond to your comments and questions</li>
<li>Monitor and analyze trends and usage</li>
<li>Detect, prevent, and address technical issues</li>
</ul>

<h2>3. Cookies and Tracking</h2>
<p>We use cookies to:</p>
<ul>
<li>Remember your preferences</li>
<li>Understand how you use our website</li>
<li>Improve user experience</li>
<li>Serve advertisements (via Google AdSense and other networks)</li>
</ul>

<p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>

<h2>4. Third-Party Services</h2>
<h3>Google AdSense</h3>
<p>We use Google AdSense to serve advertisements. Google may use cookies to serve ads based on your prior visits to our website or other websites. You may opt out of personalized advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank">Google Ads Settings</a>.</p>

<h3>Google Analytics</h3>
<p>We use Google Analytics to analyze website traffic. Google Analytics collects information anonymously and generates reports about website usage.</p>

<h2>5. Data Retention</h2>
<p>We retain your information for as long as necessary to provide you with our services and fulfill the purposes outlined in this Privacy Policy. When we have no ongoing legitimate business need to process your personal information, we will delete or anonymize it.</p>

<h2>6. Your Rights (GDPR & CCPA)</h2>
<p>If you are a resident of the European Economic Area (EEA) or California, you have certain data protection rights:</p>
<ul>
<li>The right to access your personal information</li>
<li>The right to rectification of inaccurate data</li>
<li>The right to erasure ("right to be forgotten")</li>
<li>The right to restrict processing</li>
<li>The right to data portability</li>
<li>The right to object to processing</li>
</ul>

<h2>7. Children's Privacy</h2>
<p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from children under 13.</p>

<h2>8. Changes to This Privacy Policy</h2>
<p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date.</p>

<h2>9. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us.</p>
HTML;
	}
}

// Initialize publisher legal pages
HTG_Publisher_Legal::init();


<?php
/**
 * Publisher Legal Pages Generator
 * Auto-generate GDPR/CCPA-compliant legal pages:
 * - Privacy Policy (cookies, Google Analytics, AdSense, AdX, Authorized Buyers)
 * - Cookie Policy (full cookie inventory with categories and durations)
 * - Terms of Service (advertising, IP, prohibited activities)
 * - Disclaimer (advertising, fair use, liability)
 * - Advertiser Disclosure (programmatic advertising, affiliate, FTC)
 *
 * Also hooks into WordPress Privacy Policy Guide (wp_add_privacy_policy_content).
 *
 * @package HTG
 * @since 2.1.0
 * @updated 3.0.0 Full GDPR/CCPA/ePrivacy compliance, Cookie Policy page, AdX/Authorized Buyers disclosures
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
		add_action( 'admin_init', array( __CLASS__, 'add_privacy_policy_content' ) );
	}

	/**
	 * Hook into WordPress Privacy Policy Guide (Tools > Privacy > Policy Guide)
	 * Adds our AdTech-specific privacy disclosures to WP's suggested policy text.
	 */
	public static function add_privacy_policy_content() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
			return;
		}

		$content = '<h2>' . esc_html__( 'Advertising & Cookies (AdTech Pro Theme)', 'adtech-pro' ) . '</h2>' .
		'<p>' . esc_html__( 'This website uses cookies and similar tracking technologies for analytics and advertising purposes. The following third-party services are integrated:', 'adtech-pro' ) . '</p>' .
		'<ul>' .
		'<li><strong>Google Analytics (GA4):</strong> ' . esc_html__( 'Collects anonymized usage data using cookies (_ga, _ga_*, _gid) to analyze website traffic and user behavior. Data is retained for 14 months.', 'adtech-pro' ) . '</li>' .
		'<li><strong>Google AdSense:</strong> ' . esc_html__( 'Serves display advertisements and uses cookies (IDE, NID, ANID, 1P_JAR) to deliver personalized ads based on browsing history.', 'adtech-pro' ) . '</li>' .
		'<li><strong>Google Ad Exchange (AdX):</strong> ' . esc_html__( 'Programmatic advertising marketplace where advertisers bid in real-time auctions for ad inventory. Uses DoubleClick cookies (IDE, DSID, test_cookie).', 'adtech-pro' ) . '</li>' .
		'<li><strong>Google Authorized Buyers:</strong> ' . esc_html__( 'Vetted third-party advertisers that bid on ad inventory through Google Ad Manager. These buyers may set their own cookies and receive bid request data including device type, approximate location, and page URL.', 'adtech-pro' ) . '</li>' .
		'<li><strong>Prebid.js Header Bidding:</strong> ' . esc_html__( 'Multiple demand-side platforms compete for ad impressions. Each partner operates under its own privacy policy and may set cookies.', 'adtech-pro' ) . '</li>' .
		'</ul>' .
		'<p>' . esc_html__( 'Users can opt out of personalized advertising via Google Ads Settings, the DAA opt-out tool, the NAI opt-out tool, or the EDAA opt-out tool. The Global Privacy Control (GPC) signal is honored as an opt-out of sale/sharing under CCPA/CPRA.', 'adtech-pro' ) . '</p>' .
		'<h3>' . esc_html__( 'GDPR Rights', 'adtech-pro' ) . '</h3>' .
		'<p>' . esc_html__( 'EEA/UK residents have the right to access, rectify, erase, restrict, port, and object to the processing of their personal data under the General Data Protection Regulation (GDPR). Consent can be withdrawn at any time.', 'adtech-pro' ) . '</p>' .
		'<h3>' . esc_html__( 'CCPA/CPRA Rights', 'adtech-pro' ) . '</h3>' .
		'<p>' . esc_html__( 'California residents have the right to know, delete, and opt out of the sale or sharing of personal information under the California Consumer Privacy Act (CCPA) and California Privacy Rights Act (CPRA). We do not sell personal information in the traditional sense, but the use of advertising cookies may constitute "sharing" under the CPRA.', 'adtech-pro' ) . '</p>';

		wp_add_privacy_policy_content( 'AdTech Pro Theme', $content );
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
	 * All supported legal page types
	 */
	private static function get_page_types() {
		return array(
			'disclaimer' => array(
				'slug'  => 'disclaimer',
				'title' => __( 'Disclaimer', 'adtech-pro' ),
				'desc'  => __( 'Protects you from liability for information published on your site.', 'adtech-pro' ),
				'icon'  => 'info-outline',
			),
			'disclosure' => array(
				'slug'  => 'advertiser-disclosure',
				'title' => __( 'Advertiser Disclosure', 'adtech-pro' ),
				'desc'  => __( 'Required for affiliate marketing and ad revenue transparency.', 'adtech-pro' ),
				'icon'  => 'megaphone',
			),
			'terms' => array(
				'slug'  => 'terms-of-service',
				'title' => __( 'Terms of Service', 'adtech-pro' ),
				'desc'  => __( 'Establishes rules for using your website and protects your content.', 'adtech-pro' ),
				'icon'  => 'media-document',
			),
			'privacy' => array(
				'slug'  => 'privacy-policy',
				'title' => __( 'Privacy Policy', 'adtech-pro' ),
				'desc'  => __( 'Required by GDPR, CCPA, and AdSense. Covers cookies, analytics, and programmatic advertising.', 'adtech-pro' ),
				'icon'  => 'privacy',
			),
			'cookie' => array(
				'slug'  => 'cookie-policy',
				'title' => __( 'Cookie Policy', 'adtech-pro' ),
				'desc'  => __( 'Detailed cookie disclosure for ePrivacy Directive, GDPR, and ad-tech compliance.', 'adtech-pro' ),
				'icon'  => 'admin-settings',
			),
		);
	}

	/**
	 * Render admin page
	 */
	public static function render_admin_page() {
		$page_types = self::get_page_types();
		?>
		<div class="wrap HTG-admin-wrap">
			<div class="HTG-admin-header">
				<h1>
					<span class="dashicons dashicons-shield"></span>
					<?php esc_html_e( 'Legal Pages', 'adtech-pro' ); ?>
				</h1>
				<p class="HTG-admin-tagline"><?php esc_html_e( 'Generate GDPR / CCPA / AdSense-compliant legal pages', 'adtech-pro' ); ?></p>
			</div>

			<div class="HTG-admin-row">
				<div style="width: 100%; padding: 0 40px;">
					<div class="HTG-legal-pages-grid">
						<?php foreach ( $page_types as $key => $type ) :
							$existing = get_page_by_path( $type['slug'] );
						?>
						<div class="HTG-legal-page-card">
							<div class="HTG-legal-page-icon">
								<span class="dashicons dashicons-<?php echo esc_attr( $type['icon'] ); ?>"></span>
							</div>
							<h3><?php echo esc_html( $type['title'] ); ?></h3>
							<p><?php echo esc_html( $type['desc'] ); ?></p>

							<?php if ( $existing ) : ?>
								<div class="HTG-page-exists">
									<span class="dashicons dashicons-yes-alt"></span>
									<?php esc_html_e( 'Page exists', 'adtech-pro' ); ?>
									<div style="margin-top: 8px;">
										<a href="<?php echo esc_url( get_permalink( $existing->ID ) ); ?>" target="_blank"><?php esc_html_e( 'View', 'adtech-pro' ); ?></a>
										&nbsp;|&nbsp;
										<a href="<?php echo esc_url( get_edit_post_link( $existing->ID ) ); ?>"><?php esc_html_e( 'Edit', 'adtech-pro' ); ?></a>
									</div>
								</div>
							<?php else : ?>
								<form method="post" style="margin-top: 15px;">
									<?php wp_nonce_field( 'HTG_generate_legal_page', 'HTG_legal_nonce' ); ?>
									<input type="hidden" name="page_type" value="<?php echo esc_attr( $key ); ?>">
									<button type="submit" name="generate_page" class="button button-primary"><?php esc_html_e( 'Generate Page', 'adtech-pro' ); ?></button>
								</form>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
					</div>

					<div class="HTG-legal-notice">
						<h3 style="display: flex; align-items: center; gap: 8px;">
							<span class="dashicons dashicons-warning" style="color: #f59e0b;"></span>
							<?php esc_html_e( 'Important Notice', 'adtech-pro' ); ?>
						</h3>
						<p><?php esc_html_e( 'These pages are production-ready templates pre-filled for programmatic advertising publishers using Google AdSense, AdX, and Authorized Buyers. You MUST replace placeholder text (e.g. [Your Company], [your-email@example.com]) with your actual business information. Consult a qualified attorney for jurisdiction-specific requirements.', 'adtech-pro' ); ?></p>
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
			'cookie' => array(
				'title'   => 'Cookie Policy',
				'content' => self::get_cookie_content( $site_name, $site_url ),
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
<p>The information provided by <strong>{$site_name}</strong> ("{$site_url}") is for general informational purposes only. All information on the Site is provided in good faith; however, we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the Site.</p>

<p><strong>UNDER NO CIRCUMSTANCE SHALL WE HAVE ANY LIABILITY TO YOU FOR ANY LOSS OR DAMAGE OF ANY KIND INCURRED AS A RESULT OF THE USE OF THE SITE OR RELIANCE ON ANY INFORMATION PROVIDED ON THE SITE. YOUR USE OF THE SITE AND YOUR RELIANCE ON ANY INFORMATION ON THE SITE IS SOLELY AT YOUR OWN RISK.</strong></p>

<h2>No Professional Advice</h2>
<p>The Site cannot and does not contain financial, legal, medical, or other professional advice. The information is provided for general informational and educational purposes only and is not a substitute for professional advice. Before taking any actions based on information found on this Site, we encourage you to consult with the appropriate professionals. We do not provide any kind of professional advice.</p>

<h2>Advertising Disclaimer</h2>
<p>This Site displays advertisements served by third-party advertising networks, including Google AdSense, Google Ad Exchange (AdX), and Google Authorized Buyers. These advertisements are clearly distinguishable from editorial content. We do not endorse or guarantee any products, services, or claims made in advertisements displayed on this Site. Advertisements are served based on automated algorithms and may use cookies and tracking technologies as described in our <a href="{$site_url}/privacy-policy/">Privacy Policy</a> and <a href="{$site_url}/cookie-policy/">Cookie Policy</a>.</p>

<p>The display of third-party advertisements on our Site does not constitute an endorsement, guarantee, or recommendation by {$site_name}.</p>

<h2>Third-Party Links</h2>
<p>The Site may contain links to third-party websites or content belonging to or originating from third parties. Such external links are not investigated, monitored, or checked for accuracy, adequacy, validity, reliability, availability, or completeness by us.</p>

<p>WE DO NOT WARRANT, ENDORSE, GUARANTEE, OR ASSUME RESPONSIBILITY FOR THE ACCURACY OR RELIABILITY OF ANY INFORMATION OFFERED BY THIRD-PARTY WEBSITES LINKED THROUGH THE SITE OR ANY WEBSITE OR FEATURE LINKED IN ANY BANNER OR OTHER ADVERTISING.</p>

<h2>Errors and Omissions</h2>
<p>While we have made every attempt to ensure that the information contained on this Site has been obtained from reliable sources, {$site_name} is not responsible for any errors or omissions or for the results obtained from the use of this information. All information on this Site is provided "as is," with no guarantee of completeness, accuracy, timeliness, or of the results obtained from the use of this information.</p>

<h2>Fair Use Disclaimer</h2>
<p>This Site may contain copyrighted material the use of which has not always been specifically authorized by the copyright owner. We make such material available for criticism, comment, news reporting, teaching, scholarship, or research. We believe this constitutes a "fair use" of any such copyrighted material as provided for in section 107 of the United States Copyright Law.</p>

<h2>Changes</h2>
<p>We reserve the right to modify this Disclaimer at any time without prior notice. Your continued use of the Site following any changes indicates your acceptance of the new terms.</p>

<h2>Contact Us</h2>
<p>If you have any questions about this Disclaimer, please contact us at <strong>[your-email@example.com]</strong>.</p>
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
<p><strong>{$site_name}</strong> is an independent, advertising-supported content publisher. To support our ability to provide free content to our users, we earn revenue through multiple advertising and affiliate channels. This page explains how advertising works on our Site and how it may affect the content you see.</p>

<h2>How We Earn Revenue</h2>
<p>We generate revenue through the following channels:</p>

<h3>Programmatic Display Advertising</h3>
<p>We use automated advertising technology to display advertisements on our Site. Ads are served through:</p>
<ul>
<li><strong>Google AdSense:</strong> Google's contextual advertising network that serves display ads based on page content and user interests.</li>
<li><strong>Google Ad Exchange (AdX):</strong> A programmatic marketplace where advertisers bid in real-time auctions for our ad inventory.</li>
<li><strong>Google Authorized Buyers:</strong> A vetted network of third-party advertisers that participate in real-time bidding through Google Ad Manager.</li>
<li><strong>Header Bidding (Prebid.js):</strong> Multiple demand-side platforms compete simultaneously for ad impressions before the primary ad server is called.</li>
</ul>

<p>These advertising technologies use cookies and similar tracking methods to deliver relevant ads. For full details, see our <a href="{$site_url}/privacy-policy/">Privacy Policy</a> and <a href="{$site_url}/cookie-policy/">Cookie Policy</a>.</p>

<h3>Affiliate Relationships</h3>
<p>{$site_name} participates in various affiliate marketing programs. We may earn commissions when you click on affiliate links and make a purchase. Our affiliate partners include, but are not limited to:</p>
<ul>
<li>Amazon Associates Program</li>
<li>Various CPA/CPC Affiliate Networks</li>
</ul>

<h3>Sponsored Content</h3>
<p>Occasionally, we may publish content that is sponsored by a third party. All sponsored content is clearly labeled with a "Sponsored" or "Paid Partnership" tag. Sponsored articles are reviewed by our editorial team to ensure quality and relevance to our audience.</p>

<h2>Compensation Disclosure</h2>
<p>We may receive compensation from companies whose products or services are mentioned, reviewed, or advertised on {$site_name}. This compensation may be in the form of:</p>
<ul>
<li>Cost-per-click (CPC) or cost-per-impression (CPM) advertising revenue</li>
<li>Affiliate commissions on referred sales</li>
<li>Flat-fee sponsorships</li>
<li>Free products or services for review purposes</li>
</ul>

<p>Compensation may influence which products or services appear on our Site and the prominence of their placement. However, it does not influence our editorial opinions or recommendations.</p>

<h2>Editorial Independence</h2>
<p>Our editorial content is produced independently from our advertising and affiliate operations. Compensation does not determine the content, topics, or editorial opinions expressed on this Site. Product reviews and recommendations reflect our honest assessment and are not influenced by advertiser relationships.</p>

<h2>Advertising Practices</h2>
<ul>
<li>All advertisements are clearly distinguishable from editorial content.</li>
<li>We do not accept advertisements for illegal products or services.</li>
<li>We reserve the right to reject any advertisement at our sole discretion.</li>
<li>Ad placement is determined algorithmically and may vary by user, device, and location.</li>
<li>Advertisers may use cookies and tracking technologies as disclosed in our Privacy Policy.</li>
</ul>

<h2>Third-Party Links</h2>
<p>Our Site may contain links to external websites. We are not responsible for the content, accuracy, or privacy practices of third-party sites. Clicking on an external link or ad may redirect you away from our Site.</p>

<h2>FTC Compliance</h2>
<p>This disclosure is provided in accordance with the Federal Trade Commission's (FTC) guidelines on endorsements and testimonials. We are committed to transparent and honest disclosure of all material connections between {$site_name} and any companies whose products or services we feature.</p>

<h2>Questions</h2>
<p>If you have any questions regarding this disclosure, please contact us at <strong>[your-email@example.com]</strong>.</p>
HTML;
	}

	/**
	 * Get terms content
	 */
	private static function get_terms_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Effective Date:</strong> {$date}<br><strong>Last Updated:</strong> {$date}</p>

<p>Please read these Terms of Service ("<strong>Terms</strong>") carefully before using {$site_url} (the "<strong>Site</strong>") operated by <strong>{$site_name}</strong> ("<strong>we</strong>," "<strong>us</strong>," or "<strong>our</strong>").</p>

<h2>1. Agreement to Terms</h2>
<p>By accessing or using this Site, you agree to be bound by these Terms and our <a href="{$site_url}/privacy-policy/">Privacy Policy</a>. If you do not agree to these Terms, you must not use the Site.</p>

<h2>2. Use License</h2>
<p>We grant you a limited, non-exclusive, non-transferable, revocable license to access and use the Site for personal, non-commercial purposes. Under this license you may not:</p>
<ul>
<li>Modify, copy, or create derivative works from our content</li>
<li>Use the materials for any commercial purpose without prior written consent</li>
<li>Attempt to decompile, reverse engineer, or extract source code from any software on the Site</li>
<li>Remove any copyright, trademark, or other proprietary notices</li>
<li>Transfer the materials to another person or mirror them on any other server</li>
<li>Use automated scripts, bots, or scrapers to access the Site</li>
</ul>

<h2>3. Advertising and Monetization</h2>
<p>The Site is an advertising-supported publication. By using the Site, you acknowledge and agree that:</p>
<ul>
<li>The Site displays third-party advertisements served through programmatic advertising platforms including Google AdSense, Google Ad Exchange (AdX), Google Authorized Buyers, and header bidding partners.</li>
<li>Advertisements are targeted using cookies and tracking technologies as described in our <a href="{$site_url}/privacy-policy/">Privacy Policy</a> and <a href="{$site_url}/cookie-policy/">Cookie Policy</a>.</li>
<li>You will not use ad-blocking software or technology to circumvent, block, or interfere with advertisements displayed on the Site. Ad-blocking may degrade Site functionality.</li>
<li>You will not click on advertisements fraudulently, use automated tools to generate impressions or clicks, or engage in any activity that constitutes invalid traffic (IVT) under Google's policies.</li>
<li>Advertising content is provided by third parties and we do not endorse the products or services advertised.</li>
</ul>

<h2>4. Intellectual Property</h2>
<p>All content on the Site — including text, graphics, logos, images, audio/video, and software — is the property of {$site_name} or its content suppliers and is protected by copyright, trademark, and other intellectual property laws. Unauthorized reproduction, distribution, or modification of any content is strictly prohibited.</p>

<h2>5. User-Generated Content</h2>
<p>Users may submit comments and other content. By posting content on the Site, you:</p>
<ul>
<li>Grant us a non-exclusive, royalty-free, perpetual, worldwide license to use, reproduce, modify, and display your content.</li>
<li>Represent that your content does not violate any third-party rights or applicable law.</li>
<li>Agree not to post content that is defamatory, obscene, threatening, discriminatory, or otherwise unlawful.</li>
</ul>
<p>We reserve the right to remove any user content at our sole discretion without notice.</p>

<h2>6. Disclaimer of Warranties</h2>
<p>THE SITE AND ALL CONTENT ARE PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS. {$site_name} MAKES NO WARRANTIES, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. WE DO NOT WARRANT THAT THE SITE WILL BE UNINTERRUPTED, ERROR-FREE, OR FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.</p>

<h2>7. Limitation of Liability</h2>
<p>TO THE MAXIMUM EXTENT PERMITTED BY LAW, IN NO EVENT SHALL {$site_name}, ITS OFFICERS, DIRECTORS, EMPLOYEES, OR AGENTS BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES ARISING OUT OF OR RELATED TO YOUR USE OF THE SITE, INCLUDING BUT NOT LIMITED TO DAMAGES FOR LOSS OF PROFITS, DATA, GOODWILL, OR OTHER INTANGIBLE LOSSES, EVEN IF WE HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.</p>

<h2>8. Third-Party Links and Services</h2>
<p>The Site may contain links to third-party websites, products, or services. We are not responsible for the content, accuracy, or practices of any third-party sites. Use of linked sites is at your own risk and subject to the terms and privacy policies of those sites.</p>

<h2>9. Prohibited Activities</h2>
<p>When using the Site, you agree not to:</p>
<ul>
<li>Violate any applicable laws or regulations</li>
<li>Interfere with or disrupt the Site's infrastructure or security</li>
<li>Engage in ad fraud, click fraud, or impression fraud</li>
<li>Scrape, data-mine, or harvest content without authorization</li>
<li>Impersonate any person or entity</li>
<li>Transmit malware, viruses, or other harmful code</li>
<li>Attempt to gain unauthorized access to any portion of the Site</li>
</ul>

<h2>10. Indemnification</h2>
<p>You agree to indemnify and hold harmless {$site_name} and its affiliates, officers, directors, employees, and agents from any claims, damages, losses, liabilities, costs, or expenses (including reasonable attorneys' fees) arising from your use of the Site or your violation of these Terms.</p>

<h2>11. Privacy</h2>
<p>Your use of the Site is also governed by our <a href="{$site_url}/privacy-policy/">Privacy Policy</a>, which describes how we collect, use, and share your personal information, and our <a href="{$site_url}/cookie-policy/">Cookie Policy</a>, which details our use of cookies and tracking technologies for analytics and advertising purposes.</p>

<h2>12. Modifications</h2>
<p>{$site_name} reserves the right to modify these Terms at any time. Changes take effect immediately upon posting. Your continued use of the Site after changes constitutes acceptance of the revised Terms. We encourage you to review these Terms periodically.</p>

<h2>13. Termination</h2>
<p>We may terminate or suspend your access to the Site at any time, without prior notice or liability, for any reason, including breach of these Terms.</p>

<h2>14. Severability</h2>
<p>If any provision of these Terms is held to be unenforceable or invalid, that provision will be enforced to the maximum extent possible, and the remaining provisions will remain in full force and effect.</p>

<h2>15. Governing Law</h2>
<p>These Terms are governed by and construed in accordance with the laws of [Your Jurisdiction]. You irrevocably submit to the exclusive jurisdiction of the courts in that location for the resolution of any disputes.</p>

<h2>16. Contact Us</h2>
<p>If you have any questions about these Terms, please contact us at <strong>[your-email@example.com]</strong>.</p>
HTML;
	}

	/**
	 * Get privacy content — Full GDPR / CCPA / AdTech compliant
	 */
	private static function get_privacy_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Effective Date:</strong> {$date}<br><strong>Last Updated:</strong> {$date}</p>

<p>Your privacy is important to us. This Privacy Policy explains how <strong>{$site_name}</strong> ("<strong>we</strong>," "<strong>us</strong>," or "<strong>our</strong>") collects, uses, shares, and protects information when you visit <strong>{$site_url}</strong> (the "<strong>Site</strong>"). This policy applies to all visitors, users, and others who access the Site.</p>

<p>By using the Site, you agree to the collection and use of information in accordance with this policy. If you do not agree, please discontinue use of the Site immediately.</p>

<h2>1. Information We Collect</h2>

<h3>1.1 Information You Provide Voluntarily</h3>
<ul>
<li><strong>Contact Information:</strong> Name and email address when you subscribe to our newsletter, fill out a contact form, or leave a comment.</li>
<li><strong>Comments:</strong> When you post a comment, we collect the data shown in the comments form, your IP address, and browser user-agent string to help spam detection.</li>
<li><strong>Account Data:</strong> If you create an account, we collect your username, email address, and any profile information you choose to provide.</li>
</ul>

<h3>1.2 Information Collected Automatically</h3>
<p>When you visit the Site, we and our third-party partners automatically collect certain information using cookies, pixels, web beacons, and similar technologies:</p>
<ul>
<li><strong>Device &amp; Browser Data:</strong> IP address, browser type and version, operating system, device type, screen resolution, and language preferences.</li>
<li><strong>Usage Data:</strong> Pages visited, time spent on pages, click-through data, referring/exit URLs, and scrolling behavior.</li>
<li><strong>Location Data:</strong> Approximate geographic location derived from your IP address.</li>
<li><strong>Advertising Identifiers:</strong> Mobile advertising IDs (IDFA, GAID) and cookie-based identifiers used for ad personalization and frequency capping.</li>
</ul>

<h3>1.3 Information from Third Parties</h3>
<p>We may receive information about you from third-party advertising partners, analytics providers, and social media platforms when you interact with our content on those platforms.</p>

<h2>2. Cookies and Tracking Technologies</h2>

<p>We use cookies and similar technologies extensively. For a detailed breakdown of every cookie category and how to manage them, please see our <a href="{$site_url}/cookie-policy/">Cookie Policy</a>.</p>

<h3>2.1 Categories of Cookies We Use</h3>
<ul>
<li><strong>Strictly Necessary Cookies:</strong> Required for the Site to function (e.g., session management, security, load balancing). These cannot be disabled.</li>
<li><strong>Analytics Cookies:</strong> Help us understand how visitors use the Site. We use <strong>Google Analytics (GA4)</strong>, which collects anonymized usage data. Google Analytics uses cookies such as <code>_ga</code>, <code>_ga_*</code>, and <code>_gid</code> to distinguish users and throttle request rates.</li>
<li><strong>Advertising Cookies:</strong> Used by us and our advertising partners to deliver personalized advertisements. These cookies track your browsing activity across websites to build a profile of your interests. Our advertising partners include:
  <ul>
    <li><strong>Google AdSense</strong> — serves display advertisements on our Site.</li>
    <li><strong>Google Ad Exchange (AdX)</strong> — programmatic auction marketplace connecting our ad inventory with demand-side platforms (DSPs).</li>
    <li><strong>Google Authorized Buyers</strong> — vetted third-party buyers that bid on our ad inventory in real-time auctions via Google Ad Manager.</li>
    <li><strong>Prebid.js Header Bidding Partners</strong> — multiple demand partners that compete in real-time for ad impressions before the ad server call.</li>
  </ul>
</li>
<li><strong>Functionality Cookies:</strong> Remember your preferences (e.g., dark mode, quiz responses, comment author details).</li>
</ul>

<h3>2.2 Google-Specific Technologies</h3>
<p>Google uses cookies (such as the <code>NID</code>, <code>IDE</code>, <code>DSID</code>, <code>ANID</code>, and <code>1P_JAR</code> cookies) to serve ads based on your prior visits to our Site or other websites. Google's advertising cookies enable Google and its partners to serve ads based on your visit to our Site and/or other sites on the Internet.</p>

<p>You may opt out of personalized advertising by visiting:</p>
<ul>
<li><a href="https://adssettings.google.com/" target="_blank" rel="noopener">Google Ads Settings</a></li>
<li><a href="https://optout.aboutads.info/" target="_blank" rel="noopener">Digital Advertising Alliance (DAA) opt-out</a></li>
<li><a href="https://www.youronlinechoices.eu/" target="_blank" rel="noopener">European Interactive Digital Advertising Alliance (EDAA)</a></li>
<li><a href="https://optout.networkadvertising.org/" target="_blank" rel="noopener">Network Advertising Initiative (NAI) opt-out</a></li>
</ul>

<h2>3. How We Use Your Information</h2>
<p>We process your information for the following purposes and legal bases (under GDPR):</p>

<table>
<thead><tr><th>Purpose</th><th>Legal Basis (GDPR)</th></tr></thead>
<tbody>
<tr><td>Operate and maintain the Site</td><td>Legitimate interest</td></tr>
<tr><td>Respond to comments and inquiries</td><td>Legitimate interest / Contract</td></tr>
<tr><td>Send newsletters (when opted in)</td><td>Consent</td></tr>
<tr><td>Analyze site usage via Google Analytics</td><td>Legitimate interest / Consent</td></tr>
<tr><td>Serve personalized advertisements via Google AdSense, AdX, and Authorized Buyers</td><td>Consent (EEA) / Legitimate interest (non-EEA)</td></tr>
<tr><td>Fraud prevention and security</td><td>Legitimate interest</td></tr>
<tr><td>Comply with legal obligations</td><td>Legal obligation</td></tr>
</tbody>
</table>

<h2>4. How We Share Your Information</h2>
<p>We do not sell your personal information. We share data with the following categories of recipients:</p>
<ul>
<li><strong>Google LLC:</strong> For advertising (AdSense, AdX, Authorized Buyers) and analytics (Google Analytics). Google processes data as an independent controller. See <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Google's Privacy Policy</a>.</li>
<li><strong>Header Bidding Partners:</strong> Demand-side platforms that participate in real-time bidding auctions receive bid request data (device type, approximate location, page URL, cookie IDs). These partners are bound by their own privacy policies and applicable law.</li>
<li><strong>Hosting &amp; CDN Providers:</strong> Our hosting infrastructure may process server logs containing IP addresses and request data.</li>
<li><strong>Legal Requirements:</strong> We may disclose information if required by law, court order, or governmental authority.</li>
</ul>

<h2>5. International Data Transfers</h2>
<p>Your information may be transferred to and processed in countries outside your country of residence, including the United States. Where required by law (e.g., GDPR), we ensure appropriate safeguards are in place such as Standard Contractual Clauses (SCCs) approved by the European Commission, or adequacy decisions.</p>

<h2>6. Data Retention</h2>
<ul>
<li><strong>Comments:</strong> Retained indefinitely so we can recognize and auto-approve follow-up comments. You may request deletion at any time.</li>
<li><strong>Analytics Data:</strong> Google Analytics data is retained for 14 months, after which it is automatically deleted.</li>
<li><strong>Advertising Data:</strong> Advertising cookies typically expire within 13 months. See our Cookie Policy for specific durations.</li>
<li><strong>Newsletter Subscriptions:</strong> Retained until you unsubscribe or request deletion.</li>
<li><strong>Server Logs:</strong> Retained for up to 90 days for security and diagnostic purposes.</li>
</ul>

<h2>7. Your Rights Under GDPR (EEA/UK Residents)</h2>
<p>If you are located in the European Economic Area (EEA) or the United Kingdom, you have the following rights under the General Data Protection Regulation (GDPR):</p>
<ul>
<li><strong>Right of Access (Art. 15):</strong> Request a copy of the personal data we hold about you.</li>
<li><strong>Right to Rectification (Art. 16):</strong> Request correction of inaccurate or incomplete data.</li>
<li><strong>Right to Erasure (Art. 17):</strong> Request deletion of your personal data ("right to be forgotten").</li>
<li><strong>Right to Restrict Processing (Art. 18):</strong> Request that we limit how we use your data.</li>
<li><strong>Right to Data Portability (Art. 20):</strong> Receive your data in a structured, machine-readable format.</li>
<li><strong>Right to Object (Art. 21):</strong> Object to processing based on legitimate interests, including profiling for advertising purposes.</li>
<li><strong>Right to Withdraw Consent (Art. 7):</strong> Where processing is based on consent, you may withdraw it at any time without affecting the lawfulness of prior processing.</li>
<li><strong>Right to Lodge a Complaint:</strong> You have the right to file a complaint with your local Data Protection Authority (DPA).</li>
</ul>
<p>To exercise any of these rights, contact us at <strong>[your-email@example.com]</strong>. We will respond within 30 days.</p>

<h2>8. Your Rights Under CCPA (California Residents)</h2>
<p>If you are a California resident, the California Consumer Privacy Act (CCPA) and the California Privacy Rights Act (CPRA) grant you the following rights:</p>

<h3>8.1 Right to Know</h3>
<p>You have the right to request that we disclose the categories and specific pieces of personal information we have collected about you in the past 12 months, including:</p>
<ul>
<li>Categories of personal information collected (identifiers, internet activity, geolocation, inferences).</li>
<li>Sources of the information (directly from you, automatically via cookies, from advertising partners).</li>
<li>Business purpose for collecting the information.</li>
<li>Categories of third parties with whom we share the information.</li>
</ul>

<h3>8.2 Right to Delete</h3>
<p>You may request deletion of your personal information, subject to certain exceptions (e.g., legal compliance, security, completing a transaction).</p>

<h3>8.3 Right to Opt Out of Sale / Sharing</h3>
<p>We do <strong>not sell</strong> your personal information in the traditional sense. However, the use of advertising cookies by our partners (Google AdSense, AdX, Authorized Buyers) may constitute "sharing" under the CPRA. You may opt out by:</p>
<ul>
<li>Clicking "Do Not Sell or Share My Personal Information" (if displayed on the Site).</li>
<li>Enabling the <strong>Global Privacy Control (GPC)</strong> signal in your browser.</li>
<li>Visiting the opt-out links listed in Section 2.2 above.</li>
</ul>

<h3>8.4 Right to Non-Discrimination</h3>
<p>We will not discriminate against you for exercising any of your CCPA/CPRA rights.</p>

<p>To submit a request, email us at <strong>[your-email@example.com]</strong> with the subject line "CCPA Request." We will verify your identity and respond within 45 days.</p>

<h2>9. Children's Privacy</h2>
<p>Our Site is not directed to individuals under the age of 16 (or 13 in the US under COPPA). We do not knowingly collect personal information from children. If we become aware that we have collected personal data from a child without parental consent, we will take steps to delete that information promptly. If you believe a child has provided us with personal data, please contact us immediately.</p>

<h2>10. Do Not Track (DNT) Signals</h2>
<p>Some browsers transmit "Do Not Track" signals. We currently do not respond to DNT signals. However, we do honor the <strong>Global Privacy Control (GPC)</strong> signal as an opt-out of sale/sharing under the CCPA/CPRA.</p>

<h2>11. Security</h2>
<p>We implement industry-standard security measures including HTTPS encryption, secure server infrastructure, and access controls to protect your information. However, no method of transmission over the Internet is 100% secure, and we cannot guarantee absolute security.</p>

<h2>12. Changes to This Privacy Policy</h2>
<p>We may update this Privacy Policy from time to time. We will notify you of material changes by posting the updated policy on this page and revising the "Last Updated" date. Your continued use of the Site after changes are posted constitutes acceptance of the revised policy.</p>

<h2>13. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, wish to exercise your data rights, or need to reach our Data Protection contact, please email us at:</p>
<p><strong>[your-email@example.com]</strong></p>
<p>{$site_name}<br>[Your Company Address]<br>[City, State/Country, ZIP]</p>
HTML;
	}

	/**
	 * Get cookie policy content — ePrivacy / GDPR / AdTech compliant
	 */
	private static function get_cookie_content( $site_name, $site_url ) {
		$date = date( 'F j, Y' );
		return <<<HTML
<p><strong>Effective Date:</strong> {$date}<br><strong>Last Updated:</strong> {$date}</p>

<p>This Cookie Policy explains how <strong>{$site_name}</strong> ("{$site_url}") uses cookies and similar tracking technologies when you visit our website. It should be read alongside our <a href="{$site_url}/privacy-policy/">Privacy Policy</a>.</p>

<h2>1. What Are Cookies?</h2>
<p>Cookies are small text files stored on your device (computer, tablet, or mobile) when you visit a website. They are widely used to make websites work efficiently, provide analytics, and deliver personalized advertising. Cookies can be "first-party" (set by us) or "third-party" (set by our partners).</p>

<h2>2. How We Use Cookies</h2>
<p>We use cookies for the following purposes:</p>
<ul>
<li><strong>Essential Operation:</strong> Enable core site functionality such as page navigation, secure areas, and session management.</li>
<li><strong>Analytics &amp; Performance:</strong> Understand how visitors interact with our Site so we can improve content and user experience.</li>
<li><strong>Advertising &amp; Monetization:</strong> Deliver, measure, and optimize advertisements served through programmatic advertising platforms.</li>
<li><strong>Preferences &amp; Functionality:</strong> Remember your settings and choices (e.g., theme preferences, comment author details).</li>
</ul>

<h2>3. Cookie Categories</h2>

<h3>3.1 Strictly Necessary Cookies</h3>
<p>These cookies are essential for the Site to function. They cannot be switched off.</p>
<table>
<thead><tr><th>Cookie</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
<tr><td><code>wordpress_sec_*</code></td><td>{$site_name}</td><td>Authentication &amp; security</td><td>Session</td></tr>
<tr><td><code>wordpress_logged_in_*</code></td><td>{$site_name}</td><td>Login session management</td><td>Session</td></tr>
<tr><td><code>wp-settings-*</code></td><td>{$site_name}</td><td>WordPress admin preferences</td><td>1 year</td></tr>
<tr><td><code>comment_author_*</code></td><td>{$site_name}</td><td>Pre-fill comment forms</td><td>1 year</td></tr>
</tbody>
</table>

<h3>3.2 Analytics Cookies</h3>
<p>We use <strong>Google Analytics (GA4)</strong> to collect anonymized usage data.</p>
<table>
<thead><tr><th>Cookie</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
<tr><td><code>_ga</code></td><td>Google</td><td>Distinguishes unique users</td><td>2 years</td></tr>
<tr><td><code>_ga_*</code></td><td>Google</td><td>Maintains session state (GA4)</td><td>2 years</td></tr>
<tr><td><code>_gid</code></td><td>Google</td><td>Distinguishes users (24h)</td><td>24 hours</td></tr>
<tr><td><code>_gat</code></td><td>Google</td><td>Throttle request rate</td><td>1 minute</td></tr>
</tbody>
</table>
<p>Learn more: <a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener">Google's Cookie Policy</a></p>

<h3>3.3 Advertising Cookies</h3>
<p>We monetize our Site through programmatic advertising. The following advertising platforms set cookies to deliver, personalize, and measure advertisements:</p>

<h4>Google AdSense &amp; Google Ad Exchange (AdX)</h4>
<table>
<thead><tr><th>Cookie</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
<tr><td><code>IDE</code></td><td>Google (doubleclick.net)</td><td>Serves targeted ads; tracks conversions</td><td>13 months</td></tr>
<tr><td><code>DSID</code></td><td>Google (doubleclick.net)</td><td>Identifies signed-in users for ad personalization</td><td>2 weeks</td></tr>
<tr><td><code>NID</code></td><td>Google</td><td>Stores user preferences and ad personalization</td><td>6 months</td></tr>
<tr><td><code>ANID</code></td><td>Google</td><td>Ad personalization for signed-in users</td><td>13 months</td></tr>
<tr><td><code>1P_JAR</code></td><td>Google</td><td>Ad personalization and analytics</td><td>1 month</td></tr>
<tr><td><code>CONSENT</code></td><td>Google</td><td>Stores user consent state</td><td>20 years</td></tr>
<tr><td><code>test_cookie</code></td><td>Google (doubleclick.net)</td><td>Checks browser cookie support</td><td>15 minutes</td></tr>
</tbody>
</table>

<h4>Google Authorized Buyers</h4>
<p>Google Authorized Buyers is a program where vetted advertising companies bid on ad inventory via Google Ad Manager in real-time auctions. These buyers may set their own cookies. The data shared with Authorized Buyers during a bid request includes: device type, approximate location, page URL, and cookie identifiers. Google maintains a list of certified Authorized Buyers subject to Google's data protection requirements.</p>
<p>Learn more: <a href="https://support.google.com/admanager/answer/9012903" target="_blank" rel="noopener">Google Authorized Buyers</a></p>

<h4>Prebid.js Header Bidding Partners</h4>
<p>We use Prebid.js to conduct header bidding auctions. Multiple demand-side platforms (DSPs) and supply-side platforms (SSPs) may set cookies to participate in these auctions. Each partner operates under its own privacy policy. Common partners include AppNexus/Xandr, Index Exchange, Rubicon Project/Magnite, OpenX, PubMatic, and others.</p>

<h3>3.4 Functionality Cookies</h3>
<table>
<thead><tr><th>Cookie</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
<tr><td><code>HTG_quiz_*</code></td><td>{$site_name}</td><td>Stores quiz/poll votes</td><td>30 days</td></tr>
<tr><td><code>htg_dark_mode</code></td><td>{$site_name}</td><td>Theme preference</td><td>1 year</td></tr>
</tbody>
</table>

<h2>4. How to Manage Cookies</h2>
<p>You have several options to control cookies:</p>

<h3>4.1 Browser Settings</h3>
<p>Most browsers allow you to block or delete cookies. Instructions for common browsers:</p>
<ul>
<li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
<li><a href="https://support.mozilla.org/kb/cookies-information-websites-store-on-your-computer" target="_blank" rel="noopener">Mozilla Firefox</a></li>
<li><a href="https://support.apple.com/guide/safari/manage-cookies-sfri11471" target="_blank" rel="noopener">Apple Safari</a></li>
<li><a href="https://support.microsoft.com/help/4027947" target="_blank" rel="noopener">Microsoft Edge</a></li>
</ul>
<p><strong>Note:</strong> Blocking all cookies may impair Site functionality and prevent features like commenting and saved preferences from working.</p>

<h3>4.2 Opt-Out of Personalized Advertising</h3>
<ul>
<li><a href="https://adssettings.google.com/" target="_blank" rel="noopener">Google Ads Settings</a></li>
<li><a href="https://optout.aboutads.info/" target="_blank" rel="noopener">DAA Opt-Out (US)</a></li>
<li><a href="https://www.youronlinechoices.eu/" target="_blank" rel="noopener">EDAA Opt-Out (EU)</a></li>
<li><a href="https://optout.networkadvertising.org/" target="_blank" rel="noopener">NAI Opt-Out</a></li>
</ul>

<h3>4.3 Global Privacy Control (GPC)</h3>
<p>We honor the Global Privacy Control signal. If your browser sends a GPC signal, we treat it as an opt-out of the sale or sharing of personal information under the CCPA/CPRA.</p>

<h2>5. Updates to This Cookie Policy</h2>
<p>We may update this Cookie Policy from time to time. Changes will be posted on this page with an updated "Last Updated" date.</p>

<h2>6. Contact Us</h2>
<p>If you have any questions about our use of cookies, please contact us at <strong>[your-email@example.com]</strong>.</p>
HTML;
	}
}

// Initialize publisher legal pages
HTG_Publisher_Legal::init();


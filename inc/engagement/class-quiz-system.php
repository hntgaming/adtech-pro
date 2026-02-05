<?php
/**
 * Interactive Quiz System
 * Engages readers and increases time on page
 *
 * @package HTG
 * @since 2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HTG_Quiz_System {

	/**
	 * Initialize quiz system
	 */
	public static function init() {
		add_shortcode( 'HTG_quiz', array( __CLASS__, 'render_quiz_shortcode' ) );
		add_action( 'wp_ajax_HTG_quiz_vote', array( __CLASS__, 'handle_quiz_vote' ) );
		add_action( 'wp_ajax_nopriv_HTG_quiz_vote', array( __CLASS__, 'handle_quiz_vote' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_quiz_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'save_quiz_meta_box' ) );
	}

	/**
	 * Add quiz meta box to posts
	 */
	public static function add_quiz_meta_box() {
		add_meta_box(
			'HTG_quiz_meta_box',
			__( 'Interactive Quiz', 'adtech-pro' ),
			array( __CLASS__, 'render_quiz_meta_box' ),
			'post',
			'normal',
			'high'
		);
	}

	/**
	 * Render quiz meta box
	 */
	public static function render_quiz_meta_box( $post ) {
		wp_nonce_field( 'HTG_quiz_meta_box', 'HTG_quiz_meta_box_nonce' );

		$quiz_enabled = get_post_meta( $post->ID, '_HTG_quiz_enabled', true );
		$quiz_question = get_post_meta( $post->ID, '_HTG_quiz_question', true );
		$quiz_options = get_post_meta( $post->ID, '_HTG_quiz_options', true );
		$quiz_position = get_post_meta( $post->ID, '_HTG_quiz_position', true );

		if ( empty( $quiz_options ) ) {
			$quiz_options = array( '', '', '', '' );
		}
		?>
		<div class="HTG-quiz-meta-box">
			<p>
				<label>
					<input type="checkbox" name="HTG_quiz_enabled" value="1" <?php checked( $quiz_enabled, '1' ); ?>>
					<strong><?php esc_html_e( 'Enable Interactive Quiz', 'adtech-pro' ); ?></strong>
				</label>
			</p>

			<p>
				<label for="HTG_quiz_question">
					<strong><?php esc_html_e( 'Quiz Question:', 'adtech-pro' ); ?></strong>
				</label><br>
				<input type="text" id="HTG_quiz_question" name="HTG_quiz_question" value="<?php echo esc_attr( $quiz_question ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'What do you think about this topic?', 'adtech-pro' ); ?>">
			</p>

			<p>
				<strong><?php esc_html_e( 'Answer Options (2-4 options):', 'adtech-pro' ); ?></strong>
			</p>

			<?php for ( $i = 0; $i < 4; $i++ ) : ?>
				<p>
					<label for="HTG_quiz_option_<?php echo esc_attr( $i ); ?>">
						<?php echo sprintf( __( 'Option %d:', 'adtech-pro' ), $i + 1 ); ?>
					</label><br>
					<input type="text" id="HTG_quiz_option_<?php echo esc_attr( $i ); ?>" name="HTG_quiz_options[]" value="<?php echo esc_attr( isset( $quiz_options[ $i ] ) ? $quiz_options[ $i ] : '' ); ?>" class="widefat" placeholder="<?php echo esc_attr( sprintf( __( 'Answer option %d', 'adtech-pro' ), $i + 1 ) ); ?>">
				</p>
			<?php endfor; ?>

			<p>
				<label for="HTG_quiz_position">
					<strong><?php esc_html_e( 'Quiz Position:', 'adtech-pro' ); ?></strong>
				</label><br>
				<select id="HTG_quiz_position" name="HTG_quiz_position" class="widefat">
					<option value="after_content" <?php selected( $quiz_position, 'after_content' ); ?>><?php esc_html_e( 'After Content', 'adtech-pro' ); ?></option>
					<option value="middle_content" <?php selected( $quiz_position, 'middle_content' ); ?>><?php esc_html_e( 'Middle of Content', 'adtech-pro' ); ?></option>
					<option value="before_comments" <?php selected( $quiz_position, 'before_comments' ); ?>><?php esc_html_e( 'Before Comments', 'adtech-pro' ); ?></option>
					<option value="shortcode" <?php selected( $quiz_position, 'shortcode' ); ?>><?php esc_html_e( 'Manual (use shortcode)', 'adtech-pro' ); ?></option>
				</select>
			</p>

			<p class="description">
				<?php esc_html_e( 'Shortcode:', 'adtech-pro' ); ?> <code>[HTG_quiz]</code>
			</p>
		</div>

		<style>
		.HTG-quiz-meta-box {
			background: #f8f9fa;
			padding: 20px;
			border-radius: 8px;
			margin-top: 10px;
		}
		.HTG-quiz-meta-box label {
			font-weight: 500;
		}
		</style>
		<?php
	}

	/**
	 * Save quiz meta box
	 */
	public static function save_quiz_meta_box( $post_id ) {
		if ( ! isset( $_POST['HTG_quiz_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['HTG_quiz_meta_box_nonce'], 'HTG_quiz_meta_box' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save quiz enabled
		$quiz_enabled = isset( $_POST['HTG_quiz_enabled'] ) ? '1' : '0';
		update_post_meta( $post_id, '_HTG_quiz_enabled', $quiz_enabled );

		// Save quiz question
		if ( isset( $_POST['HTG_quiz_question'] ) ) {
			update_post_meta( $post_id, '_HTG_quiz_question', sanitize_text_field( $_POST['HTG_quiz_question'] ) );
		}

		// Save quiz options
		if ( isset( $_POST['HTG_quiz_options'] ) && is_array( $_POST['HTG_quiz_options'] ) ) {
			$options = array_map( 'sanitize_text_field', $_POST['HTG_quiz_options'] );
			$options = array_filter( $options ); // Remove empty options
			update_post_meta( $post_id, '_HTG_quiz_options', $options );
		}

		// Save quiz position
		if ( isset( $_POST['HTG_quiz_position'] ) ) {
			update_post_meta( $post_id, '_HTG_quiz_position', sanitize_text_field( $_POST['HTG_quiz_position'] ) );
		}
	}

	/**
	 * Render quiz
	 */
	public static function render_quiz( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$quiz_enabled = get_post_meta( $post_id, '_HTG_quiz_enabled', true );
		
		if ( ! $quiz_enabled ) {
			return '';
		}

		$question = get_post_meta( $post_id, '_HTG_quiz_question', true );
		$options = get_post_meta( $post_id, '_HTG_quiz_options', true );

		if ( empty( $question ) || empty( $options ) || count( $options ) < 2 ) {
			return '';
		}

		$quiz_id = 'quiz-' . $post_id;
		$votes_meta = get_post_meta( $post_id, '_HTG_quiz_votes', true );
		
		if ( ! is_array( $votes_meta ) ) {
			$votes_meta = array();
		}

		$total_votes = array_sum( $votes_meta );
		$user_voted = isset( $_COOKIE[ 'HTG_quiz_' . $post_id ] );

		ob_start();
		?>
		<div class="HTG-quiz-container" id="<?php echo esc_attr( $quiz_id ); ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>">
			<div class="HTG-quiz-header">
				<span class="HTG-quiz-icon">📊</span>
				<h3 class="HTG-quiz-question"><?php echo esc_html( $question ); ?></h3>
			</div>

			<?php if ( ! $user_voted ) : ?>
				<div class="HTG-quiz-options">
					<?php foreach ( $options as $index => $option ) : ?>
						<button 
							class="HTG-quiz-option" 
							data-option-index="<?php echo esc_attr( $index ); ?>"
							data-option-text="<?php echo esc_attr( $option ); ?>"
						>
							<span class="HTG-quiz-option-text"><?php echo esc_html( $option ); ?></span>
							<span class="HTG-quiz-option-arrow">→</span>
						</button>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="HTG-quiz-results">
					<?php
					foreach ( $options as $index => $option ) :
						$option_votes = isset( $votes_meta[ $index ] ) ? intval( $votes_meta[ $index ] ) : 0;
						$percentage = $total_votes > 0 ? round( ( $option_votes / $total_votes ) * 100 ) : 0;
						$user_choice = isset( $_COOKIE[ 'HTG_quiz_' . $post_id ] ) && $_COOKIE[ 'HTG_quiz_' . $post_id ] == $index;
						?>
						<div class="HTG-quiz-result <?php echo $user_choice ? 'user-choice' : ''; ?>">
							<div class="HTG-quiz-result-header">
								<span class="HTG-quiz-result-text">
									<?php echo esc_html( $option ); ?>
									<?php if ( $user_choice ) : ?>
										<span class="HTG-quiz-your-vote">✓ <?php esc_html_e( 'Your vote', 'adtech-pro' ); ?></span>
									<?php endif; ?>
								</span>
								<span class="HTG-quiz-result-percentage"><?php echo esc_html( $percentage ); ?>%</span>
							</div>
							<div class="HTG-quiz-result-bar">
								<div class="HTG-quiz-result-fill" style="width: <?php echo esc_attr( $percentage ); ?>%"></div>
							</div>
							<div class="HTG-quiz-result-votes"><?php echo esc_html( number_format_i18n( $option_votes ) ); ?> <?php echo _n( 'vote', 'votes', $option_votes, 'adtech-pro' ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="HTG-quiz-footer">
				<span class="HTG-quiz-total-votes">
					<?php echo esc_html( number_format_i18n( $total_votes ) ); ?> <?php echo _n( 'vote', 'votes', $total_votes, 'adtech-pro' ); ?>
				</span>
				<?php if ( ! $user_voted ) : ?>
					<span class="HTG-quiz-prompt"><?php esc_html_e( 'Share your opinion!', 'adtech-pro' ); ?></span>
				<?php else : ?>
					<span class="HTG-quiz-thanks"><?php esc_html_e( 'Thanks for voting!', 'adtech-pro' ); ?> 🎉</span>
				<?php endif; ?>
			</div>

			<div class="HTG-quiz-loading" style="display: none;">
				<div class="HTG-quiz-spinner"></div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render quiz shortcode
	 */
	public static function render_quiz_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'id' => get_the_ID(),
		), $atts );

		return self::render_quiz( $atts['id'] );
	}

	/**
	 * Handle quiz vote via AJAX
	 */
	public static function handle_quiz_vote() {
		check_ajax_referer( 'HTG_quiz_nonce', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$option_index = isset( $_POST['option_index'] ) ? intval( $_POST['option_index'] ) : -1;

		if ( ! $post_id || $option_index < 0 ) {
			wp_send_json_error( array( 'message' => __( 'Invalid request', 'adtech-pro' ) ) );
		}

		// Check if user already voted
		if ( isset( $_COOKIE[ 'HTG_quiz_' . $post_id ] ) ) {
			wp_send_json_error( array( 'message' => __( 'You already voted', 'adtech-pro' ) ) );
		}

		// Get current votes
		$votes = get_post_meta( $post_id, '_HTG_quiz_votes', true );
		
		if ( ! is_array( $votes ) ) {
			$votes = array();
		}

		// Increment vote count
		if ( ! isset( $votes[ $option_index ] ) ) {
			$votes[ $option_index ] = 0;
		}
		$votes[ $option_index ]++;

		// Update votes
		update_post_meta( $post_id, '_HTG_quiz_votes', $votes );

		// Set cookie (expires in 1 year)
		setcookie( 'HTG_quiz_' . $post_id, $option_index, time() + YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );

		// Calculate results
		$options = get_post_meta( $post_id, '_HTG_quiz_options', true );
		$total_votes = array_sum( $votes );
		$results = array();

		foreach ( $options as $index => $option ) {
			$option_votes = isset( $votes[ $index ] ) ? intval( $votes[ $index ] ) : 0;
			$percentage = $total_votes > 0 ? round( ( $option_votes / $total_votes ) * 100 ) : 0;
			
			$results[] = array(
				'text'       => $option,
				'votes'      => $option_votes,
				'percentage' => $percentage,
				'is_user_choice' => $index == $option_index,
			);
		}

		wp_send_json_success( array(
			'results'     => $results,
			'total_votes' => $total_votes,
			'message'     => __( 'Thanks for voting!', 'adtech-pro' ),
		) );
	}

	/**
	 * Auto-insert quiz in content
	 */
	public static function auto_insert_quiz( $content ) {
		if ( ! is_single() ) {
			return $content;
		}

		global $post;
		
		$quiz_enabled = get_post_meta( $post->ID, '_HTG_quiz_enabled', true );
		$quiz_position = get_post_meta( $post->ID, '_HTG_quiz_position', true );

		if ( ! $quiz_enabled || 'shortcode' === $quiz_position ) {
			return $content;
		}

		$quiz_html = self::render_quiz( $post->ID );

		if ( empty( $quiz_html ) ) {
			return $content;
		}

		switch ( $quiz_position ) {
			case 'after_content':
				$content .= $quiz_html;
				break;

			case 'middle_content':
				$paragraphs = explode( '</p>', $content );
				$middle = floor( count( $paragraphs ) / 2 );
				
				if ( $middle > 0 ) {
					$paragraphs[ $middle ] = $quiz_html . $paragraphs[ $middle ];
				}
				
				$content = implode( '</p>', $paragraphs );
				break;

			case 'before_comments':
				// Will be handled by action hook in template
				break;
		}

		return $content;
	}
}

// Initialize quiz system
HTG_Quiz_System::init();

// Auto-insert quiz
add_filter( 'the_content', array( 'HTG_Quiz_System', 'auto_insert_quiz' ), 20 );

// Hook for before_comments position
add_action( 'HTG_before_comments', function() {
	if ( ! is_single() ) {
		return;
	}

	$quiz_position = get_post_meta( get_the_ID(), '_HTG_quiz_position', true );
	
	if ( 'before_comments' === $quiz_position ) {
		echo HTG_Quiz_System::render_quiz();
	}
} );


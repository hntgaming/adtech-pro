<?php

/**
 * Displays latest or category wised posts list.
 *
 */

class HTG_Popular_Posts extends WP_Widget {

	/* Register Widget with WordPress*/
	function __construct() {
		parent::__construct(
			'HTG_popular_posts', // Base ID
			esc_html__( 'H&T AdTech Pro: Popular Posts', 'HTG' ), // Name
			array( 'description' => esc_html__( 'Displays popular posts based on the comment count. Use this widget in the main sidebars.', 'HTG' ), ) // Args
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */

	public function form( $instance ) {
        
		$defaults = array(
			'title'		    =>	esc_html__( 'Popular Posts', 'HTG' ),
			'category'	    =>	'all',
			'number_posts'	=>  5,
            'date_range'    =>  ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'HTG' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label><?php esc_html_e( 'Category', 'HTG' ); ?></label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name('category'), 'selected' => $instance['category'], 'show_option_all' => 'Show popular posts from all categories' ) ); ?>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_posts' ); ?>"><?php esc_html_e( 'Number of posts:', 'HTG' ); ?></label>
			<input type="number" id="<?php echo $this->get_field_id( 'number_posts' ); ?>" name="<?php echo $this->get_field_name( 'number_posts' );?>" value="<?php echo absint( $instance['number_posts'] ) ?>" size="3"/> 
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'date_range' ); ?>"><?php esc_html_e( 'Enter the number of days to display popular posts:', 'HTG' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'date_range' ); ?>" name="<?php echo $this->get_field_name( 'date_range' ); ?>" type="text" value="<?php echo esc_attr( $instance['date_range'] ); ?>">
		</p>

	<?php

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );	
		$instance[ 'category' ]	= absint( $new_instance[ 'category' ] );
		$instance[ 'number_posts' ] = (int)$new_instance[ 'number_posts' ];
        $instance[ 'date_range' ] = ( ! empty( $new_instance['date_range'] ) ) ? (int)( $new_instance['date_range'] ) : '';
		return $instance;
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	
	public function widget( $args, $instance ) {
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';	
        $title = apply_filters( 'widget_title', $title , $instance, $this->id_base );
		$category = ( ! empty( $instance['category'] ) ) ? absint( $instance['category'] ) : 0;
		$number_posts = ( ! empty( $instance['number_posts'] ) ) ? absint( $instance['number_posts'] ) : 5; 
        $date_range = ( ! empty( $instance['date_range'] ) ) ? absint( $instance['date_range'] ) : '';

        $post_args = array(
            'cat' 					=>	$category,
            'posts_per_page' 		=>	$number_posts,
            'no_found_rows' 		=>  true,
            'post_status'           => 'publish', 
            'orderby'               => 'comment_count', 
            'order'                 => 'desc',
            'ignore_sticky_posts'   => 1, 
        );

        if( isset( $date_range ) && ! empty( $date_range ) ) {
            $post_args[ 'date_query' ] = array(
                array(
                    'after' => $date_range . ' days ago'
                )
            );
        }

		// Latest Posts
		$popular_posts = new WP_Query( $post_args );	

		echo $before_widget; ?>
		<div class="HTG-popular-posts">
		<?php
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
		?>

		
		<?php if( $popular_posts -> have_posts() ) : ?>	
			<?php while ( $popular_posts -> have_posts() ) : $popular_posts -> the_post(); ?>
					<div class="hms-post">
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="hms-thumb">
								<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">	
									<?php the_post_thumbnail( 'HTG-thumbnail' ); ?>
								</a>
							</div>
						<?php } ?>
						<div class="hms-details">
							<?php the_title( sprintf( '<h3 class="hms-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
							<p class="hms-meta"><?php echo HTG_posted_datetime(); ?></p>
						</div>
					</div><!-- .hms-post -->
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
        
        </div><!-- .HTG-category-posts -->


	<?php
		echo $after_widget;
	}

}

// Register single category posts widget
function HTG_register_popular_posts() {
    register_widget( 'HTG_Popular_Posts' );
}
add_action( 'widgets_init', 'HTG_register_popular_posts' );
<div class="hm-authorbox">

    <div class="hm-author-img">
        <?php echo get_avatar( get_the_author_meta( 'ID' ), '100' ); ?>
    </div>

    <div class="hm-author-content">
        <h4 class="author-name"><?php printf( esc_attr__( 'About %s', 'adtech-pro' ), get_the_author() );?></h4>
        <p class="author-description"><?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?></p>
        <a class="author-posts-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo esc_attr( get_the_author() ); ?>">
            <?php printf( esc_html__( 'View all posts by %s &rarr;', 'adtech-pro' ), esc_attr( get_the_author() ) ); ?>
        </a>
    </div>

</div>
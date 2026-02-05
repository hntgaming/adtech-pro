<?php

$categories = get_the_category();

if ( $categories ) { ?>

    <div class="hm-related-posts">
    
    <div class="wt-container">
        <h4 class="widget-title"><?php _e( 'Related Posts', 'adtech-pro' ); ?></h4>
    </div>

    <div class="hmrp-container">

        <?php

        $first_category = esc_attr( $categories[0]->term_id );
        $args = array(
            'cat'                   => array($first_category),
            'post__not_in'          => array($post->ID),
            'posts_per_page'        => 3,
            'ignore_sticky_posts'   => true
        );

        // Filter for Related Posts query arguments.
        $args = apply_filters( 'HTG_related_posts_query_arguments', $args );

        $related_posts = new WP_Query($args);

        if( $related_posts->have_posts() ) :
            while ($related_posts->have_posts()) : $related_posts->the_post(); ?>

                <div class="hm-rel-post">
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail( 'HTG-grid' ); ?>
                    </a>
                    <h3 class="post-title">
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    <p class="hms-meta"><?php echo HTG_posted_datetime() ?></p>
                </div>
            
            <?php
            endwhile;
        endif;

        wp_reset_postdata();

        ?>

    </div>
    </div>

    <?php

}
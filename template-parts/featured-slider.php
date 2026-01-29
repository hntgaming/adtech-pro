<?php 
if ( true == get_theme_mod( 'show_slider', true ) ) :

    $hm_slider_category = get_theme_mod( 'slider_category', '0' );

    $slider_posts = new WP_Query(
        array(
            'cat'                   => $hm_slider_category,
            'posts_per_page'        => 5,
            'no_found_rows' 		=> true,
            'ignore_sticky_posts'   => true
        )
    );

    ?>

    <div class="HTG-featured-slider">
        <section class="slider">
            <div class="hm-slider hm-swiper">
                <div class="hm-swiper-wrapper">
                    <?php
                        if ( $slider_posts->have_posts() ) :
                        
                        $hm_slide_count = 1;

                        while( $slider_posts->have_posts() ) : $slider_posts->the_post();
                        $hm_lazy_loading = 'lazy';
                    ?>
                     
                    <div class="hm-swiper-slide">
                        <div class="hm-slide-holder">
                            <div class="hm-slide-image">
                                <?php 
                                    if ( $hm_slide_count == 1 ) {
                                        $hm_lazy_loading = false;
                                    }
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( 'HTG-featured', array( 'loading' => $hm_lazy_loading ) );
                                    } else {
                                        $featured_image_url = get_template_directory_uri() . '/images/slide.jpg'; ?>
                                        <img src="<?php echo esc_url( $featured_image_url ); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php
                                    }
                                ?>
                            </div>
                            <div class="hm-slide-content">
                                <a class="hmwcsw" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" aria-label="<?php the_title_attribute(); ?>" rel="bookmark"></a>
                                <div class="hm-slider-details">
                                    <?php HTG_category_list(); ?>
                                    <a href="<?php the_permalink(); ?>" rel="bookmark"><h3 class="hm-slider-title"><?php the_title(); ?></h3></a>
                                    <div class="slide-entry-meta">
                                        <?php HTG_posted_on(); ?>
                                    </div><!-- .entry-meta -->
                                </div><!-- .hm-slider-details -->

                            </div><!-- .hm-slide-content -->
                        </div><!-- .hm-slide-holder -->
                    </div><!-- .hm-swiper-slide -->
                        
                    <?php 
                        $hm_slide_count++;
                        endwhile;
                        endif;

                    ?>
                </div><!-- .hm-swiper-wrapper -->
                <div class="hm-swiper-button-next"></div>
                <div class="hm-swiper-button-prev"></div>
            </div><!-- .hm-slider hm-swiper -->
        </section><!-- .slider -->

        <?php $slider_posts->rewind_posts(); ?>

        <div thumbsSlider="" class="hm-swiper hm-thumb-swiper">
            <div class="hm-swiper-wrapper">
                <?php
                    if ( $slider_posts->have_posts() ) :
                        while( $slider_posts->have_posts() ) : $slider_posts->the_post();

                            if ( has_post_thumbnail() ) { 
                                $thumb_id               = get_post_thumbnail_id();
                                $thumb_url_array        = wp_get_attachment_image_src( $thumb_id, 'HTG-thumbnail' );
                                $thumb_url              = ( ! empty( $thumb_url_array ) ) ? $thumb_url_array[0] : get_template_directory_uri() . '/images/slide-thumb.jpg';
                            } else {
                                $thumb_url = get_template_directory_uri() . '/images/slide-thumb.jpg';
                            }
                        ?>

                        <div class="hm-swiper-slide">
                            <div class="hm-thumb-bg"><img src="<?php echo esc_url( $thumb_url ); ?>" width="135" height="93" alt="<?php the_title_attribute(); ?>" /></div>
                        </div><!-- .hm-swiper-slide -->

                    <?php 
                        endwhile; 
                    endif; 
                    wp_reset_postdata(); 
                ?>
            </div><!-- .hm-swiper-wrapper -->
        </div><!-- .hm-thumb-swiper -->

    </div><!-- .hm-slider -->

<?php endif;
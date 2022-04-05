<?php
/**
 * Plugin Name: Carousel
 */


add_shortcode('carousel_posts','carousel_posts');
function carousel_posts(){
    $args = array(
        'post_type'  => 'strona',
        'meta_key'   => 'promowane',
        'meta_value' => 'Promowane',
        'orderby'    => 'rand',
    );

    $loop = new WP_Query($args);

    if ( $loop->have_posts() ) : ?>

        <div class="slideshow-container">

            <?php while ( $loop->have_posts() ) : $loop->the_post();?>

            <div class="mySlides fade">
                <img src="https://i.pinimg.com/originals/6f/06/a3/6f06a3f3052c2bafdc65b1ad74eab594.jpg" style="height: 150px; width: 1600px;">
                <div class="text">
                    <a href="<?php echo get_post_permalink(); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" style="height: 150px; width: 170px;">
                    </a>
                    <a href="<?php echo get_post_permalink(); ?>">

                        <h3 class="tex-carousel"><?php the_title(); ?></h3>
                    </a>

                </div>
            </div>
        <?php endwhile; ?>
        </div>
        <?php endif;
        wp_reset_postdata();
}






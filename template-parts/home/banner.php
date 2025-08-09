<?php
/**
 * Template part for displaying banner on homepage
 *
 * @package commicpro
 */

// Debug: Check if we're on the right page
echo '<!-- Debug: Current page is ' . (is_front_page() ? 'front page' : 'not front page') . ' -->';

// Get posts from truyen-chu post type
$args = array(
    'post_type' => 'truyen_chu',
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC'
);

$query = new WP_Query($args);

if ($query->have_posts()) :
?>
<section class="section-hero-banner py-5">
    <div class="container">
                <div class="carousel">
                    <?php
                    while ($query->have_posts()) :
                        $query->the_post();
                        ?>
                        <div class="carousel-cell">
                            <div class="">
                                <div class="">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="h-100 d-block swiper-slide-a overflow-hidden">
                                        <?php 
                                        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                        ?>
                                        <img class="attachment-medium img-custom-banner size-medium wp-post-image w-100" 
                                            src="<?php echo $featured_img_url ?>" 
                                            alt="<?php the_title_attribute(); ?>" 
                                            onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
                                        />
                                    </a>
                                </div>
                                <div class="mt-2">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <h3 class="fs-2 line-clamp line-clamp-1 text-capitalize font-secondary-title mb-1"><?php the_title(); ?></h3>
                                    </a>
                                    <div class="synopsis line-clamp line-clamp-1">
                                         <?php
                                        // Hiển thị tác giả
                                        $tac_gia = get_the_terms(get_the_ID(), 'tac_gia');
                                        if ($tac_gia && !is_wp_error($tac_gia)) : ?>
                                            <p class="mb-1">
                                                <small class="text-muted-custom">
                                                    <?php echo esc_html($tac_gia[0]->name); ?>
                                                </small>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
    </div>
</section>
<?php
else:
    // Debug: Show message if no posts found
    echo '<!-- Debug: No posts found in truyen_chu post type -->';
endif;
wp_reset_postdata(); 
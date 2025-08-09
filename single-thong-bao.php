<?php
/**
 * The template for displaying single thong-bao
 *
 * @package commicpro
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-thong-bao'); ?>>
                <header class="entry-header mb-4">
                    <div class="entry-meta mb-3">
                        <span class="posted-on">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo get_the_date(); ?>
                        </span>
                        <?php
                        // Get terms of danh-muc-thong-bao
                        $terms = get_the_terms(get_the_ID(), 'danh-muc-thong-bao');
                        if ($terms && !is_wp_error($terms)) :
                            ?>
                            <span class="category">
                                <i class="fas fa-folder"></i>
                                <?php
                                $term_names = array();
                                foreach ($terms as $term) {
                                    $term_names[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                }
                                echo implode(', ', $term_names);
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="entry-thumbnail mb-4">
                        <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'commicpro'),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

                <footer class="entry-footer mt-4">
                    <?php
                    // Get related posts from the same category
                    $related_args = array(
                        'post_type' => 'thong-bao',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'danh-muc-thong-bao',
                                'field' => 'term_id',
                                'terms' => wp_list_pluck($terms, 'term_id'),
                            ),
                        ),
                    );

                    $related_query = new WP_Query($related_args);

                    if ($related_query->have_posts()) :
                        ?>
                        <div class="related-posts mt-5">
                            <h3 class="related-title"><?php esc_html_e('Thông báo liên quan', 'commicpro'); ?></h3>
                            <div class="row">
                                <?php
                                while ($related_query->have_posts()) :
                                    $related_query->the_post();
                                    ?>
                                    <div class="col-md-4">
                                        <div class="related-post">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>" class="related-thumbnail">
                                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                                </a>
                                            <?php endif; ?>
                                            <h4 class="related-post-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                            <div class="related-post-date">
                                                <?php echo get_the_date(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php
                        wp_reset_postdata();
                    endif;
                    ?>
                </footer>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer(); 
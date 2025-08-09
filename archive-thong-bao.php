<?php
/**
 * The template for displaying archive for thong-bao taxonomy
 *
 * @package commicpro
 */

get_header();
?>

<main id="primary" class="site-main page-thong-bao">
    <div class="container py-4">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thông báo</li>
            </ol>
        </nav>

        <header class="page-header mb-4">
            <h1 class="page-title"><?php esc_html_e('Thông báo', 'commicpro'); ?></h1>
        </header>

        <?php
        // Get all terms of thong-bao taxonomy
        $terms = get_terms(array(
            'taxonomy' => 'danh-muc-thong-bao',
            'hide_empty' => true,
        ));

        if (!empty($terms) && !is_wp_error($terms)) :
            foreach ($terms as $term) :
                // Get posts for each term
                $args = array(
                    'post_type' => 'thong-bao',
                    'posts_per_page' => 6, // Limit to 6 posts per category
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'danh-muc-thong-bao',
                            'field' => 'term_id',
                            'terms' => $term->term_id,
                        ),
                    ),
                );

                $query = new WP_Query($args);
                ?>
                <div class="taxonomy-section mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="taxonomy-title mb-0">
                            <i class="fas fa-bullhorn me-2"></i>
                            <?php echo esc_html($term->name); ?>
                        </h2>
                        <a href="<?php echo esc_url(get_term_link($term)); ?>" class="btn btn-outline-primary btn-sm">
                            Xem tất cả
                        </a>
                    </div>
                    
                    <?php if ($query->have_posts()) : ?>
                        <div class="row g-4">
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <div class="col-md-4">
                                    <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 shadow-sm hover-shadow'); ?>>
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <div class="card-body">
                                            <h3 class="card-title h5">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <div class="card-text text-muted small">
                                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                            </div>
                                            
                                            <div class="entry-meta mt-3">
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    <?php echo get_the_date(); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php esc_html_e('Không có bài viết nào trong danh mục này.', 'commicpro'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                </div>
            <?php endforeach;
        else : ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php esc_html_e('Không tìm thấy danh mục nào.', 'commicpro'); ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer(); 
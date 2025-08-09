<?php
/**
 * The template for displaying danh-muc-thong-bao taxonomy archive
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
                <li class="breadcrumb-item"><a href="<?php echo esc_url(get_post_type_archive_link('thong-bao')); ?>">Thông báo</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    $term = get_queried_object();
                    echo esc_html($term->name);
                    ?>
                </li>
            </ol>
        </nav>

        <header class="page-header mb-4">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Danh mục: %s', 'commicpro'),
                    '<span>' . esc_html($term->name) . '</span>'
                );
                ?>
            </h1>
            <?php if ($term->description) : ?>
                <div class="archive-description mt-3">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="row g-4">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="col-md-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 shadow-sm hover-shadow'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="card-img-top">
                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                </a>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h2 class="card-title h5">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
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

                <div class="col-12 mt-4">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                        'class' => 'pagination justify-content-center',
                    ));
                    ?>
                </div>

            <?php else : ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php esc_html_e('Không có bài viết nào trong danh mục này.', 'commicpro'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer(); 
<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package commicpro
 */

get_header();

// Modify search query to only search in truyen_chu post type
function modify_search_query($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', 'truyen_chu');
        $query->set('posts_per_page', 12);
    }
    return $query;
}
add_filter('pre_get_posts', 'modify_search_query');

// Get search results
$search_query = get_search_query();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'post_type' => 'truyen_chu',
    'posts_per_page' => 12,
    'paged' => $paged,
    's' => $search_query
);

$search_query = new WP_Query($args);
?>

<main id="primary" class="site-main">
    <div class="container py-4">
        <?php if ($search_query->have_posts()) : ?>
            <header class="page-header mb-4">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('Kết quả tìm kiếm truyện cho: %s', 'commicpro'),
                        '<span class="text-warning">' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
                <div class="search-meta">
                    <?php
                    printf(
                        esc_html__('Tìm thấy %d truyện', 'commicpro'),
                        $search_query->found_posts
                    );
                    ?>
                </div>
            </header>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-6 g-4">
                <?php
                while ($search_query->have_posts()) :
                    $search_query->the_post();
                    get_template_part('template-parts/home/item-card');
                endwhile;
                ?>
            </div>

            <?php if ($search_query->max_num_pages > 1) : ?>
                <div class="pagination-wrapper mt-4">
                    <?php custom_pagination($search_query); ?>
                </div>
            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        <?php else : ?>
            <div class="no-results text-center py-5">
                <div class="no-results-icon mb-4">
                    <i class="fas fa-search fa-3x text-muted"></i>
                </div>
                <h2 class="mb-3"><?php esc_html_e('Không tìm thấy truyện nào', 'commicpro'); ?></h2>
                <p class="text-muted mb-4">
                    <?php esc_html_e('Xin lỗi, không tìm thấy truyện nào phù hợp với từ khóa của bạn. Vui lòng thử lại với từ khóa khác.', 'commicpro'); ?>
                </p>
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<style>
.page-title {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.search-meta {
    color: #6c757d;
    font-size: 1.1rem;
}

.pagination {
    margin-bottom: 0;
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
}

.pagination li {
    margin: 0 0.25rem;
}

.pagination a,
.pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    color: #0d6efd;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
}

.pagination a:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #0a58ca;
}

.pagination .current {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff;
}

.no-results {
    max-width: 600px;
    margin: 0 auto;
}

.search-form-wrapper {
    max-width: 500px;
    margin: 0 auto;
}

.search-form-wrapper .search-field {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}

.search-form-wrapper .search-submit {
    width: 100%;
    padding: 0.75rem 1rem;
    background-color: #0d6efd;
    border: none;
    border-radius: 0.25rem;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

.search-form-wrapper .search-submit:hover {
    background-color: #0b5ed7;
}
</style>

<?php
get_footer();

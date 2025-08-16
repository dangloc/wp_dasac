<?php
/**
 * The template for displaying tag archives
 */
get_header();
$tag = get_queried_object();
// Lấy toàn bộ truyện với tag này (không phân trang PHP)
$args = array(
    'post_type' => 'truyen_chu',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field' => 'term_id',
            'terms' => $tag->term_id,
        ),
    ),
);
$custom_query = new WP_Query($args);
$all_posts = [];
if ($custom_query->have_posts()) {
    while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $author = '';
            $author_terms = get_the_terms(get_the_ID(), 'tac_gia');
            if ($author_terms && !is_wp_error($author_terms)) {
                $author = join(', ', wp_list_pluck($author_terms, 'name'));
            }
            $tags = get_the_tags();
            $tag_list = '';
            if ($tags) {
                $tag_names = array_map(function($t) { return $t->name; }, $tags);
                $tag_list = join(', ', $tag_names);
            }
            $all_posts[] = [
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'author' => $author,
                'tags' => $tag_list,
            ];
    }
    wp_reset_postdata();
}
?>
<main id="primary" class="site-main">
    <div class="container py-4">
        <header class="page-header mb-4 d-flex justify-content-between">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Tag: %s', 'commicpro'),
                    '<span>' . esc_html($tag->name) . '</span>'
                );
                ?>
            </h1>
            <?php if ($tag->description) : ?>
                <div class="archive-description">
                    <?php echo wp_kses_post($tag->description); ?>
                </div>
            <?php endif; ?>
            <div class="the-loai-selector">
                <select id="the-loai-select" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <?php
                    $all_tags = get_tags([
                        'hide_empty' => false,
                    ]);
                    foreach ($all_tags as $tag_item) {
                        $selected = ($tag_item->slug === $tag->slug) ? 'selected' : '';
                        echo '<option value="' . esc_attr($tag_item->slug) . '" ' . $selected . '>' . esc_html($tag_item->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </header>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const theLoaiSelect = document.getElementById('the-loai-select');
            theLoaiSelect.addEventListener('change', function() {
                const selectedSlug = this.value;
                if (selectedSlug) {
                    const newURL = 'https://dasactruyen.xyz/index.php/tag/' + selectedSlug + '/';
                    window.location.href = newURL;
                }
            });
        });
        </script>
        <style>
        .the-loai-selector { max-width: 300px; }
        .the-loai-selector .form-select { border: 1px solid #ced4da; border-radius: 6px; padding: 8px 12px; font-size: 14px; }
        .the-loai-selector .form-select:focus { border-color: #80bdff; box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); }
        .tag-post-item { margin-bottom: 18px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #eee; padding-bottom: 12px; }
        .tag-post-item img { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; }
        .tag-post-item a { text-decoration: none; color: #333; font-weight: 500; }
        .tag-post-item a:hover { color: #fca311; }
        </style>
        <div class="row">
            <div class="col-lg-9">
                <div id="tag-post-list"></div>
                <div id="tag-pagination" class="mt-3"></div>
            </div>
            <div class="col-lg-3">
                <?php get_template_part( 'sidebar' );  ?>
            </div>
        </div>
    </div>
</main>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/libs/paginationjs/simplePagination.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/libs/paginationjs/jquery.simplePagination.js"></script>
<script>
var tagPosts = <?php echo json_encode($all_posts); ?>;
$(function() {
    var itemsPerPage = 12;
    function renderPosts(page) {
        var start = (page - 1) * itemsPerPage;
        var end = start + itemsPerPage;
        var html = '';
        var posts = tagPosts.slice(start, end);
        if (posts.length === 0) {
            html = '<p>Không tìm thấy truyện nào với tag này.</p>';
        } else {
            posts.forEach(function(post) {
                html += '<div class="tag-post-item">';
                html += '<a href="' + post.permalink + '">';
                html += '<img src="' + (post.thumbnail ? post.thumbnail : '<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png') + '" alt="' + post.title + '" />';
                html += '</a>';
                html += '<div class="d-flex flex-column">';
                html += '<span class="">' + post.title + '</span>';
                if (post.author) {
                    html += '<div><small><strong>Tác giả:</strong> ' + post.author + '</small></div>';
                }
                if (post.tags) {
                    html += '<div><small><strong>Tags:</strong> ' + post.tags + '</small></div>';
                }
                html += '</div>';
                html += '</div>';
            });
        }
        $('#tag-post-list').html(html);
    }
    $('#tag-pagination').pagination({
        items: tagPosts.length,
        itemsOnPage: itemsPerPage,
        cssStyle: 'light-theme',
        onPageClick: function(pageNumber) {
            renderPosts(pageNumber);
        }
    });
    renderPosts(1);
});
</script>
<?php
get_footer();
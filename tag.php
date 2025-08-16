<?php
/**
 * The template for displaying tag archives
 */

get_header();

$tag = get_queried_object();

// Create custom query for truyen_chu posts with this tag
$args = array(
    'post_type' => 'truyen_chu',
    'posts_per_page' => 12,
    'paged' => max(1, get_query_var('paged')),
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field' => 'term_id',
            'terms' => $tag->term_id,
        ),
    ),
);
$custom_query = new WP_Query($args);
$GLOBALS['wp_query'] = $custom_query; 
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
        .the-loai-selector {
            max-width: 300px;
        }
        
        .the-loai-selector .form-select {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
        }
        
        .the-loai-selector .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        </style>
        
        <div class="row">
            <div class="col-lg-9">
                <div class="row m-0">
                    <?php if ($custom_query->have_posts()) : ?>
                        <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                        <?php get_template_part( 'template-parts/home/item-card' ); ?>
                        <?php endwhile; ?>
        
                        <div class="col-12">
                            <?php
                            // PHÂN TRANG
                            $total_pages = $custom_query->max_num_pages;
                            $current_page = max(1, get_query_var('paged'));
                            if ($total_pages > 1) {
                                echo '<nav class="d-flex justify-content-center">';
                                echo '<ul class="pagination">';
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    echo '<li class="page-item'.($i == $current_page ? ' active' : '').'">';
                                    echo '<a href="' . get_pagenum_link($i) . '" class="page-numbers page-link">'.$i.'</a>';
                                    echo '</li>';
                                }
                                echo '</ul></nav>';
                            }
                            ?>
                        </div>
        
                    <?php else : ?>
                        <div class="col-12">
                            <p><?php esc_html_e('Không tìm thấy truyện nào với tag này.', 'commicpro'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-3">
					<?php get_template_part( 'sidebar' );  ?>
			</div>
        </div>

    </div>
</main>

<?php
wp_reset_postdata();
wp_reset_query();
echo '<!-- paged: ' . get_query_var('paged') . ' -->';
get_footer(); 
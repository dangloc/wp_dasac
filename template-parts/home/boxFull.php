<?php
// Lấy các truyện có trạng thái đã hoàn thành
$completed_args = array(
    'post_type'      => 'truyen_chu',
    'posts_per_page' => 13,
    'orderby'        => 'date',
    'order'          => 'ASC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'trang_thai',
            'field'    => 'slug',
            'terms'    => array('da-hoan-thanh'),
        ),
    ),
);
$completed_query = new WP_Query($completed_args);

$completed_term_link = '';
$completed_term = get_term_by('slug', 'da-hoan-thanh', 'trang_thai');
if ($completed_term && !is_wp_error($completed_term)) {
    $completed_term_link = get_term_link($completed_term);
}

if ($completed_query->have_posts()) : ?>
    <?php
    // Lấy tất cả thể loại
    $all_the_loai = get_terms([
        'taxonomy' => 'the_loai',
        'hide_empty' => false,
    ]);
    $selected_the_loai = isset($_GET['the_loai']) ? intval($_GET['the_loai']) : 0;
    ?>
    <script>
    window.ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>

    <section class="box-full pb-5" data-aos="fade-up">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title mb-0"><span>Truyện đã hoàn thành</span></div>
            <div>
                <select id="box-full-the-loai-select" class="form-select">
                    <option value="0">Tất cả</option>
                    <?php foreach ($all_the_loai as $term): ?>
                        <option value="<?php echo $term->term_id; ?>" <?php selected($selected_the_loai, $term->term_id); ?>>
                            <?php echo esc_html($term->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div id="box-full-wrap">
            <?php render_box_full($selected_the_loai); ?>
        </div>

        <div class="text-center mt-5">
            <a class="btn btn-secondary" href="<?php echo esc_url($completed_term_link); ?>">Xem Thêm</a>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('box-full-the-loai-select');
        function loadBoxFull(the_loai) {
            const data = new FormData();
            data.append('action', 'filter_box_full_by_the_loai');
            data.append('the_loai', the_loai);
            fetch(window.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('box-full-wrap').innerHTML = html;
            });
        }
        select.addEventListener('change', function() {
            loadBoxFull(this.value);
        });
    });
    </script>
<?php endif; wp_reset_postdata(); ?>

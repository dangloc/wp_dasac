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
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Truyện mới cập nhật</h3>
    </div>
    <div>
        <select id="the-loai-select" class="form-select">
            <option value="0">Tất cả</option>
            <?php foreach ($all_the_loai as $term): ?>
                <option value="<?php echo $term->term_id; ?>" <?php selected($selected_the_loai, $term->term_id); ?>>
                    <?php echo esc_html($term->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div id="table-truyen-wrap">
<?php render_table_truyen($selected_the_loai, 1); ?>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('the-loai-select');
    function loadTable(the_loai, paged = 1) {
        const data = new FormData();
        data.append('action', 'filter_truyen_by_the_loai');
        data.append('the_loai', the_loai);
        data.append('paged', paged);
        fetch(window.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('table-truyen-wrap').innerHTML = html;
        });
    }
    select.addEventListener('change', function() {
        loadTable(this.value, 1);
    });
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('page-numbers')) {
            e.preventDefault();
            const page = e.target.dataset.page;
            const the_loai = select.value;
            loadTable(the_loai, page);
        }
    });
});
</script>

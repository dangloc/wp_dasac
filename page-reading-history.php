<?php
/**
 * Template Name: Reading History
 */

get_header();

// Check if user is logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

$user_id = get_current_user_id();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$reading_history = get_user_reading_history($user_id, 12, $paged); // Limit to 12 items per page
?>

<main id="primary" class="site-main">
    <div class="container py-4">
        <header class="page-header mb-4">
            <h1 class="page-title"><?php esc_html_e('Lịch sử đọc truyện', 'commicpro'); ?></h1>
        </header>

        <div class="row">
            <?php if (!empty($reading_history)) : ?>
                <?php foreach ($reading_history as $history) : 
                    $story = get_post($history->story_id);
                    if (!$story) continue;
                ?>
                    <div class="col-md-2 col-6 mb-4">
                        <div class="card-custom">
                            <?php if (has_post_thumbnail($story->ID)) : ?>
                                <a href="<?php echo get_permalink($story->ID); ?>" class="card-img-top">
                                    <img src="<?php echo get_the_post_thumbnail_url($story->ID, 'medium'); ?>" 
                                        class="img-fluid wp-post-image" 
                                        alt="<?php echo esc_attr($story->post_title); ?>">
                                </a>
                            <?php endif; ?>
                            <div class="card-body-custom">
                                <h5 class="card-title-custom">
                                    <a href="<?php echo get_permalink($story->ID); ?>">
                                        <?php echo esc_html($story->post_title); ?>
                                    </a>
                                </h5>
                                <p class="card-text mb-1">
                                    <?php printf(
                                        esc_html__('Đã đọc đến: %s', 'commicpro'),
                                        '<strong>' . esc_html($history->chapter_title) . '</strong>'
                                    ); ?>
                                </p>
                                <p class="card-text mb-1">
                                    <small class="text-muted-custom">
                                        <?php printf(
                                            esc_html__('Lần đọc cuối: %s', 'commicpro'),
                                            date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($history->last_read))
                                        ); ?>
                                    </small>
                                </p>
                                <div class="d-flex flex-column gap-2">
                                    <a href="<?php echo get_permalink($history->chapter_id); ?>" class="btn btn-primary">
                                        <?php esc_html_e('Đọc tiếp', 'commicpro'); ?>
                                    </a>
                                    <a href="<?php echo get_permalink($story->ID); ?>" class="btn btn-outline-primary">
                                        <?php esc_html_e('Xem truyện', 'commicpro'); ?>
                                    </a>
                                    <button class="btn btn-outline-danger delete-history" data-history-id="<?php echo $history->id; ?>">
                                        <?php esc_html_e('Xóa', 'commicpro'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="col-12 mt-4">
                    <?php
                    $total_items = get_user_reading_history_count($user_id);
                    $total_pages = ceil($total_items / 12);
                    if ($total_pages > 1) {
                        echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = $i == $paged ? ' active' : '';
                            echo '<li class="page-item' . $active . '"><a class="page-link" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
                        }
                        echo '</ul></nav>';
                    }
                    ?>
                </div>
            <?php else : ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <?php esc_html_e('Bạn chưa đọc truyện nào.', 'commicpro'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    $('.delete-history').on('click', function() {
        if (confirm('<?php esc_html_e('Bạn có chắc chắn muốn xóa lịch sử đọc này?', 'commicpro'); ?>')) {
            var historyId = $(this).data('history-id');
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'delete_reading_history',
                    history_id: historyId,
                    nonce: '<?php echo wp_create_nonce('delete_reading_history_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                }
            });
        }
    });
});
</script>

<?php
get_footer(); 
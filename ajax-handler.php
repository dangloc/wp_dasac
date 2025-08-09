<?php
// Đảm bảo file này được gọi từ WordPress
if (!defined('ABSPATH')) {
    exit;
}

// Đăng ký AJAX action cho đếm số chương
add_action('wp_ajax_get_chapter_count', 'get_chapter_count_handler');
add_action('wp_ajax_nopriv_get_chapter_count', 'get_chapter_count_handler');

// Đăng ký AJAX action cho lấy chương mới nhất
add_action('wp_ajax_get_latest_chapter', 'get_latest_chapter_handler');
add_action('wp_ajax_nopriv_get_latest_chapter', 'get_latest_chapter_handler');

function get_chapter_count_handler() {
    // Kiểm tra nonce để bảo mật
    check_ajax_referer('chapter_count_nonce', 'nonce');

    $truyen_id = isset($_POST['truyen_id']) ? intval($_POST['truyen_id']) : 0;
    
    if ($truyen_id > 0) {
        $chuong_count = get_posts([
            'post_type' => 'chuong_truyen',
            'posts_per_page' => -1,
            'meta_key' => 'chuong_with_truyen',
            'meta_value' => $truyen_id
        ]);
        
        wp_send_json_success([
            'count' => count($chuong_count)
        ]);
    }
    
    wp_send_json_error('Invalid truyen ID');
}

function get_latest_chapter_handler() {
    // Kiểm tra nonce để bảo mật
    check_ajax_referer('chapter_count_nonce', 'nonce');

    $truyen_id = isset($_POST['truyen_id']) ? intval($_POST['truyen_id']) : 0;
    
    if ($truyen_id > 0) {
        $latest_chapter = get_posts([
            'post_type' => 'chuong_truyen',
            'posts_per_page' => 1,
            'meta_key' => 'chuong_with_truyen',
            'meta_value' => $truyen_id,
            'orderby' => 'date',
            'order' => 'DESC'
        ]);
        
        if (!empty($latest_chapter)) {
            $chapter = $latest_chapter[0];
            wp_send_json_success([
                'id' => $chapter->ID,
                'title' => $chapter->post_title,
                'link' => get_permalink($chapter->ID),
                'date' => get_the_date('d/m/Y', $chapter->ID)
            ]);
        } else {
            wp_send_json_error('No chapters found');
        }
    }
    
    wp_send_json_error('Invalid truyen ID');
} 
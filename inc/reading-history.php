<?php
/**
 * Reading History functionality
 */

// Create table on theme activation
function create_reading_history_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reading_history';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        story_id bigint(20) NOT NULL,
        chapter_id bigint(20) NOT NULL,
        chapter_number int(11) NOT NULL,
        last_read datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY user_id (user_id),
        KEY story_id (story_id),
        KEY chapter_id (chapter_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);
    // Verify table exists
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
    
    if ($table_exists) {
        // Check table structure
        $columns = $wpdb->get_results("DESCRIBE $table_name");
    }
}
add_action('after_switch_theme', 'create_reading_history_table');
add_action('init', 'create_reading_history_table');

// Save reading history
function save_reading_history($user_id, $story_id, $chapter_id, $chapter_number) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reading_history';
    
    // Check if record exists
    $existing = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE user_id = %d AND story_id = %d",
        $user_id,
        $story_id
    ));

    if ($existing) {
        // Update existing record
        $wpdb->update(
            $table_name,
            array(
                'chapter_id' => $chapter_id,
                'chapter_number' => $chapter_number,
                'last_read' => current_time('mysql')
            ),
            array(
                'user_id' => $user_id,
                'story_id' => $story_id
            )
        );
    } else {
        // Insert new record
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'story_id' => $story_id,
                'chapter_id' => $chapter_id,
                'chapter_number' => $chapter_number,
                'last_read' => current_time('mysql')
            )
        );
    }
}

// Get user's reading history
function get_user_reading_history($user_id, $limit = 12, $paged = 1) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reading_history';
    
    $offset = ($paged - 1) * $limit;
    
    return $wpdb->get_results($wpdb->prepare(
        "SELECT rh.*, 
                p.post_title as story_title,
                c.post_title as chapter_title
        FROM $table_name rh 
        JOIN {$wpdb->posts} p ON rh.story_id = p.ID 
        JOIN {$wpdb->posts} c ON rh.chapter_id = c.ID
        WHERE rh.user_id = %d 
        ORDER BY rh.last_read DESC 
        LIMIT %d OFFSET %d",
        $user_id,
        $limit,
        $offset
    ));
}

// Get total count of reading history items
function get_user_reading_history_count($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reading_history';
    
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d",
        $user_id
    ));
}

// Delete reading history item
function delete_reading_history_item($history_id, $user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'reading_history';
    
    return $wpdb->delete(
        $table_name,
        array(
            'id' => $history_id,
            'user_id' => $user_id
        ),
        array('%d', '%d')
    );
}

// Handle AJAX delete reading history
function handle_delete_reading_history() {
    check_ajax_referer('delete_reading_history_nonce', 'nonce');
    
    $history_id = intval($_POST['history_id']);
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        wp_send_json_error('Bạn cần đăng nhập để thực hiện thao tác này');
        return;
    }
    
    $result = delete_reading_history_item($history_id, $user_id);
    
    if ($result) {
        wp_send_json_success('Xóa lịch sử đọc thành công');
    } else {
        wp_send_json_error('Có lỗi xảy ra khi xóa lịch sử đọc');
    }
}
add_action('wp_ajax_delete_reading_history', 'handle_delete_reading_history');

// Add reading history to single chapter page
function track_chapter_reading() {
    if (is_singular('chuong_truyen') && is_user_logged_in()) {
        global $post;
        error_log('Current post ID: ' . $post->ID);
        error_log('Current post type: ' . $post->post_type);
        
        // Get story ID from ACF field
        $story = get_field('chuong_with_truyen');
        
        if (!$story) {
            error_log('Không tìm thấy truyện cho chương ID: ' . $post->ID);
            return;
        }
        
        // Get chapter number from URL
        $current_url = $_SERVER['REQUEST_URI'];
        
        // Try different URL patterns
        $chapter_number = 0;
        
        // Pattern 1: /chuong-{number}-{slug}/
        if (preg_match('/chuong-(\d+)-([^\/]+)/', $current_url, $matches)) {
            $chapter_number = intval($matches[1]);
            error_log('Found chapter number from pattern 1: ' . $chapter_number);
        }
        // Pattern 2: /chuong/{number}/{slug}/
        elseif (preg_match('/chuong\/(\d+)\/([^\/]+)/', $current_url, $matches)) {
            $chapter_number = intval($matches[1]);
            error_log('Found chapter number from pattern 2: ' . $chapter_number);
        }
        // Pattern 3: /chuong/{slug}/{number}/
        elseif (preg_match('/chuong\/([^\/]+)\/(\d+)/', $current_url, $matches)) {
            $chapter_number = intval($matches[2]);
        }
        
        if ($chapter_number > 0) {
            
            save_reading_history(
                get_current_user_id(),
                $story->ID,
                $post->ID,
                $chapter_number
            );
        } else {
            
            // Try to get chapter number from post title
            if (preg_match('/Chương\s+(\d+)/', $post->post_title, $matches)) {
                $chapter_number = intval($matches[1]);
                
                save_reading_history(
                    get_current_user_id(),
                    $story->ID,
                    $post->ID,
                    $chapter_number
                );
            }
        }
    }
}
add_action('wp', 'track_chapter_reading');

// Add reading history page template
function add_reading_history_page_template($templates) {
    $templates['page-reading-history.php'] = 'Reading History';
    return $templates;
}
add_filter('theme_page_templates', 'add_reading_history_page_template');

// Load reading history template
function load_reading_history_template($template) {
    if (is_page_template('page-reading-history.php')) {
        $new_template = locate_template(array('page-reading-history.php'));
        if (!empty($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_reading_history_template'); 
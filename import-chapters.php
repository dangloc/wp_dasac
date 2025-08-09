<?php
/*
Plugin Name: Import Chapters
Description: Import multiple chapters from text file
Version: 1.0
Author: Your Name
*/

// Add menu item to admin
function import_chapters_menu() {
    add_menu_page(
        'Import Chapters',
        'Import Chapters',
        'manage_options',
        'import-chapters',
        'import_chapters_page',
        'dashicons-upload'
    );
}
add_action('admin_menu', 'import_chapters_menu');

// Create the import page
function import_chapters_page() {
    ?>
    <div class="wrap">
        <h1>Import Chapters</h1>
        
        <!-- Delete Chapters Section -->
        <h2>Delete Chapters</h2>
        <form method="post" action="">
            <?php wp_nonce_field('delete_chapters_nonce', 'delete_chapters_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th><label for="delete_truyen_chu">Select Truyen Chu to Delete Chapters</label></th>
                    <td>
                        <select name="delete_truyen_chu" id="delete_truyen_chu" required>
                            <option value="">Select a truyen chu...</option>
                            <?php
                            $truyen_chus = get_posts(array(
                                'post_type' => 'truyen_chu',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC'
                            ));
                            foreach ($truyen_chus as $truyen) {
                                echo '<option value="' . $truyen->ID . '">' . $truyen->post_title . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="delete_chapters" class="button button-danger" value="Delete All Chapters" onclick="return confirm('Are you sure you want to delete all chapters for this truyen chu? This action cannot be undone.');">
            </p>
        </form>

        <hr>

        <!-- Import Chapters Section -->
        <h2>Import Chapters</h2>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('import_chapters_nonce', 'import_chapters_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th><label for="truyen_chu">Select Truyen Chu</label></th>
                    <td>
                        <select name="truyen_chu" id="truyen_chu" required>
                            <option value="">Select a truyen chu...</option>
                            <?php
                            $truyen_chus = get_posts(array(
                                'post_type' => 'truyen_chu',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC'
                            ));
                            foreach ($truyen_chus as $truyen) {
                                echo '<option value="' . $truyen->ID . '">' . $truyen->post_title . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="chapter_file">Upload Chapter File</label></th>
                    <td>
                        <input type="file" name="chapter_file" id="chapter_file" accept=".txt" required>
                        <p class="description">Upload a .txt file containing chapters. Each chapter should start with "Chương X" followed by the content.</p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="import_chapters" class="button button-primary" value="Import Chapters">
            </p>
        </form>
    </div>
    <?php
}

// Handle chapter deletion
function handle_chapter_deletion() {
    if (!isset($_POST['delete_chapters_nonce']) || !wp_verify_nonce($_POST['delete_chapters_nonce'], 'delete_chapters_nonce')) {
        return;
    }

    if (!isset($_POST['delete_truyen_chu']) || empty($_POST['delete_truyen_chu'])) {
        wp_die('Please select a truyen chu to delete chapters.');
    }

    $truyen_chu_id = intval($_POST['delete_truyen_chu']);

    // Get all chapters related to this truyen chu
    $chapters = get_posts(array(
        'post_type' => 'chuong_truyen',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'chuong_with_truyen',
                'value' => $truyen_chu_id,
                'compare' => '='
            )
        )
    ));

    $deleted_count = 0;
    foreach ($chapters as $chapter) {
        if (wp_delete_post($chapter->ID, true)) {
            $deleted_count++;
        }
    }

    wp_die(sprintf('Successfully deleted %d chapters.', $deleted_count));
}
add_action('admin_init', 'handle_chapter_deletion');

// Handle the import
function handle_chapter_import() {
    if (!isset($_POST['import_chapters_nonce']) || !wp_verify_nonce($_POST['import_chapters_nonce'], 'import_chapters_nonce')) {
        return;
    }

    if (!isset($_POST['truyen_chu']) || empty($_POST['truyen_chu'])) {
        wp_die('Please select a truyen chu.');
    }

    if (!isset($_FILES['chapter_file']) || $_FILES['chapter_file']['error'] !== UPLOAD_ERR_OK) {
        wp_die('Please upload a valid file.');
    }

    $truyen_chu_id = intval($_POST['truyen_chu']);
    $file_content = file_get_contents($_FILES['chapter_file']['tmp_name']);
    
    // Remove BOM if exists
    $file_content = preg_replace('/^\xEF\xBB\xBF/', '', $file_content);
    
    // Debug: Log file content
    error_log('=== DEBUG: File Content Start ===');
    error_log(substr($file_content, 0, 1000)); // Log first 1000 characters
    error_log('=== DEBUG: File Content End ===');
    
    // Get existing chapter count for this truyen chu
    $existing_chapters = get_posts(array(
        'post_type' => 'chuong_truyen',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'chuong_with_truyen',
                'value' => $truyen_chu_id,
                'compare' => '='
            )
        )
    ));
    $next_chapter_number = count($existing_chapters) + 1;
    
    // Debug: Log existing chapters
    error_log('=== DEBUG: Existing Chapters ===');
    error_log('Next chapter number: ' . $next_chapter_number);
    
    // Split content into chapters - improved regex to handle chapter markers better
    $chapters = array();
    $chapter_titles = array();
    
    // Find all chapter markers and their positions
    preg_match_all('/Chương\s+(\d+)/', $file_content, $matches, PREG_OFFSET_CAPTURE);
    
    // Debug: Log chapter markers
    error_log('=== DEBUG: Chapter Markers ===');
    error_log(print_r($matches[0], true));
    
    // Extract chapters based on markers
    $total_markers = count($matches[0]);
    for ($i = 0; $i < $total_markers; $i++) {
        $start_pos = $matches[0][$i][1];
        $end_pos = ($i < $total_markers - 1) ? $matches[0][$i + 1][1] : strlen($file_content);
        $chapter_content = substr($file_content, $start_pos, $end_pos - $start_pos);
        
        // Remove the chapter marker from the content
        $chapter_content = preg_replace('/^Chương\s+\d+\s*/', '', $chapter_content);
        
        // Only add non-empty chapters and ensure chapter numbers are sequential
        if (trim($chapter_content) !== '') {
            $chapter_number = intval($matches[1][$i][0]);
            // Skip if this chapter number is already processed (duplicate marker)
            if (!in_array($chapter_number, $chapter_titles)) {
                $chapters[] = $chapter_content;
                $chapter_titles[] = $chapter_number;
            }
        }
    }
    
    // Sort chapters by their numbers
    array_multisort($chapter_titles, SORT_NUMERIC, $chapters);
    
    // Debug: Log chapter splitting results
    error_log('=== DEBUG: Chapter Splitting Results ===');
    error_log('Number of chapters found: ' . count($chapters));
    error_log('Chapter titles found: ' . print_r($chapter_titles, true));
    
    if (empty($chapters)) {
        wp_die('No chapters found in the file.');
    }

    $success_count = 0;
    $error_count = 0;
    $debug_log = array();

    // Get truyen chu slug
    $truyen_chu_slug = get_post_field('post_name', $truyen_chu_id);
    
    foreach ($chapters as $index => $content) {
        $chapter_title = "Chương " . $chapter_titles[$index];
        
        // Debug: Log chapter content
        error_log('=== DEBUG: Chapter ' . $chapter_titles[$index] . ' ===');
        error_log('Content length: ' . strlen(trim($content)));
        error_log('First 100 characters: ' . substr(trim($content), 0, 100));
        
        // Create chapter post
        $chapter_post = array(
            'post_title'    => $chapter_title,
            'post_content'  => trim($content),
            'post_status'   => 'publish',
            'post_type'     => 'chuong_truyen',
            'post_name'     => "chuong-{$chapter_titles[$index]}-{$truyen_chu_slug}" // Add custom slug
        );

        $chapter_id = wp_insert_post($chapter_post);

        if ($chapter_id) {
            // Set the truyen_chu relationship
            update_field('chuong_with_truyen', $truyen_chu_id, $chapter_id);
            $success_count++;
            $debug_log[] = "Chapter {$chapter_titles[$index]} created successfully. ID: {$chapter_id}";
        } else {
            $error_count++;
            $debug_log[] = "Failed to create Chapter {$chapter_titles[$index]}";
        }
    }

    // Debug: Log final results
    error_log('=== DEBUG: Import Results ===');
    error_log(print_r($debug_log, true));

    wp_die(sprintf(
        'Nhập hàng loạt thành công. Thành công %d chương. Thất bại %d chương.<br><br>Debug Log:<br>%s',
        $success_count,
        $error_count,
        implode('<br>', $debug_log)
    ));
}
add_action('admin_init', 'handle_chapter_import'); 
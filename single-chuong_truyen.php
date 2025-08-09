<?php
/**
 * The template for displaying single chapter
 */

// Lấy thông tin truyện cha từ ACF field
$truyen = get_field('chuong_with_truyen');
$truyen_id = $truyen ? $truyen->ID : 0;

if (!$truyen_id) {
    wp_safe_redirect(home_url());
    exit;
}

// Lấy thông tin khóa chương và giá
$locked_from = get_post_meta($truyen_id, '_locked_from', true);
$chapter_price = get_post_meta($truyen_id, '_chapter_price', true);

// Lấy số thứ tự chương hiện tại
$args = array(
    'post_type' => 'chuong_truyen',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'chuong_with_truyen',
            'value' => $truyen_id,
            'compare' => '='
        )
    ),
    'orderby' => 'meta_value_num',
    'meta_key' => 'chapter_number',
    'order' => 'ASC'
);
$chapters = new WP_Query($args);
$current_chapter_number = 1;
$is_locked = false;
$current_post_id = get_the_ID();

if ($chapters->have_posts()) {
    while ($chapters->have_posts()) {
        $chapters->the_post();
        if (get_the_ID() == $current_post_id) {
            $is_locked = $locked_from && $current_chapter_number >= $locked_from;
            break;
        }
        $current_chapter_number++;
    }
}
wp_reset_postdata();

// Kiểm tra xem user đã mua chương này chưa bằng hàm mới
$user_id = get_current_user_id();
$is_purchased = can_user_read_chapter($user_id, $current_post_id, $truyen_id);

// Nếu chương bị khóa và chưa mua, chuyển hướng về trang truyện
if ($is_locked && !$is_purchased) {
    wp_safe_redirect(get_permalink($truyen_id));
    exit;
}

// Xử lý mua chương
if (isset($_POST['buy_chapter']) && wp_verify_nonce($_POST['buy_chapter_nonce'], 'buy_chapter_' . $current_post_id)) {
    $user_balance = get_user_meta($user_id, '_user_balance', true);
    
    if ($user_balance >= $chapter_price) {
        // Trừ tiền
        update_user_meta($user_id, '_user_balance', $user_balance - $chapter_price);
        
        // Thêm vào danh sách chương đã mua
        $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
        if (!is_array($purchased_chapters)) {
            $purchased_chapters = array();
        }
        $purchased_chapters[] = $current_post_id;
        update_user_meta($user_id, '_purchased_chapters', $purchased_chapters);
        
        // Refresh trang
        wp_safe_redirect(get_permalink());
        exit;
    } else {
        $error_message = 'Số dư không đủ để mua chương này!';
    }
}

// Get current chapter number and story ID from URL
$current_url = $_SERVER['REQUEST_URI'];
preg_match('/chuong-(\d+)-([^\/]+)/', $current_url, $matches);
$current_chapter = isset($matches[1]) ? intval($matches[1]) : 0;
$story_slug = isset($matches[2]) ? $matches[2] : '';

// Get next chapter
$next_chapter = $current_chapter + 1;
$next_chapter_url = home_url("/index.php/chuong/chuong-{$next_chapter}-{$story_slug}/");

// Get previous chapter
$prev_chapter = $current_chapter - 1;
$prev_chapter_url = $prev_chapter > 0 ? home_url("/index.php/chuong/chuong-{$prev_chapter}-{$story_slug}/") : '';


// *** THÊM LOGIC QUẢNG CÁO ***
// Lấy số chương từ URL hoặc từ current_chapter_number
$chapter_number = $current_chapter > 0 ? $current_chapter : $current_chapter_number;
$show_ad = ($chapter_number > 0 && $chapter_number == 2);
$link_qc = get_field("link_qc", 2);
$img_qc_field = get_field("qc_img", 2);
$img_qc = $img_qc_field ? $img_qc_field['url'] : '';

get_header();
?>

<main id="primary" class="site-main">
    <div class="container py-4">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-4">
                    <h1 class="entry-title" style="color: #000"><?php the_title(); ?></h1>
                    
                    <?php if ($truyen) : ?>
                        <div class="truyen-link mb-3">
                            <a href="<?php echo get_permalink($truyen_id); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-book"></i> Về truyện: <?php echo esc_html($truyen->post_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </header>

                                <!-- *** THÊM KHỐI QUẢNG CÁO *** -->
                <div id="chapter-ad-block" style="display: <?php echo $show_ad ? 'block' : 'none'; ?>;">
                    <?php if ($show_ad && $img_qc && $link_qc): ?>
                        <div style="text-align:center; margin: 20px 0; padding: 20px; background: transparent; border-radius: 10px; border: 2px solid #dee2e6;">
                            <h4 style="color: #fff; margin-bottom: 10px;">Mời bạn CLICK vào liên kết bên dưới và</h4>
                            <h3><strong style="color: red; font-weight: bold;">MỞ ỨNG DỤNG SHOPEE</strong> để tiếp tục đọc!</h3> 
                            <a style="display: block; font-size: 16px; margin: 10px 0; color: #007bff; word-break: break-all;" href="<?php echo esc_url($link_qc); ?>">👉<?php echo esc_url($link_qc); ?></a>
                            <img src="<?php echo esc_url($img_qc); ?>" alt="Ad Banner"
                                id="adBannerClick"
                                style="width: 100%; max-width: 800px; cursor: pointer; border-radius: 8px; object-fit: contain; margin: 15px 0;" />
                            <h3 style="color: red; font-weight: bold; margin: 20px 0;"><?php echo get_bloginfo('name'); ?> XIN CHÂN THÀNH CẢM ƠN QUÝ ĐỌC GIẢ!</h3>
                            <p style="color: #666; font-size: 14px; margin-top: 15px;">
                                <i class="fas fa-info-circle"></i> Click vào bất kỳ đâu trong khung này để mở Shopee và tiếp tục đọc truyện
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- NỘI DUNG CHƯƠNG -->
                <div id="chapter-content" style="display: <?php echo $show_ad ? 'none' : 'block'; ?>;">
                    <div class="entry-content" id="reader-content" style="color: #000">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Floating Sidebar -->
                <div id="floating-sidebar">
                    <button class="sidebar-btn" id="menu-btn" title="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <button class="sidebar-btn" id="scroll-down-btn" title="Cuộn xuống">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <button class="sidebar-btn" id="settings-btn" title="Cài đặt">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="sidebar-btn" id="bookmark-btn" title="Đánh dấu">
                        <i class="fas fa-bookmark"></i>
                    </button>

                    <button class="sidebar-btn" id="scroll-up-btn" title="Cuộn lên">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button class="sidebar-btn" id="fullscreen-btn" title="Toàn màn hình">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>

                <!-- Settings Modal -->
                <div id="settings-modal" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Cài đặt</h3>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-body">
                            <div class="setting-group">
                                <label>Màu nền</label>
                                <div class="color-palette">
                                    <div class="color-swatch" data-color="#f8f9fa" style="background-color: #f8f9fa;"></div>
                                    <div class="color-swatch" data-color="#ffe6e6" style="background-color: #ffe6e6;"></div>
                                    <div class="color-swatch" data-color="#fff3cd" style="background-color: #fff3cd;"></div>
                                    <div class="color-swatch" data-color="#ffeaa7" style="background-color: #ffeaa7;"></div>
                                    <div class="color-swatch" data-color="#d1ecf1" style="background-color: #d1ecf1;"></div>
                                    <div class="color-swatch active" data-color="#ffffff" style="background-color: #ffffff; border: 2px solid #000;"></div>
                                    <div class="color-swatch" data-color="#f8f9fa" style="background-color: #f8f9fa;"></div>
                                    <div class="color-swatch" data-color="#6c757d" style="background-color: #6c757d;"></div>
                                    <div class="color-swatch" data-color="#212529" style="background-color: #212529;"></div>
                                </div>
                            </div>
                            
                            <div class="setting-group">
                                <label>Font chữ</label>
                                <select id="font-select">
                                    <option value="Arial">Arial</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Roboto">Roboto</option>
                                    <option value="Open Sans">Open Sans</option>
                                </select>
                            </div>
                            
                            <div class="setting-group">
                                <label>Cỡ chữ</label>
                                <div class="number-control">
                                    <button class="control-btn" id="font-size-down">‹</button>
                                    <span id="font-size-value">21</span>
                                    <button class="control-btn" id="font-size-up">›</button>
                                </div>
                            </div>
                            
                            <div class="setting-group">
                                <label>Khoảng cách</label>
                                <div class="number-control">
                                    <button class="control-btn" id="line-height-down">‹</button>
                                    <span id="line-height-value">2.3</span>
                                    <button class="control-btn" id="line-height-up">›</button>
                                </div>
                            </div>
                            
                            <div class="setting-group">
                                <label>Chiều rộng khung</label>
                                <div class="number-control">
                                    <button class="control-btn" id="max-width-down">‹</button>
                                    <span id="max-width-value">900</span>
                                    <button class="control-btn" id="max-width-up">›</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                /* Floating Sidebar */
                #floating-sidebar {
                    position: fixed;
                    right: 20px;
                    top: 50%;
                    transform: translateY(-50%);
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    z-index: 1000;
                }

                .sidebar-btn {
                    width: 50px;
                    height: 50px;
                    border: none;
                    border-radius: 8px;
                    background: #333;
                    color: white;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    transition: all 0.3s ease;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                }

                .sidebar-btn:hover {
                    background: #555;
                    transform: scale(1.1);
                }

                /* Settings Modal */
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 2000;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.5);
                }

                .modal-content {
                    background-color: white;
                    margin: 10% auto;
                    padding: 0;
                    border-radius: 10px;
                    width: 90%;
                    max-width: 500px;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                }

                .modal-header {
                    padding: 20px;
                    border-bottom: 1px solid #eee;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .modal-header h3 {
                    margin: 0;
                    color: #333;
                }

                .close {
                    color: #aaa;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }

                .close:hover {
                    color: #000;
                }

                .modal-body {
                    padding: 20px;
                }

                .setting-group {
                    margin-bottom: 20px;
                }

                .setting-group label {
                    display: block;
                    margin-bottom: 10px;
                    font-weight: bold;
                    color: #333;
                }

                /* Color Palette */
                .color-palette {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 10px;
                }

                .color-swatch {
                    width: 40px;
                    height: 40px;
                    border-radius: 8px;
                    cursor: pointer;
                    border: 2px solid transparent;
                    transition: all 0.3s ease;
                }

                .color-swatch:hover {
                    transform: scale(1.1);
                }

                .color-swatch.active {
                    border-color: #007bff;
                    transform: scale(1.1);
                }

                /* Number Controls */
                .number-control {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }

                .control-btn {
                    width: 40px;
                    height: 40px;
                    border: 1px solid #ddd;
                    background: white;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 18px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .control-btn:hover {
                    background: #f8f9fa;
                }

                #font-size-value, #line-height-value, #max-width-value {
                    font-weight: bold;
                    min-width: 40px;
                    text-align: center;
                }

                /* Font Select */
                #font-select {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    font-size: 14px;
                }

                /* Reader Content Styles */
                #reader-content {
                    transition: all 0.3s ease;
                    margin: 0 auto;
                    padding: 20px;
                    line-height: 1.6;
                    font-family: Arial, sans-serif;
                    font-size: 21px;
                    max-width: 900px;
                    background-color: #ffffff;
                }

                /* Responsive */
                @media (max-width: 768px) {
                    #floating-sidebar {
                        right: 10px;
                    }
                    
                    .sidebar-btn {
                        width: 45px;
                        height: 45px;
                        font-size: 16px;
                    }
                    
                    .modal-content {
                        width: 95%;
                        margin: 5% auto;
                    }
                }
                </style>

                <script>
                jQuery(document).ready(function($) {
                    // Reader settings
                    let readerSettings = {
                        backgroundColor: '#ffffff',
                        fontFamily: 'Arial',
                        fontSize: 21,
                        lineHeight: 2.3,
                        maxWidth: 900
                    };

                    // Load settings from localStorage
                    const savedSettings = localStorage.getItem('readerSettings');
                    if (savedSettings) {
                        readerSettings = { ...readerSettings, ...JSON.parse(savedSettings) };
                        applySettings();
                    }

                    // Apply settings to reader
                    function applySettings() {
                        const content = $('#reader-content');
                        content.css({
                            'background-color': readerSettings.backgroundColor,
                            'font-family': readerSettings.fontFamily,
                            'font-size': readerSettings.fontSize + 'px',
                            'line-height': readerSettings.lineHeight,
                            'max-width': readerSettings.maxWidth + 'px'
                        });
                        
                        // Update modal values
                        $('#font-size-value').text(readerSettings.fontSize);
                        $('#line-height-value').text(readerSettings.lineHeight);
                        $('#max-width-value').text(readerSettings.maxWidth);
                        $('#font-select').val(readerSettings.fontFamily);
                        
                        // Update color swatches
                        $('.color-swatch').removeClass('active');
                        $(`.color-swatch[data-color="${readerSettings.backgroundColor}"]`).addClass('active');
                        
                        // Save to localStorage
                        localStorage.setItem('readerSettings', JSON.stringify(readerSettings));
                    }

                    // Settings modal
                    $('#settings-btn').click(function() {
                        $('#settings-modal').show();
                    });

                    $('.close').click(function() {
                        $('#settings-modal').hide();
                    });

                    $(window).click(function(e) {
                        if (e.target == $('#settings-modal')[0]) {
                            $('#settings-modal').hide();
                        }
                    });

                    // Color swatches
                    $('.color-swatch').click(function() {
                        readerSettings.backgroundColor = $(this).data('color');
                        applySettings();
                    });

                    // Font select
                    $('#font-select').change(function() {
                        readerSettings.fontFamily = $(this).val();
                        applySettings();
                    });

                    // Font size controls
                    $('#font-size-up').click(function() {
                        if (readerSettings.fontSize < 40) {
                            readerSettings.fontSize++;
                            applySettings();
                        }
                    });

                    $('#font-size-down').click(function() {
                        if (readerSettings.fontSize > 12) {
                            readerSettings.fontSize--;
                            applySettings();
                        }
                    });

                    // Line height controls
                    $('#line-height-up').click(function() {
                        if (readerSettings.lineHeight < 4) {
                            readerSettings.lineHeight += 0.1;
                            applySettings();
                        }
                    });

                    $('#line-height-down').click(function() {
                        if (readerSettings.lineHeight > 1) {
                            readerSettings.lineHeight -= 0.1;
                            applySettings();
                        }
                    });

                    // Max width controls
                    $('#max-width-up').click(function() {
                        if (readerSettings.maxWidth < 1200) {
                            readerSettings.maxWidth += 50;
                            applySettings();
                        }
                    });

                    $('#max-width-down').click(function() {
                        if (readerSettings.maxWidth > 400) {
                            readerSettings.maxWidth -= 50;
                            applySettings();
                        }
                    });

                    // Sidebar button functions
                    $('#scroll-down-btn').click(function() {
                        $('html, body').animate({
                            scrollTop: $(window).scrollTop() + 300
                        }, 300);
                    });

                    $('#scroll-up-btn').click(function() {
                        $('html, body').animate({
                            scrollTop: $(window).scrollTop() - 300
                        }, 300);
                    });

                    $('#prev-btn').click(function() {
                        <?php if ($prev_chapter > 0) : ?>
                        window.location.href = '<?php echo home_url("/index.php/chuong/chuong-{$prev_chapter}-{$story_slug}/"); ?>';
                        <?php endif; ?>
                    });

                    $('#next-btn').click(function() {
                        <?php if (!empty($next_chapter_post)) : ?>
                        <?php if ($next_chapter_locked && !can_user_read_chapter($user_id, $next_chapter_post->ID, $truyen_id)) : ?>
                        // Trigger buy chapter modal
                        $('.buy-next-chapter').click();
                        <?php else: ?>
                        window.location.href = '<?php echo $next_chapter_url; ?>';
                        <?php endif; ?>
                        <?php endif; ?>
                    });

                    $('#bookmark-btn').click(function() {
                        // Toggle bookmark
                        $(this).toggleClass('active');
                        if ($(this).hasClass('active')) {
                            $(this).find('i').removeClass('far').addClass('fas');
                        } else {
                            $(this).find('i').removeClass('fas').addClass('far');
                        }
                    });

                    $('#fullscreen-btn').click(function() {
                        if (!document.fullscreenElement) {
                            document.documentElement.requestFullscreen();
                            $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
                        } else {
                            document.exitFullscreen();
                            $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
                        }
                    });

                    // Keyboard shortcuts
                    $(document).keydown(function(e) {
                        if (e.key === 'ArrowRight') {
                            $('#next-btn').click();
                        } else if (e.key === 'ArrowLeft') {
                            $('#prev-btn').click();
                        } else if (e.key === 'ArrowDown') {
                            $('#scroll-down-btn').click();
                        } else if (e.key === 'ArrowUp') {
                            $('#scroll-up-btn').click();
                        } else if (e.key === 'Escape') {
                            $('#settings-modal').hide();
                        }
                    });
                });
                </script>

                <!-- Mobile Circular Popup -->
                <div id="mobile-circular-popup" style="display: none;">
                    <?php if ($img_qc && $link_qc): ?>
                        <div class="mobile-popup-content">
                            <img src="<?php echo esc_url($img_qc); ?>" alt="Mobile Ad" />
                            <div class="close-icon">×</div>
                        </div>
                    <?php endif; ?>
                </div>

                <style>
                    @media (max-width: 768px) {
                        #mobile-circular-popup {
                            position: fixed;
                            top: 20%;
                            right: 0;
                            width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            background: #fff;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                            z-index: 1000;
                            cursor: pointer;
                            transition: transform 0.3s ease;
                        }

                        #mobile-circular-popup:hover {
                            transform: scale(1.1);
                        }

                        .mobile-popup-content {
                            width: 100%;
                            height: 100%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            position: relative;
                        }

                        .mobile-popup-content img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            border-radius: 50%;
                        }

                        .close-icon {
                            position: absolute;
                            top: 2px;
                            right: 2px;
                            width: 20px;
                            height: 20px;
                            background: rgba(0, 0, 0, 0.5);
                            color: white;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 16px;
                            font-weight: bold;
                            cursor: pointer;
                            z-index: 1001;
                        }
                    }
                </style>

                <?php
                // Kiểm tra chương tiếp theo có bị khóa không
                $next_chapter_locked = false;
                $next_chapter_price = 0;
                if ($next_chapter) {
                    $next_chapter_number = $current_chapter_number + 1;
                    $next_chapter_locked = $locked_from && $next_chapter_number >= $locked_from;
                    if ($next_chapter_locked) {
                        // Lấy giá chương (ưu tiên giá riêng của chương)
                        $next_chapter_price = get_post_meta($next_chapter->ID, '_chapter_price', true);
                        if ($next_chapter_price === '') {
                            $next_chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                        }
                        if ($next_chapter_price === '') {
                            $next_chapter_price = 0;
                        }
                    }
                }
                ?>
                <nav class="chapter-navigation mt-4">
                    <div class="row">
                        <div class="col-6">
                            <?php if ($prev_chapter > 0) : ?>
                                <a href="<?php echo home_url("/index.php/chuong/chuong-{$prev_chapter}-{$story_slug}/"); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-chevron-left"></i> Chương trước
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 text-end button-next-chapter" style="display: <?php echo $show_ad ? 'none' : 'block'; ?>;">
                            <?php 
                            // Check if next chapter exists and is locked
                            $next_chapter = $current_chapter + 1;
                            $next_chapter_url = home_url("/index.php/chuong/chuong-{$next_chapter}-{$story_slug}/");
                            
                            // Get next chapter post to check if it exists
                            $all_chapters  = get_posts(array(
                                'post_type' => 'chuong_truyen',
                                'meta_query' => array(
                                    array(
                                        'key' => 'chuong_with_truyen',
                                        'value' => $truyen_id,
                                        'compare' => '='
                                    )
                                ),
                                'posts_per_page' => -1,
                                'orderby' => 'date',
                                'order' => 'ASC',
                            ));


                            $next_chapter_post = null;

                            for ($i = 0; $i < count($all_chapters); $i++) {
                                if ($all_chapters[$i]->ID == get_the_ID() && isset($all_chapters[$i + 1])) {
                                    $next_chapter_post = $all_chapters[$i + 1];
                                    break;
                                }
                            }


                            if (!empty($next_chapter_post)) :
                                $next_chapter_locked = $locked_from && $next_chapter >= $locked_from;
                                // Get chapter price
                                $next_chapter_price = get_post_meta($next_chapter_post->ID, '_chapter_price', true);
                                if ($next_chapter_price === '') {
                                    $next_chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                                }
                                if ($next_chapter_price === '') {
                                    $next_chapter_price = 0;
                                }
                                
                                if ($next_chapter_locked && !can_user_read_chapter($user_id, $next_chapter_post->ID, $truyen_id)) : ?>
                                    <a href="javascript:void(0)" class="btn btn-outline-primary buy-next-chapter" 
                                       data-chapter-id="<?php echo $next_chapter_post->ID; ?>"
                                       data-truyen-id="<?php echo $truyen_id; ?>"
                                       data-price="<?php echo number_format($next_chapter_price); ?>">
                                        Chương sau <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $next_chapter_url; ?>" class="btn btn-outline-primary">
                                        Chương sau <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>

                <script>
                jQuery(document).ready(function($) {
                    // Define redirectUrl outside of conditions
                    const redirectUrl = <?php echo json_encode($link_qc); ?>;

                    // *** XỬ LÝ QUẢNG CÁO ***
                    <?php if ($show_ad && $img_qc && $link_qc): ?>
                    const truyenId = <?php echo json_encode($truyen_id); ?>;
                    const adClickedKey = 'shopee_ad_clicked_' + truyenId;
                    const adBlock = document.getElementById('chapter-ad-block');
                    const content = document.getElementById('chapter-content');
                    const btnNext = document.getElementsByClassName('button-next-chapter');

                    // Nếu đã click quảng cáo ở truyện này rồi thì ẩn quảng cáo
                    if (localStorage.getItem(adClickedKey) === 'true') {
                        adBlock.style.display = 'none';
                        btnNext[0].style.display = 'block';
                        content.style.display = 'block';
                    } else {
                        // Gán click cho toàn bộ khối quảng cáo
                        adBlock.addEventListener('click', function() {
                            window.open(redirectUrl, "_blank");
                            adBlock.style.display = 'none';
                            content.style.display = 'block';
                            btnNext[0].style.display = 'block';
                            localStorage.setItem(adClickedKey, 'true');
                        });
                        
                        // Thêm hiệu ứng hover
                        adBlock.addEventListener('mouseenter', function() {
                            this.style.transform = 'scale(1.02)';
                            this.style.transition = 'transform 0.3s ease';
                        });
                        
                        adBlock.addEventListener('mouseleave', function() {
                            this.style.transform = 'scale(1)';
                        });
                    }
                    <?php endif; ?>

                    // *** XỬ LÝ MOBILE POPUP ***
                    <?php if ($img_qc && $link_qc): ?>
                    const mobilePopup = document.getElementById('mobile-circular-popup');
                    const mobilePopupKey = 'mobile_popup_clicked';
                    const pageLoadCountKey = 'mobile_popup_page_loads';
                    
                    // Lấy số lần load trang
                    let pageLoads = parseInt(localStorage.getItem(pageLoadCountKey) || '0');
                    pageLoads++;
                    localStorage.setItem(pageLoadCountKey, pageLoads);

                    // Kiểm tra xem popup đã được click chưa
                    const isPopupClicked = localStorage.getItem(mobilePopupKey) === 'true';
                    
                    // Hiển thị popup nếu:
                    // 1. Chưa được click hoặc
                    // 2. Đã được click và đã load trang 10 lần
                    if (!isPopupClicked || (isPopupClicked && pageLoads % 10 === 0)) {
                        if (window.innerWidth <= 768 && mobilePopup) {
                            mobilePopup.style.display = 'block';
                        }
                    }

                    // Hàm xử lý click popup
                    function handlePopupClick() {
                        window.open(redirectUrl, "_blank");
                        mobilePopup.style.display = 'none';
                        localStorage.setItem(mobilePopupKey, 'true');
                        localStorage.setItem(pageLoadCountKey, '0');
                    }

                    // Gán click cho mobile popup và close icon
                    if (mobilePopup) {
                        // Click vào popup
                        mobilePopup.addEventListener('click', function(e) {
                            // Nếu click vào close icon thì không xử lý ở đây
                            if (!e.target.closest('.close-icon')) {
                                handlePopupClick();
                            }
                        });

                        // Click vào close icon
                        const closeIcon = mobilePopup.querySelector('.close-icon');
                        if (closeIcon) {
                            closeIcon.addEventListener('click', function(e) {
                                e.stopPropagation(); // Ngăn event bubble lên popup
                                handlePopupClick();
                            });
                        }
                    }
                    <?php endif; ?>

                    $('.buy-next-chapter').on('click', function(e) {
                        e.preventDefault();
                        var chapterId = $(this).data('chapter-id');
                        var truyenId = $(this).data('truyen-id');
                        var price = $(this).data('price');
                        
                        Swal.fire({
                            title: 'Xác nhận mua chương?',
                            html: `Bạn có muốn mua chương này với giá <strong>${price} Kim tệ</strong> không?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Mua ngay',
                            cancelButtonText: 'Hủy',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                return $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    type: 'POST',
                                    data: {
                                        action: 'buy_chapter',
                                        chapter_id: chapterId,
                                        truyen_id: truyenId,
                                        nonce: '<?php echo wp_create_nonce('buy_chapter_nonce'); ?>'
                                    }
                                });
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (result.value.success) {
                                    Swal.fire({
                                        title: 'Thành công!',
                                        text: result.value.data.message,
                                        icon: 'success'
                                    }).then(() => {
                                        // Chuyển hướng đến chương đã mua
                                        window.location.href = '<?php echo get_permalink($next_chapter_post->ID); ?>';
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: result.value.data,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    });
                });
                </script>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
// Thêm phần comment
if (comments_open() || get_comments_number()) :
    echo '<div class="container py-4">';
    echo '<div class="card">';
    echo '<div class="card-body">';
    echo '<h3 class="card-title mb-4">Bình luận</h3>';
    // Đảm bảo post ID được truyền vào
    global $post;
    $post_id = $post->ID;
    comments_template('', true);
    echo '</div>';
    echo '</div>';
    echo '</div>';
endif;
?>

<?php get_footer(); ?> 
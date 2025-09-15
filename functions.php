<?php
/**
 * commicpro functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package commicpro
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Lấy top truyện có lượt xem cao nhất trong tuần
 * @param int $limit Số lượng truyện lấy
 * @return array Mảng post_id
 */
function get_top_viewed_truyen_week($limit = 10) {
    $args = array(
        'post_type'      => 'truyen_chu',
        'posts_per_page' => $limit,
        'meta_key'       => '_weekly_view_count',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => '_weekly_view_count',
                'value'   => '0',
                'compare' => '>',
                'type'    => 'NUMERIC',
            ),
        ),
    );
    return new WP_Query($args);
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function commicpro_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on commicpro, use a find and replace
		* to change 'commicpro' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'commicpro', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'commicpro' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'commicpro_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'commicpro_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function commicpro_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'commicpro_content_width', 640 );
}
add_action( 'after_setup_theme', 'commicpro_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function commicpro_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'commicpro' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'commicpro' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'commicpro_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function commicpro_scripts() {
	// Enqueue Bootstrap CSS from CDN
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );
	
	// Enqueue theme style
	wp_enqueue_style( 'commicpro-style', get_stylesheet_uri(), array('bootstrap'), _S_VERSION );
	wp_style_add_data( 'commicpro-style', 'rtl', 'replace' );

	// Enqueue Bootstrap JS from CDN
	wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.2', true );
	
	// Enqueue theme scripts
	wp_enqueue_script( 'commicpro-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Flickity for homepage only
	if (is_front_page()) {
		wp_enqueue_style('flickity-css', get_template_directory_uri() . '/assets/libs/flickity/flickity.css', array(), '2.4.0');
		wp_enqueue_script('flickity-js', get_template_directory_uri() . '/assets/libs/flickity/flickity.pkgd.min.js', array('jquery'), '2.4.0', true);
	}
}
add_action('wp_enqueue_scripts', 'commicpro_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

require get_template_directory() . '/inc/reading-history.php';

function register_post_type_truyen_chu() {
    register_post_type('truyen_chu', array(
        'label' => 'Truyện chữ',
        'description' => 'Các truyện chữ dạng tiểu thuyết',
        'public' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'sticky'),
        'rewrite' => array('slug' => 'truyen-chu'),
        'has_archive' => true,
		'taxonomies' => array('post_tag'),
        'labels' => array(
            'name' => 'Truyện chữ',
            'singular_name' => 'Truyện chữ',
            'menu_name' => 'Truyện chữ',
            'all_items' => 'Tất cả truyện',
            'add_new' => 'Thêm truyện',
            'add_new_item' => 'Thêm truyện mới',
            'edit_item' => 'Chỉnh sửa truyện',
            'new_item' => 'Truyện mới',
            'view_item' => 'Xem truyện',
            'search_items' => 'Tìm truyện',
        )
    ));
}
add_action('init', 'register_post_type_truyen_chu');

// Hỗ trợ sticky posts cho truyen_chu
function enable_sticky_for_truyen_chu() {
    // Thêm meta box sticky cho truyen_chu
    add_meta_box(
        'truyen_chu_sticky',
        'Truyện nổi bật',
        'truyen_chu_sticky_meta_box',
        'truyen_chu',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'enable_sticky_for_truyen_chu');

// Hiển thị meta box sticky
function truyen_chu_sticky_meta_box($post) {
    wp_nonce_field('truyen_chu_sticky_nonce', 'truyen_chu_sticky_nonce');
    $is_sticky = get_post_meta($post->ID, '_sticky_truyen', true);
    ?>
    <label>
        <input type="checkbox" name="sticky_truyen" value="1" <?php checked($is_sticky, '1'); ?> />
        Đánh dấu là truyện nổi bật
    </label>
    <p><em>Truyện nổi bật sẽ hiển thị ở đầu danh sách.</em></p>
    <?php
}

// Lưu thông tin sticky
function save_truyen_chu_sticky($post_id) {
    if (!isset($_POST['truyen_chu_sticky_nonce']) || !wp_verify_nonce($_POST['truyen_chu_sticky_nonce'], 'truyen_chu_sticky_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $is_sticky = isset($_POST['sticky_truyen']) ? '1' : '0';
    update_post_meta($post_id, '_sticky_truyen', $is_sticky);
}
add_action('save_post_truyen_chu', 'save_truyen_chu_sticky');

// Thêm cột sticky trong admin list
function add_sticky_column_to_truyen_chu($columns) {
    $columns['sticky_truyen'] = 'Nổi bật';
    return $columns;
}
add_filter('manage_truyen_chu_posts_columns', 'add_sticky_column_to_truyen_chu');

// Hiển thị nội dung cột sticky
function show_sticky_column_content($column, $post_id) {
    if ($column === 'sticky_truyen') {
        $is_sticky = get_post_meta($post_id, '_sticky_truyen', true);
        if ($is_sticky === '1') {
            echo '<span style="color: #d63384;">★ Nổi bật</span>';
        } else {
            echo '—';
        }
    }
}
add_action('manage_truyen_chu_posts_custom_column', 'show_sticky_column_content', 10, 2);

// Function để lấy sticky truyen_chu
function get_sticky_truyen_chu() {
    $args = array(
        'post_type' => 'truyen_chu',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_sticky_truyen',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    return $query->posts ? wp_list_pluck($query->posts, 'ID') : array();
}

// Sửa đổi query để hiển thị sticky posts đầu tiên
function modify_truyen_chu_query_for_sticky($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_post_type_archive('truyen_chu') || (is_home() && get_query_var('post_type') === 'truyen_chu')) {
            $sticky_posts = get_sticky_truyen_chu();
            
            if (!empty($sticky_posts)) {
                // Nếu đang ở trang đầu tiên
                if (!$query->get('paged') || $query->get('paged') <= 1) {
                    $query->set('post__not_in', array());
                    $query->set('orderby', 'menu_order date');
                    $query->set('order', 'DESC');
                    
                    // Thêm meta query để ưu tiên sticky posts
                    $meta_query = $query->get('meta_query') ?: array();
                    $meta_query[] = array(
                        'relation' => 'OR',
                        array(
                            'key' => '_sticky_truyen',
                            'value' => '1',
                            'compare' => '='
                        ),
                        array(
                            'key' => '_sticky_truyen',
                            'compare' => 'NOT EXISTS'
                        )
                    );
                    $query->set('meta_query', $meta_query);
                }
            }
        }
    }
}
add_action('pre_get_posts', 'modify_truyen_chu_query_for_sticky');

function register_taxonomies_truyen_chu() {
    // Tác giả
    register_taxonomy('tac_gia', 'truyen_chu', array(
        'label' => 'Tác giả',
        'public' => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Tác giả',
            'singular_name' => 'Tác giả',
            'search_items' => 'Tìm tác giả',
            'all_items' => 'Tất cả tác giả',
            'edit_item' => 'Chỉnh sửa tác giả',
            'add_new_item' => 'Thêm tác giả',
        )
    ));

    // Thể loại
    register_taxonomy('the_loai', 'truyen_chu', array(
        'label' => 'Thể loại',
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Thể loại',
            'singular_name' => 'Thể loại',
            'search_items' => 'Tìm thể loại',
            'all_items' => 'Tất cả thể loại',
            'edit_item' => 'Chỉnh sửa thể loại',
            'add_new_item' => 'Thêm thể loại',
        )
    ));

    // Năm phát hành
    register_taxonomy('nam_phat_hanh', 'truyen_chu', array(
        'label' => 'Năm phát hành',
        'public' => true,
        'hierarchical' => false,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Năm phát hành',
            'singular_name' => 'Năm phát hành',
            'search_items' => 'Tìm năm',
            'all_items' => 'Tất cả năm',
            'edit_item' => 'Chỉnh sửa năm',
            'add_new_item' => 'Thêm năm phát hành',
        )
    ));

    // Trạng thái
    register_taxonomy('trang_thai', 'truyen_chu', array(
        'label' => 'Trạng thái',
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'labels' => array(
            'name' => 'Trạng thái',
            'singular_name' => 'Trạng thái',
            'search_items' => 'Tìm trạng thái',
            'all_items' => 'Tất cả trạng thái',
            'edit_item' => 'Chỉnh sửa trạng thái',
            'add_new_item' => 'Thêm trạng thái',
        )
    ));
}
add_action('init', 'register_taxonomies_truyen_chu');

function register_post_type_chuong_truyen() {
    register_post_type('chuong_truyen', array(
        'label' => 'Chương truyện',
        'public' => true,
        'show_ui' => true,
        'has_archive' => false,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'chuong'),
        'supports' => array('title', 'editor', 'comments'),
        'labels' => array(
            'name' => 'Chương',
            'singular_name' => 'Chương',
        )
    ));
}
add_action('init', 'register_post_type_chuong_truyen');

// 1. Thêm cột mới "Tên truyện"
add_filter('manage_chuong_truyen_posts_columns', 'add_truyen_column_to_chuong');
function add_truyen_column_to_chuong($columns) {
    $columns['chuong_with_truyen'] = 'Thuộc Truyện';
    return $columns;
}

// 2. Hiển thị nội dung cột
add_action('manage_chuong_truyen_posts_custom_column', 'show_truyen_column_in_chuong', 10, 2);
function show_truyen_column_in_chuong($column, $post_id) {
    if ($column === 'chuong_with_truyen') {
        $truyen = get_field('chuong_with_truyen', $post_id); // Field ACF Post Object
        if ($truyen) {
            echo '<a href="' . get_edit_post_link($truyen->ID) . '">' . esc_html(get_the_title($truyen->ID)) . '</a>';
        } else {
            echo '<em>Chưa chọn</em>';
        }
    }
}

// Thêm meta box cho truyện chữ
function add_truyen_chu_meta_boxes() {
    add_meta_box(
        'truyen_chu_settings',
        'Cài đặt truyện',
        'render_truyen_chu_settings',
        'truyen_chu',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_truyen_chu_meta_boxes');

// Render meta box
function render_truyen_chu_settings($post) {
    wp_nonce_field('truyen_chu_settings_nonce', 'truyen_chu_settings_nonce');
    
    $locked_from = get_post_meta($post->ID, '_locked_from', true);
    $chapter_price = get_post_meta($post->ID, '_chapter_price', true);
    
    // Nếu chưa có giá hoặc giá bằng 0, set giá mặc định là 500
    if ($chapter_price === '' || $chapter_price == 0) {
        $chapter_price = 500;
    }
    ?>
    <div class="form-group">
        <label for="locked_from">Khóa từ chương:</label>
        <input type="number" id="locked_from" name="locked_from" value="<?php echo esc_attr($locked_from); ?>" min="1" class="form-control">
        <p class="description">Để trống nếu không muốn khóa chương nào</p>
    </div>
    <div class="form-group">
        <label for="chapter_price">Giá mỗi chương (VNĐ):</label>
        <input type="number" id="chapter_price" name="chapter_price" value="<?php echo esc_attr($chapter_price); ?>" min="0" class="form-control">
        <p class="description">Giá mặc định: 500 VNĐ</p>
    </div>
    <?php
}

// Lưu meta box
function save_truyen_chu_settings($post_id) {
    if (!isset($_POST['truyen_chu_settings_nonce']) || 
        !wp_verify_nonce($_POST['truyen_chu_settings_nonce'], 'truyen_chu_settings_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['locked_from'])) {
        update_post_meta($post_id, '_locked_from', sanitize_text_field($_POST['locked_from']));
    }
    
    if (isset($_POST['chapter_price'])) {
        $chapter_price = sanitize_text_field($_POST['chapter_price']);
        
        // Nếu không nhập giá hoặc giá bằng 0, set giá mặc định là 500
        if ($chapter_price === '' || $chapter_price == 0) {
            $chapter_price = 500;
        }
        
        update_post_meta($post_id, '_chapter_price', $chapter_price);
    } else {
        // Nếu không có field chapter_price được gửi lên, set giá mặc định là 500
        update_post_meta($post_id, '_chapter_price', 500);
    }
}
add_action('save_post_truyen_chu', 'save_truyen_chu_settings');

// Thêm cột giá tiền vào danh sách truyện
function add_truyen_chu_price_column($columns) {
    $columns['chapter_price'] = 'Giá chương';
    return $columns;
}
add_filter('manage_truyen_chu_posts_columns', 'add_truyen_chu_price_column');

// Hiển thị giá tiền trong cột
function display_truyen_chu_price_column($column, $post_id) {
    if ($column === 'chapter_price') {
        $price = get_post_meta($post_id, '_chapter_price', true);
        // Nếu không có giá hoặc giá bằng 0, sử dụng giá mặc định 500
        if ($price === '' || $price == 0) {
            $price = 500;
        }
        echo number_format($price) . ' VNĐ';
    }
}
add_action('manage_truyen_chu_posts_custom_column', 'display_truyen_chu_price_column', 10, 2);

// Thêm meta box cho user để quản lý số dư (sử dụng user meta)
function add_user_balance_meta_box() {
    add_meta_box(
        'user_balance',
        'Số dư tài khoản',
        'render_user_balance_meta_box',
        'user',
        'normal',
        'high'
    );
}
add_action('add_user_meta_boxes', 'add_user_balance_meta_box');

// Render meta box số dư (sử dụng user meta)
function render_user_balance_meta_box($user) {
    // Lấy số dư từ user meta
    $balance = get_user_meta($user->ID, '_user_balance', true);
    if ($balance === '') { // Khởi tạo nếu chưa có
        $balance = 0;
    }
    ?>
    <div class="form-group">
        <label for="user_balance">Số dư (VNĐ):</label>
        <input type="number" id="user_balance" name="user_balance" value="<?php echo esc_attr($balance); ?>" min="0" step="0.01" class="form-control">
    </div>
    <?php
}

// Lưu số dư user (sử dụng user meta)
function save_user_balance($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    if (isset($_POST['user_balance'])) {
        // Lưu vào user meta
        update_user_meta($user_id, '_user_balance', sanitize_text_field($_POST['user_balance']));
    }
}
add_action('personal_options_update', 'save_user_balance');
add_action('edit_user_profile_update', 'save_user_balance');

// Hàm lưu thông tin mua chương
function save_chapter_purchase($user_id, $chapter_id, $truyen_id) {
    // Lấy danh sách chương đã mua của user
    $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
    if (!is_array($purchased_chapters)) {
        $purchased_chapters = array();
    }
    
    // Lấy giá chương (ưu tiên giá riêng của chương, nếu không có thì lấy giá mặc định từ truyện)
    $chapter_price = get_post_meta($chapter_id, '_chapter_price', true);
    if ($chapter_price === '') {
        $chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
    }
    if ($chapter_price === '' || $chapter_price == 0) {
        $chapter_price = 500; // Giá mặc định
    }
    $chapter_price = floatval($chapter_price);
    
    // Thêm thông tin mua chương vào mảng, sử dụng chapter_id làm key để dễ kiểm tra sự tồn tại
    $purchased_chapters[$chapter_id] = array(
        'chapter_id' => $chapter_id,
        'truyen_id' => $truyen_id,
        'purchase_date' => current_time('mysql'),
        'price' => $chapter_price
    );
    
    // Lưu lại user meta
    update_user_meta($user_id, '_purchased_chapters', $purchased_chapters);
    
    // Lưu log giao dịch
    $transaction_log = get_user_meta($user_id, '_chapter_purchase_log', true);
    if (!is_array($transaction_log)) {
        $transaction_log = array();
    }
    
    $transaction_log[] = array(
        'chapter_id' => $chapter_id,
        'truyen_id' => $truyen_id,
        'purchase_date' => current_time('mysql'),
        'price' => $chapter_price,
        'type' => 'purchase'
    );
    
    update_user_meta($user_id, '_chapter_purchase_log', $transaction_log);
}

// Hàm kiểm tra user đã mua chương chưa
function has_user_purchased_chapter($user_id, $chapter_id) {
    // Kiểm tra trạng thái VIP trước
    if (check_user_vip_status($user_id)) {
        return true; // Nếu là VIP thì có thể đọc tất cả chương
    }

    $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
    if (!is_array($purchased_chapters)) {
        return false;
    }
    // Kiểm tra sự tồn tại của chapter_id trong mảng đã mua
    return isset($purchased_chapters[$chapter_id]);
}

// Hàm kiểm tra quyền đọc chương
function can_user_read_chapter($user_id, $chapter_id, $truyen_id) {
    // Nếu là VIP thì có thể đọc tất cả chương
    if (check_user_vip_status($user_id)) {
        return true;
    }

    // Kiểm tra đã mua chương chưa
    return has_user_purchased_chapter($user_id, $chapter_id);
}

// Hàm lấy danh sách chương đã mua của user
function get_user_purchased_chapters($user_id, $truyen_id = null) {
    $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
    if (!is_array($purchased_chapters)) {
        return array();
    }
    
    if ($truyen_id) {
        // Lọc theo truyện
        return array_filter($purchased_chapters, function($purchase) use ($truyen_id) {
            return $purchase['truyen_id'] == $truyen_id;
        });
    }
    
    return $purchased_chapters;
}

// Hàm lấy lịch sử giao dịch của user
function get_user_purchase_history($user_id) {
    return get_user_meta($user_id, '_chapter_purchase_log', true);
}

// Thêm trang quản lý chương đã mua vào profile user
function add_purchased_chapters_to_profile($user) {
    $purchased_chapters = get_user_purchased_chapters($user->ID);
    if (!empty($purchased_chapters)) : ?>
        <h3>Chương đã mua</h3>
        <table class="form-table">
            <tr>
                <th>Truyện</th>
                <th>Chương</th>
                <th>Ngày mua</th>
                <th>Giá</th>
            </tr>
            <?php foreach ($purchased_chapters as $chapter_id => $purchase) : 
                $truyen = get_post($purchase['truyen_id']);
                $chapter = get_post($chapter_id);
                if ($truyen && $chapter) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_permalink($truyen->ID); ?>">
                                <?php echo esc_html($truyen->post_title); ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo get_permalink($chapter->ID); ?>">
                                <?php echo esc_html($chapter->post_title); ?>
                            </a>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($purchase['purchase_date'])); ?></td>
                        <td><?php echo number_format($purchase['price']); ?> VNĐ</td>
                    </tr>
                <?php endif;
            endforeach; ?>
        </table>
    <?php endif;
}
add_action('show_user_profile', 'add_purchased_chapters_to_profile');
add_action('edit_user_profile', 'add_purchased_chapters_to_profile');

// Thêm SweetAlert2 vào theme
function add_sweetalert2_scripts() {
    wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', array(), '11.0.0', true);
}
add_action('wp_enqueue_scripts', 'add_sweetalert2_scripts');

// Thêm meta box cho chương truyện
function add_chuong_truyen_meta_boxes() {
    add_meta_box(
        'chuong_truyen_settings',
        'Cài đặt chương',
        'render_chuong_truyen_settings',
        'chuong_truyen',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_chuong_truyen_meta_boxes');

// Render meta box cho chương truyện
function render_chuong_truyen_settings($post) {
    wp_nonce_field('chuong_truyen_settings_nonce', 'chuong_truyen_settings_nonce');
    
    $chapter_price = get_post_meta($post->ID, '_chapter_price', true);
    ?>
    <div class="form-group">
        <label for="chapter_price">Giá chương (VNĐ):</label>
        <input type="number" id="chapter_price" name="chapter_price" value="<?php echo esc_attr($chapter_price); ?>" min="0" class="form-control">
        <p class="description">Để trống nếu muốn sử dụng giá mặc định từ truyện</p>
    </div>
    <?php
}

// Lưu meta box chương truyện
function save_chuong_truyen_settings($post_id) {
    if (!isset($_POST['chuong_truyen_settings_nonce']) || 
        !wp_verify_nonce($_POST['chuong_truyen_settings_nonce'], 'chuong_truyen_settings_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['chapter_price'])) {
        update_post_meta($post_id, '_chapter_price', sanitize_text_field($_POST['chapter_price']));
    }
}
add_action('save_post_chuong_truyen', 'save_chuong_truyen_settings');

// Thêm cột giá tiền vào danh sách chương
function add_chuong_truyen_price_column($columns) {
    $columns['chapter_price'] = 'Giá chương';
    return $columns;
}
add_filter('manage_chuong_truyen_posts_columns', 'add_chuong_truyen_price_column');

// Hiển thị giá tiền trong cột
function display_chuong_truyen_price_column($column, $post_id) {
    if ($column === 'chapter_price') {
        $price = get_post_meta($post_id, '_chapter_price', true);
        if ($price && $price > 0) {
            echo number_format($price) . ' VNĐ';
        } else {
            // Lấy giá từ truyện gốc
            $truyen_id = get_field('chuong_with_truyen', $post_id);
            if ($truyen_id) {
                $truyen_price = get_post_meta($truyen_id, '_chapter_price', true);
                if ($truyen_price && $truyen_price > 0) {
                    echo number_format($truyen_price) . ' VNĐ (Từ truyện)';
                } else {
                    echo '500 VNĐ (Giá mặc định)';
                }
            } else {
                echo '500 VNĐ (Giá mặc định)';
            }
        }
    }
}
add_action('manage_chuong_truyen_posts_custom_column', 'display_chuong_truyen_price_column', 10, 2);

// Cập nhật hàm handle_buy_chapter để xử lý giá riêng của chương
function handle_buy_chapter() {
    check_ajax_referer('buy_chapter_nonce', 'nonce');
    
    $chapter_id = intval($_POST['chapter_id']);
    $truyen_id = intval($_POST['truyen_id']);
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        wp_send_json_error('Vui lòng đăng nhập để mua chương');
        return;
    }
    
    // Kiểm tra đã mua chưa
    if (has_user_purchased_chapter($user_id, $chapter_id)) {
        wp_send_json_error('Bạn đã mua chương này rồi');
        return;
    }
    
    // Lấy giá chương (ưu tiên giá riêng của chương, nếu không có thì lấy giá mặc định từ truyện)
    $chapter_price = get_post_meta($chapter_id, '_chapter_price', true);
    if ($chapter_price === '') {
        $chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
    }
    if ($chapter_price === '' || $chapter_price == 0) {
        $chapter_price = 500; // Giá mặc định
    }
    $chapter_price = floatval($chapter_price);

    // Kiểm tra số dư từ user meta
    $user_balance = get_user_meta($user_id, '_user_balance', true);
    if ($user_balance === '') {
        $user_balance = 0;
    }
    $user_balance = floatval($user_balance);

    if ($user_balance < $chapter_price) {
        wp_send_json_error('Số dư không đủ để mua chương này');
        return;
    }
    
    // Trừ tiền và cập nhật user meta số dư
    $new_balance = $user_balance - $chapter_price;
    $updated = update_user_meta($user_id, '_user_balance', $new_balance);

    if ($updated === false) {
        error_log('Lỗi cập nhật user meta _user_balance cho user ' . $user_id . ': ' . print_r($updated, true));
        wp_send_json_error('Có lỗi xảy ra khi cập nhật số dư user meta.');
        return;
    }

    // Lưu thông tin mua chương
    save_chapter_purchase($user_id, $chapter_id, $truyen_id);
    
    wp_send_json_success(array(
        'message' => 'Mua chương thành công',
        'redirect' => get_permalink($chapter_id)
    ));
}
add_action('wp_ajax_buy_chapter', 'handle_buy_chapter');

// Hàm xử lý mua combo
function handle_buy_combo() {
    check_ajax_referer('buy_chapter_nonce', 'nonce');
    
    $truyen_id = intval($_POST['truyen_id']);
    $user_id = get_current_user_id();
    
    if (!$user_id) {
        wp_send_json_error('Vui lòng đăng nhập để mua combo');
        return;
    }

    // Lấy thông tin truyện
    $trang_thai = get_the_terms($truyen_id, 'trang_thai');
    $is_completed = false;
    $discount_percentage = 0;
    
    if ($trang_thai && !is_wp_error($trang_thai)) {
        $is_completed = ($trang_thai[0]->slug === 'da-hoan-thanh');
        if ($is_completed) {
            $discount_percentage = get_field('giam_gia_bao_nhieu', $truyen_id);
            error_log('Truyện đã hoàn thành, giảm giá: ' . $discount_percentage . '%');
        }
    }

    // Lấy danh sách chương
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
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    $chapters = new WP_Query($args);

    // Tính tổng giá combo chỉ cho các chương chưa mua
    $combo_original = 0;
    $all_purchased = true;
    $chapter_number = 1;
    $locked_from = get_post_meta($truyen_id, '_locked_from', true);
    $chapters_to_purchase = array();
    
    foreach ($chapters->posts as $chapter_post) {
        $is_locked = $locked_from && $chapter_number >= $locked_from;
        $is_purchased = has_user_purchased_chapter($user_id, $chapter_post->ID);

        $chapter_price = get_post_meta($chapter_post->ID, '_chapter_price', true);
        if ($chapter_price === '') {
            $chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
        }
        if ($chapter_price === '' || $chapter_price == 0) {
            $chapter_price = 500; // Giá mặc định
        }

        if ($is_locked && !$is_purchased) {
            $combo_original += floatval($chapter_price);
            $all_purchased = false;
            $chapters_to_purchase[] = array(
                'id' => $chapter_post->ID,
                'price' => floatval($chapter_price)
            );
        }
        $chapter_number++;
    }

    // Kiểm tra nếu không có chương nào cần mua
    if ($combo_original <= 0) {
        wp_send_json_error('Không có chương nào cần mua trong combo');
        return;
    }

    // Tính giá combo sau giảm
    $combo_price = $combo_original;
    if ($is_completed && $discount_percentage > 0) {
        $combo_price = $combo_original * (1 - ($discount_percentage / 100));
        error_log('Áp dụng giảm giá: ' . $discount_percentage . '%');
        error_log('Giá gốc: ' . $combo_original);
        error_log('Giá sau giảm: ' . $combo_price);
    } else {
        error_log('Không áp dụng giảm giá. Lý do:');
        error_log('- Truyện hoàn thành: ' . ($is_completed ? 'Có' : 'Không'));
        error_log('- Phần trăm giảm giá: ' . $discount_percentage);
    }

    // Kiểm tra số dư
    $user_balance = get_user_meta($user_id, '_user_balance', true);
    if ($user_balance === '') {
        $user_balance = 0;
    }
    $user_balance = floatval($user_balance);

    if ($user_balance < $combo_price) {
        wp_send_json_error('Số dư không đủ để mua combo');
        return;
    }

    // Trừ tiền và cập nhật số dư
    $new_balance = $user_balance - $combo_price;
    
    // Thêm log để debug
    error_log('User ID: ' . $user_id);
    error_log('Old Balance: ' . $user_balance);
    error_log('Combo Original Price: ' . $combo_original);
    error_log('Discount Percentage: ' . $discount_percentage);
    error_log('Combo Final Price: ' . $combo_price);
    error_log('New Balance: ' . $new_balance);
    
    // Cập nhật số dư với kiểm tra kỹ hơn
    $updated = update_user_meta($user_id, '_user_balance', $new_balance);
    
    if ($updated === false) {
        error_log('Lỗi cập nhật user meta _user_balance cho user ' . $user_id);
        error_log('Giá trị cũ: ' . $user_balance);
        error_log('Giá trị mới: ' . $new_balance);
        wp_send_json_error('Có lỗi xảy ra khi cập nhật số dư. Vui lòng thử lại sau.');
        return;
    }

    // Kiểm tra lại số dư sau khi cập nhật
    $check_balance = get_user_meta($user_id, '_user_balance', true);
    if ($check_balance != $new_balance) {
        error_log('Số dư không khớp sau khi cập nhật');
        error_log('Số dư mong đợi: ' . $new_balance);
        error_log('Số dư thực tế: ' . $check_balance);
        wp_send_json_error('Có lỗi xảy ra khi cập nhật số dư. Vui lòng thử lại sau.');
        return;
    }

    // Mua tất cả các chương chưa mua với giá 0 (vì đã trừ tiền combo)
    foreach ($chapters_to_purchase as $chapter) {
        // Lưu thông tin mua chương với giá 0 vì đã trả qua combo
        $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
        if (!is_array($purchased_chapters)) {
            $purchased_chapters = array();
        }
        
        $purchased_chapters[$chapter['id']] = array(
            'chapter_id' => $chapter['id'],
            'truyen_id' => $truyen_id,
            'purchase_date' => current_time('mysql'),
            'price' => 0, // Giá 0 vì đã trả qua combo
            'is_combo_purchase' => true // Đánh dấu là mua qua combo
        );
        
        update_user_meta($user_id, '_purchased_chapters', $purchased_chapters);
        
        // Lưu log giao dịch
        $transaction_log = get_user_meta($user_id, '_chapter_purchase_log', true);
        if (!is_array($transaction_log)) {
            $transaction_log = array();
        }
        
        $transaction_log[] = array(
            'chapter_id' => $chapter['id'],
            'truyen_id' => $truyen_id,
            'purchase_date' => current_time('mysql'),
            'price' => 0, // Giá 0 vì đã trả qua combo
            'type' => 'combo_purchase'
        );
        
        update_user_meta($user_id, '_chapter_purchase_log', $transaction_log);
    }
    
    wp_send_json_success(array(
        'message' => 'Mua combo thành công',
        'new_balance' => $new_balance
    ));
}
add_action('wp_ajax_buy_combo', 'handle_buy_combo');

// Đăng ký Custom Post Type: Thông báo
function register_post_type_thong_bao() {
    $labels = array(
        'name' => 'Thông báo',
        'singular_name' => 'Thông báo',
        'add_new' => 'Thêm mới',
        'add_new_item' => 'Thêm mới Thông báo',
        'edit_item' => 'Chỉnh sửa Thông báo',
        'new_item' => 'Thông báo mới',
        'view_item' => 'Xem Thông báo',
        'search_items' => 'Tìm kiếm Thông báo',
        'not_found' => 'Không tìm thấy Thông báo nào',
        'not_found_in_trash' => 'Không tìm thấy Thông báo nào trong thùng rác',
        'menu_name' => 'Thông báo',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite' => array('slug' => 'thong-bao'),
        'show_in_rest' => true, // Cho phép sử dụng với Gutenberg/REST API
        'taxonomies' => array('danh-muc-thong-bao'), // Gán taxonomy
    );

    register_post_type('thong-bao', $args);
}
add_action('init', 'register_post_type_thong_bao');

// Đăng ký Taxonomy: Danh mục Thông báo
function register_taxonomy_danh_muc_thong_bao() {
    $labels = array(
        'name'              => 'Danh mục Thông báo',
        'singular_name'     => 'Danh mục',
        'search_items'      => 'Tìm danh mục',
        'all_items'         => 'Tất cả danh mục',
        'edit_item'         => 'Chỉnh sửa danh mục',
        'update_item'       => 'Cập nhật danh mục',
        'add_new_item'      => 'Thêm danh mục mới',
        'new_item_name'     => 'Tên danh mục mới',
        'menu_name'         => 'Danh mục Thông báo',
    );

    $args = array(
        'hierarchical'      => true, // true = như category, false = như tag
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'danh-muc-thong-bao'),
        'show_in_rest'      => true,
    );

    register_taxonomy('danh-muc-thong-bao', array('thong-bao'), $args);
}
add_action('init', 'register_taxonomy_danh_muc_thong_bao');


function register_my_menus() {
    register_nav_menus(
        array(
            'header-menu' => __( 'Header Menu' )
        )
    );
}
add_action( 'init', 'register_my_menus' );

class Bootstrap_Dropdown_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		// Nếu item là divider (custom class)
		if ( in_array( 'dropdown-divider', $classes ) ) {
			$output .= '<li><hr class="dropdown-divider"></li>';
			return;
		}

		$class_names = join( ' ', array_filter( $classes ) );
		$output .= sprintf(
			'<li><a class="dropdown-item %s" href="%s">%s</a></li>',
			esc_attr( $class_names ),
			esc_url( $item->url ),
			esc_html( $item->title )
		);
	}
}



function register_my_menus_footer() {
    register_nav_menus(
        array(
            'footer-menu' => __( 'Footer Menu' )
        )
    );
}
add_action( 'init', 'register_my_menus_footer' );

function enqueue_video_handle_script() {
    if (is_front_page() || is_home() ||  is_post_type_archive('truyen_chu') || is_tax('trang_thai') || is_tax('the_loai') || is_tax('tac_gia') || is_tag() || is_singular('truyen_chu')) {
        // Swiper CSS
        wp_enqueue_style(
            'swiper-css',
            get_template_directory_uri() . '/assets/libs/swiper/swiper-bundle.min.css',
            array(),
            false
        );
        // wp_enqueue_style(
        //     'owl-css',
        //     get_template_directory_uri() . '/assets/libs/owlcarousel/css/owl.carousel.min.css',
        //     array(),
        //     false
        // );

        // Swiper JS
        wp_enqueue_script(
            'swiper-js',
            get_template_directory_uri() . '/assets/libs/swiper/swiper-bundle.min.js',
            array(),
            '11.1.15',
            true
        );
        

        // Custom JS for Swiper initialization
        wp_enqueue_script(
            'swiper-init',
            get_template_directory_uri() . '/assets/js/swiper-init.js',
            array('swiper-js'),
            '1.0.0',
            true
        );
    }
    // Nếu là trang single-truyen-chu thì enqueue AOS
    if (is_singular('truyen_chu')) {
        wp_enqueue_style(
            'aos-css',
            get_template_directory_uri() . '/assets/libs/aos/aos.css',
            array(),
            false
        );
        wp_enqueue_script(
            'aos-js',
            get_template_directory_uri() . '/assets/libs/aos/aos.js',
            array(),
            '2.3.4',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_video_handle_script');

// Bật comments cho custom post types
function commicpro_add_comments_support() {
    add_post_type_support('truyen_chu', 'comments');
    add_post_type_support('chuong_truyen', 'comments');
}
add_action('init', 'commicpro_add_comments_support');

/**
 * Xử lý AJAX đăng nhập - Phiên bản tối ưu
 * Có khả năng hiển thị thông báo lỗi cụ thể (bao gồm cả lỗi chưa kích hoạt).
 */
function ajax_login() {
    // 1. Xác thực Nonce
    check_ajax_referer('ajax_login_nonce', 'nonce');

    // 2. Lấy dữ liệu
    $info = array();
    $info['user_login'] = sanitize_text_field($_POST['username']);
    $info['user_password'] = $_POST['password'];
    $info['remember'] = isset($_POST['remember']) ? true : false;

    // 3. Xác thực người dùng
    // Sử dụng wp_signon thay vì wp_authenticate để có thể xử lý cả cookie
    $user_signon = wp_signon($info, false);

    // 4. Kiểm tra kết quả
    if (is_wp_error($user_signon)) {
        /**
         * Đây là phần tối ưu quan trọng.
         * Lấy thông báo lỗi cụ thể từ WP_Error và gửi về cho client.
         * Ví dụ: "Tài khoản của bạn chưa được kích hoạt." hoặc "Mật khẩu không đúng."
         */
        wp_send_json_error($user_signon->get_error_message());
    } else {
        /**
         * wp_signon đã tự xử lý việc set cookie và current user.
         * Chúng ta không cần gọi lại wp_set_auth_cookie và wp_set_current_user.
         */
        wp_send_json_success('Đăng nhập thành công');
    }
}
add_action('wp_ajax_nopriv_ajax_login', 'ajax_login');
add_action('wp_ajax_ajax_login', 'ajax_login'); // Thêm hook này để xử lý cho cả user đã đăng nhập (dù ít khi xảy ra)

/**
 * --------------------------------------------------------------------------
 * TÍNH NĂNG KÍCH HOẠT TÀI KHOẢN QUA EMAIL
 * --------------------------------------------------------------------------
 */

/**
 * PHẦN 1: SỬA ĐỔI QUÁ TRÌNH ĐĂNG KÝ AJAX
 * Ghi đè hoặc sửa đổi hàm ajax_register của bạn.
 * Thay vì chỉ tạo user, chúng ta sẽ thêm meta để đánh dấu là chưa kích hoạt và gửi email.
 */
add_action('wp_ajax_nopriv_ajax_register', 'my_theme_ajax_register_with_activation');

function my_theme_ajax_register_with_activation() {
    // 1. Xác thực Nonce để bảo mật
    check_ajax_referer('ajax_register_nonce', 'nonce');

    // 2. Lấy và làm sạch dữ liệu
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];

    // 3. Kiểm tra dữ liệu đầu vào
    if (empty($username) || empty($email) || empty($password)) {
        wp_send_json_error('Vui lòng điền đầy đủ thông tin.');
    }
    if (username_exists($username)) {
        wp_send_json_error('Tên đăng nhập này đã tồn tại.');
    }
    if (email_exists($email)) {
        wp_send_json_error('Địa chỉ email này đã được sử dụng.');
    }
    if (!is_email($email)) {
        wp_send_json_error('Địa chỉ email không hợp lệ.');
    }
    if (strlen($password) < 6) {
        wp_send_json_error('Mật khẩu phải có ít nhất 6 ký tự.');
    }

    // 4. Tạo người dùng
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error($user_id->get_error_message());
    } else {
        // 5. TẠO MÃ KÍCH HOẠT & LƯU VÀO USER META
        // Tạo một mã ngẫu nhiên, khó đoán
        $activation_key = wp_generate_password(20, false); 
        
        // Đánh dấu tài khoản là chưa kích hoạt (0 = false)
        update_user_meta($user_id, '_is_activated', 0);
        
        // Lưu mã kích hoạt
        update_user_meta($user_id, '_activation_key', $activation_key);

        // 6. GỬI EMAIL KÍCH HOẠT
        $activation_link = add_query_arg(
            array(
                'action' => 'activate',
                'user_id' => $user_id,
                'key' => $activation_key,
            ),
            home_url() // Link sẽ có dạng: yoursite.com/?action=activate&user_id=123&key=xyz...
        );

        $subject = '[' . get_bloginfo('name') . '] Kích hoạt tài khoản của bạn';
        
        $message = "Chào mừng bạn đã đăng ký tài khoản tại " . get_bloginfo('name') . ".\n\n";
        $message .= "Để hoàn tất đăng ký và kích hoạt tài khoản, vui lòng nhấn vào đường link dưới đây:\n";
        $message .= $activation_link . "\n\n";
        $message .= "Nếu bạn không thực hiện việc đăng ký này, vui lòng bỏ qua email này.\n\n";
        $message .= "Trân trọng,\n";
        $message .= "BQT " . get_bloginfo('name');
        
        // Gửi mail bằng hàm của WordPress
        wp_mail($email, $subject, $message);

        // 7. Gửi phản hồi thành công về cho AJAX
        wp_send_json_success('Đăng ký thành công. Vui lòng kiểm tra hộp thư email (cả mục Spam/Quảng cáo) để kích hoạt tài khoản của bạn.');
    }
}


/**
 * PHẦN 2: XỬ LÝ LINK KÍCH HOẠT
 * Hàm này sẽ chạy mỗi khi trang được tải, lắng nghe các tham số trên URL.
 */
add_action('init', 'my_theme_handle_account_activation');

function my_theme_handle_account_activation() {
    // Chỉ chạy khi có tham số 'action' là 'activate'
    if (isset($_GET['action']) && $_GET['action'] === 'activate' && isset($_GET['user_id']) && isset($_GET['key'])) {
        
        $user_id = intval($_GET['user_id']);
        $sent_key = sanitize_text_field($_GET['key']);
        
        // Lấy mã đã lưu trong database
        $stored_key = get_user_meta($user_id, '_activation_key', true);

        // Kiểm tra xem user có tồn tại và mã có khớp không
        if ($user_id > 0 && !empty($stored_key) && hash_equals($stored_key, $sent_key)) {
            // Kích hoạt thành công!
            
            // Cập nhật trạng thái kích hoạt (1 = true)
            update_user_meta($user_id, '_is_activated', 1);
            
            // Xóa mã kích hoạt đi để không thể sử dụng lại
            delete_user_meta($user_id, '_activation_key');
            
            // Chuyển hướng người dùng đến trang đăng nhập với thông báo thành công
            wp_redirect(add_query_arg('activated', 'true', wp_login_url()));
            exit;
        } else {
            // Kích hoạt thất bại
            wp_redirect(add_query_arg('activated', 'false', wp_login_url()));
            exit;
        }
    }
}

/**
 * PHẦN 3: NGĂN ĐĂNG NHẬP NẾU TÀI KHOẢN CHƯA KÍCH HOẠT
 * Can thiệp vào quá trình xác thực đăng nhập.
 */
add_filter('wp_authenticate_user', 'my_theme_prevent_inactive_login', 10, 2);

function my_theme_prevent_inactive_login($user, $password) {
    if (isset($user->ID)) {
        $is_activated = get_user_meta($user->ID, '_is_activated', true);
        
        // Nếu meta tồn tại và giá trị là 0 (chưa kích hoạt)
        if ($is_activated === '0' || $is_activated === 0) {
            // Trả về một WP_Error để ngăn đăng nhập
            return new WP_Error(
                'account_not_activated',
                '<strong>LỖI:</strong> Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt.'
            );
        }
    }
    return $user;
}

/**
 * PHẦN 4 (Tùy chọn): HIỂN THỊ THÔNG BÁO TRÊN TRANG ĐĂNG NHẬP
 * Hiển thị thông báo "Kích hoạt thành công/thất bại" sau khi chuyển hướng.
 */
add_filter('login_message', 'my_theme_custom_login_message');

function my_theme_custom_login_message($message) {
    if (isset($_GET['activated'])) {
        if ($_GET['activated'] === 'true') {
            $message = '<p class="message green">Tài khoản của bạn đã được kích hoạt thành công! Vui lòng đăng nhập.</p>';
        } elseif ($_GET['activated'] === 'false') {
            $message = '<p class="message"><strong>LỖI:</strong> Link kích hoạt không hợp lệ hoặc đã hết hạn.</p>';
        }
    }
    return $message;
}

// Thêm một chút CSS cho thông báo màu xanh
function my_theme_login_styles() {
    echo '<style>.login .message.green { border-left-color: #4CAF50; }</style>';
}
add_action('login_head', 'my_theme_login_styles');

// Xử lý AJAX đăng ký
// function ajax_register() {
//     check_ajax_referer('ajax_register_nonce', 'nonce');

//     $username = sanitize_text_field($_POST['username']);
//     $email = sanitize_email($_POST['email']);
//     $password = $_POST['password'];

//     // Kiểm tra username
//     if (username_exists($username)) {
//         wp_send_json_error('Tên đăng nhập đã tồn tại');
//         return;
//     }

//     // Kiểm tra email
//     if (email_exists($email)) {
//         wp_send_json_error('Email đã được sử dụng');
//         return;
//     }

//     // Tạo user mới
//     $user_id = wp_create_user($username, $password, $email);

//     if (is_wp_error($user_id)) {
//         wp_send_json_error($user_id->get_error_message());
//         return;
//     }

//     // Gửi email xác nhận
//     $user = get_user_by('id', $user_id);
//     $to = $email;
//     $subject = 'Xác nhận đăng ký tài khoản';
//     $message = sprintf(
//         'Xin chào %s,\n\nCảm ơn bạn đã đăng ký tài khoản tại %s.\n\nTên đăng nhập: %s\n\nVui lòng truy cập trang web để đăng nhập: %s',
//         $username,
//         get_bloginfo('name'),
//         $username,
//         wp_login_url()
//     );
//     $headers = array('Content-Type: text/plain; charset=UTF-8');
//     wp_mail($to, $subject, $message, $headers);

//     wp_send_json_success('Đăng ký thành công');
// }
// add_action('wp_ajax_nopriv_ajax_register', 'ajax_register');

// Đăng ký AJAX handler cho việc mua gói VIP
add_action('wp_ajax_process_vip_purchase', 'process_vip_purchase_handler');
function process_vip_purchase_handler() {
    // Kiểm tra đăng nhập
    if (!is_user_logged_in()) {
        wp_send_json_error('Bạn cần đăng nhập để thực hiện chức năng này');
        exit;
    }

    // Lấy thông tin người dùng hiện tại
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    // Lấy số dư hiện tại của người dùng
    $current_balance = get_user_meta($user_id, '_user_balance', true);
    $current_balance = floatval($current_balance);

    // Lấy thông tin gói VIP được chọn
    $package_type = isset($_POST['package_type']) ? sanitize_text_field($_POST['package_type']) : '';
    $package_price = 0;
    $package_duration = 0;

    // Xác định thông tin gói
    if ($package_type === 'vip_2_months') { // 30 ngày
        $package_price = 299000;
        $package_duration = 30; // 30 ngày
    } elseif ($package_type === 'vip_3_months') { // 60 ngày
        $package_price = 599000;
        $package_duration = 60; // 60 ngày
    } elseif ($package_type === 'vip_permanent') {
        $package_price = 999999;
        $package_duration = -1; // -1 đại diện cho vĩnh viễn
    } else {
        wp_send_json_error('Gói VIP không hợp lệ');
        exit;
    }

    // Kiểm tra số dư
    if ($current_balance < $package_price) {
        wp_send_json_error('Số dư không đủ để mua gói VIP này');
        exit;
    }

    // Trừ tiền từ số dư
    $new_balance = $current_balance - $package_price;
    update_user_meta($user_id, '_user_balance', $new_balance);

    // Cập nhật thông tin gói VIP
    $vip_data = array(
        'package_type' => $package_type,
        'purchase_date' => current_time('mysql'),
        'expiry_date' => $package_duration === -1 ? 'permanent' : date('Y-m-d H:i:s', strtotime("+{$package_duration} days")),
        'is_active' => true
    );

    update_user_meta($user_id, 'vip_package', $vip_data);

    // Gửi thông báo thành công
    wp_send_json_success(array(
        'message' => 'Mua gói VIP thành công',
        'new_balance' => $new_balance,
        'vip_data' => $vip_data
    ));
}

// Hàm kiểm tra trạng thái VIP của người dùng
function check_user_vip_status($user_id) {
    $vip_data = get_user_meta($user_id, 'vip_package', true);
    
    if (!$vip_data || !$vip_data['is_active']) {
        return false;
    }

    // Nếu là gói vĩnh viễn
    if ($vip_data['package_type'] === 'vip_permanent') {
        return true;
    }

    // Kiểm tra hết hạn cho gói 30 ngày và 60 ngày
    if ($vip_data['package_type'] === 'vip_2_months' || $vip_data['package_type'] === 'vip_3_months') {
        $expiry_date = strtotime($vip_data['expiry_date']);
        $current_date = strtotime(current_time('mysql'));
        return $current_date <= $expiry_date;
    }

    return false;
}

// Ẩn thanh công cụ WordPress cho người dùng đã đăng nhập
function hide_admin_bar() {
    if (is_user_logged_in()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar');

// Handle avatar update
add_action('wp_ajax_update_user_avatar', 'handle_update_user_avatar');
function handle_update_user_avatar() {
    check_ajax_referer('update_avatar_nonce', 'security');

    if (!isset($_FILES['avatar'])) {
        wp_send_json_error('No file uploaded');
    }

    $file = $_FILES['avatar'];
    $user_id = get_current_user_id();

    // Check file type
    $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error('Invalid file type');
    }

    // Check file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        wp_send_json_error('File too large');
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    // Upload the file
    $attachment_id = media_handle_upload('avatar', 0);

    if (is_wp_error($attachment_id)) {
        wp_send_json_error('Error uploading file');
    }

    // Update user avatar
    update_user_meta($user_id, 'avatar_id', $attachment_id);
    
    wp_send_json_success();
}

// Handle avatar deletion
add_action('wp_ajax_delete_user_avatar', 'handle_delete_user_avatar');
function handle_delete_user_avatar() {
    check_ajax_referer('delete_avatar_nonce', 'security');

    $user_id = get_current_user_id();
    $avatar_id = get_user_meta($user_id, 'avatar_id', true);

    if ($avatar_id) {
        // Delete the attachment
        wp_delete_attachment($avatar_id, true);
        // Remove the user meta
        delete_user_meta($user_id, 'avatar_id');
    }

    wp_send_json_success();
}

// Override default avatar
add_filter('get_avatar_url', 'custom_get_avatar_url', 10, 3);
function custom_get_avatar_url($url, $id_or_email, $args) {
    if (is_numeric($id_or_email)) {
        $user_id = $id_or_email;
    } elseif (is_object($id_or_email)) {
        $user_id = $id_or_email->ID;
    } else {
        $user = get_user_by('email', $id_or_email);
        $user_id = $user ? $user->ID : 0;
    }

    if ($user_id) {
        $avatar_id = get_user_meta($user_id, 'avatar_id', true);
        if ($avatar_id) {
            $avatar_url = wp_get_attachment_image_url($avatar_id, 'thumbnail');
            if ($avatar_url) {
                return $avatar_url;
            }
        }
    }

    return $url;
}

// Thêm trường VIP vào form chỉnh sửa user
function add_vip_fields_to_user_profile($user) {
    // Lấy thông tin VIP hiện tại
    $vip_data = get_user_meta($user->ID, 'vip_package', true);
    $is_vip = !empty($vip_data) && $vip_data['is_active'];
    $package_type = isset($vip_data['package_type']) ? $vip_data['package_type'] : '';
    $expiry_date = isset($vip_data['expiry_date']) ? $vip_data['expiry_date'] : '';
    
    // Tạo nonce field để bảo mật
    wp_nonce_field('vip_management_nonce', 'vip_management_nonce');
    ?>
    <h2>Quản lý VIP</h2>
    <table class="form-table">
        <tr>
            <th><label for="is_vip">Trạng thái VIP</label></th>
            <td>
                <label>
                    <input type="checkbox" name="is_vip" id="is_vip" value="1" <?php checked($is_vip, true); ?>>
                    Set VIP cho tài khoản này
                </label>
            </td>
        </tr>
        <tr>
            <th><label for="vip_package_type">Loại gói VIP</label></th>
            <td>
                <select name="vip_package_type" id="vip_package_type">
                    <option value="vip_3_months" <?php selected($package_type, 'vip_3_months'); ?>>VIP 3 tháng</option>
                    <option value="vip_permanent" <?php selected($package_type, 'vip_permanent'); ?>>VIP vĩnh viễn</option>
                </select>
            </td>
        </tr>
        <?php if ($is_vip && $expiry_date !== 'permanent'): ?>
        <tr>
            <th><label>Ngày hết hạn</label></th>
            <td>
                <input type="text" value="<?php echo esc_attr($expiry_date); ?>" readonly>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    <?php
}
add_action('edit_user_profile', 'add_vip_fields_to_user_profile');
add_action('show_user_profile', 'add_vip_fields_to_user_profile');

// Lưu thông tin VIP khi cập nhật user
function save_vip_fields($user_id) {
    // Kiểm tra nonce
    if (!isset($_POST['vip_management_nonce']) || 
        !wp_verify_nonce($_POST['vip_management_nonce'], 'vip_management_nonce')) {
        return;
    }

    // Kiểm tra quyền
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    // Lấy thông tin VIP hiện tại
    $vip_data = get_user_meta($user_id, 'vip_package', true);
    if (!is_array($vip_data)) {
        $vip_data = array();
    }

    // Cập nhật trạng thái VIP
    $is_vip = isset($_POST['is_vip']);
    $package_type = isset($_POST['vip_package_type']) ? sanitize_text_field($_POST['vip_package_type']) : '';

    if ($is_vip) {
        // Nếu đang set VIP
        $vip_data['is_active'] = true;
        $vip_data['package_type'] = $package_type;
        $vip_data['purchase_date'] = current_time('mysql');
        
        // Set ngày hết hạn
        if ($package_type === 'vip_permanent') {
            $vip_data['expiry_date'] = 'permanent';
        } else {
            $vip_data['expiry_date'] = date('Y-m-d H:i:s', strtotime('+3 months'));
        }
    } else {
        // Nếu bỏ VIP
        $vip_data['is_active'] = false;
    }

    // Cập nhật user meta
    update_user_meta($user_id, 'vip_package', $vip_data);
}
add_action('personal_options_update', 'save_vip_fields');
add_action('edit_user_profile_update', 'save_vip_fields');

// Thêm meta box cho điều chỉnh số dư thủ công
function add_manual_balance_section($user) {
    // Kiểm tra quyền admin
    if (!current_user_can('manage_options')) {
        return;
    }

    // Lấy số dư hiện tại
    $current_balance = get_user_meta($user->ID, '_user_balance', true);
    if ($current_balance === '') {
        $current_balance = 0;
    }

    // Tạo nonce field để bảo mật
    wp_nonce_field('manual_balance_adjustment_nonce', 'manual_balance_adjustment_nonce');
    ?>
    <h2>Điều chỉnh số dư thủ công</h2>
    <table class="form-table">
        <tr>
            <th><label>Số dư hiện tại</label></th>
            <td>
                <strong><?php echo number_format($current_balance); ?> VNĐ</strong>
            </td>
        </tr>
        <tr>
            <th><label for="balance_adjustment">Số tiền điều chỉnh (VNĐ)</label></th>
            <td>
                <input type="number" id="balance_adjustment" name="balance_adjustment" class="regular-text" step="1000">
                <p class="description">Nhập số dương để cộng tiền, số âm để trừ tiền</p>
            </td>
        </tr>
        <tr>
            <th><label for="adjustment_note">Ghi chú</label></th>
            <td>
                <textarea id="adjustment_note" name="adjustment_note" class="regular-text" rows="3" style="width: 25em;"></textarea>
            </td>
        </tr>
    </table>
    <?php
}
add_action('edit_user_profile', 'add_manual_balance_section');
add_action('show_user_profile', 'add_manual_balance_section');

function custom_pagination($query = null) {
    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }

    $big = 999999999; // số lớn để thay thế sau
    $pages = paginate_links(array(
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => max(1, get_query_var('paged')),
        'total'     => $query->max_num_pages,
        'type'      => 'array',
        'prev_text' => __('Previous'),
        'next_text' => __('Next'),
    ));

    if (is_array($pages)) {
        echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';

        foreach ($pages as $page) {
            // Kiểm tra xem có class="current" không để xác định trang đang chọn
            if (strpos($page, 'current') !== false) {
                echo '<li class="page-item active">' . str_replace('page-numbers', 'page-link', $page) . '</li>';
            } elseif (strpos($page, 'dots') !== false) {
                echo '<li class="page-item disabled">' . str_replace('page-numbers dots', 'page-link', $page) . '</li>';
            } else {
                echo '<li class="page-item">' . str_replace('page-numbers', 'page-link', $page) . '</li>';
            }
        }

        echo '</ul></nav>';
    }
}

// Tạo trang lịch sử đọc nếu chưa tồn tại
function create_reading_history_page() {
    $page = get_page_by_path('reading-history');
    if (!$page) {
        $page_id = wp_insert_post(array(
            'post_title' => 'Lịch sử đọc truyện',
            'post_name' => 'reading-history',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '',
            'page_template' => 'page-reading-history.php'
        ));
    }
}
add_action('after_switch_theme', 'create_reading_history_page');

// Tạm thời tạo bảng reading_history
function create_reading_history_table_now() {
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
    dbDelta($sql);
}
// Chạy một lần để tạo bảng
create_reading_history_table_now();

// Xử lý điều chỉnh số dư
function handle_manual_balance_adjustment($user_id) {
    error_log('=== Bắt đầu xử lý điều chỉnh số dư ===');
    error_log('User ID: ' . $user_id);

    // Kiểm tra nonce
    if (!isset($_POST['manual_balance_adjustment_nonce'])) {
        error_log('Lỗi: Không tìm thấy nonce');
        return;
    }
    if (!wp_verify_nonce($_POST['manual_balance_adjustment_nonce'], 'manual_balance_adjustment_nonce')) {
        error_log('Lỗi: Nonce không hợp lệ');
        return;
    }

    // Kiểm tra quyền admin
    if (!current_user_can('manage_options')) {
        error_log('Lỗi: Không có quyền admin');
        return;
    }

    // Lấy thông tin điều chỉnh
    $adjustment = isset($_POST['balance_adjustment']) ? floatval($_POST['balance_adjustment']) : 0;
    $note = isset($_POST['adjustment_note']) ? sanitize_textarea_field($_POST['adjustment_note']) : '';
    
    error_log('Số tiền điều chỉnh: ' . $adjustment);
    error_log('Ghi chú: ' . $note);

    if ($adjustment == 0) {
        error_log('Lỗi: Số tiền điều chỉnh bằng 0');
        return;
    }

    // Lấy số dư hiện tại
    $current_balance = get_user_meta($user_id, '_user_balance', true);
    if ($current_balance === '') {
        $current_balance = 0;
    }
    $current_balance = floatval($current_balance);
    error_log('Số dư hiện tại: ' . $current_balance);

    // Tính số dư mới
    $new_balance = $current_balance + $adjustment;
    if ($new_balance < 0) {
        $new_balance = 0; // Không cho phép số dư âm
    }
    error_log('Số dư mới: ' . $new_balance);

    // Tính Kim Tệ
    $kim_te = floor($new_balance);
    error_log('Kim Tệ: ' . $kim_te);

    // Kết nối DB
    global $wpdb;
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        error_log('Lỗi kết nối MySQL: ' . $conn->connect_error);
        return;
    }
    error_log('Kết nối MySQL thành công');

    // Tìm VIP level tương ứng
    $vip_id = NULL;
    $vip_name = '';
    $vip_color = '';
    $vip_query = $conn->prepare("SELECT id, name, color_code FROM wp_vip_levels WHERE kim_te <= ? ORDER BY kim_te DESC LIMIT 1");
    if ($vip_query) {
        $vip_query->bind_param("i", $kim_te);
        $vip_query->execute();
        $vip_result = $vip_query->get_result();

        if ($vip_result->num_rows > 0) {
            $vip_row = $vip_result->fetch_assoc();
            $vip_id = $vip_row['id'];
            $vip_name = $vip_row['name'];
            $vip_color = $vip_row['color_code'];
            error_log('Tìm thấy VIP level: ID=' . $vip_id . ', Name=' . $vip_name);
        } else {
            error_log('Không tìm thấy VIP level phù hợp');
        }
        $vip_query->close();
    } else {
        error_log('Lỗi prepare VIP query: ' . $conn->error);
    }

    // Cập nhật số dư và VIP level trong wp_users
    $update_sql = "UPDATE wp_users SET price = ?, vip_level_id = ? WHERE ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt) {
        $update_stmt->bind_param("dii", $new_balance, $vip_id, $user_id);
        $update_result = $update_stmt->execute();
        error_log('Cập nhật wp_users: ' . ($update_result ? 'Thành công' : 'Thất bại - ' . $update_stmt->error));
        $update_stmt->close();
    } else {
        error_log('Lỗi prepare update wp_users: ' . $conn->error);
    }

    // Cập nhật các user meta
    $update_balance = update_user_meta($user_id, '_user_balance', $new_balance);
    error_log('Cập nhật _user_balance: ' . ($update_balance ? 'Thành công' : 'Thất bại'));
    
    $update_vip_id = update_user_meta($user_id, '_user_vip_level_id', $vip_id);
    error_log('Cập nhật _user_vip_level_id: ' . ($update_vip_id ? 'Thành công' : 'Thất bại'));

    // Cập nhật VIP name nếu VIP ID >= 1
    if ($vip_id !== NULL && $vip_id >= 1) {
        $update_vip_name = update_user_meta($user_id, '_user_vip_name', $vip_name);
        error_log('Cập nhật _user_vip_name: ' . ($update_vip_name ? 'Thành công' : 'Thất bại'));
    } else {
        delete_user_meta($user_id, '_user_vip_name');
        error_log('Xóa _user_vip_name');
    }

    // Cập nhật VIP color nếu VIP ID >= 4
    if ($vip_id !== NULL && $vip_id >= 4) {
        $update_vip_color = update_user_meta($user_id, '_user_vip_color', $vip_color);
        error_log('Cập nhật _user_vip_color: ' . ($update_vip_color ? 'Thành công' : 'Thất bại'));
    } else {
        delete_user_meta($user_id, '_user_vip_color');
        error_log('Xóa _user_vip_color');
    }

    // Ghi log giao dịch
    $admin_user = wp_get_current_user();
    $transaction_data = array(
        'gateway' => 'manual_adjustment',
        'transaction_date' => current_time('mysql'),
        'amount_in' => $adjustment > 0 ? $adjustment : 0,
        'amount_out' => $adjustment < 0 ? abs($adjustment) : 0,
        'transaction_content' => $note ?: 'Điều chỉnh số dư thủ công bởi ' . $admin_user->user_login,
        'reference_number' => 'MANUAL-' . time(),
        'body' => json_encode(array(
            'admin_id' => $admin_user->ID,
            'admin_username' => $admin_user->user_login,
            'note' => $note,
            'old_balance' => $current_balance,
            'new_balance' => $new_balance,
            'adjustment' => $adjustment
        ))
    );

    $insert_sql = "INSERT INTO tb_transactions 
        (gateway, transaction_date, amount_in, amount_out, transaction_content, reference_number, body, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
    $insert_stmt = $conn->prepare($insert_sql);
    if ($insert_stmt) {
        $insert_stmt->bind_param(
            "ssddsss",
            $transaction_data['gateway'],
            $transaction_data['transaction_date'],
            $transaction_data['amount_in'],
            $transaction_data['amount_out'],
            $transaction_data['transaction_content'],
            $transaction_data['reference_number'],
            $transaction_data['body']
        );
        $insert_result = $insert_stmt->execute();
        error_log('Thêm giao dịch: ' . ($insert_result ? 'Thành công' : 'Thất bại - ' . $insert_stmt->error));
        $insert_stmt->close();
    } else {
        error_log('Lỗi prepare insert transaction: ' . $conn->error);
    }

    $conn->close();
    error_log('=== Kết thúc xử lý điều chỉnh số dư ===');

    // Thêm thông báo thành công
    add_action('admin_notices', function() use ($adjustment) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>Đã điều chỉnh số dư thành công: <?php echo number_format($adjustment); ?> VNĐ</p>
        </div>
        <?php
    });
}
add_action('personal_options_update', 'handle_manual_balance_adjustment');
add_action('edit_user_profile_update', 'handle_manual_balance_adjustment');

/**
 * Add a meta box to the page edit screen for homepage setting.
 */
function commicpro_add_homepage_checkbox_meta_box() {
    add_meta_box(
        'commicpro_homepage_meta_box', // Unique ID
        __( 'Homepage Settings', 'commicpro' ), // Box title
        'commicpro_homepage_checkbox_meta_box_html', // Content callback
        'page', // Post type
        'side', // Context ('normal', 'advanced', or 'side')
        'high' // Priority ('high', 'core', 'default', 'low')
    );
}
add_action( 'add_meta_boxes', 'commicpro_add_homepage_checkbox_meta_box' );

/**
 * Display the homepage checkbox meta box HTML.
 *
 * @param WP_Post $post The current post object.
 */
function commicpro_homepage_checkbox_meta_box_html( $post ) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'commicpro_save_homepage_checkbox', 'commicpro_homepage_checkbox_nonce' );

    // Get the current homepage setting.
    $homepage_id = get_option( 'page_on_front' );
    $show_on_front = get_option( 'show_on_front' );

    // Check if the current page is set as the static homepage.
    $is_homepage = ( $show_on_front === 'page' && (int) $homepage_id === $post->ID );

    ?>
    <label for="commicpro_set_as_homepage">
        <input type="checkbox" id="commicpro_set_as_homepage" name="commicpro_set_as_homepage" value="1" <?php checked( $is_homepage ); ?> />
        <?php esc_html_e( 'Set this page as the static homepage', 'commicpro' ); ?>
    </label>
    <?php
}

/**
 * Save the homepage checkbox setting and update WordPress options.
 *
 * @param int $post_id The ID of the post being saved.
 */
function commicpro_save_homepage_checkbox( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['commicpro_homepage_checkbox_nonce'] ) ) {
        return $post_id;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['commicpro_homepage_checkbox_nonce'], 'commicpro_save_homepage_checkbox' ) ) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return $post_id;
    }

    // Check if the checkbox was submitted.
    $set_as_homepage = isset( $_POST['commicpro_set_as_homepage'] ) ? 1 : 0;

    // Get the current homepage setting.
    $current_homepage_id = (int) get_option( 'page_on_front' );
    $current_show_on_front = get_option( 'show_on_front' );

    if ( $set_as_homepage ) {
        // Checkbox is checked.
        // Set this page as the static homepage.
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $post_id );

    } elseif ( (int) $post_id === $current_homepage_id && $current_show_on_front === 'page' ) {
        // Checkbox is not checked AND this page was previously the homepage.
        // Revert to showing latest posts.
        update_option( 'show_on_front', 'posts' );
        update_option( 'page_on_front', 0 ); // Reset the page_on_front option

    }
    // If the checkbox is not checked and this page was NOT the homepage, do nothing.
}
add_action( 'save_post_page', 'commicpro_save_homepage_checkbox' );

// Add filter dropdown for chapters in admin
function add_chuong_truyen_filter() {
    global $typenow;
    if ($typenow === 'chuong_truyen') {
        $current_truyen = isset($_GET['chuong_with_truyen']) ? $_GET['chuong_with_truyen'] : '';
        
        // Get all truyen_chu posts
        $truyen_posts = get_posts(array(
            'post_type' => 'truyen_chu',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        if (!empty($truyen_posts)) {
            echo '<select name="chuong_with_truyen">';
            echo '<option value="">' . __('Tất cả truyện', 'commicpro') . '</option>';
            
            foreach ($truyen_posts as $truyen) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    $truyen->ID,
                    selected($current_truyen, $truyen->ID, false),
                    esc_html($truyen->post_title)
                );
            }
            
            echo '</select>';
        }
    }
}
add_action('restrict_manage_posts', 'add_chuong_truyen_filter');

// Modify the query to filter chapters by story
function filter_chuong_truyen_by_story($query) {
    global $pagenow;
    if (is_admin() && $pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'chuong_truyen' && isset($_GET['chuong_with_truyen']) && !empty($_GET['chuong_with_truyen'])) {
        $query->query_vars['meta_key'] = 'chuong_with_truyen';
        $query->query_vars['meta_value'] = $_GET['chuong_with_truyen'];
    }
}
add_action('pre_get_posts', 'filter_chuong_truyen_by_story');

// Đăng ký script cho chapter count và latest chapter
function enqueue_chapter_count_script() {
    // Enqueue jQuery first
    wp_enqueue_script('jquery');
    
    // Enqueue chapter count script
    wp_enqueue_script('chapter-count', get_template_directory_uri() . '/assets/js/chapter-count.js', array('jquery'), time(), true);
    
    // Enqueue latest chapter script
    wp_enqueue_script('latest-chapter', get_template_directory_uri() . '/assets/js/latest-chapter.js', array('jquery'), time(), true);
    
    // Localize script with unique names
    wp_localize_script('chapter-count', 'chapter_count_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('chapter_count_nonce')
    ));
    
    wp_localize_script('latest-chapter', 'latest_chapter_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('chapter_count_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_chapter_count_script');

// Include AJAX handler
require_once get_template_directory() . '/ajax-handler.php';

// ... existing code ...
require_once get_template_directory() . '/withdrawal-functions.php';
// ... existing code ...

add_action('wp_ajax_filter_truyen_by_the_loai', 'filter_truyen_by_the_loai_callback');
add_action('wp_ajax_nopriv_filter_truyen_by_the_loai', 'filter_truyen_by_the_loai_callback');
function filter_truyen_by_the_loai_callback() {
    $the_loai = isset($_POST['the_loai']) ? intval($_POST['the_loai']) : 0;
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    render_table_truyen($the_loai, $paged);
    wp_die();
}

if (!function_exists('render_table_truyen')) {
    function render_table_truyen($the_loai_id = 0, $paged = 1) {
        $args = array(
            'post_type' => 'truyen_chu',
            'posts_per_page' => 30,
            'orderby' => 'modified',
            'order' => 'DESC',
            'paged' => $paged
        );
        if ($the_loai_id) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'the_loai',
                    'field' => 'term_id',
                    'terms' => $the_loai_id,
                )
            );
        }
        $query = new WP_Query($args);
        if ($query->have_posts()) : ?>
            <table class="table">
                <tbody>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <tr>
                        <td>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </td>
                        <td>
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'trang_thai');
                            if ($terms && !is_wp_error($terms)) {
                                echo esc_html($terms[0]->name);
                            } else {
                                echo 'Không rõ';
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $modified_time = get_the_modified_time('U');
                            echo human_time_diff($modified_time, current_time('timestamp')) . ' trước';
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <?php
            // PHÂN TRANG
            $total_pages = $query->max_num_pages;
            if ($total_pages > 1) {
                echo '<nav class="d-flex justify-content-center">';
                echo '<ul class="pagination">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo '<li class="page-item'.($i == $paged ? ' active' : '').'">';
                    echo '<a href="#" class="page-numbers page-link" data-page="'.$i.'">'.$i.'</a>';
                    echo '</li>';
                }
                echo '</ul></nav>';
            }
            wp_reset_postdata();
        else:
            echo '<p>Không có truyện nào.</p>';
        endif;
    }
}

// Chức năng đếm lượt xem cho truyen_chu
function set_truyen_view_count($post_id) {
    if (is_single() && get_post_type($post_id) == 'truyen_chu') {
        // Tổng view
        $count_key = 'truyen_view_count';
        $count = get_post_meta($post_id, $count_key, true);
        if ($count == '') {
            $count = 0;
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '0');
        } else {
            $count++;
            update_post_meta($post_id, $count_key, $count);
        }

        // View tuần
        $weekly_key = '_weekly_view_count';
        $weekly_count = get_post_meta($post_id, $weekly_key, true);
        if ($weekly_count == '') {
            $weekly_count = 0;
            delete_post_meta($post_id, $weekly_key);
            add_post_meta($post_id, $weekly_key, '0');
        }
        $weekly_count++;
        update_post_meta($post_id, $weekly_key, $weekly_count);
    }
}
add_action('wp_head', 'set_truyen_view_count');

// Hàm reset lượt xem tuần cho tất cả truyện
function reset_weekly_view_count() {
    $args = array(
        'post_type' => 'truyen_chu',
        'posts_per_page' => -1,
        'fields' => 'ids'
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            update_post_meta($post_id, '_weekly_view_count', 0);
        }
    }
}

// Lên lịch reset vào mỗi thứ 2 hàng tuần
if (!wp_next_scheduled('reset_weekly_view_count_event')) {
    wp_schedule_event(strtotime('next monday'), 'weekly', 'reset_weekly_view_count_event');
}
add_action('reset_weekly_view_count_event', 'reset_weekly_view_count');

// Lấy lượt xem của truyện
function get_truyen_view_count($post_id) {
    $count_key = 'truyen_view_count';
    $count = get_post_meta($post_id, $count_key, true);
    if ($count == '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return "0";
    }
    return $count;
} 

// Hiển thị lượt xem
function display_truyen_view_count($post_id) {
    $count = get_truyen_view_count($post_id);
    return number_format($count);
}

// Lấy lượt xem tuần
function get_truyen_weekly_view_count($post_id) {
    $count = get_post_meta($post_id, '_weekly_view_count', true);
    if ($count == '') {
        delete_post_meta($post_id, '_weekly_view_count');
        add_post_meta($post_id, '_weekly_view_count', '0');
        return "0";
    }
    return $count;
}

// Hiển thị lượt xem tuần
function display_truyen_weekly_view_count($post_id) {
    $count = get_truyen_weekly_view_count($post_id);
    return number_format($count);
}

// Lấy top truyện xem nhiều
function get_top_viewed_truyen($posts_per_page = 10) {
    $args = array(
        'post_type' => 'truyen_chu',
        'posts_per_page' => $posts_per_page,
        'meta_key' => 'truyen_view_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'truyen_view_count',
                'value' => '0',
                'compare' => '>',
                'type' => 'NUMERIC'
            )
        )
    );
    return new WP_Query($args);
}

// Hiển thị top view trong sidebar
function display_top_viewed_truyen_sidebar($posts_per_page = 9) {
    $top_view_query = get_top_viewed_truyen($posts_per_page);
    if ($top_view_query->have_posts()) : ?>
        <section class="section-table pb-2">
            <div class="section-title"><span>Top lượt xem</span></div>
            <div class="ranking-table">
                <?php 
                $rank = 1;
                while ($top_view_query->have_posts()) : $top_view_query->the_post(); ?>
                    <div class="ranking-item d-flex align-items-center" data-rank="<?php echo $rank; ?>">
                        <div class="rank-number">
                            <span class="rank-badge rank-<?php echo $rank; ?>"><?php echo $rank; ?></span>
                        </div>
                        <div class="story-info flex-grow-1">
                            <a href="<?php the_permalink(); ?>" class="story-link">
                                <div class="story-title"><?php the_title(); ?></div>
                                <div class="story-meta">
                                    <?php
                                    // Hiển thị thể loại
                                    $the_loai = get_the_terms(get_the_ID(), 'the_loai');
                                    if ($the_loai && !is_wp_error($the_loai)) : ?>
                                        <small class="text-muted-custom genre-tags">
                                            <?php
                                            $the_loai_names = array();
                                            foreach ($the_loai as $term) {
                                                $the_loai_names[] = $term->name;
                                            }
                                            echo esc_html(implode(', ', array_slice($the_loai_names, 0, 2))); // Chỉ hiển thị 2 thể loại đầu
                                            ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                        <div class="story-stats">
                            <small class="text-muted-custom view-count">
                                <i class="fas fa-eye"></i>
                                <?php echo display_truyen_view_count(get_the_ID()); ?>
                            </small>
                        </div>
                    </div>
                <?php 
                $rank++;
                endwhile; ?>
            </div>
        </section>
    <?php endif; 
    wp_reset_postdata();
}

// Shortcode hiển thị lượt xem
function truyen_view_count_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
    ), $atts);
    
    return '<span class="truyen-view-count"><i class="fas fa-eye"></i> ' . display_truyen_view_count($atts['post_id']) . ' lượt xem</span>';
}
add_shortcode('truyen_view_count', 'truyen_view_count_shortcode');

// Hiển thị lượt xem trong single post
// function display_truyen_view_count_in_single() {
//     if (is_single() && get_post_type() == 'truyen_chu') {
//         echo '<div class="truyen-view-count-display">';
//         echo '<i class="fas fa-eye"></i> ';
//         echo display_truyen_view_count(get_the_ID()) . ' lượt xem';
//         echo '</div>';
//     }
// }
// add_action('wp_head', 'display_truyen_view_count_in_single');

// Function render box full
if (!function_exists('render_box_full')) {
    function render_box_full($the_loai_id = 0) {
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
        
        if ($the_loai_id) {
            $completed_args['tax_query'][] = array(
                'taxonomy' => 'the_loai',
                'field'    => 'term_id',
                'terms'    => $the_loai_id,
            );
        }
        
        $completed_query = new WP_Query($completed_args);
        
        if ($completed_query->have_posts()) : ?>
            <div class="row g-4 align-items-stretch">
                <?php 
                // Item lớn bên trái
                $completed_query->the_post();
                $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                ?>
                <div class="col-12 col-lg-3">
                    <div class="position-relative h-100 d-flex flex-column box-full-left">
                        <a class="d-block flex-grow-1" href="<?php the_permalink(); ?>">
                            <img class="img-fluid w-100" style="object-fit: cover;" 
                                 src="<?php echo $featured_img_url ? $featured_img_url : get_template_directory_uri() . '/assets/images/icon-book.png'; ?>" 
                                 alt="<?php the_title_attribute(); ?>" 
                                 onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'" />
                            <p class="count-port"><small class="text-muted-custom chapter-count" data-truyen-id="<?php echo get_the_ID(); ?>">...</small></p>
                        </a>
                        <a href="<?php the_permalink(); ?>" class="box-full-left-title text-truncate"><?php the_title(); ?></a>
                    </div>
                </div>

                <div class="col-12 col-lg-9">
                    <div class="row g-3 box-full-right">
                        <?php while ($completed_query->have_posts()) : $completed_query->the_post(); ?>
                            <div class="col-6 col-md-3 col-lg-2">
                                <div class="position-relative h-100 box-full-right-item">
                                    <a href="<?php the_permalink(); ?>" class="d-block">
                                        <?php $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>
                                        <img class="img-fluid" style="height: 100%; object-fit: cover"
                                             src="<?php echo $thumb_url ? $thumb_url : get_template_directory_uri() . '/assets/images/icon-book.png'; ?>" 
                                             alt="<?php the_title_attribute(); ?>" 
                                             onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'" />
                                        <div class="slide-title box-full-right-title line-clamp line-clamp-1"><?php the_title(); ?></div>
                                        <p class="count-port">
                                            <small class="text-muted-custom chapter-count" data-truyen-id="<?php echo get_the_ID(); ?>">...</small>
                                        </p>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Không có truyện nào.</p>
        <?php endif;
        wp_reset_postdata();
    }
}

// AJAX callback cho box full
add_action('wp_ajax_filter_box_full_by_the_loai', 'filter_box_full_by_the_loai_callback');
add_action('wp_ajax_nopriv_filter_box_full_by_the_loai', 'filter_box_full_by_the_loai_callback');
function filter_box_full_by_the_loai_callback() {
    $the_loai = isset($_POST['the_loai']) ? intval($_POST['the_loai']) : 0;
    render_box_full($the_loai);
    wp_die();
}

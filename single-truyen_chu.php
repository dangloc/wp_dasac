<?php
/**
 * The template for displaying single truyện chữ
 */

get_header();

// Tăng lượt view khi truy cập single truyện
if (have_posts()) {
    while (have_posts()) {
        the_post();
        set_truyen_view_count(get_the_ID());
        break; // Chỉ cần lấy post đầu tiên
    }
    rewind_posts(); // Reset lại loop
}
?>

<main id="primary" class="site-main single-truyen-chu">
    <div class="container py-4">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-custom-single-truyen mb-4">
                            <?php 
                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                            ?>
                            <img class="img-fluid" 
                                src="<?php echo $featured_img_url ? $featured_img_url : get_template_directory_uri() . '/assets/images/icon-book.png'; ?>" 
                                alt="<?php the_title_attribute(); ?>" 
                                onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
                            />
                        </div>
                    </div>

                    <div class="col-md-8">
                        <header class="entry-header mb-4">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            <!-- Hiển thị lượt view -->
                            <div class="truyen-view-count-display mb-2">
                                <i class="fas fa-eye"></i> 
                                <?php echo display_truyen_view_count(get_the_ID()); ?> lượt xem
                            </div>
                        </header>
                       
                        <div class="card-cutom-single mb-4">
                            <div class="card-body-cutom-single">
                                <?php
                                // Hiển thị tác giả
                                $tac_gia = get_the_terms(get_the_ID(), 'tac_gia');
                                if ($tac_gia && !is_wp_error($tac_gia)) : ?>
                                    <p class="mb-2">
                                        <a href="<?php echo get_term_link($tac_gia[0]); ?>"><?php echo esc_html($tac_gia[0]->name); ?></a>
                                    </p>
                                <?php endif; ?>

                                 <?php
                                // Hiển thị trạng thái
                                $trang_thai = get_the_terms(get_the_ID(), 'trang_thai');
                                if ($trang_thai && !is_wp_error($trang_thai)) : ?>
                                    <p class="mb-2">
                                        <span class="<?php echo esc_attr($trang_thai[0]->slug); ?>">
                                            <?php echo esc_html($trang_thai[0]->name); ?>
                                        </span>
                                    </p>
                                <?php endif; ?>

                                <?php
                                // Hiển thị thể loại
                                $the_loai = get_the_terms(get_the_ID(), 'the_loai');
                                if ($the_loai && !is_wp_error($the_loai)) : ?>
                                    <p class="mb-2">
                                        <?php
                                        $the_loai_names = array();
                                        foreach ($the_loai as $term) {
                                            $the_loai_names[] = '<a href="' . get_term_link($term) . '">' . $term->name . '</a>';
                                        }
                                        echo implode(' ', $the_loai_names);
                                        ?>
                                    </p>
                                <?php endif; ?>

                                <?php
                                // Hiển thị năm phát hành
                                $nam_phat_hanh = get_the_terms(get_the_ID(), 'nam_phat_hanh');
                                if ($nam_phat_hanh && !is_wp_error($nam_phat_hanh)) : ?>
                                    <p class="mb-2">
                                        <?php echo esc_html($nam_phat_hanh[0]->name); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php
                        // Hiển thị tags
                        $tags = get_the_tags();
                        if ($tags) : ?>
                            <p class="entry-tags d-flex align-items-center flex-wrap mt-2 overflow-hidden">
                                     <?php foreach ($tags as $tag) : ?>
                                            <span>
                                                <a style="white-space: nowrap;" href="<?php echo get_tag_link($tag->term_id); ?>" class="btn btn-sm btn-outline-secondary">
                                                   #<?php echo esc_html($tag->name); ?>
                                                </a>
                                            </span>
                                        <?php endforeach; ?>
                            </p>
                        <?php endif; ?>
                        </div>
                            <?php
                            // Hiển thị danh sách chương
                            $truyen_id = get_the_ID();
                            // Lấy thông tin khóa chương và giá
                            $locked_from = get_post_meta($truyen_id, '_locked_from', true);
                            $chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                            // Lấy danh sách chương đã mua của user
                            $user_id = get_current_user_id();
                            $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
                            if (!is_array($purchased_chapters)) {
                                $purchased_chapters = array();
                            }

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
                            ?>
                         <?php 
                            // Sử dụng shortcode của plugin Favorites để hiển thị nút favorite
                            echo do_shortcode('[ratemypost id="' . get_the_ID() . '"]');
                        ?>
                        <div class="d-flex flex-md-row flex-column gap-2 mb-3">
                            <?php
                            // Lấy chương đầu và cuối
                            $first_chapter = null;
                            $last_chapter = null;
                            if ($chapters->have_posts()) {
                                $chapters_array = $chapters->posts;
                                $first_chapter = $chapters_array[0];
                                $last_chapter = end($chapters_array);
                            }

                            // Kiểm tra xem chương cuối có bị khóa không
                            $is_last_chapter_locked = false;
                            if ($last_chapter && $locked_from) {
                                $last_chapter_number = count($chapters_array);
                                $is_last_chapter_locked = $last_chapter_number >= $locked_from;
                            }
                            ?>
                            <?php if ($first_chapter): ?>
                                <a href="<?php echo get_permalink($first_chapter->ID); ?>" class="btn btn-warning">Đọc Từ Đầu</a>
                            <?php endif; ?>
                            <?php if ($last_chapter): ?>
                                <a href="<?php echo $is_last_chapter_locked ? 'javascript:void(0)' : get_permalink($last_chapter->ID); ?>" 
                                   class="btn btn-warning <?php echo $is_last_chapter_locked ? 'disabled' : ''; ?>"
                                   <?php echo $is_last_chapter_locked ? 'title="Chương này đã bị khóa"' : ''; ?>>
                                    Chương Mới Nhất
                                </a>
                            <?php endif; ?>
                            <?php 
                            // Sử dụng shortcode của plugin Favorites để hiển thị nút favorite
                            echo do_shortcode('[favorite_button post_id="' . get_the_ID() . '"]');
                            ?>
                        </div>
                       
                        <style>
                            .toggle-content.active::after {
                                content: ' ▲'; /* hoặc dùng icon */
                            }
                            .toggle-content::after {
                                content: ' ▼';
                            }
                            .btn-view{
                                color: #f4be44 !important;
                            }
                      </style>
                        <?php if (trim(get_the_content())): ?>
                            <div class="entry-content">
                                <div class="content-preview" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php the_content(); ?>
                                </div>
                                <div class="content-full" style="display: none;">
                                    <?php the_content(); ?>
                                </div>
                                <button class="btn btn-view p-0 mt-2 toggle-content">Xem thêm</button>
                            </div>
                        <?php endif; ?>

                        <script>
                        jQuery(document).ready(function($) {
                            $('.toggle-content').on('click', function() {
                                var $preview = $(this).siblings('.content-preview');
                                var $full = $(this).siblings('.content-full');
                                var $button = $(this);
                                $button.toggleClass('active');

                                if ($preview.is(':visible')) {
                                    $preview.slideUp(300, function() {
                                        $full.slideDown(300);
                                        $button.text('Rút gọn');
                                    });
                                } else {
                                    $full.slideUp(300, function() {
                                        $preview.slideDown(300);
                                        $button.text('Xem thêm');
                                    });
                                }
                            });
                        });
                        </script>
                    </div>
                </div>
            </article>

           
            <div class="row">
                <div class="col-lg-9">

                    <div class="chapter-list-box">
                        <?php
                        // Check if story is completed and has discount
                        $trang_thai = get_the_terms(get_the_ID(), 'trang_thai');
                        $is_completed = false;
                        $discount_percentage = 0;
                        
                        if ($trang_thai && !is_wp_error($trang_thai)) {
                            $is_completed = ($trang_thai[0]->slug === 'da-hoan-thanh');
                            if ($is_completed) {
                                $discount_percentage = get_field('giam_gia_bao_nhieu');
                            }
                        }

                        // Tính tổng giá combo chỉ cho các chương chưa mua
                        $combo_original = 0;
                        $all_purchased = true;
                        $chapter_number = 1;
                        foreach ($chapters->posts as $chapter_post) {
                            $is_locked = $locked_from && $chapter_number >= $locked_from;
                            $is_purchased = has_user_purchased_chapter($user_id, $chapter_post->ID);

                            $chapter_price = get_post_meta($chapter_post->ID, '_chapter_price', true);
                            if ($chapter_price === '') {
                                $chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                            }
                            if ($chapter_price === '') {
                                $chapter_price = 0;
                            }

                            if ($is_locked) {
                                if (!$is_purchased) {
                                    $combo_original += floatval($chapter_price);
                                    $all_purchased = false;
                                }
                            }
                            $chapter_number++;
                        }

                        // Tính giá combo sau giảm
                        $combo_price = 0;
                        if ($is_completed && $discount_percentage > 0 && $combo_original > 0) {
                            $combo_price = $combo_original * (1 - ($discount_percentage / 100));
                        }

                        // Get user balance
                        $user_balance = get_user_meta($user_id, '_user_balance', true);
                        if ($user_balance === '') {
                            $user_balance = 0;
                        }

                        // Hiển thị nút combo nếu còn chương chưa mua
                        if (!$all_purchased && $combo_price > 0) :
                        ?>
                        <div class="combo-purchase-box mb-4" data-aos="zoom-out" data-aos-duration="800" data-aos-delay="100" data-aos-easing="ease-in-out">
                            <div class="combo-purchase-box-bg">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/bg_vip.jpg" alt="">
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-white">Mua combo full truyện</h4>
                                    <p class="card-text">
                                        <span class="text-decoration-line-through text-white"><?php echo number_format($combo_original); ?> Kim tệ</span>
                                        <span class="text-danger fw-bold ms-2"><?php echo number_format($combo_price); ?> Kim tệ</span>
                                        <span class="badge bg-danger ms-2">Giảm <?php echo $discount_percentage; ?>%</span>
                                    </p>
                                    <p class="text-decoration-underline text-white">Tiết kiệm <?php echo number_format($combo_original - $combo_price); ?> Kim tệ</p>
                                    <div class="d-flex flex-md-row flex-column gap-2 align-items-center">
                                        <button class="btn btn-warning buy-combo me-2" 
                                                data-truyen-id="<?php echo $truyen_id; ?>" 
                                                data-price="<?php echo number_format($combo_price); ?>"
                                                data-original-price="<?php echo number_format($combo_original); ?>"
                                                data-discount="<?php echo $discount_percentage; ?>"
                                                data-balance="<?php echo $user_balance; ?>"
                                                <?php echo ($user_balance < $combo_price) ? 'disabled' : ''; ?>>
                                            <i class="fas fa-shopping-cart"></i> Mua combo ngay
                                        </button>
                                        <?php if ($user_balance < $combo_price): ?>
                                            <p class="text-danger mt-2">Số dư không đủ để mua combo. Vui lòng nạp thêm Kim tệ.</p>
                                        <?php endif; ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="chapter-list-title">Danh sách chương (<?php echo $chapters->post_count; ?> chương)</div>
                        <?php if (check_user_vip_status($user_id)): ?>
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-crown me-2"></i> Tài khoản VIP của bạn có thể đọc tất cả các chương
                            </div>
                        <?php endif; ?>
                        <ul class="chapter-list-ul">
                            <?php 
                            $chapter_number = 1;
                            foreach ($chapters->posts as $chapter_post) :
                                $is_locked = $locked_from && $chapter_number >= $locked_from;
                                $can_read = can_user_read_chapter($user_id, $chapter_post->ID, $truyen_id);
                                $chapter_url = get_permalink($chapter_post->ID);
                                
                                // Get chapter price
                                $chapter_price = get_post_meta($chapter_post->ID, '_chapter_price', true);
                                if ($chapter_price === '') {
                                    $chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                                }
                                if ($chapter_price === '') {
                                    $chapter_price = 0;
                                }
                            
                            ?>
                                <li>
                                    <?php if ($is_locked && !$can_read): ?>
                                        <a href="javascript:void(0)" class="buy-chapter" 
                                        data-chapter-id="<?php echo $chapter_post->ID; ?>" 
                                        data-truyen-id="<?php echo $truyen_id; ?>" 
                                        data-price="<?php echo number_format($chapter_price); ?>">
                                            <?php echo esc_html($chapter_post->post_title); ?>
                                            <span class="badge bg-warning text-dark ms-2">
                                                <i class="fas fa-lock"></i> <?php echo number_format((float)$chapter_price); ?> Kim tệ
                                            </span>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo $chapter_url; ?>">
                                            <?php echo esc_html($chapter_post->post_title); ?>
                                            <?php if ($can_read): ?>
                                                <span class="badge bg-success ms-2">
                                                    <?php if (check_user_vip_status($user_id)): ?>
                                                        <i class="fas fa-crown"></i> VIP
                                                    <?php else: ?>
                                                        <i class="fas fa-check"></i> Đã mua
                                                    <?php endif; ?>
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php 
                            $chapter_number++;
                            endforeach; 
                            ?>
                        </ul>
                    </div>

                    <script>
                    jQuery(document).ready(function($) {
                        $('.buy-chapter, .buy-combo').on('click', function(e) {
                            e.preventDefault();
                            var truyenId = $(this).data('truyen-id');
                            var price = $(this).data('price');
                            var originalPrice = $(this).data('original-price');
                            var discount = $(this).data('discount');
                            var isCombo = $(this).hasClass('buy-combo');
                            var chapterId = $(this).data('chapter-id');
                            var userBalance = $(this).data('balance');
                            
                            // Check balance for combo purchase
                            if (isCombo && userBalance < price) {
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: 'Số dư không đủ để mua combo. Vui lòng nạp thêm Kim tệ.',
                                    icon: 'error'
                                });
                                return;
                            }
                            
                            var confirmMessage = 'Bạn có muốn ';
                            if (isCombo) {
                                confirmMessage += 'mua combo toàn bộ chương với giá <strong>' + price + ' Kim tệ</strong>';
                                if (discount) {
                                    confirmMessage += ' (Giảm ' + discount + '% từ ' + originalPrice + ' Kim tệ)';
                                }
                            } else {
                                confirmMessage += 'mua chương này với giá <strong>' + price + ' Kim tệ</strong>';
                            }
                            confirmMessage += ' không?';
                            
                            Swal.fire({
                                title: isCombo ? 'Xác nhận mua combo?' : 'Xác nhận mua chương?',
                                html: confirmMessage,
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
                                            action: isCombo ? 'buy_combo' : 'buy_chapter',
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
                                            window.location.reload();
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

                    <?php
                    // Thêm phần comment
                    if (comments_open() || get_comments_number()) :
                        echo '<div class="container mt-4 px-0">';
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


                    <?php
                        $the_loai = get_the_terms(get_the_ID(), 'the_loai');
                        if ($the_loai && !is_wp_error($the_loai)) {
                            $the_loai_ids = wp_list_pluck($the_loai, 'term_id');
                            
                            $related_args = array(
                                'post_type' => 'truyen_chu',
                                'posts_per_page' => 4,
                                'post__not_in' => array(get_the_ID()),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'the_loai',
                                        'field' => 'term_id',
                                        'terms' => $the_loai_ids
                                    )
                                )
                            );
                            
                            $related_query = new WP_Query($related_args);
                            
                            if ($related_query->have_posts()) : ?>
                                <div class="related-posts mt-5">
                                    <h2 class="h3 mb-4">Truyện liên quan</h2>
                                    <div class="row latest-chapter-container">
                                        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                        <?php get_template_part( 'template-parts/home/item-card' ); ?>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            <?php endif;
                            wp_reset_postdata();
                        }
                    ?>
                </div>
                <div class="col-lg-3">
                        <?php get_template_part( 'sidebar' );  ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>



<?php get_footer(); ?> 

<script>
     document.addEventListener('DOMContentLoaded', function() {
         if (typeof AOS !== 'undefined') {
            AOS.init({ once: false });
         }
     });
</script>
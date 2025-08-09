<?php
/**
 * Template Name: Lịch Sử Thanh Toán
 */

get_header();

// Kiểm tra đăng nhập
if (!is_user_logged_in()) {
    wp_redirect(home_url('/wp-login.php'));
    exit;
}

$user_id = get_current_user_id();
$purchased_chapters = get_user_purchased_chapters($user_id);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Lịch Sử Mua Chương</h1>
            
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Truyện</th>
                                    <th>Chương</th>
                                    <th>Ngày mua</th>
                                    <th>Giá</th>
                                    <th>Loại mua</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($purchased_chapters)) {
                                    foreach ($purchased_chapters as $chapter_id => $purchase) {
                                        $truyen = get_post($purchase['truyen_id']);
                                        $chapter = get_post($chapter_id);
                                        if ($truyen && $chapter) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo get_permalink($truyen->ID); ?>" class="text-decoration-none text-black">
                                                        <?php echo esc_html($truyen->post_title); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo get_permalink($chapter->ID); ?>" class="text-decoration-none text-black">
                                                        <?php echo esc_html($chapter->post_title); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($purchase['purchase_date'])); ?></td>
                                                <td>
                                                    <?php 
                                                    if (isset($purchase['is_combo_purchase']) && $purchase['is_combo_purchase']) {
                                                        echo '<span class="text-success">Mua qua combo</span>';
                                                    } else {
                                                        echo number_format((float)$purchase['price'], 0, ',', '.') . ' VNĐ';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if (isset($purchase['is_combo_purchase']) && $purchase['is_combo_purchase']) {
                                                        echo '<span class="badge bg-success">Combo</span>';
                                                    } else {
                                                        echo '<span class="badge bg-primary">Lẻ</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo get_permalink($chapter->ID); ?>" class="btn btn-sm btn-primary">
                                                        Đọc ngay
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="text-center">Bạn chưa mua chương nào</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?> 
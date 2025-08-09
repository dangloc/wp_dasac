<?php
/**
 * Template Name: Ví Tiền
 */

get_header();

// Kiểm tra đăng nhập
if (!is_user_logged_in()) {
    wp_redirect(home_url('/wp-login.php'));
    exit;
}

// Lấy thông tin người dùng hiện tại
$current_user = wp_get_current_user();
$user_balance = get_user_meta($current_user->ID, '_user_balance', true);
$user_balance = floatval($user_balance);

// Lấy thông tin gói VIP hiện tại
$vip_data = get_user_meta($current_user->ID, 'vip_package', true);
$current_vip_status = array(
    'vip_2_months' => false, // Đổi tên để khớp với code mới
    'vip_permanent' => false
);

if ($vip_data && $vip_data['is_active']) {
    if ($vip_data['package_type'] === 'vip_permanent') {
        $current_vip_status['vip_permanent'] = true;
    } else if ($vip_data['package_type'] === 'vip_2_months') { // Đổi tên để khớp
        // Kiểm tra hết hạn
        $expiry_date = strtotime($vip_data['expiry_date']);
        $current_date = strtotime(current_time('mysql'));
        if ($current_date <= $expiry_date) {
            $current_vip_status['vip_2_months'] = true;
        }
    }
}

?>

<!-- Thêm Font Awesome để có icon đẹp -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="page-goi-vip-premium">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="vip-title">Chọn Gói VIP</h2>
            <p class="lead">Số dư của đạo hữu: <span class="balance-text"><?php echo number_format($user_balance); ?> Kim Tệ</span></p>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Gói VIP 2 Tháng -->
            <div class="col-lg-5 col-md-6">
                <div class="pricing-card pricing-card-popular h-100">
                    <div class="popular-badge">Phổ Biến</div>
                    <div class="card-body mt-3">
                        <h5 class="card-title">VIP 2 THÁNG</h5>
                        <h1 class="card-price">350,000<small class="price-currency"> kim tệ</small></h1>
                        <p class="price-duration">/ 2 Tháng</p>
                        
                        <ul class="list-unstyled feature-list">
                            <li><i class="fas fa-book-open-reader me-2"></i> Đọc truyện không giới hạn 2 tháng</li>
                            <li><i class="fas fa-crown me-2"></i> Danh hiệu VIP Tạm thời</li>
                        </ul>

                        <?php if ($current_vip_status['vip_2_months']): ?>
                            <button class="btn btn-vip-purchased w-100 mt-4" disabled>
                                <i class="fas fa-check-circle me-2"></i> Đã Kích Hoạt
                            </button>
                        <?php else: ?>
                            <button class="btn btn-vip btn-vip-popular w-100 mt-4 buy-vip" data-package="vip_2_months">
                               Kích Hoạt Ngay
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Gói VIP Vĩnh Viễn -->
            <div class="col-lg-5 col-md-6">
                <div class="pricing-card pricing-card-permanent h-100">
                     <div class="corner-ribbon"><span>Tối ưu nhất</span></div>
                    <div class="card-body mt-3">
                        <h5 class="card-title">VIP VĨNH VIỄN</h5>
                        <h1 class="card-price">800,000<small class="price-currency"> kim tệ</small></h1>
                        <p class="price-duration">/ Vĩnh Cửu</p>
                        
                        <ul class="list-unstyled feature-list">
                            <li><i class="fas fa-meteor me-2"></i> Đứng đầu trong thế giới <?php echo get_bloginfo('name'); ?></li>
                            <li><i class="fas fa-infinity me-2"></i> Đọc truyện không giới hạn mãi mãi</li>
                            <li><i class="fas fa-gem me-2"></i> Danh hiệu VIP Siêu Cấp Vip Pro</li>
                            <li><i class="fas fa-shield-halved me-2"></i> Khung avatar thể hiện Đẳng Cấp Tu Tiên</li>
                        </ul>

                         <?php if ($current_vip_status['vip_permanent']): ?>
                            <button class="btn btn-vip-purchased w-100 mt-4" disabled>
                                <i class="fas fa-check-circle me-2"></i> Đã Kích Hoạt
                            </button>
                        <?php else: ?>
                            <button class="btn btn-vip btn-vip-permanent w-100 mt-4 buy-vip" data-package="vip_permanent">
                                Lên Đỉnh Ngay
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('.buy-vip').on('click', function() {
        // Giữ nguyên phần JS của bạn vì nó đã xử lý logic tốt
        const packageType = $(this).data('package');
        // đổi tên package trong data gửi đi
        if (packageType === 'vip_2_months') {
            package_type_to_send = 'vip_3_months'; // Gửi đi tên cũ để tương thích backend
        } else {
            package_type_to_send = packageType;
        }

        const button = $(this);
        
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'process_vip_purchase',
                package_type: package_type_to_send // Sử dụng tên package cũ
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: response.data.message,
                        confirmButtonText: 'Tuyệt vời!',
                        confirmButtonColor: '#fca311' // Màu vàng cho nút
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('.balance-text').text(response.data.new_balance.toLocaleString() + ' Kim Tệ');
                            button.replaceWith('<button class="btn btn-vip-purchased w-100 mt-4" disabled><i class="fas fa-check-circle me-2"></i> Đã Kích Hoạt</button>');
                            // Tùy chọn: reload trang để cập nhật toàn bộ trạng thái
                            // location.reload(); 
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất Bại!',
                        text: response.data,
                        confirmButtonText: 'Thử lại',
                        confirmButtonColor: '#d33'
                    });
                    // Kích hoạt lại nút nếu thất bại
                    button.prop('disabled', false).text(packageType === 'vip_permanent' ? 'Lên Đỉnh Ngay' : 'Kích Hoạt Ngay');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi hệ thống xảy ra, vui lòng thử lại sau.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#d33'
                });
                 button.prop('disabled', false).text(packageType === 'vip_permanent' ? 'Lên Đỉnh Ngay' : 'Kích Hoạt Ngay');
            }
        });
    });
});
</script>


<?php get_footer(); ?>
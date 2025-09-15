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
    'vip_30_days' => false,
    'vip_60_days' => false,
    'vip_permanent' => false
);

if ($vip_data && $vip_data['is_active']) {
    if ($vip_data['package_type'] === 'vip_permanent') {
        $current_vip_status['vip_permanent'] = true;
    } else if ($vip_data['package_type'] === 'vip_2_months') { // 30 ngày
        // Kiểm tra hết hạn
        $expiry_date = strtotime($vip_data['expiry_date']);
        $current_date = strtotime(current_time('mysql'));
        if ($current_date <= $expiry_date) {
            $current_vip_status['vip_30_days'] = true;
        }
    } else if ($vip_data['package_type'] === 'vip_3_months') { // 60 ngày
        // Kiểm tra hết hạn
        $expiry_date = strtotime($vip_data['expiry_date']);
        $current_date = strtotime(current_time('mysql'));
        if ($current_date <= $expiry_date) {
            $current_vip_status['vip_60_days'] = true;
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

         <!-- Gói VIP 30 Ngày -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card pricing-card-basic h-100">
                    <div class="popular-badge">Phổ Biến</div>
                    <div class="card-body mt-3">
                        <h5 class="card-title">VIP 30 NGÀY</h5>
                        <h1 class="card-price">299,000<small class="price-currency"> kim tệ</small></h1>
                        <p class="price-duration">/ 30 Ngày</p>
                        
                        <ul class="list-unstyled feature-list">
                            <li><i class="fas fa-book-open-reader me-2"></i> Đọc truyện không giới hạn 30 ngày</li>
                            <li><i class="fas fa-ban me-2"></i> Không bao gồm danh hiệu VIP</li>
                        </ul>

                        <?php if ($current_vip_status['vip_30_days']): ?>
                            <button class="btn btn-vip-purchased w-100 mt-4" disabled>
                                <i class="fas fa-check-circle me-2"></i> Đã Kích Hoạt
                            </button>
                        <?php else: ?>
                            <button class="btn btn-vip btn-vip-popular w-100 mt-4 buy-vip" data-package="vip_30_days">
                               Kích Hoạt Ngay
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

             <!-- Gói VIP 60 Ngày -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card pricing-card-popular h-100">
                    <div class="popular-badge">Phổ Biến</div>
                    <div class="card-body mt-3">
                        <h5 class="card-title">VIP 60 NGÀY</h5>
                        <h1 class="card-price">599,000<small class="price-currency"> kim tệ</small></h1>
                        <p class="price-duration">/ 60 Ngày</p>
                        
                        <ul class="list-unstyled feature-list">
                            <li><i class="fas fa-book-open-reader me-2"></i> Đọc truyện không giới hạn 60 ngày</li>
                            <li><i class="fas fa-crown me-2"></i> Danh hiệu VIP Tạm thời</li>
                        </ul>

                        <?php if ($current_vip_status['vip_60_days']): ?>
                            <button class="btn btn-vip-purchased w-100 mt-4" disabled>
                                <i class="fas fa-check-circle me-2"></i> Đã Kích Hoạt
                            </button>
                        <?php else: ?>
                            <button class="btn btn-vip btn-vip-popular w-100 mt-4 buy-vip" data-package="vip_60_days">
                               Kích Hoạt Ngay
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Gói VIP Vĩnh Viễn -->
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card pricing-card-permanent h-100">
                     <div class="corner-ribbon"><span>Tối ưu nhất</span></div>
                    <div class="card-body mt-3">
                        <h5 class="card-title">VIP VĨNH VIỄN</h5>
                        <h1 class="card-price">999,999<small class="price-currency"> kim tệ</small></h1>
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

<style>
/* Style differentiation: basic vs popular */
.pricing-card-basic {
    border: 1px solid #e9ecef;
    background: #ffffff;
    box-shadow: none;
}
.pricing-card-basic .popular-badge { display: none; }
.pricing-card-basic .card-title { color: #495057; }
.pricing-card-basic .card-price { color: #343a40; font-weight: 700; }
.pricing-card-basic .feature-list li { color: #6c757d; }
.pricing-card-basic .btn-vip { background: #6c757d; border-color: #6c757d; }
.pricing-card-basic .btn-vip:hover { filter: brightness(0.95); }

/* Keep popular (60-day) more highlighted if needed */
.pricing-card-popular {
    box-shadow: 0 10px 25px rgba(252, 163, 17, 0.25);
    border: 1px solid rgba(252, 163, 17, 0.35);
}
</style>

<script>
jQuery(document).ready(function($) {
    $('.buy-vip').on('click', function() {
        // Giữ nguyên phần JS của bạn vì nó đã xử lý logic tốt
        const packageType = $(this).data('package');
        // đổi tên package trong data gửi đi
        if (packageType === 'vip_30_days') {
            package_type_to_send = 'vip_2_months'; // Gửi đi tên cũ để tương thích backend
        } else if (packageType === 'vip_60_days') {
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
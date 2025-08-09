<?php
// Kiểm tra đăng nhập
if (!is_user_logged_in()) {
    wp_send_json_error('Bạn cần đăng nhập để thực hiện chức năng này');
    exit;
}

// Lấy thông tin người dùng hiện tại
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Lấy số dư hiện tại của người dùng
$current_balance = get_user_meta($user_id, 'user_balance', true);
$current_balance = floatval($current_balance);

// Lấy thông tin gói VIP được chọn
$package_type = isset($_POST['package_type']) ? sanitize_text_field($_POST['package_type']) : '';
$package_price = 0;
$package_duration = 0;

// Xác định thông tin gói
if ($package_type === 'vip_3_months') {
    $package_price = 350000;
    $package_duration = 3; // 3 tháng
} elseif ($package_type === 'vip_permanent') {
    $package_price = 800000;
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
update_user_meta($user_id, 'user_balance', $new_balance);

// Cập nhật thông tin gói VIP
$vip_data = array(
    'package_type' => $package_type,
    'purchase_date' => current_time('mysql'),
    'expiry_date' => $package_duration === -1 ? 'permanent' : date('Y-m-d H:i:s', strtotime("+{$package_duration} months")),
    'is_active' => true
);

update_user_meta($user_id, 'vip_package', $vip_data);

// Gửi thông báo thành công
wp_send_json_success(array(
    'message' => 'Mua gói VIP thành công',
    'new_balance' => $new_balance,
    'vip_data' => $vip_data
)); 
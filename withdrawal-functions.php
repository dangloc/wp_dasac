<?php
// Add withdrawal request handler
add_action('wp_ajax_request_withdrawal', 'handle_withdrawal_request');
function handle_withdrawal_request() {
    check_ajax_referer('withdrawal_nonce', 'security');
    
    $user_id = get_current_user_id();
    $amount = floatval($_POST['amount']);
    $user_balance = get_user_meta($user_id, '_user_balance', true);
    $bank_account = sanitize_text_field($_POST['bank_account']);
    $bank_name = sanitize_text_field($_POST['bank_name']);
    
    // Verify user has enough balance
    if ($amount > $user_balance) {
        wp_send_json_error('Số dư không đủ');
        return;
    }
    if (empty($bank_account) || empty($bank_name)) {
        wp_send_json_error('Vui lòng nhập đầy đủ thông tin ngân hàng');
        return;
    }
    
    // Create withdrawal request
    $withdrawal_data = array(
        'user_id' => $user_id,
        'amount' => $amount,
        'fee' => $amount * 0.05,
        'net_amount' => $amount * 0.95,
        'status' => 'pending',
        'date' => current_time('mysql'),
        'user_name' => get_user_meta($user_id, 'nickname', true),
        'bank_account' => $bank_account,
        'bank_name' => $bank_name
    );
    
    // Get existing requests
    $existing_requests = get_option('withdrawal_requests', array());
    $existing_requests[] = $withdrawal_data;
    update_option('withdrawal_requests', $existing_requests);
    
    wp_send_json_success('Yêu cầu rút tiền đã được gửi thành công');
}

// Add admin menu for withdrawal requests
add_action('admin_menu', 'add_withdrawal_menu');
function add_withdrawal_menu() {
    add_menu_page(
        'Yêu cầu rút tiền',
        'Yêu cầu rút tiền',
        'manage_options',
        'withdrawal-requests',
        'display_withdrawal_requests',
        'dashicons-money-alt',
        30
    );
}

// Display withdrawal requests in admin
function display_withdrawal_requests() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $requests = get_option('withdrawal_requests', array());
    ?>
    <div class="wrap">
        <h1>Yêu cầu rút tiền</h1>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Số tài khoản</th>
                    <th>Ngân hàng</th>
                    <th>Số tiền</th>
                    <th>Phí (5%)</th>
                    <th>Số tiền nhận</th>
                    <th>Ngày yêu cầu</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $index => $request): ?>
                    <tr>
                        <td><?php echo esc_html($request['user_name']); ?></td>
                        <td><?php echo esc_html($request['bank_account']); ?></td>
                        <td><?php echo esc_html($request['bank_name']); ?></td>
                        <td><?php echo number_format($request['amount']); ?> Kim tệ</td>
                        <td><?php echo number_format($request['fee']); ?> Kim tệ</td>
                        <td><?php echo number_format($request['net_amount']); ?> Kim tệ</td>
                        <td><?php echo date('d/m/Y H:i', strtotime($request['date'])); ?></td>
                        <td>
                            <span class="status-<?php echo esc_attr($request['status']); ?>">
                                <?php 
                                switch($request['status']) {
                                    case 'pending':
                                        echo 'Chờ xử lý';
                                        break;
                                    case 'completed':
                                        echo 'Đã hoàn thành';
                                        break;
                                    case 'rejected':
                                        echo 'Đã từ chối';
                                        break;
                                }
                                ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($request['status'] === 'pending'): ?>
                                <button class="button button-primary approve-withdrawal" data-index="<?php echo $index; ?>">
                                    Duyệt
                                </button>
                                <button class="button button-secondary reject-withdrawal" data-index="<?php echo $index; ?>">
                                    Từ chối
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('.approve-withdrawal').on('click', function() {
            const index = $(this).data('index');
            if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu rút tiền này?')) {
                $.post(ajaxurl, {
                    action: 'process_withdrawal',
                    security: '<?php echo wp_create_nonce("process_withdrawal_nonce"); ?>',
                    index: index,
                    status: 'completed'
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + response.data);
                    }
                });
            }
        });
        
        $('.reject-withdrawal').on('click', function() {
            const index = $(this).data('index');
            if (confirm('Bạn có chắc chắn muốn từ chối yêu cầu rút tiền này?')) {
                $.post(ajaxurl, {
                    action: 'process_withdrawal',
                    security: '<?php echo wp_create_nonce("process_withdrawal_nonce"); ?>',
                    index: index,
                    status: 'rejected'
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + response.data);
                    }
                });
            }
        });
    });
    </script>
    
    <style>
    .status-pending { color: #f0ad4e; }
    .status-completed { color: #5cb85c; }
    .status-rejected { color: #d9534f; }
    </style>
    <?php
}

// Handle withdrawal processing
add_action('wp_ajax_process_withdrawal', 'process_withdrawal_request');
function process_withdrawal_request() {
    check_ajax_referer('process_withdrawal_nonce', 'security');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Không có quyền thực hiện thao tác này');
        return;
    }
    
    $index = intval($_POST['index']);
    $status = sanitize_text_field($_POST['status']);
    
    $requests = get_option('withdrawal_requests', array());
    
    if (!isset($requests[$index])) {
        wp_send_json_error('Không tìm thấy yêu cầu rút tiền');
        return;
    }
    
    $request = $requests[$index];
    
    if ($status === 'completed') {
        // Check if user still has enough balance
        $user_balance = get_user_meta($request['user_id'], '_user_balance', true);
        if ($request['amount'] > $user_balance) {
            wp_send_json_error('Người dùng không còn đủ số dư để thực hiện giao dịch');
            return;
        }
        
        // Deduct balance from user
        update_user_meta($request['user_id'], '_user_balance', $user_balance - $request['amount']);
    }
    
    // Update request status
    $requests[$index]['status'] = $status;
    update_option('withdrawal_requests', $requests);
    
    wp_send_json_success('Cập nhật trạng thái thành công');
} 
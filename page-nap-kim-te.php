<?php
/**
 * Template Name: Ví Tiền
 */

get_header();

if (!is_user_logged_in()) {
    wp_redirect(home_url('/wp-login.php'));
    exit;
}

$user_id = get_current_user_id();
$balance = get_user_meta($user_id, '_user_balance', true);
if ($balance === '') {
    $balance = 0;
}

// Danh sách mệnh giá
$amounts = [50000, 100000, 200000, 350000, 500000, 800000, 1500000, 2500000];

// Lấy danh sách QR code
$qr_images = get_field('danh_sach_hinh_anh_qr_code', 110);

// Lấy PayPal Client ID từ theme option (sẽ setup trong hướng dẫn)
$paypal_client_id = get_option('paypal_client_id', '');
?>

<style>
    .nap-xu-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    .page-title-section {
        margin-bottom: 30px;
    }
    
    .page-title-section h1 {
        font-size: 32px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .page-title-section .subtitle {
        font-size: 16px;
        color: #666;
    }
    
    /* Payment Method Tabs */
    .payment-method-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    
    .payment-tab {
        padding: 12px 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }
    
    .payment-tab:hover {
        border-color: #f5576c;
        color: #f5576c;
    }
    
    .payment-tab.active {
        background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
        color: #fff;
        border-color: transparent;
    }
    
    .payment-tab i {
        font-size: 18px;
    }
    
    /* Alert Box */
    .alert-box {
        background: #fff5f7;
        border: 1px solid #ffd6e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        color: #c2185b;
        font-size: 14px;
    }
    
    .alert-box a {
        color: #f5576c;
        text-decoration: underline;
        font-weight: 500;
    }
    
    /* Bank Selection */
    .bank-selection-section {
        margin-bottom: 25px;
    }
    
    .bank-selection-section h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }
    
    .bank-card {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .bank-card:hover {
        border-color: #f5576c;
    }
    
    .bank-card.selected {
        border-color: #28a745;
        background: #f8fff9;
    }
    
    .bank-card .check-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #28a745;
        font-size: 20px;
        display: none;
    }
    
    .bank-card.selected .check-icon {
        display: block;
    }
    
    .bank-logo {
        width: 60px;
        height: 60px;
        background: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        color: #333;
    }
    
    .bank-name {
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }
    
    /* Amount Select */
    .amount-select-section {
        margin-bottom: 25px;
    }
    
    .amount-select-section h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }
    
    .amount-select-group {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .amount-select-group select {
        flex: 1;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 16px;
        background: #fff;
        cursor: pointer;
        transition: border-color 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        padding-right: 40px;
    }
    
    .amount-select-group select:focus {
        outline: none;
        border-color: #f5576c;
    }
    
    .amount-select-group .vnd-btn {
        padding: 12px 20px;
        background: #e0e0e0;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        color: #666;
        cursor: default;
    }
    
    .amount-hint {
        font-size: 13px;
        color: #999;
        margin-top: 5px;
    }
    
    /* Coins Received Summary */
    .coins-summary {
        background: #fff5f7;
        border: 1px solid #ffd6e0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .coins-summary-label {
        font-size: 16px;
        color: #c2185b;
        font-weight: 500;
    }
    
    .coins-summary-amount {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 24px;
        font-weight: 700;
        color: #f5576c;
    }
    
    .coins-summary-amount i {
        font-size: 28px;
    }
    
    /* Payment Button */
    .payment-button {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 12px;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .payment-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    
    .payment-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    /* History Link */
    .history-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #666;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
    }
    
    .history-link:hover {
        color: #f5576c;
    }
    
    /* Sticky Summary Card */
    .sticky-summary-card {
        position: fixed;
        top: 100px;
        right: 20px;
        width: 250px;
        background: #fff5f7;
        border: 1px solid #ffd6e0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }
    
    .sticky-summary-card .coins-display {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .sticky-summary-card .coins-display i {
        font-size: 32px;
        color: #f5576c;
        margin-bottom: 10px;
    }
    
    .sticky-summary-card .coins-display .amount {
        font-size: 28px;
        font-weight: 700;
        color: #f5576c;
    }
    
    .sticky-summary-card .coins-display .label {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
    
    .sticky-summary-card .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sticky-summary-card .info-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .sticky-summary-card .info-list li i {
        color: #f5576c;
        margin-top: 3px;
    }
    
    /* PayPal Container */
    .payment-section {
        display: none;
    }
    
    .payment-section.active {
        display: block;
    }
    
    .paypal-container {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 25px;
        text-align: center;
    }
    
    #paypal-button-container {
        margin: 20px 0;
        min-height: 50px;
    }
    
    #paypal-button-container > div {
        display: flex !important;
        justify-content: center;
    }
    
    .paypal-note {
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        font-size: 14px;
        color: #004085;
    }
    
    @media (max-width: 991px) {
        .sticky-summary-card {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        .payment-method-tabs {
            flex-direction: column;
        }
        
        .payment-tab {
            width: 100%;
        }
        
        .amount-select-group {
            flex-direction: column;
        }
        
        .amount-select-group .vnd-btn {
            width: 100%;
        }
    }
</style>

<div class="nap-xu-container">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Title -->
            <div class="page-title-section">
                <h1>Nạp kim tệ </h1>
                <p class="subtitle">Nạp kim tệ với nhiều ưu đãi hấp dẫn</p>
            </div>
            
            <!-- Payment Method Tabs -->
            <div class="payment-method-tabs">
                <div class="payment-tab active" data-method="bank-auto">
                    <i class="fas fa-robot"></i>
                    <span>Bank</span>
                </div>
                <div class="payment-tab" data-method="paypal">
                    <i class="fab fa-paypal"></i>
                    <span>PayPal</span>
                </div>
            </div>
            
            <!-- Alert Box -->
            <div class="alert-box">
                Vui lòng liên hệ về <a href="#" target="_blank">fanpage</a> để được hỗ trợ nếu có vấn đề về nạp xu
            </div>
            
            <!-- Bank Payment Section -->
            <div class="payment-section active" id="bank-payment">
                <!-- Bank Selection -->
                <div class="bank-selection-section">
                    <h3>Chuyển khoản đến ngân hàng</h3>
                    <div class="bank-card selected" data-bank="ocb">
                        <div class="bank-logo">Se</div>
                        <div class="bank-name">SePay</div>
                        <i class="fas fa-check-circle check-icon"></i>
                    </div>
                </div>
                
                <!-- Amount Select -->
                <div class="amount-select-section">
                    <h3>Chọn mệnh giá muốn nạp (VNĐ)</h3>
                    <div class="amount-select-group">
                        <select id="amountSelect">
                            <option value="">-- Chọn mệnh giá --</option>
                            <?php foreach ($amounts as $amount): ?>
                                <option value="<?php echo $amount; ?>" data-amount="<?php echo $amount; ?>">
                                    <?php echo number_format($amount, 0, ',', '.'); ?> VNĐ
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="vnd-btn" disabled>VNĐ</button>
                    </div>
                    <p class="amount-hint">Số tiền tối thiểu: 50,000 VNĐ, phải là bội số của 10.000</p>
                </div>
                
                <!-- Coins Received Summary -->
                <div class="coins-summary">
                    <span class="coins-summary-label">Kim tệ nhận được:</span>
                    <div class="coins-summary-amount">
                        <span id="coinsAmount">0</span>
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                
                <!-- Payment Button -->
                <button class="payment-button" id="paymentButton" data-bs-toggle="modal" data-bs-target="#depositModal">
                    <i class="fas fa-robot"></i>
                    <span>Thanh toán tự động</span>
                </button>
                
                <!-- History Link -->
                <a href="<?php echo home_url('/index.php/lich-su-thanh-toan'); ?>" class="history-link">
                    <i class="fas fa-history"></i>
                    <span>Lịch sử nạp tự động</span>
                </a>
            </div>
            
            <!-- PayPal Payment Section -->
            <div class="payment-section" id="paypal-payment">
                <div class="paypal-container">
                    <h3>Thanh toán với PayPal</h3>
                    <p>Chọn mệnh giá muốn nạp rồi nhấn nút dưới để tiến hành thanh toán</p>
                    
                    <!-- Amount Select for PayPal -->
                    <div class="amount-select-section">
                        <h3>Chọn mệnh giá muốn nạp (VNĐ)</h3>
                        <div class="amount-select-group">
                            <select id="paypalAmountSelect">
                                <option value="">-- Chọn mệnh giá --</option>
                                <?php foreach ($amounts as $amount): ?>
                                    <option value="<?php echo $amount; ?>" data-amount="<?php echo $amount; ?>">
                                        <?php echo number_format($amount, 0, ',', '.'); ?> VNĐ
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="vnd-btn" disabled>VNĐ</button>
                        </div>
                        <p class="amount-hint">Số tiền tối thiểu: 50,000 VNĐ, phải là bội số của 10.000</p>
                    </div>
                    
                    <!-- Coins Summary for PayPal -->
                    <div class="coins-summary">
                        <span class="coins-summary-label">Kim tệ nhận được:</span>
                        <div class="coins-summary-amount">
                            <span id="paypalCoinsAmount">0</span>
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                   
                    
                    <!-- PayPal Button Container (SDK Buttons) -->
                    <div id="paypal-button-container" style="margin-top: 20px;"></div>
                    
                    <!-- Note -->
                    <div class="paypal-note">
                        <strong>Lưu ý:</strong> Giao dịch sẽ được xử lý ngay sau khi thanh toán thành công trên PayPal. Xu sẽ được cộng vào tài khoản của bạn trong vòng vài giây.
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Sticky Summary Card -->
        <div class="col-lg-4">
            <div class="sticky-summary-card">
                <div class="coins-display">
                    <i class="fas fa-coins"></i>
                    <div class="amount"><?php echo number_format($balance); ?></div>
                    <div class="label">Số xu hiện có trong tài khoản</div>
                </div>
                <ul class="info-list">
                    <li>
                        <i class="fas fa-arrow-right"></i>
                        <span>Nạp xu tự động nhận xu ngay sau khi thanh toán</span>
                    </li>
                    <li>
                        <i class="fas fa-arrow-right"></i>
                        <span>Dùng xu để đọc truyện trả phí</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Hidden QR Images -->
<?php if ($qr_images): ?>
<div id="qr-images" class="d-none">
    <?php foreach ($qr_images as $image): ?>
        <img
            src="<?php echo esc_url($image['url']); ?>"
            data-img="<?php echo esc_attr((int)$image['title']); ?>"
            alt="<?php echo esc_attr($image['alt']); ?>"
        />
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Modal -->
<div id="depositModal" class="modal fade" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 id="depositModalLabel" class="modal-title">Xác nhận nạp kim tệ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body text-center">
                <div class="my-3">
                    <img id="qrImagePreview" class="img-fluid" style="max-height: 250px;" alt="QR Code" />
                </div>
                <?php 
                $current_user = wp_get_current_user();
                $username = esc_html($current_user->user_login);
                ?>
                <h2 style="color:red">Nội dung chuyển khoản bắt buộc phải nhập: <p><?php echo $username; ?></p></h2>
                <p class="text-black">( 1000 kim tệ tương đương 1000 vnđ )</p>
                <h5 class="text-black">Khi chuyển khoản xong thì bấm xác nhận và đợi 1 phút sau đó kiểm tra số dư</h5>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
                <button id="confirmDepositBtn" class="btn btn-success" type="button">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>

<!-- SweetAlert2 CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
jQuery(function ($) {
    // Tỷ lệ quy đổi: 1000 VNĐ = 1000 xu
    const EXCHANGE_RATE = 1;
    
    let selectedAmount = 0;
    const amountSelect = $('#amountSelect');
    const coinsAmount = $('#coinsAmount');
    const paymentButton = $('#paymentButton');
    const confirmBtn = $('#confirmDepositBtn');
    const modalEl = document.getElementById('depositModal');
    const qrImagePreview = $('#qrImagePreview');
    
    // Tính số xu nhận được
    function updateCoinsAmount(vndAmount) {
        if (!vndAmount || vndAmount === 0) {
            coinsAmount.text('0');
            paymentButton.prop('disabled', true);
            return;
        }
        const coins = Math.floor(vndAmount * EXCHANGE_RATE);
        coinsAmount.text(coins.toLocaleString('vi-VN'));
        paymentButton.prop('disabled', false);
    }
    
    // Xử lý khi chọn mệnh giá
    amountSelect.on('change', function() {
        const amount = parseInt($(this).val()) || 0;
        selectedAmount = amount;
        updateCoinsAmount(amount);
    });
    
    // Payment method tabs
    $('.payment-tab').on('click', function() {
        const method = $(this).data('method');
        
        $('.payment-tab').removeClass('active');
        $(this).addClass('active');
        
        // Toggle payment sections
        $('.payment-section').removeClass('active');
        
        if (method === 'bank-auto') {
            $('#bank-payment').addClass('active');
            // Reset PayPal container
            if (window.paypalInitialized) {
                // Nếu cần reset, có thể làm ở đây
            }
        } else if (method === 'paypal') {
            $('#paypal-payment').addClass('active');
            // Initialize PayPal buttons nếu chưa được initialize
            if (!window.paypalInitialized) {
                // Delay để đảm bảo DOM render xong
                setTimeout(function() {
                    initializePayPalButtons();
                    window.paypalInitialized = true;
                }, 100);
            }
        }
    });
    
    // Bank selection
    $('.bank-card').on('click', function() {
        $('.bank-card').removeClass('selected');
        $(this).addClass('selected');
    });
    
    // Khởi tạo modal instance một lần để tránh tạo nhiều instance
    let modalInstance = bootstrap.Modal.getInstance(modalEl);
    if (!modalInstance) {
        modalInstance = new bootstrap.Modal(modalEl, {
            backdrop: true,
            keyboard: true,
            focus: true
        });
    }
    
    // Payment button click - mở modal với QR code tương ứng
    paymentButton.on('click', function(e) {
        e.preventDefault();
        
        const amount = parseInt(amountSelect.val()) || 0;
        
        if (!amount || amount === 0) {
            alert('Vui lòng chọn mệnh giá nạp');
            return false;
        }
        
        selectedAmount = amount;
        
        // Tìm QR code tương ứng
        const qrImg = $('#qr-images img[data-img="' + amount + '"]');
        if (qrImg.length) {
            qrImagePreview.attr('src', qrImg.attr('src'));
        } else {
            // Nếu không tìm thấy QR code cho số tiền cụ thể, lấy QR code đầu tiên
            const firstQr = $('#qr-images img').first();
            if (firstQr.length) {
                qrImagePreview.attr('src', firstQr.attr('src'));
            } else {
                qrImagePreview.attr('src', '');
            }
        }
        
        // Mở modal
        if (modalInstance) {
            modalInstance.show();
        }
    });
    
    // Modal show event - đảm bảo QR code được load đúng
    modalEl.addEventListener('show.bs.modal', function (event) {
        const amount = parseInt(amountSelect.val()) || 0;
        
        if (amount > 0) {
            selectedAmount = amount;
            
            // Tìm QR code tương ứng
            const qrImg = $('#qr-images img[data-img="' + amount + '"]');
            if (qrImg.length) {
                qrImagePreview.attr('src', qrImg.attr('src'));
            } else {
                // Nếu không tìm thấy QR code cho số tiền cụ thể, lấy QR code đầu tiên
                const firstQr = $('#qr-images img').first();
                if (firstQr.length) {
                    qrImagePreview.attr('src', firstQr.attr('src'));
                } else {
                    qrImagePreview.attr('src', '');
                }
            }
        }
    });
    
    // Xử lý khi modal đóng - đảm bảo overlay được remove
    modalEl.addEventListener('hidden.bs.modal', function (event) {
        // Remove overlay nếu còn sót lại
        $('.modal-backdrop').remove();
        // Remove class modal-open từ body
        $('body').removeClass('modal-open');
        // Remove style overflow và padding từ body
        $('body').css({
            'overflow': '',
            'padding-right': ''
        });
    });
    
    // Khởi tạo - disable button ban đầu
    paymentButton.prop('disabled', true);
    coinsAmount.text('0');
    
    // PayPal Amount Select
    const paypalAmountSelect = $('#paypalAmountSelect');
    const paypalCoinsAmount = $('#paypalCoinsAmount');
    
    // Update PayPal coins amount
    function updatePayPalCoinsAmount(vndAmount) {
        if (!vndAmount || vndAmount === 0) {
            paypalCoinsAmount.text('0');
            return;
        }
        const coins = Math.floor(vndAmount * EXCHANGE_RATE);
        paypalCoinsAmount.text(coins.toLocaleString('vi-VN'));
    }
    
    // Xử lý khi chọn mệnh giá cho PayPal
    paypalAmountSelect.on('change', function() {
        const amount = parseInt($(this).val()) || 0;
        updatePayPalCoinsAmount(amount);
    });
    
    // Initialize PayPal Buttons
    function initializePayPalButtons() {
        // Clear previous buttons if any
        $('#paypal-button-container').empty();
        
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'blue',
                shape:  'rect',
                label:  'paypal',
                height: 45,
                tagline: false
            },
            
            // Set up the transaction
            createOrder: function(data, actions) {
                const amount = parseInt(paypalAmountSelect.val()) || 0;
                
                if (!amount || amount === 0) {
                    // Hiển thị error message thay vì alert
                    document.querySelector('#paypal-button-container').innerHTML = '<p style="color: red; text-align: center;">Vui lòng chọn mệnh giá nạp</p>';
                    return;
                }
                
                // Convert VND to USD (approximate: 1 USD = 23,000 VND)
                // Điều chỉnh tỷ giá theo thực tế của bạn
                const usdAmount = (amount / 23000).toFixed(2);
                
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: "USD",
                            value: usdAmount,
                            breakdown: {
                                item_total: {
                                    currency_code: "USD",
                                    value: usdAmount
                                }
                            }
                        },
                        items: [{
                            name: "Nạp kim tệ",
                            description: "Nạp " + amount.toLocaleString('vi-VN') + " VNĐ",
                            sku: "coin-" + amount,
                            unit_amount: {
                                currency_code: "USD",
                                value: usdAmount
                            },
                            quantity: "1"
                        }],
                        custom_id: amount + "|" + <?php echo $user_id; ?>
                    }]
                });
            },
            
            // Finalize the transaction
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    // Show success message
                    const amount = parseInt(paypalAmountSelect.val()) || 0;
                    const coins = Math.floor(amount * EXCHANGE_RATE);
                    
                    // Show loading
                    Swal.fire({
                        title: 'Đang xử lý...',
                        text: 'Vui lòng chờ trong khi chúng tôi xác nhận giao dịch',
                        icon: 'info',
                        allowOutsideClick: false,
                        didOpen: (toast) => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Make AJAX call to confirm payment
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'paypal_confirm_payment',
                            nonce: '<?php echo wp_create_nonce('paypal_nonce'); ?>',
                            payment_id: orderData.id,
                            transaction_id: 'paypal_' + orderData.id,
                            amount: amount,
                            coins: coins
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Thanh toán thành công! ' + coins.toLocaleString('vi-VN') + ' xu đã được cộng vào tài khoản của bạn.');
                                location.reload();
                            } else {
                                alert('Có lỗi xảy ra: ' + response.data.message);
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi xử lý giao dịch. Vui lòng liên hệ hỗ trợ.',
                                icon: 'error',
                                confirmButtonText: 'Đóng',
                                confirmButtonColor: '#dc3545'
                            });
                        }
                    });
                });
            },
            
            // Handle errors
            onError: function (err) {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.',
                    icon: 'error',
                    confirmButtonText: 'Thử lại',
                    confirmButtonColor: '#dc3545'
                });
                console.error('PayPal Error:', err);
            },
            
            // Handle when user closes the popup
            onCancel: function(data) {
                Swal.fire({
                    title: 'Đã hủy',
                    text: 'Thanh toán đã bị hủy. Vui lòng thử lại.',
                    icon: 'warning',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#ffc107'
                });
                console.log('Payment cancelled by user');
            }
        }).render('#paypal-button-container');
    }
    
    window.paypalInitialized = false;
    window.paypalSDKLoaded = false;
});
</script>

<!-- PayPal SDK -->
<?php if (!empty($paypal_client_id)): ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo esc_attr($paypal_client_id); ?>&components=buttons&locale=vi_VN"></script>
<script>
    jQuery(function ($) {
        const paypalClientId = '<?php echo !empty($paypal_client_id) ? esc_attr($paypal_client_id) : ''; ?>';
        const userId = '<?php echo get_current_user_id(); ?>';
        const EXCHANGE_RATE = 1;
        
        const paypalAmountSelect = $('#paypalAmountSelect');
        const paypalPaymentButton = $('#paypalPaymentButton');
        const paypalCoinsAmount = $('#paypalCoinsAmount');
        
        // Update PayPal coins
        function updatePaypalCoins(vndAmount) {
            if (!vndAmount || vndAmount === 0) {
                paypalCoinsAmount.text('0');
                paypalPaymentButton.prop('disabled', true);
            } else {
                const coins = Math.floor(vndAmount * EXCHANGE_RATE);
                paypalCoinsAmount.text(coins.toLocaleString('vi-VN'));
                paypalPaymentButton.prop('disabled', false);
            }
        }
        
        paypalAmountSelect.on('change', function() {
            updatePaypalCoins(parseInt($(this).val()) || 0);
        });
        
        // Click button to render PayPal buttons
        paypalPaymentButton.on('click', function(e) {
            e.preventDefault();
            
            const amount = parseInt(paypalAmountSelect.val()) || 0;
            if (!amount || amount === 0) {
                alert('Vui lòng chọn mệnh giá nạp');
                return;
            }
            
            // Render PayPal buttons
            if (typeof paypal !== 'undefined' && paypal.Buttons) {
                renderPayPalButtons(amount);
            }
        });
        
        function renderPayPalButtons(amount) {
            $('#paypal-button-container').empty(); // Clear previous without removing element
            
            paypal.Buttons({
                style: {
                    layout: 'vertical',
                    color: 'blue',
                    shape: 'rect',
                    label: 'paypal',
                    height: 45
                },
                
                createOrder: function(data, actions) {
                    const usdAmount = (amount / 23000).toFixed(2);
                    
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                currency_code: "USD",
                                value: usdAmount
                            }
                        }]
                    });
                },
                
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(orderData) {
                        const coins = Math.floor(amount * EXCHANGE_RATE);
                        
                        $.ajax({
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            type: 'POST',
                            data: {
                                action: 'paypal_confirm_payment',
                                nonce: '<?php echo wp_create_nonce('paypal_nonce'); ?>',
                                payment_id: orderData.id,
                                amount: amount,
                                coins: coins
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Thành công!',
                                        html: coins.toLocaleString('vi-VN') + ' xu đã được cộng vào tài khoản của bạn.',
                                        icon: 'success',
                                        confirmButtonText: 'Đóng',
                                        confirmButtonColor: '#28a745'
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: response.data.message,
                                        icon: 'error',
                                        confirmButtonText: 'Thử lại',
                                        confirmButtonColor: '#dc3545'
                                    });
                                }
                            }
                        });
                    });
                },
                
                onError: function(err) {
                    console.error(err);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            }).render('#paypal-button-container');
        }
    });
</script>
<?php else: ?>
<script>
    jQuery(function ($) {
        $('#paypalPaymentButton').prop('disabled', true).text('PayPal chưa được cấu hình');
    });
</script>
<?php endif; ?>

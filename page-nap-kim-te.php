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
?>

<style>
    .custom-btn{
        white-space: nowrap;
        color: #e53a22 !important;
        border: 1px solid #e53a22 !important;

        
    }
    @media screen and (max-width: 560px) {
        .custom-btn{
        white-space: wrap;
    }
    }
    .custom-text-title{
        font-size: 48px;
        margin-bottom: 12px;
        font-family: "Island Moments", cursive;
        font-weight: 400;
        color: #e53a22;
        font-style: normal;
    }
</style>

<div class="container text-center my-3" style="height: 65vh">
    <div class="d-flex flex-column justify-content-center h-100">
        <div class="d-flex p-2 justify-content-center">
            <h3 class="custom-text-title">Mời tiên trưởng chọn gọi nạp kim tệ đột phá!!!</h3>
        </div>
        <div class="row row-cols-2 row-cols-md-4 gx-3 gy-3">
            <?php 
            $amounts = [50000, 100000, 200000, 500000, 1000000];
            foreach ($amounts as $amount): ?>
                <div class="col mb-3">
                    <button class="btn custom-btn w-100" data-bs-toggle="modal" data-bs-target="#depositModal" data-amount="<?php echo $amount; ?>">
                        Nạp <?php echo number_format($amount, 0, ',', '.'); ?> Kim tệ
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
$qr_images = get_field('danh_sach_hinh_anh_qr_code', 110); // Lấy danh sách ảnh từ ACF Gallery
if ($qr_images):
?>
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

                ob_start();
                ?>
                <!-- HTML ở đây -->
                <h2 style="color:red">Nội dung chuyển khoản bắt buộc phải xóa hết và nhập lại duy nhất: <p><?php echo $username; ?></p></h2>
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

<script>
jQuery(function ($) {
    let selectedAmount = 0;
    const confirmBtn = $('#confirmDepositBtn');
    const modalEl = document.getElementById('depositModal');
    const qrImagePreview = $('#qrImagePreview');

    modalEl.addEventListener('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        selectedAmount = button.data('amount');

        const qrImg = $('#qr-images img[data-img="' + selectedAmount + '"]');
        if (qrImg.length) {
            qrImagePreview.attr('src', qrImg.attr('src'));
        } else {
            qrImagePreview.attr('src', '');
        }
    });

    confirmBtn.on('click', function () {
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        modalInstance.hide();
        location.reload();
    });
});
</script>

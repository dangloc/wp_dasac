<?php
/**
 * The template for displaying author profile
 */

get_header();

$author = get_user_by('slug', get_query_var('author_name'));
$author_id = $author->ID;

// Get author stats
$published_posts = count_user_posts($author_id, 'truyen_chu', true);
$user_balance = get_user_meta($author_id, '_user_balance', true);
$vip_name = get_user_meta($author_id, '_user_vip_name', true);
$is_vip = check_user_vip_status($author_id);

$kychuvodanh = get_template_directory_uri() . '/assets/images/tiennu.png';
$tanlinhkichu = get_template_directory_uri() . '/assets/images/tiennu.png';
$kichuthuctinh = get_template_directory_uri() . '/assets/images/tiennu.png';
$kichuphonglinhhoa = get_template_directory_uri() . '/assets/images/ngocnu.png';
$thonglinhphonglinhtran = get_template_directory_uri() . '/assets/images/tienco.png';
$kichutoithuong = get_template_directory_uri() . '/assets/images/huyennu.png';
$linhvuong = get_template_directory_uri() . '/assets/images/thannu.png';
$linhvuongmongcanh = get_template_directory_uri() . '/assets/images/thienton.png';

$type_vip_name = ['Ký Chủ Vô Danh', 'Tân Linh Ký Chủ', 'Ký Chủ Thức Tỉnh', 'Ký Chủ Phong Linh Hóa', 'Thống Lĩnh Phong Linh Trấn', 'Ký Chủ Tối Thượng', 'Linh Vương', 'Linh Vương Mộng Cảnh'];
// Get author's stories
$args = array(
    'post_type' => 'truyen_chu',
    'author' => $author_id,
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC'
);
$author_stories = new WP_Query($args);
?>

<div class="container py-5 author-page">
    <div class="row">
        <!-- Author Profile Card -->
        <div class="col-md-12 mb-4">
            <div class="card-author" style="background-image: url('<?php echo get_avatar_url($author_id, ['size' => '300']); ?>');">
                <div class="card-author-overlay"></div>
                <div class="card-body text-center">
                    <div class="avatar-author">
                        <div class="avatar-author-bg
                            <?php
                                switch ($vip_name) {
                                    case 'Ký Chủ Vô Danh':
                                        echo 'ky-chu-vo-danh';
                                        break;
                                    case 'Tân Linh Ký Chủ':
                                        echo 'tan-linh-ky-chu';
                                        break;
                                    case 'Ký Chủ Thức Tỉnh':
                                        echo 'ky-chu-thuc-tinh';
                                        break;
                                    case 'Ký Chủ Phong Linh Hóa':
                                        echo 'ky-chu-phong-linh-hoa';
                                        break;
                                    case 'Thống Lĩnh Phong Linh Trấn':
                                        echo 'thong-linh-phong-linh-tran';
                                        break;
                                    case 'Ký Chủ Tối Thượng':
                                        echo 'ky-chu-toi-thuong';
                                        break;
                                    case 'Linh Vương':
                                        echo 'linh-vuong';
                                        break;
                                    case 'Linh Vương Mộng Cảnh':
                                        echo 'linh-vuong-mong-canh';
                                        break;
                                    default:
                                        // Nếu không khớp với loại VIP nào
                                        echo '';
                                        break;
                                }
                            ?>
                        ">
                            <?php 
                                switch ($vip_name) {
                                    case 'Ký Chủ Vô Danh':
                                        echo '<img src="' . $kychuvodanh . '" alt="Ký Chủ Vô Danh">';
                                        break;
                                    case 'Tân Linh Ký Chủ':
                                        echo '<img src="' . $tanlinhkichu . '" alt="Tân Linh Ký Chủ">';
                                        break;
                                    case 'Ký Chủ Thức Tỉnh':
                                        echo '<img src="' . $kichuthuctinh . '" alt="Ký Chủ Thức Tỉnh">';
                                        break;
                                    case 'Ký Chủ Phong Linh Hóa':
                                        echo '<img src="' . $kichuphonglinhhoa . '" alt="Ký Chủ Phong Linh Hóa">';
                                        break;
                                    case 'Thống Lĩnh Phong Linh Trấn':
                                        echo '<img src="' . $thonglinhphonglinhtran . '" alt="Thống Lĩnh Phong Linh Trấn">';
                                        break;
                                    case 'Ký Chủ Tối Thượng':
                                        echo '<img src="' . $kichutoithuong . '" alt="Ký Chủ Tối Thượng">';
                                        break;
                                    case 'Linh Vương':
                                        echo '<img src="' . $linhvuong . '" alt="Linh Vương">';
                                        break;
                                    case 'Linh Vương Mộng Cảnh':
                                        echo '<img src="' . $linhvuongmongcanh . '" alt="Linh Vương Mộng Cảnh">';
                                        break;
                                    default:
                                        // Nếu không khớp với loại VIP nào
                                        echo '';
                                        break;
                                }
                            ?>
                        </div>
                        <?php echo get_avatar($author_id, 120, '', '', array('class' => 'rounded-circle mb-3')); ?>
                    </div>
                    <?php if (is_user_logged_in() && get_current_user_id() === $author_id): ?>
                        <div class="avatar-actions mb-3">
                            <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#updateAvatarModal">
                                <i class="fas fa-camera"></i> Cập nhật ảnh
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="deleteAvatarBtn">
                                <i class="fas fa-trash"></i> Xóa ảnh
                            </button>
                        </div>
                    <?php endif; ?>
                    <h3 class="card-title mb-2"><?php echo esc_html($author->display_name); ?></h3>
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                        <?php if ($vip_name): ?>
                            <button class="btn-cus-vip <?php
                                 switch ($vip_name) {
                                    case 'Ký Chủ Vô Danh':
                                        echo 'btn-cus-vip--ky-chu-vo-danh';
                                        break;
                                    case 'Tân Linh Ký Chủ':
                                        echo 'btn-cus-vip--tan-linh-ky-chu';
                                        break;
                                    case 'Ký Chủ Thức Tỉnh':
                                        echo 'btn-cus-vip--ky-chu-thuc-tinh';
                                        break;
                                    case 'Ký Chủ Phong Linh Hóa':
                                        echo 'btn-cus-vip--ky-chu-phong-linh-hoa';
                                        break;
                                    case 'Thống Lĩnh Phong Linh Trấn':
                                        echo 'btn-cus-vip--thong-linh-phong-linh-tran';
                                        break;
                                    case 'Ký Chủ Tối Thượng':
                                        echo 'btn-cus-vip--ky-chu-toi-thuong';
                                        break;
                                    case 'Linh Vương':
                                        echo 'btn-cus-vip--linh-vuong';
                                        break;
                                    case 'Linh Vương Mộng Cảnh':
                                        echo 'btn-cus-vip--linh-vuong-mong-canh';
                                        break;
                                    default:
                                        echo '';
                                        break;
                                }
                                ?>">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <?php echo esc_html($vip_name); ?>
                            </button>
                        <?php endif; ?>
                        <?php if ($is_vip): ?>
                            <div class="vip-badge" title="Tài khoản VIP">
                                <i class="fas fa-crown text-warning"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <div class="text-center">
                            <h5 class="mb-0"><?php echo number_format($published_posts); ?></h5>
                            <small class="text-muted">Truyện</small>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0"><?php echo number_format((float)$user_balance); ?></h5>
                            <small class="text-muted">Kim tệ</small>
                        </div>
                        <?php if (is_user_logged_in() && get_current_user_id() === $author_id && (float)$user_balance >= 5000): ?>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-warning" id="withdrawBtn">
                                        <i class="fas fa-money-bill-wave"></i> Rút tiền
                                    </button>
                                </div>
                            <?php endif; ?>
                    </div>
                    <?php if ($author->description): ?>
                        <p class="card-text"><?php echo nl2br(esc_html($author->description)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Author's Stories -->
        <div class="col-md-12">
            <div class="">
                <div class="card-body">
                    <?php if ($author_stories->have_posts()): ?>
                        <h4 class="card-title mb-4">Truyện của <?php echo esc_html($author->display_name); ?></h4>
                        <div class="row">
                            <?php while ($author_stories->have_posts()): $author_stories->the_post(); 
                                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                if (!$thumbnail) {
                                    $thumbnail = get_template_directory_uri() . '/assets/images/no-image.jpg';
                                }
                            ?>
                                 <?php get_template_part( 'template-parts/home/item-card' ); ?>
                            <?php endwhile; ?>
                        </div>

                        <?php if ($author_stories->max_num_pages > 1): ?>
                            <div class="mt-4">
                                <?php
                                echo paginate_links(array(
                                    'total' => $author_stories->max_num_pages,
                                    'current' => max(1, get_query_var('paged')),
                                    'prev_text' => '&laquo; Trước',
                                    'next_text' => 'Sau &raquo;',
                                    'type' => 'list',
                                    'class' => 'pagination justify-content-center'
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php else: 
                        // Hiển thị danh sách favorites cho người dùng thường
                        if (function_exists('get_user_favorites')): ?>
                            <h4 class="card-title mb-4">Truyện yêu thích của bạn</h4>
                            <?php 
                            // Sử dụng shortcode của plugin Favorites
                            echo do_shortcode('[user_favorites include_thumbnails="true" thumbnail_size="medium" post_types="truyen_chu" posts_per_page="12"]');
                            ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (is_user_logged_in() && get_current_user_id() === $author_id): ?>
<!-- Avatar Update Modal -->
<div class="modal fade" id="updateAvatarModal" tabindex="-1" aria-labelledby="updateAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="updateAvatarModalLabel">Cập nhật ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="avatarUpdateForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="avatarFile" class="form-label">Chọn ảnh mới</label>
                        <input type="file" class="form-control" id="avatarFile" name="avatar" accept="image/*" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle avatar update
    $('#avatarUpdateForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'update_user_avatar');
        formData.append('security', '<?php echo wp_create_nonce("update_avatar_nonce"); ?>');

        // Show loading state
        Swal.fire({
            title: 'Đang cập nhật...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Ảnh đại diện đã được cập nhật',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: response.data || 'Có lỗi xảy ra khi cập nhật ảnh đại diện'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi cập nhật ảnh đại diện'
                });
            }
        });
    });

    // Handle avatar deletion
    $('#deleteAvatarBtn').on('click', function() {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa ảnh đại diện?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Đang xóa...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'delete_user_avatar',
                        security: '<?php echo wp_create_nonce("delete_avatar_nonce"); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Ảnh đại diện đã được xóa',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.data || 'Có lỗi xảy ra khi xóa ảnh đại diện'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi xóa ảnh đại diện'
                        });
                    }
                });
            }
        });
    });

    // Handle withdrawal request
    $('#withdrawBtn').on('click', function() {
        const balance = <?php echo (float)$user_balance; ?>;
        const fee = 0.05; // 5% fee
        const minWithdraw = 5000;
        
        Swal.fire({
            title: 'Rút tiền',
            html: `
                <div class="text-start">
                    <p>Số dư hiện tại: <strong>${balance.toLocaleString()} Kim tệ</strong></p>
                    <div class="mb-2">
                        <label for="withdrawAmountInput">Nhập số tiền muốn rút (tối thiểu 5.000):</label>
                        <input type="number" id="withdrawAmountInput" class="swal2-input" min="${minWithdraw}" max="${balance}" value="${balance}" style="width: 70%;" />
                    </div>
                    <div class="mb-2">
                        <label for="bankAccountInput">Số tài khoản ngân hàng:</label>
                        <input type="text" id="bankAccountInput" class="swal2-input" placeholder="Nhập số tài khoản" style="width: 70%;" />
                    </div>
                    <div class="mb-2">
                        <label for="bankNameInput">Tên ngân hàng:</label>
                        <input type="text" id="bankNameInput" class="swal2-input" placeholder="Nhập tên ngân hàng" style="width: 70%;" />
                    </div>
                    <div id="withdrawFeeInfo">
                        <p>Phí rút tiền (5%): <strong>${(balance * fee).toLocaleString()} Kim tệ</strong></p>
                        <p>Số tiền nhận được: <strong>${(balance * (1-fee)).toLocaleString()} Kim tệ</strong></p>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xác nhận rút tiền',
            cancelButtonText: 'Hủy',
            didOpen: () => {
                const input = document.getElementById('withdrawAmountInput');
                const feeInfo = document.getElementById('withdrawFeeInfo');
                input.addEventListener('input', function() {
                    let val = parseFloat(input.value) || 0;
                    if (val > balance) val = balance;
                    if (val < minWithdraw) val = minWithdraw;
                    const feeAmount = val * fee;
                    const receiveAmount = val - feeAmount;
                    feeInfo.innerHTML = `
                        <p>Phí rút tiền (5%): <strong>${feeAmount.toLocaleString()} Kim tệ</strong></p>
                        <p>Số tiền nhận được: <strong>${receiveAmount.toLocaleString()} Kim tệ</strong></p>
                    `;
                });
            },
            preConfirm: () => {
                const amountInput = document.getElementById('withdrawAmountInput');
                const bankAccountInput = document.getElementById('bankAccountInput');
                const bankNameInput = document.getElementById('bankNameInput');
                let val = parseFloat(amountInput.value) || 0;
                if (val > balance) val = balance;
                if (val < minWithdraw) {
                    Swal.showValidationMessage('Số tiền rút tối thiểu là 5.000 Kim tệ');
                    return false;
                }
                if (!bankAccountInput.value.trim()) {
                    Swal.showValidationMessage('Vui lòng nhập số tài khoản ngân hàng');
                    return false;
                }
                if (!bankNameInput.value.trim()) {
                    Swal.showValidationMessage('Vui lòng nhập tên ngân hàng');
                    return false;
                }
                return {
                    amount: val,
                    bank_account: bankAccountInput.value.trim(),
                    bank_name: bankNameInput.value.trim()
                };
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const { amount, bank_account, bank_name } = result.value;
                // Show loading state
                Swal.fire({
                    title: 'Đang xử lý...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'request_withdrawal',
                        security: '<?php echo wp_create_nonce("withdrawal_nonce"); ?>',
                        amount: amount,
                        bank_account: bank_account,
                        bank_name: bank_name
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Yêu cầu rút tiền của bạn đã được gửi đến admin',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.data || 'Có lỗi xảy ra khi gửi yêu cầu rút tiền'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi gửi yêu cầu rút tiền'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?php endif; ?>

<?php get_footer(); ?> 
<?php
/**
 * Template Name: Đăng ký làm tác giả
 * 
 * @package commicpro
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

// Check if user already has an application
$user_id = get_current_user_id();
$existing_application = get_user_meta($user_id, '_author_application', true);
$has_application = !empty($existing_application);
$application_status = $has_application ? (isset($existing_application['status']) ? $existing_application['status'] : 'pending') : '';

get_header();
?>

<main id="primary" class="site-main">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="author-registration-form">
                    <h1 class="page-title mb-4">Đăng ký làm tác giả</h1>
                    
                    <?php if ($has_application) : ?>
                        <!-- Show existing application status -->
                        <?php if ($application_status === 'pending') : ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-clock"></i>
                                <strong>Đơn đăng ký đang được xem xét</strong>
                                <p class="mb-0 mt-2">Bạn đã gửi đơn đăng ký vào ngày <?php echo date('d/m/Y H:i', strtotime($existing_application['submitted_at'])); ?>. Chúng tôi sẽ xem xét và phản hồi trong vòng 24-48 giờ.</p>
                            </div>
                        <?php elseif ($application_status === 'approved') : ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i>
                                <strong>Chúc mừng! Bạn đã là tác giả</strong>
                                <p class="mb-0 mt-2">Đơn đăng ký của bạn đã được duyệt. Bạn có thể bắt đầu đăng truyện ngay bây giờ!</p>
                                <a href="<?php echo admin_url('post-new.php?post_type=truyen_chu'); ?>" class="btn btn-success mt-3">
                                    <i class="fas fa-plus"></i> Đăng truyện mới
                                </a>
                            </div>
                        <?php elseif ($application_status === 'rejected') : ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-times-circle"></i>
                                <strong>Đơn đăng ký chưa được chấp nhận</strong>
                                <p class="mb-0 mt-2">Rất tiếc, đơn đăng ký của bạn chưa được chấp nhận. Vui lòng liên hệ admin qua email <?php echo get_option('admin_email'); ?> để biết thêm chi tiết.</p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Show application details -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Thông tin đơn đăng ký</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="200">Link Facebook:</th>
                                        <td><a href="<?php echo esc_url($existing_application['facebook_link']); ?>" target="_blank"><?php echo esc_html($existing_application['facebook_link']); ?></a></td>
                                    </tr>
                                    <?php if (!empty($existing_application['telegram_link'])) : ?>
                                    <tr>
                                        <th>Link Telegram:</th>
                                        <td><a href="<?php echo esc_url($existing_application['telegram_link']); ?>" target="_blank"><?php echo esc_html($existing_application['telegram_link']); ?></a></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($existing_application['other_platform'])) : ?>
                                    <tr>
                                        <th>Nền tảng khác:</th>
                                        <td><?php echo esc_html($existing_application['other_platform']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($existing_application['other_platform_link'])) : ?>
                                    <tr>
                                        <th>Link nền tảng khác:</th>
                                        <td><a href="<?php echo esc_url($existing_application['other_platform_link']); ?>" target="_blank"><?php echo esc_html($existing_application['other_platform_link']); ?></a></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($existing_application['bio'])) : ?>
                                    <tr>
                                        <th>Giới thiệu:</th>
                                        <td><?php echo nl2br(esc_html($existing_application['bio'])); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        
                    <?php else : ?>
                        <!-- Show registration form -->
                        <p class="subtitle">Hãy điền đầy đủ thông tin liên hệ để đăng ký trở thành tác giả trên Pink Novel</p>
                        
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i>
                            Để trở thành tác giả, bạn cần cung cấp thông tin liên hệ và giới thiệu bản thân. Đơn đăng ký của bạn sẽ được xem xét và phản hồi trong vòng 24-48 giờ.
                        </div>

                        <form id="author-registration-form" method="post" novalidate>
                            <?php wp_nonce_field('author_registration_nonce', 'author_registration_nonce'); ?>
                            
                            <!-- Link Facebook -->
                        <div class="mb-3">
                            <label for="facebook_link" class="form-label">
                                Link Facebook <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fab fa-facebook"></i>
                                </span>
                                <input 
                                    type="url" 
                                    class="form-control" 
                                    id="facebook_link" 
                                    name="facebook_link" 
                                    placeholder="https://facebook.com/profile"
                                    required
                                >
                                <div class="invalid-feedback">
                                    Vui lòng nhập link Facebook hợp lệ.
                                </div>
                            </div>
                            <small class="form-text text-muted">Link Facebook cá nhân của bạn để liên hệ</small>
                        </div>

                        <!-- Link Telegram -->
                        <div class="mb-3">
                            <label for="telegram_link" class="form-label">
                                Link Telegram (không bắt buộc)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fab fa-telegram"></i>
                                </span>
                                <input 
                                    type="url" 
                                    class="form-control" 
                                    id="telegram_link" 
                                    name="telegram_link" 
                                    placeholder="https://t.me/username"
                                >
                            </div>
                            <small class="form-text text-muted">Link Telegram của bạn (nếu có)</small>
                        </div>

                        <!-- Nền tảng khác -->
                        <div class="mb-3">
                            <label for="other_platform" class="form-label">
                                Nền tảng khác (không bắt buộc)
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="other_platform" 
                                name="other_platform" 
                                placeholder="Facebook, Wattpad, Truyenfull, v.v."
                            >
                            <small class="form-text text-muted">Nền tảng khác mà bạn đã từng đăng truyện</small>
                        </div>

                        <!-- Link nền tảng khác -->
                        <div class="mb-3">
                            <label for="other_platform_link" class="form-label">
                                Link nền tảng khác (không bắt buộc)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input 
                                    type="url" 
                                    class="form-control" 
                                    id="other_platform_link" 
                                    name="other_platform_link" 
                                    placeholder="https://example.com/profile"
                                >
                            </div>
                            <small class="form-text text-muted">Link tới trang cá nhân của bạn trên nền tảng khác</small>
                        </div>

                        <!-- Giới thiệu bản thân -->
                        <div class="mb-3">
                            <label for="bio" class="form-label">
                                Giới thiệu bản thân (không bắt buộc)
                            </label>
                            <textarea 
                                class="form-control" 
                                id="bio" 
                                name="bio" 
                                rows="5" 
                                maxlength="1000"
                                placeholder="Giới thiệu về bạn, kinh nghiệm viết truyện, thể loại sở trường, v.v. (tối đa 50 ký tự)"
                            ></textarea>
                            <div class="d-flex justify-content-between">
                                <small class="form-text text-muted">Giới thiệu về bản thân, kinh nghiệm viết truyện, thể loại sở trường, v.v. (tối đa 50 ký tự)</small>
                                <small class="char-count text-muted">0/1000</small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                <span class="btn-text">Gửi đơn đăng ký</span>
                                <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.author-registration-form {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.author-registration-form .page-title {
    color: #333;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.author-registration-form .subtitle {
    color: #666;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

.author-registration-form .alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 2rem;
}

.author-registration-form .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.author-registration-form .input-group {
    border: 1px solid #ced4da;
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.author-registration-form .input-group .input-group-text {
    background-color: #f8f9fa;
    border: none;
    color: #6c757d;
}

.author-registration-form .input-group .form-control {
    border: none;
    box-shadow: none !important;
}

.author-registration-form .input-group:focus-within {
    border-color: #e53a22;
    box-shadow: 0 0 0 3px rgba(229, 58, 34, 0.1);
}

.author-registration-form .input-group.is-invalid {
    border-color: #dc3545;
}

.author-registration-form .form-control {
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 0.75rem;
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.author-registration-form .form-control:focus {
    border-color: #e53a22;
    box-shadow: 0 0 0 3px rgba(229, 58, 34, 0.1);
}

.author-registration-form textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.author-registration-form .btn-primary {
    background-color: #e53a22;
    border-color: #e53a22;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.author-registration-form .btn-primary:hover {
    background-color: #be2b16;
    border-color: #be2b16;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 58, 34, 0.3);
}

.author-registration-form .btn-primary:disabled {
    background-color: #6c757d;
    border-color: #6c757d;
    cursor: not-allowed;
}

.author-registration-form .char-count {
    font-size: 0.875rem;
}

.author-registration-form .text-danger {
    color: #dc3545;
}

.author-registration-form .form-text {
    font-size: 0.875rem;
    color: #6c757d;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Character counter for bio
    $('#bio').on('input', function() {
        const length = $(this).val().length;
        $('.char-count').text(length + '/1000');
    });

    // Toggle button loading state
    function toggleButtonLoading(button, isLoading) {
        const btnText = button.find('.btn-text');
        const spinner = button.find('.spinner-border');
        if (isLoading) {
            button.prop('disabled', true);
            spinner.removeClass('d-none');
        } else {
            button.prop('disabled', false);
            spinner.addClass('d-none');
        }
    }

    // Form submission
    $('#author-registration-form').on('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const form = $(this);
        
        // Reset validation
        form.find('.is-invalid').removeClass('is-invalid');
        
        // Validate Facebook link (required)
        const facebookLink = $('#facebook_link').val().trim();
        if (facebookLink === '') {
            $('#facebook_link').closest('.input-group').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            return;
        }
        
        const submitButton = form.find('button[type="submit"]');
        toggleButtonLoading(submitButton, true);
        
        const formData = {
            action: 'submit_author_registration',
            nonce: $('#author_registration_nonce').val(),
            facebook_link: facebookLink,
            telegram_link: $('#telegram_link').val().trim(),
            other_platform: $('#other_platform').val().trim(),
            other_platform_link: $('#other_platform_link').val().trim(),
            bio: $('#bio').val().trim()
        };
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#e53a22'
                    }).then(() => {
                        // Reset form
                        form[0].reset();
                        $('.char-count').text('0/1000');
                    });
                } else {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: response.data.message || 'Đã có lỗi xảy ra, vui lòng thử lại.',
                        icon: 'error',
                        confirmButtonText: 'Thử lại',
                        confirmButtonColor: '#e53a22'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#e53a22'
                });
            },
            complete: function() {
                toggleButtonLoading(submitButton, false);
            }
        });
    });
});
</script>

<?php
get_footer();

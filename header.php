<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package commicpro
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-site-verification" content="la4PZI4ISH6kgrzxE1SMgiZ9y8QzHLfFtlBi2gobYug" />

	<?php wp_head(); ?>

	 <!-- <script>
		document.addEventListener('DOMContentLoaded', function() {
			// Prevent text selection
			document.addEventListener('selectstart', function(e) {
				e.preventDefault();
			});
			
			// Prevent copy
			document.addEventListener('copy', function(e) {
				e.preventDefault();
				// Add watermark to clipboard if copy is attempted
				const watermark = '\n\n--- Bản quyền © ' + new Date().getFullYear() + ' ' + document.title + ' ---\n';
				e.clipboardData.setData('text/plain', watermark);
			});
			
			// Prevent cut
			document.addEventListener('cut', function(e) {
				e.preventDefault();
			});
			
			// Prevent right-click
			document.addEventListener('contextmenu', function(e) {
				e.preventDefault();
			});

			// Prevent drag
			document.addEventListener('dragstart', function(e) {
				e.preventDefault();
			});

			// Prevent keyboard shortcuts
			document.addEventListener('keydown', function(e) {
				// Prevent Ctrl+C, Ctrl+X, Ctrl+A, Ctrl+U (view source)
				if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'x' || e.key === 'a' || e.key === 'u')) {
					e.preventDefault();
				}
				// Prevent F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
				if (e.key === 'F12' || 
					(e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C'))) {
					e.preventDefault();
				}
			});

			// Add CSS to prevent text selection and add watermark
			const style = document.createElement('style');
			style.textContent = `
				* {
					-webkit-user-select: none !important;
					-moz-user-select: none !important;
					-ms-user-select: none !important;
					user-select: none !important;
				}
				.entry-content {
					-webkit-user-select: text !important;
					-moz-user-select: text !important;
					-ms-user-select: text !important;
					user-select: text !important;
					position: relative;
				}
				.entry-content::before {
					content: '';
					position: fixed;
					top: 0;
					left: 0;
					width: 100%;
					height: 100%;
					pointer-events: none;
					background: linear-gradient(45deg, 
						rgba(255,255,255,0) 0%,
						rgba(255,255,255,0.1) 25%,
						rgba(255,255,255,0) 50%,
						rgba(255,255,255,0.1) 75%,
						rgba(255,255,255,0) 100%);
					z-index: 9999;
				}
				.entry-content::after {
					content: '${document.title}';
					position: fixed;
					top: 50%;
					left: 50%;
					transform: translate(-50%, -50%) rotate(-45deg);
					font-size: 24px;
					color: rgba(0,0,0,0.1);
					pointer-events: none;
					white-space: nowrap;
					z-index: 9999;
				}
			`;
			document.head.appendChild(style);

			// Disable DevTools
			(function() {
				function detectDevTools() {
					const widthThreshold = window.outerWidth - window.innerWidth > 160;
					const heightThreshold = window.outerHeight - window.innerHeight > 160;
					if (widthThreshold || heightThreshold) {
						document.body.innerHTML = 'DevTools không được phép sử dụng trên trang này.';
					}
				}
				window.addEventListener('resize', detectDevTools);
				setInterval(detectDevTools, 1000);
			})();

			// Disable console
			(function() {
				const methods = ['log', 'debug', 'info', 'warn', 'error', 'assert', 'dir', 'dirxml', 'group', 'groupEnd', 'time', 'timeEnd', 'count', 'trace', 'profile', 'profileEnd'];
				methods.forEach(method => {
					console[method] = function() {};
				});
			})();
		});
	</script> -->
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'commicpro' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-branding container-header">
			<div class="site-branding-left">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php $logo_url = get_field('logo_url', 2); ?>
					<img src="<?php echo $logo_url['url']; ?>" alt="logo">
				</a>
				<nav id="site-navigation" class="main-navigation">
					<!-- <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php //esc_html_e( 'Primary Menu', 'commicpro' ); ?></button> -->
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
					?>
				</nav>
			</div>
			<div class="site-branding-mid w-100 d-lg-block d-none">
				<div class="search-form-container d-flex align-items-center w-100">
					<form role="search" method="get" class="d-flex" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" class="form-control search-input" placeholder="Tìm kiếm truyện..." value="<?php echo get_search_query(); ?>" name="s" aria-label="Search">
					</form>
				</div>
			</div>
			<div class="site-branding-right">
				<?php
				if ( is_user_logged_in() ) :
					$current_user = wp_get_current_user();
					?>
					<div class="d-lg-block d-none">
						<a class="btn btn-pay" href="<?php echo esc_url( home_url( '/' ) ); ?>index.php/nap-kim-te">Nạp tiền</a>
					</div>
					<div class="user-info dropdown">
						<!-- Trigger dropdown on hover -->
						<div class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
								<?php echo get_avatar( $current_user->ID, 32 ); ?>
						</div>

						<!-- Dropdown menu -->
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="<?php echo esc_url( get_author_posts_url( $current_user->ID ) ); ?>">Thông tin tài khoản</a></li>
							<li><hr class="dropdown-divider"></li>
							<?php
							wp_nav_menu( array(
								'theme_location' => 'header-menu',
								'container'      => false,
								'items_wrap'     => '%3$s', // chỉ in <li>...</li>
								'walker'         => new Bootstrap_Dropdown_Walker()
							) );
							?>
							<li><hr class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Đăng xuất</a></li>
					</ul>
					</div>
				<?php else : ?>
					<div class="auth-buttons d-none d-md-flex">
						<button class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</button>
						<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</button>
					</div>
					<div class="auth-dropdown d-md-none">
						<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="fas fa-user"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</button></li>
							<li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</button></li>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<!-- Login Modal -->
	<div class="modal fade" id="loginModal" aria-hidden="true" aria-labelledby="loginModalLabel" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="loginModalLabel">Đăng nhập</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="login-form" method="post" novalidate>
						<div class="mb-3">
							<label for="login-username" class="form-label">Tên đăng nhập</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
								<input type="text" class="form-control" id="login-username" name="username" required>
								<div class="invalid-feedback">Vui lòng nhập tên đăng nhập.</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="login-password" class="form-label">Mật khẩu</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
								<input type="password" class="form-control" id="login-password" name="password" required>
								<span class="input-group-text password-toggle" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
								<div class="invalid-feedback">Vui lòng nhập mật khẩu.</div>
							</div>
						</div>
						<div class="mb-3 form-check">
							<input type="checkbox" class="form-check-input" id="remember-me" name="remember">
							<label class="form-check-label" for="remember-me">Ghi nhớ đăng nhập</label>
						</div>
						<div class="d-grid">
							<button type="submit" class="btn btn-primary">
								<span class="btn-text">Đăng nhập</span>
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
							</button>
						</div>
					</form>
					<div class="d-flex align-items-center my-3">
						<hr class="flex-grow-1">
						<span class="mx-2 text-muted">Hoặc</span>
						<hr class="flex-grow-1">
					</div>
					<div class="d-grid">
						<a class="btn btn-outline-secondary" href="https://dasactruyen.xyz/wp-login.php?loginSocial=google" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="google" data-popupwidth="600" data-popupheight="600">
							<img style="width: 20px; height: 20px; margin-right: 8px;" src="<?php bloginfo('template_url'); ?>/assets/images/google.svg" alt="Google">
							Đăng nhập bằng Google
						</a>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<p class="mb-0">Chưa có tài khoản? 
						<button class="btn btn-link p-0" data-bs-target="#registerModal" data-bs-toggle="modal" data-bs-dismiss="modal">
							Đăng ký ngay
						</button>
					</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Register Modal -->
	<div class="modal fade" id="registerModal" aria-hidden="true" aria-labelledby="registerModalLabel" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="registerModalLabel">Đăng ký tài khoản</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="register-form" method="post" novalidate>
						<div class="mb-3">
							<label for="register-username" class="form-label">Tên đăng nhập</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
								<input type="text" class="form-control" id="register-username" name="username" required>
								<div class="invalid-feedback">Tên đăng nhập không được để trống và chỉ chứa ký tự a-z, 0-9.</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="register-email" class="form-label">Email</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-envelope"></i></span>
								<input type="email" class="form-control" id="register-email" name="email" required>
								<div class="invalid-feedback">Vui lòng nhập một địa chỉ email hợp lệ.</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="register-password" class="form-label">Mật khẩu</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-lock"></i></span>
								<input type="password" class="form-control" id="register-password" name="password" required minlength="6">
								<span class="input-group-text password-toggle" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
								<div class="invalid-feedback">Mật khẩu phải có ít nhất 6 ký tự.</div>
							</div>
						</div>
						<div class="mb-3">
							<label for="register-password-confirm" class="form-label">Xác nhận mật khẩu</label>
							<div class="input-group">
								<span class="input-group-text"><i class="fas fa-check-circle"></i></span>
								<input type="password" class="form-control" id="register-password-confirm" name="password_confirm" required>
								<span class="input-group-text password-toggle" style="cursor: pointer;"><i class="fas fa-eye"></i></span>
								<div class="invalid-feedback">Mật khẩu xác nhận không khớp.</div>
							</div>
						</div>
						<div class="d-grid">
							<button type="submit" class="btn btn-primary">
								<span class="btn-text">Đăng ký</span>
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
							</button>
						</div>
					</form>
					<div class="d-flex align-items-center my-3">
						<hr class="flex-grow-1">
						<span class="mx-2 text-muted">Hoặc</span>
						<hr class="flex-grow-1">
					</div>
					<div class="d-grid">
						<a class="btn btn-outline-secondary" href="https://dasactruyen.xyz/wp-login.php?loginSocial=google" data-plugin="nsl" data-action="connect" data-redirect="current" data-provider="google" data-popupwidth="600" data-popupheight="600">
							<img style="width: 20px; height: 20px; margin-right: 8px;" src="<?php bloginfo('template_url'); ?>/assets/images/google.svg" alt="Google">
							Đăng nhập bằng Google
						</a>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<p class="mb-0">Đã có tài khoản? 
						<button class="btn btn-link p-0" data-bs-target="#loginModal" data-bs-toggle="modal" data-bs-dismiss="modal">
							Đăng nhập
						</button>
					</p>
				</div>
			</div>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		// --- CÁC HÀM TIỆN ÍCH ---

		// Chuyển đổi ký tự có dấu thành không dấu và lọc ký tự đặc biệt
		function removeAccents(str) {
			return str.normalize('NFD')
				.replace(/[\u0300-\u036f]/g, '')
				.replace(/đ/g, 'd').replace(/Đ/g, 'D')
				.replace(/[^a-zA-Z0-9]/g, ''); // Chỉ cho phép chữ và số
		}
		
		// Kiểm tra định dạng email
		function validateEmail(email) {
			const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(String(email).toLowerCase());
		}

		// Hàm hiển thị/ẩn trạng thái loading trên nút bấm
		function toggleButtonLoading(button, isLoading) {
			const btnText = button.find('.btn-text');
			const spinner = button.find('.spinner-border');
			if (isLoading) {
				button.prop('disabled', true);
				btnText.addClass('d-none');
				spinner.removeClass('d-none');
			} else {
				button.prop('disabled', false);
				btnText.removeClass('d-none');
				spinner.addClass('d-none');
			}
		}

		// --- XỬ LÝ FORM ĐĂNG KÝ ---
		const registerForm = $('#register-form');
		const registerUsername = $('#register-username');
		const registerEmail = $('#register-email');
		const registerPassword = $('#register-password');
		const registerPasswordConfirm = $('#register-password-confirm');

		// Tự động chuyển tên đăng nhập thành không dấu
		registerUsername.on('input', function() {
			var input = $(this);
			var originalValue = input.val();
			var convertedValue = removeAccents(originalValue);
			if(originalValue !== convertedValue) {
			input.val(convertedValue);
			}
		});

		registerForm.on('submit', function(e) {
			e.preventDefault();
			let isValid = true;

			// Reset all errors
			registerForm.find('.is-invalid').removeClass('is-invalid');

			// Validate username
			if (registerUsername.val().trim() === '') {
				registerUsername.addClass('is-invalid').next('.invalid-feedback').text('Tên đăng nhập không được để trống.');
				isValid = false;
			}

			// Validate email
			if (!validateEmail(registerEmail.val())) {
				registerEmail.addClass('is-invalid').next('.invalid-feedback').text('Vui lòng nhập một địa chỉ email hợp lệ.');
				isValid = false;
			}

			// Validate password
			if (registerPassword.val().length < 6) {
				registerPassword.addClass('is-invalid').next('.invalid-feedback').text('Mật khẩu phải có ít nhất 6 ký tự.');
				isValid = false;
			}

			// Validate password confirm
			if (registerPassword.val() !== registerPasswordConfirm.val() || registerPasswordConfirm.val() === '') {
				registerPasswordConfirm.addClass('is-invalid').next('.invalid-feedback').text('Mật khẩu xác nhận không khớp.');
				isValid = false;
			}

			if (!isValid) return; // Dừng lại nếu có lỗi

			// --- Nếu form hợp lệ, tiến hành gửi AJAX ---
			const submitButton = $(this).find('button[type="submit"]');
			toggleButtonLoading(submitButton, true);

			var formData = {
				action: 'ajax_register',
				username: registerUsername.val(),
				email: registerEmail.val(),
				password: registerPassword.val(),
				nonce: '<?php echo wp_create_nonce('ajax_register_nonce'); ?>'
			};

			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				data: formData,
				success: function(response) {
					if (response.success) {
						Swal.fire({
							title: 'Thành công!',
							text: 'Đăng ký thành công. Vui lòng kiểm tra email để xác nhận tài khoản.',
							icon: 'success',
							confirmButtonText: 'OK'
						}).then(() => {
							$('#registerModal').modal('hide');
							$('#loginModal').modal('show');
						});
					} else {
						Swal.fire({
							title: 'Lỗi!',
							text: response.data,
							icon: 'error',
							confirmButtonText: 'Thử lại'
						});
					}
				},
				error: function() {
					Swal.fire({
						title: 'Lỗi!',
						text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
						icon: 'error',
						confirmButtonText: 'OK'
					});
				},
				complete: function() {
					toggleButtonLoading(submitButton, false);
				}
			});
		});

		// --- XỬ LÝ FORM ĐĂNG NHẬP ---
		const loginForm = $('#login-form');
		const loginUsername = $('#login-username');
		const loginPassword = $('#login-password');

		loginForm.on('submit', function(e) {
			e.preventDefault();
			let isValid = true;

			loginForm.find('.is-invalid').removeClass('is-invalid');

			if(loginUsername.val().trim() === '') {
				loginUsername.addClass('is-invalid');
				isValid = false;
			}
			if(loginPassword.val().trim() === '') {
				loginPassword.addClass('is-invalid');
				isValid = false;
			}

			if (!isValid) return;

			const submitButton = $(this).find('button[type="submit"]');
			toggleButtonLoading(submitButton, true);

			var formData = {
				action: 'ajax_login',
				username: loginUsername.val(),
				password: loginPassword.val(),
				remember: $('#remember-me').is(':checked'),
				nonce: '<?php echo wp_create_nonce('ajax_login_nonce'); ?>'
			};

			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				type: 'POST',
				data: formData,
				success: function(response) {
					if (response.success) {
						Swal.fire({
							title: 'Thành công!',
							text: 'Đăng nhập thành công',
							icon: 'success',
							timer: 1500,
							showConfirmButton: false
						}).then(() => {
							window.location.reload();
						});
					} else {
						Swal.fire({
							title: 'Lỗi!',
							text: response.data,
							icon: 'error',
							confirmButtonText: 'OK'
						});
					}
				},
				error: function() {
					Swal.fire({
						title: 'Lỗi!',
						text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
						icon: 'error',
						confirmButtonText: 'OK'
					});
				},
				complete: function() {
					toggleButtonLoading(submitButton, false);
				}
			});
		});

		// --- XỬ LÝ CHUNG CHO CÁC MODAL ---

		// Chức năng ẩn/hiện mật khẩu
		$('.password-toggle').on('click', function() {
			const passwordInput = $(this).prev('input[type="password"], input[type="text"]');
			const icon = $(this).find('i');
			if (passwordInput.attr('type') === 'password') {
				passwordInput.attr('type', 'text');
				icon.removeClass('fa-eye').addClass('fa-eye-slash');
			} else {
				passwordInput.attr('type', 'password');
				icon.removeClass('fa-eye-slash').addClass('fa-eye');
			}
		});

		// Reset form khi modal bị đóng
		$('.modal').on('hidden.bs.modal', function () {
			const form = $(this).find('form');
			if (form.length) {
				form[0].reset();
				form.find('.is-invalid').removeClass('is-invalid');
				toggleButtonLoading(form.find('button[type="submit"]'), false);
			}
		});
	});
	</script>

<?php wp_footer(); ?>
</body>
</html>

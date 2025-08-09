<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package commicpro
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h3 class="comments-title mb-4">
			<?php
			$comments_number = get_comments_number();
			if ($comments_number === 1) {
				printf(
					esc_html__('1 bình luận', 'commicpro')
				);
			} else {
				printf(
					esc_html(_n('%s bình luận', '%s bình luận', $comments_number, 'commicpro')),
					number_format_i18n($comments_number)
				);
			}
			?>
		</h3>

		<ol class="comment-list list-unstyled">
			<?php
			wp_list_comments(array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 60,
				'callback'    => 'commicpro_comment_callback'
			));
			?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<nav class="comment-navigation" role="navigation">
				<div class="nav-links">
					<div class="nav-previous"><?php previous_comments_link(esc_html__('Bình luận cũ hơn', 'commicpro')); ?></div>
					<div class="nav-next"><?php next_comments_link(esc_html__('Bình luận mới hơn', 'commicpro')); ?></div>
				</div>
			</nav>
		<?php endif; ?>

		<?php if (!comments_open()) : ?>
			<p class="no-comments"><?php esc_html_e('Bình luận đã bị đóng.', 'commicpro'); ?></p>
		<?php endif; ?>

		<?php
	endif; // Check for have_comments().

	comment_form(array(
		'title_reply'          => 'Để lại bình luận',
		'title_reply_to'       => 'Trả lời %s',
		'cancel_reply_link'    => 'Hủy trả lời',
		'label_submit'         => 'Gửi bình luận',
		'comment_field'        => '<div class="form-group mb-3">
									<label for="comment" class="form-label">Bình luận</label>
									<textarea id="comment" name="comment" class="form-control" rows="5" required></textarea>
								  </div>',
		'comment_notes_before' => '<p class="comment-notes mb-3">Email của bạn sẽ không được hiển thị công khai.</p>',
		'class_submit'         => 'btn btn-primary',
		'class_form'           => 'comment-form',
	));
	?>

</div><!-- #comments -->

<?php
// Custom comment callback function
function commicpro_comment_callback($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	$comment_author_id = $comment->user_id;
	$vip_name = get_user_meta($comment_author_id, '_user_vip_name', true);
	$is_vip = check_user_vip_status($comment_author_id);
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment-item mb-4'); ?>>
		<div class="comment-body">
			<div class="comment-meta d-flex align-items-center mb-2">
				<div class="comment-author vcard">
					<?php echo get_avatar($comment, 60, '', '', array('class' => 'rounded-circle me-3')); ?>
				</div>
				<div class="comment-metadata">
					<div class="comment-author-name d-flex align-items-center gap-2">
						<?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
						<?php if ($vip_name): ?>
							<button class="btn-cus-vip btn-cus-vip--sm <?php
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
							<div class="vip-badge vip-badge--sm" title="Tài khoản VIP">
								<i class="fas fa-crown text-warning"></i>
							</div>
						<?php endif; ?>
					</div>
					<div class="comment-date">
						<a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
							<time datetime="<?php comment_time('c'); ?>">
								<?php printf(esc_html__('%1$s lúc %2$s', 'commicpro'), get_comment_date(), get_comment_time()); ?>
							</time>
						</a>
					</div>
				</div>
			</div>

			<div class="comment-content">
				<?php comment_text(); ?>
			</div>

			<div class="reply">
				<?php
				comment_reply_link(array_merge($args, array(
					'reply_text' => 'Trả lời',
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
					'before'     => '<span class="btn btn-sm btn-outline-secondary">',
					'after'      => '</span>'
				)));
				?>
			</div>
		</div>
	<?php
}

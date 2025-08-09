<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package commicpro
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info container">
			<div class="row">
				<div class="col-md-4">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php $logo_url = get_field('logo_url', 2); ?>
							<img src="<?php echo $logo_url['url']; ?>" alt="logo">
						</a>
				</div>
				<div class="col-md-4">
					<h4>Thông báo và hướng dẫn</h4>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu',
							'menu_id'        => 'footer-menu',
						)
					);
					?>
				</div>
				<div class="col-md-4 overfollow-hidden">
					<h4>Theo dõi fanpage</h4>
					<?php echo get_field('iframe_fb', 2); ?>
				</div>
			</div>
			<div class="d-flex justify-content-center">
				<p class="copyright">Copyright © 2025 <?php echo get_bloginfo('name'); ?>. All rights reserved.</p>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

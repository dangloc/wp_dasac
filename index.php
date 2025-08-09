<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package commicpro
 */

get_header();
?>

	<main id="primary" class="site-main container">

		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			get_template_part( 'template-parts/home/banner' );

			?>
			<div class="row">
				<div class="col-lg-8">
					<?php 
					get_template_part( 'template-parts/home/slider-latest' );

					get_template_part( 'template-parts/home/slider-popular' );

					get_template_part( 'template-parts/home/slider-taxonomy-1' );

					get_template_part( 'template-parts/home/slider-taxonomy-2' );


					the_posts_navigation();
					?>
				</div>
				<div class="col-lg-4">
					<?php get_template_part( 'sidebar' );  ?>
				</div>
			</div>
			<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();

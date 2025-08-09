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
            <div class="scroll-ul">
                <div class="swiper category-slider">
                    <ul class="swiper-wrapper">
                        <?php
                        $terms = get_terms([
                            'taxonomy' => 'the_loai',
                            'hide_empty' => false,
                        ]);
                        $cnt = 0;
                        $array_slug = array();
                        $colors = array(
                            '#3C5F71', '#69507A', '#3F5B6E', '#796F54', '#8C713E', 
                            '#796846', '#836344', '#8B613C', '#69507A', '#4B6B5C', '#3C5F71'
                        );
                        foreach ($terms as $term) {
                            $cnt++;
                            array_push($array_slug, $term->slug);
                            $colorIndex = ($cnt - 1) % count($colors);
                        ?>
                            <li class="swiper-slide">
                                <a href="<?php echo get_term_link($term->term_id, 'the_loai'); ?>">
									<span class="h-100 w-100 overflow-hidden d-flex justify-content-center"  style="border: 1px solid #ccc; border-radius: 8px; padding: 8px 12px; white-space: nowrap; background-color: <?php echo $colors[$colorIndex]; ?>; color: white;">
										<?php echo $term->name; ?>
									</span>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <style>
                .btn-cus {
                    width: 100%;
                    border: none;
                    padding: 8px 15px;
                    border-radius: 4px;
                    transition: opacity 0.3s;
                }
                .btn-cus:hover {
                    opacity: 0.8;
                }
            </style>

			
			<div class="row">
				<div class="col-lg-9">
                    <?php get_template_part('template-parts/home/tableTruyen'); ?>
				</div>
				<div class="col-lg-3">
					<?php get_template_part( 'sidebar' );  ?>
				</div>
			</div>

            <?php get_template_part('template-parts/home/boxFull'); ?>
			<?php
		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer(); 
<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package commicpro
 */
?>

<aside id="secondary" class="widget-area">
	

	<div class="sidebar-top-rank">

		<ul class="nav nav-pills d-flex justify-content-center mb-3" id="pills-tab" role="tablist">
			<li class="nav-item d-flex justify-content-center w-50" role="presentation">
				<button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Top hot</button>
			</li>
			<li class="nav-item d-flex justify-content-center w-50" role="presentation">
				<button class="nav-link" id="pills-view-tab" data-bs-toggle="pill" data-bs-target="#pills-view" type="button" role="tab" aria-controls="pills-view" aria-selected="false">Top view</button>
			</li>
		</ul>
		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
				<?php
				// Lấy 9 truyện đã hoàn thành
				$truyen_query = new WP_Query([
						'post_type' => 'truyen_chu',
						'posts_per_page' => 9,
						'meta_key' => 'rmp_rating_val_sum', // Meta key của plugin Rate My Post
						'orderby' => 'meta_value_num',
						'order' => 'DESC'
				]);

				if ($truyen_query->have_posts()) : ?>
					<section class="section-slider pb-2">
						<div class="section-title"><span>Top đánh giá cao</span></div>
						<div class="swiper swiper-latest-top-sidebar">
							<div class="swiper-wrapper">
								<?php 
								$rank = 1;
								while ($truyen_query->have_posts()) : $truyen_query->the_post(); ?>
									<div class="swiper-slide">
										<a href="<?php the_permalink(); ?>">
											<div class="d-flex sidebar-top-rank-item justify-content-between" style="gap: 12px;">
												<div class="img-box position-relative">
													<?php 
													$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
													?>
													<img src="<?php echo $featured_img_url ?>" 
														alt="<?php the_title_attribute(); ?>" 
														onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
													/>
													<!-- Rank badge -->
													<div class="rank-badge rank-<?php echo $rank; ?>">
														<?php if ($rank == 1): ?>
															1
														<?php elseif ($rank == 2): ?>
															2
														<?php elseif ($rank == 3): ?>
															3
														<?php else: ?>
															<span><?php echo $rank; ?></span>
														<?php endif; ?>
													</div>
												</div>
												<div class="title-box">
													<div class="slide-title"><?php the_title(); ?></div>
													<div>
														<p class="count-port mb-0">
															<small class="text-muted-custom">
																<i class="fas fa-eye"></i>
																<?php echo display_truyen_view_count(get_the_ID()); ?> lượt xem
															</small>
														</p>
														<?php 
														$trang_thai = get_the_terms(get_the_ID(), 'trang_thai');
														$is_completed = false;
														if ($trang_thai && !is_wp_error($trang_thai)) {
															foreach ($trang_thai as $term) {
																if ($term->slug === 'da-hoan-thanh') {
																	$is_completed = true;
																	break;
																}
															}
														}
														?>

														<?php
														// Hiển thị thể loại
														$the_loai = get_the_terms(get_the_ID(), 'the_loai');
														if ($the_loai && !is_wp_error($the_loai)) : ?>
															<p class="mb-0">
																<small class="text-muted-custom">
																	<?php
																	$the_loai_names = array();
																	foreach ($the_loai as $term) {
																		$the_loai_names[] = $term->name;
																	}
																	echo esc_html(implode(', ', $the_loai_names));
																	?>
																</small>
															</p>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</a>
									</div>
								<?php 
								$rank++;
								endwhile; ?>
							</div>
						</div>
					</section>
				<?php endif; 
				wp_reset_postdata();
				?>
			</div>
			<div class="tab-pane fade" id="pills-view" role="tabpanel" aria-labelledby="pills-view-tab" tabindex="0">
				<?php display_top_viewed_truyen_sidebar(9); ?>
			</div>
		</div>
		
	</div>


	<div class="sidebar-top-new">
		<?php
		// Lấy 9 truyện đã hoàn thành
		$truyen_new_query = new WP_Query([
			'post_type'      => 'truyen_chu',
			'posts_per_page' => 6,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => [
				[
					'taxonomy' => 'trang_thai',
					'field'    => 'slug',
					'terms'    => 'da-hoan-thanh'
				]
			]
		]);

		if ($truyen_new_query->have_posts()) : ?>
			<section class="py-3">
				<div class="section-title"><span>Truyện full mới nhất</span></div>
				<div class="">
					<div class="row gy-3 mt-3">
						<?php while ($truyen_new_query->have_posts()) : $truyen_new_query->the_post(); ?>
							<div class="col-12">
								<a href="<?php the_permalink(); ?>">
									<div class="d-flex sidebar-top-new-item justify-content-between">
										<div class="img-box">
											<?php 
											$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
											?>
											<img src="<?php echo $featured_img_url ?>" 
												alt="<?php the_title_attribute(); ?>" 
												onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
											/>
										</div>
										<div class="title-box">
											<div class="slide-title"><?php the_title(); ?></div>
											<div>
												<?php 
												$trang_thai1 = get_the_terms(get_the_ID(), 'trang_thai');
												$is_completed1 = false;
												if ($trang_thai1 && !is_wp_error($trang_thai1)) {
													foreach ($trang_thai1 as $term) {
														if ($term->slug === 'da-hoan-thanh') {
															$is_completed1 = true;
															break;
														}
													}
												}
												?>
												
												<?php
												// Hiển thị thể loại
												$the_loai = get_the_terms(get_the_ID(), 'the_loai');
												if ($the_loai && !is_wp_error($the_loai)) : ?>
													<p class="mb-1">
														<small class="text-muted-custom">
															<?php
															$the_loai_names = array();
															foreach ($the_loai as $term) {
																$the_loai_names[] = $term->name;
															}
															echo esc_html(implode(', ', $the_loai_names));
															?>
														</small>
													</p>
												<?php endif; ?>
											</div>
										</div>
									</div>
								</a>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</section>
		<?php endif; 
		wp_reset_postdata();
		?>
	</div>
</aside>

<style>
/* Rank Badge Styles */
.rank-badge {
    position: absolute;
    top: -0;
    right: -0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
    color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    z-index: 2;
}

.rank-badge.rank-1 {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    animation: crown-glow 2s ease-in-out infinite alternate;
}

.rank-badge.rank-2 {
    background: linear-gradient(45deg, #C0C0C0, #A9A9A9);
}

.rank-badge.rank-3 {
    background: linear-gradient(45deg, #CD7F32, #B8860B);
}

.rank-badge.rank-4,
.rank-badge.rank-5,
.rank-badge.rank-6,
.rank-badge.rank-7,
.rank-badge.rank-8,
.rank-badge.rank-9 {
    background: linear-gradient(45deg, #6c757d, #495057);
}

.rank-badge i {
    font-size: 14px;
}

.rank-badge span {
    font-size: 11px;
    font-weight: 700;
}

@keyframes crown-glow {
    from {
        box-shadow: 0 2px 4px rgba(0,0,0,0.3), 0 0 5px rgba(255, 215, 0, 0.5);
    }
    to {
        box-shadow: 0 2px 4px rgba(0,0,0,0.3), 0 0 15px rgba(255, 215, 0, 0.8);
    }
}

/* Hover effect for rank badges */
.sidebar-top-rank-item:hover .rank-badge {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

/* View count styling */
.count-port .fas.fa-eye {
    margin-right: 4px;
    color: #6c757d;
}
</style>

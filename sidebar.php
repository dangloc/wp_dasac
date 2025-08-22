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
				<button class="nav-link active" id="pills-week-tab" data-bs-toggle="pill" data-bs-target="#pills-week" type="button" role="tab" aria-controls="pills-week" aria-selected="true">Top View Tuần</button>
			</li>
			<li class="nav-item d-flex justify-content-center w-50" role="presentation">
				<button class="nav-link" id="pills-total-tab" data-bs-toggle="pill" data-bs-target="#pills-total" type="button" role="tab" aria-controls="pills-total" aria-selected="false">Top View Tổng</button>
			</li>
		</ul>
		<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show active" id="pills-week" role="tabpanel" aria-labelledby="pills-week-tab" tabindex="0">
				<?php
				// Top View Tuần
				if (function_exists('get_top_viewed_truyen_week')) {
					$top_week = get_top_viewed_truyen_week(9);
				} else {
					$top_week = array();
				}
				if (!empty($top_week)) : ?>
					<section class="section-table pb-2">
						<div class="section-title"><span>Top View Tuần</span></div>
						<div class="ranking-table">
							<?php $rank = 1;
							foreach ($top_week as $post_id) :
								$post = get_post($post_id);
								if (!$post) continue;
							?>
								<div class="ranking-item d-flex align-items-center" data-rank="<?php echo $rank; ?>">
									<div class="rank-number">
										<span class="rank-badge rank-<?php echo $rank; ?>"><?php echo $rank; ?></span>
									</div>
									<div class="story-info flex-grow-1">
										<a href="<?php echo get_permalink($post_id); ?>" class="story-link">
											<div class="story-title"><?php echo esc_html(get_the_title($post_id)); ?></div>
											<div class="story-meta">
												<?php
												$the_loai = get_the_terms($post_id, 'the_loai');
												if ($the_loai && !is_wp_error($the_loai)) : ?>
													<small class="text-muted-custom genre-tags">
														<?php
														$the_loai_names = array();
														foreach ($the_loai as $term) {
															$the_loai_names[] = $term->name;
														}
														echo esc_html(implode(', ', array_slice($the_loai_names, 0, 2)));
														?>
													</small>
												<?php endif; ?>
											</div>
										</a>
									</div>
									<div class="story-stats">
										<small class="text-muted-custom view-count">
											<i class="fas fa-eye"></i>
											<?php echo get_post_meta($post_id, '_weekly_view_count', true) ? number_format(get_post_meta($post_id, '_weekly_view_count', true)) : 0; ?>
										</small>
									</div>
								</div>
							<?php $rank++;
							endforeach; ?>
						</div>
					</section>
				<?php else: ?>
					<div class="section-title"><span>Không có dữ liệu tuần này.</span></div>
				<?php endif; ?>
			</div>
			<div class="tab-pane fade" id="pills-total" role="tabpanel" aria-labelledby="pills-total-tab" tabindex="0">
				<?php
				// Top View Tổng
				$top_total = get_top_viewed_truyen(9);
				if (!empty($top_total)) : ?>
					<section class="section-table pb-2">
						<div class="section-title"><span>Top View Tổng</span></div>
						<div class="ranking-table">
							<?php $rank = 1;
							foreach ($top_total as $post_id) :
								$post = get_post($post_id);
								if (!$post) continue;
							?>
								<div class="ranking-item d-flex align-items-center" data-rank="<?php echo $rank; ?>">
									<div class="rank-number">
										<span class="rank-badge rank-<?php echo $rank; ?>"><?php echo $rank; ?></span>
									</div>
									<div class="story-info flex-grow-1">
										<a href="<?php echo get_permalink($post_id); ?>" class="story-link">
											<div class="story-title"><?php echo esc_html(get_the_title($post_id)); ?></div>
											<div class="story-meta">
												<?php
												$the_loai = get_the_terms($post_id, 'the_loai');
												if ($the_loai && !is_wp_error($the_loai)) : ?>
													<small class="text-muted-custom genre-tags">
														<?php
														$the_loai_names = array();
														foreach ($the_loai as $term) {
															$the_loai_names[] = $term->name;
														}
														echo esc_html(implode(', ', array_slice($the_loai_names, 0, 2)));
														?>
													</small>
												<?php endif; ?>
											</div>
										</a>
									</div>
									<div class="story-stats">
										<small class="text-muted-custom view-count">
											<i class="fas fa-eye"></i>
											<?php echo display_truyen_view_count($post_id); ?>
										</small>
									</div>
								</div>
							<?php $rank++;
							endforeach; ?>
						</div>
					</section>
				<?php else: ?>
					<div class="section-title"><span>Không có dữ liệu tổng.</span></div>
				<?php endif; ?>
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
/* Ranking Table Styles */
.ranking-table {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	min-width: 100%;
}

.ranking-item {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s ease;
    position: relative;
}

.ranking-item:last-child {
    border-bottom: none;
}

.ranking-item:hover {
    background-color: #f8f9fa;
}

.rank-number {
    width: 30px;
    text-align: center;
    margin-right: 12px;
}

.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 20px;
    height: 20px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 11px;
    color: white;
}

.rank-badge.rank-1 {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    box-shadow: 0 2px 6px rgba(255, 215, 0, 0.4);
}

.rank-badge.rank-2 {
    background: linear-gradient(45deg, #C0C0C0, #A9A9A9);
    box-shadow: 0 2px 6px rgba(192, 192, 192, 0.4);
}

.rank-badge.rank-3 {
    background: linear-gradient(45deg, #CD7F32, #B8860B);
    box-shadow: 0 2px 6px rgba(205, 127, 50, 0.4);
}

.rank-badge.rank-4,
.rank-badge.rank-5,
.rank-badge.rank-6,
.rank-badge.rank-7,
.rank-badge.rank-8,
.rank-badge.rank-9 {
    background: linear-gradient(45deg, #6c757d, #495057);
}

.story-info {
    min-width: 0; /* Cho phép text truncate */
}

.story-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.story-link:hover {
    text-decoration: none;
    color: inherit;
}

.story-title {
    font-weight: 500;
    font-size: 13px;
    line-height: 1.3;
    margin-bottom: 2px;
    color: #333;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.story-meta {
    margin-top: 2px;
}

.genre-tags {
    font-size: 11px;
    color: #6c757d;
    display: inline-block;
}

.story-stats {
    text-align: right;
    min-width: 60px;
}

.view-count {
    font-size: 10px;
    color: #6c757d;
    white-space: nowrap;
}

.view-count .fas {
    margin-right: 3px;
    font-size: 9px;
}

/* Responsive */
@media (max-width: 768px) {
    .ranking-item {
        padding: 6px 8px;
    }
    
    .story-title {
        font-size: 12px;
    }
    
    .genre-tags,
    .view-count {
        font-size: 10px;
    }
}

/* Animation for top 3 */
.rank-badge.rank-1 {
    animation: subtle-glow 3s ease-in-out infinite alternate;
}

@keyframes subtle-glow {
    from {
        box-shadow: 0 2px 6px rgba(255, 215, 0, 0.4);
    }
    to {
        box-shadow: 0 2px 10px rgba(255, 215, 0, 0.6);
    }
}

/* Old rank badge styles for other sections */
.sidebar-top-rank-item .rank-badge,
.sidebar-top-new-item .rank-badge {
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

.sidebar-top-rank-item .rank-badge.rank-1,
.sidebar-top-new-item .rank-badge.rank-1 {
    background: linear-gradient(45deg, #FFD700, #FFA500);
    animation: crown-glow 2s ease-in-out infinite alternate;
}

.sidebar-top-rank-item .rank-badge.rank-2,
.sidebar-top-new-item .rank-badge.rank-2 {
    background: linear-gradient(45deg, #C0C0C0, #A9A9A9);
}

.sidebar-top-rank-item .rank-badge.rank-3,
.sidebar-top-new-item .rank-badge.rank-3 {
    background: linear-gradient(45deg, #CD7F32, #B8860B);
}

.sidebar-top-rank-item .rank-badge.rank-4,
.sidebar-top-rank-item .rank-badge.rank-5,
.sidebar-top-rank-item .rank-badge.rank-6,
.sidebar-top-rank-item .rank-badge.rank-7,
.sidebar-top-rank-item .rank-badge.rank-8,
.sidebar-top-rank-item .rank-badge.rank-9,
.sidebar-top-new-item .rank-badge {
    background: linear-gradient(45deg, #6c757d, #495057);
}

.sidebar-top-rank-item .rank-badge i,
.sidebar-top-new-item .rank-badge i {
    font-size: 14px;
}

.sidebar-top-rank-item .rank-badge span,
.sidebar-top-new-item .rank-badge span {
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
.sidebar-top-rank-item:hover .rank-badge,
.sidebar-top-new-item:hover .rank-badge {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

/* View count styling */
.count-port .fas.fa-eye {
    margin-right: 4px;
    color: #6c757d;
}
</style>

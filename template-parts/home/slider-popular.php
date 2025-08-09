<?php
// template-parts/home/slider-popular.php
$popular_query = new WP_Query([
    'post_type' => 'truyen_chu',
    'posts_per_page' => 6,
    'meta_key' => 'view_count', // Đúng tên field ACF
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
]);
if ($popular_query->have_posts()) : ?>
<section class="section-slider py-5">
    <div class="section-title"><span>Truyện xem nhiều</span> <a href="<?php echo get_post_type_archive_link('truyen_chu'); ?>">Xem thêm >></a></div>
    <div class="swiper swiper-popular">
        <div class="swiper-wrapper">
            <?php while ($popular_query->have_posts()) : $popular_query->the_post(); ?>
                <div class="swiper-slide" data-truyen-id="<?php echo get_the_ID(); ?>">
                    <a href="<?php the_permalink(); ?>">
                        <?php 
                        $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        ?>
                        <img src="<?php echo $featured_img_url ?>" 
                            alt="<?php the_title_attribute(); ?>" 
                            onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
                        />
                        <div class="slide-title"><?php the_title(); ?></div>
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
                            <p class="count-port">
                                <?php
                                if($is_completed){
                                    ?>
                                    Full
                                    <?php
                                }else{
                                    ?>
                                    <small class="text-muted-custom chapter-count" data-truyen-id="<?php echo get_the_ID(); ?>">
                                        <?php echo $is_completed ? 'Full' : '...'; ?>
                                    </small>
                                    <?php
                                }
                                ?>
                            </p>
                    </a>
                    <?php
                                // Hiển thị tác giả
                                $tac_gia = get_the_terms(get_the_ID(), 'tac_gia');
                                if ($tac_gia && !is_wp_error($tac_gia)) : ?>
                                    <p class="mb-1">
                                        <small class="text-muted-custom">
                                            <strong>Tác giả:</strong> 
                                            <?php echo esc_html($tac_gia[0]->name); ?>
                                        </small>
                                    </p>
                                <?php endif; ?>

                                <?php
                                // Hiển thị thể loại
                                $the_loai = get_the_terms(get_the_ID(), 'the_loai');
                                if ($the_loai && !is_wp_error($the_loai)) : ?>
                                    <p class="mb-1">
                                        <small class="text-muted-custom">
                                            <strong>Thể loại:</strong> 
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
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; wp_reset_postdata(); ?>
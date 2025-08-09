<?php
/**
 * The template for displaying nam_phat_hanh taxonomy archive
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container py-4">
        <header class="page-header mb-4 d-flex justify-content-between">
            <h1 class="page-title">
                <?php
                $term = get_queried_object();
                printf(
                    esc_html__('Năm phát hành: %s', 'commicpro'),
                    '<span>' . esc_html($term->name) . '</span>'
                );
                ?>
            </h1>

            <div class="the-loai-selector">
                <select id="the-loai-select" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <?php
                    $all_the_loai = get_terms([
                        'taxonomy' => 'nam_phat_hanh',
                        'hide_empty' => false,
                    ]);
                    foreach ($all_the_loai as $the_loai) {
                        $selected = ($the_loai->slug === $term->slug) ? 'selected' : '';
                        echo '<option value="' . esc_attr($the_loai->slug) . '" ' . $selected . '>' . esc_html($the_loai->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </header>

                <script>
        document.addEventListener('DOMContentLoaded', function() {
            const theLoaiSelect = document.getElementById('the-loai-select');
            
            theLoaiSelect.addEventListener('change', function() {
                const selectedSlug = this.value;
                if (selectedSlug) {
                    const newURL = 'https://dasactruyen.xyz/index.php/nam_phat_hanh/' + selectedSlug + '/';
                    window.location.href = newURL;
                }
            });
        });
        </script>

        <div class="row">
            <div class="col-lg-9">
                <div class="row m-0 latest-chapter-container">
                    <?php if (have_posts()) : ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="col-md-4 col-sm-6 mb-4">
                                <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>" class="card-img-top">
                                            <?php 
                                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                            ?>
                                            <img class="img-fluid" 
                                                src="<?php echo $featured_img_url ?>" 
                                                alt="<?php the_title_attribute(); ?>" 
                                                onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
                                            />
                                        </a>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <h2 class="card-title h5">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="card-text">
                                            <?php the_excerpt(); ?>
                                        </div>
        
                                        <div class="card-meta">
                                            <?php
                                            // Hiển thị tác giả
                                            $tac_gia = get_the_terms(get_the_ID(), 'tac_gia');
                                            if ($tac_gia && !is_wp_error($tac_gia)) : ?>
                                                <p class="mb-1">
                                                    <small class="text-muted">
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
                                                    <small class="text-muted">
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
                                    </div>
                                </article>
                            </div>
                        <?php endwhile; ?>
        
                        <div class="col-12">
                            <?php
                            the_posts_pagination(array(
                                'mid_size' => 2,
                                'prev_text' => __('Trước', 'commicpro'),
                                'next_text' => __('Sau', 'commicpro'),
                                'class' => 'pagination justify-content-center'
                            ));
                            ?>
                        </div>
        
                    <?php else : ?>
                        <div class="col-12">
                            <p><?php esc_html_e('Không tìm thấy truyện nào trong năm này.', 'commicpro'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-3">
					<?php get_template_part( 'sidebar' );  ?>
			</div>
        </div>

    </div>
</main>

<?php
get_footer(); 
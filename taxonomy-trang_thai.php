<?php
/**
 * The template for displaying trang_thai taxonomy archive
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
                    esc_html__('Trạng thái: %s', 'commicpro'),
                    '<span>' . esc_html($term->name) . '</span>'
                );
                ?>
            </h1>
            <?php if ($term->description) : ?>
                <div class="archive-description">
                    <?php echo wp_kses_post($term->description); ?>
                </div>
            <?php endif; ?>

            <div class="the-loai-selector">
                <select id="the-loai-select" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <?php
                    $all_the_loai = get_terms([
                        'taxonomy' => 'trang_thai',
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
                    const newURL = 'https://dasactruyen.xyz/index.php/trang_thai/' + selectedSlug + '/';
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
                            <?php get_template_part( 'template-parts/home/item-card' ); ?>
                            <?php endwhile; ?>
            
                            <div class="col-12">
                                <?php custom_pagination(); ?>
                            </div>
            
                        <?php else : ?>
                            <div class="col-12">
                                <p><?php esc_html_e('Không tìm thấy truyện nào trong trạng thái này.', 'commicpro'); ?></p>
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
<?php
/**
 * Template part: Item Card for truyen-chu
 *
 * @param WP_Post $post (global $post hoặc truyền vào)
 *
 * Sử dụng: include hoặc get_template_part('template-parts/home/item-card', null, ['post' => $post]);
 */
$post = isset($args['post']) ? $args['post'] : get_post();
if (!$post) return;

$link = get_permalink($post);
$title = get_the_title($post);
$thumb = get_the_post_thumbnail($post->ID, 'medium', ['class' => 'item-thumb']);
$excerpt = get_the_excerpt($post);
?>
                    <div class="col-md-3 col-6 mb-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class('card-custom'); ?> data-truyen-id="<?php echo get_the_ID(); ?>">
                            <a href="<?php the_permalink(); ?>" class="card-img-top">
                                <?php 
                                $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                ?>
                                <img class="img-fluid" 
                                    src="<?php echo $featured_img_url ? $featured_img_url : get_template_directory_uri() . '/assets/images/icon-book.png'; ?>" 
                                    alt="<?php the_title_attribute(); ?>" 
                                    onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
                                />
                            </a>
                            <?php $tac_gia = get_the_terms(get_the_ID(), 'trang_thai'); ?>
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
                            
                            <div class="card-body-custom">
                                <h4 class="card-title-custom">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h4>

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
                        </article>
                    </div>
<?php
/**
 * Template Name: Bảng xếp hạng
 */

get_header();

// Thêm Font Awesome nếu chưa có, để dùng cho icon sao và vương miện
wp_enqueue_style('font-awesome-6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css');
?>

<div class="container py-5 ranking-page-container">
    <h1 class="ranking-title mb-5">Bảng Phong Thần</h1>
    
    <?php
    // Cải thiện query: Sắp xếp theo điểm trung bình, sau đó là số lượt vote
    $args = array(
        'post_type'      => 'truyen_chu',
        'posts_per_page' => 25,
        'meta_query'     => array(
            'relation' => 'AND',
            'avg_rating_clause' => array(
                'key'     => 'rmp_avg_rating',
                'compare' => 'EXISTS',
            ),
            'vote_count_clause' => array(
                'key'     => 'rmp_vote_count',
                'compare' => 'EXISTS',
            ),
        ),
        'orderby'        => array(
            'avg_rating_clause' => 'DESC',
            'vote_count_clause' => 'DESC',
        ),
    );
    
    $ranked_stories = new WP_Query($args);
    
    if ($ranked_stories->have_posts()) : ?>
        <div class="ranking-table-wrapper">
            <table class="table ranking-table">
                <thead>
                    <tr>
                        <th scope="col" class="text-center" style="width: 80px;">Hạng</th>
                        <th scope="col">Tác Phẩm</th>
                        <th scope="col">Tác Giả</th>
                        <th scope="col" class="text-center">Trạng Thái</th>
                        <th scope="col" style="width: 180px;">Đánh Giá</th>
                        <th scope="col" class="text-center" style="width: 100px;">Lượt Vote</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    while ($ranked_stories->have_posts()) : $ranked_stories->the_post();
                        // Lấy thông tin
                        $avg_rating = get_post_meta(get_the_ID(), 'rmp_avg_rating', true);
                        $votes_count = get_post_meta(get_the_ID(), 'rmp_vote_count', true);
                        
                        $tac_gia = get_the_terms(get_the_ID(), 'tac_gia');
                        $tac_gia_name = !is_wp_error($tac_gia) && !empty($tac_gia) ? $tac_gia[0]->name : 'Chưa cập nhật';

                        $trang_thai = get_the_terms(get_the_ID(), 'trang_thai');
                        $trang_thai_name = !is_wp_error($trang_thai) && !empty($trang_thai) ? $trang_thai[0]->name : 'Chưa cập nhật';
                        $trang_thai_slug = !is_wp_error($trang_thai) && !empty($trang_thai) ? $trang_thai[0]->slug : 'chua-cap-nhat';

                        // Thêm class cho Top 3
                        $rank_class = '';
                        if ($rank == 1) $rank_class = 'rank-gold';
                        elseif ($rank == 2) $rank_class = 'rank-silver';
                        elseif ($rank == 3) $rank_class = 'rank-bronze';
                    ?>
                        <tr class="<?php echo $rank_class; ?>">
                            <td class="rank-cell">
                                <span class="rank-number"><?php echo $rank; ?></span>
                            </td>
                            <td class="story-cell">
                                <a href="<?php the_permalink(); ?>" class="story-title-link">
                                    <?php if ($rank == 1) echo '<i class="fas fa-crown rank-icon"></i> '; ?>
                                    <?php the_title(); ?>
                                </a>
                            </td>
                            <td class="author-cell"><?php echo esc_html($tac_gia_name); ?></td>
                            <td class="status-cell text-center">
                                <span class="status-badge status-<?php echo esc_attr($trang_thai_slug); ?>">
                                    <?php echo esc_html($trang_thai_name); ?>
                                </span>
                            </td>
                            <td class="rating-cell">
                                <div class="star-rating">
                                    <?php 
                                    // Hiển thị sao đánh giá
                                    $rating_floor = floor($avg_rating);
                                    $rating_half = ($avg_rating - $rating_floor >= 0.5) ? true : false;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating_floor) {
                                            echo '<i class="fas fa-star"></i>'; // Sao đầy
                                        } elseif ($rating_half && $i == $rating_floor + 1) {
                                            echo '<i class="fas fa-star-half-alt"></i>'; // Nửa sao
                                        } else {
                                            echo '<i class="far fa-star"></i>'; // Sao rỗng
                                        }
                                    }
                                    ?>
                                </div>
                                <span class="rating-score"><?php echo number_format($avg_rating, 1); ?></span>
                            </td>
                            <td class="votes-cell text-center"><?php echo number_format($votes_count); ?></td>
                        </tr>
                    <?php 
                        $rank++;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
        
        <?php
        wp_reset_postdata();
    else : ?>
        <div class="alert alert-info">
            Chưa có tác phẩm nào trên Bảng Phong Thần.
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>
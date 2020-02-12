<?php
$postId = get_the_ID();
$show_compare = get_theme_mod('show_listing_compare', true);

$reviewId = get_post_id_by_meta_k_v('review_car', $postId);

$startAt = get_post_meta($reviewId, 'show_title_start_at', true);
$price = stm_listing_price_view(get_post_meta($postId, 'stm_genuine_price', true));
$hwy = get_post_meta($postId, 'highway_mpg', true);
$cwy = get_post_meta($postId, 'sity_mpg', true);

if(!is_null($reviewId)) {

    $performance = get_post_meta($reviewId, 'performance', true);
    $comfort = get_post_meta($reviewId, 'comfort', true);
    $interior = get_post_meta($reviewId, 'interior', true);
    $exterior = get_post_meta($reviewId, 'exterior', true);

    $ratingSumm = (($performance + $comfort + $interior + $exterior) / 4);
}

$cars_in_compare = array();
if (!empty($_COOKIE['compare_ids'])) {
$cars_in_compare = $_COOKIE['compare_ids'];
}

$car_already_added_to_compare = '';
$car_compare_status = esc_html__('Add to compare', 'motors');

if (!empty($cars_in_compare) and in_array(get_the_ID(), $cars_in_compare)) {
$car_already_added_to_compare = 'active';
$car_compare_status = esc_html__('Remove from compare', 'motors');
}
?>
<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="magazine-listing-item">
        <div class="magazine-loop">
            <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <a href="<?php the_permalink(); ?>">
                <div class="img">
                    <?php stm_listings_load_template('loop/default/grid/image'); ?>
                    <div class='fa-round'><i class='fa fa-share'></i></div>
                </div>
                <?php if(!empty($show_compare) and $show_compare): ?>
                    <div
                        class="stm-listing-compare stm-compare-directory-new <?php echo esc_attr($car_already_added_to_compare); ?>"
                        data-id="<?php echo esc_attr(get_the_id()); ?>"
                        data-title="<?php echo stm_generate_title_from_slugs(get_the_id(), false); ?>"
                        data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($car_compare_status); ?>"
                    >
                        <i class="stm-boats-icon-add-to-compare"></i>
                    </div>
                <?php endif; ?>
            </a>
            <div class="middle_info <?php if($ratingSumm > 0) echo 'middle-rating'; ?>">
                <div class="car_info">
                    <?php if(!empty($startAt)): ?>
                        <div class="starting-at normal-font">
                            <?php echo esc_html__('Starting at', 'motors'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="price heading-font">
                        <?php echo stm_do_lmth($price); ?>
                    </div>
                    <?php if(empty($startAt)): ?>
                        <div class="mpg normal-font">
                            <?php echo esc_html($hwy) . esc_html__('Hwy', 'motors') . ' / ' . esc_html($cwy) . esc_html__('City', 'motors'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if($ratingSumm > 0) :?>
                    <div class="rating">
                        <div class="rating-stars">
                            <i class="rating-empty"></i>
                            <i class="rating-color" style="width: <?php echo esc_attr($ratingSumm) * 20; ?>%;"></i>
                        </div>
                        <div class="rating-text heading-font">
                            <?php echo sprintf(esc_html__('%s out of 5.0', 'motors'), $ratingSumm); ?>
                        </div>
                        <div class="rating-details-popup">
                            <ul class="rating-params">
                                <li>
                                    <span class="normal-font"><?php echo esc_html__('Performance', 'motors')?></span>
                                    <div class="rating-stars">
                                        <i class="rating-empty"></i>
                                        <i class="rating-color" style="width: <?php echo esc_attr($performance) * 20; ?>%;"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="normal-font"><?php echo esc_html__('Comfort', 'motors')?></span>
                                    <div class="rating-stars">
                                        <i class="rating-empty"></i>
                                        <i class="rating-color" style="width: <?php echo esc_attr($comfort) * 20; ?>%;"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="normal-font"><?php echo esc_html__('Interior', 'motors')?></span>
                                    <div class="rating-stars">
                                        <i class="rating-empty"></i>
                                        <i class="rating-color" style="width: <?php echo esc_attr($interior) * 20; ?>%;"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="normal-font"><?php echo esc_html__('Exterior', 'motors')?></span>
                                    <div class="rating-stars">
                                        <i class="rating-empty"></i>
                                        <i class="rating-color" style="width: <?php echo esc_attr($exterior) * 20; ?>%;"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-review normal-font">
                        <?php echo esc_html__('No reviews for this Vehicle', 'motors'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="excerpt normal-font">
                <?php the_excerpt_max_charlength(115, get_the_ID()); ?>
            </div>
        </div>
    </div>
</div>
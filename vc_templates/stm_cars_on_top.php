<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';

stm_motors_enqueue_scripts_styles('cars_on_top');

if( empty($on_top_numbers) ) $on_top_numbers = 4;

$args = array(
    'post_type' => stm_listings_post_type(),
    'post_status' => 'publish',
    'posts_per_page' => intval($on_top_numbers),
    'order' => 'rand',
    'meta_query' => array(
        array(
            'key'     => 'special_car',
            'value'   => 'on',
            'compare' => '='
        ),
        array(
            'relation' => 'OR',
            array(
                'key' => 'car_mark_as_sold',
                'value' => '',
                'compare'  => 'NOT EXISTS'
            ),
            array(
                'key' => 'car_mark_as_sold',
                'value' => '',
                'compare'  => '='
            )
        )
    )
);

$featured_query = new WP_Query($args);

$inventoryLink = stm_get_listing_archive_link() . '?featured_top=true';
?>

<div class="stm_cars_on_top <?php echo esc_attr($css_class); ?>">
    <h2><?php echo stm_do_lmth($content); ?></h2>
    <?php if($featured_query->have_posts()): ?>
        <div class="row row-<?php echo intval($on_top_numbers); ?> car-listing-row">
            <?php
            while($featured_query->have_posts()): $featured_query->the_post();
                if($on_top_numbers >= 4) {
                    get_template_part('partials/listing-cars/listing-grid-directory-loop-4');
                } else {
                    get_template_part('partials/listing-cars/listing-grid-directory-loop-3');
                }

            endwhile; ?>
        </div>
        <?php if(!empty($show_more) and $show_more == 'yes'): ?>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="dp-in">
                        <a class="load-more-btn" href="<?php echo esc_url(stm_get_listing_archive_link().'?featured_top=true'); ?>">
                            <?php esc_html_e('Show all', 'motors'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php wp_reset_postdata(); ?>
        <div class="btn-wrap">
            <a href="<?php echo esc_url($inventoryLink); ?>" class="button listing_add_cart heading-font">
                <?php echo esc_html__("Show all premium cars", 'motors'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

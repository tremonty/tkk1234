<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

stm_motors_enqueue_scripts_styles('stm_inventory_no_filter');

if(empty($posts_per_page)) $posts_per_page = 4;

$args = array(
    'post_type' => stm_listings_post_type(),
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'suppress_filters' => 0,
    'order_by' => 'ID',
    'order' => $order_by
    );

if(is_listing()) {
    $args['meta_query'][] = array(
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
    );
}

$query = new WP_Query($args);

?>

<div class="stm-inventory-no-filter-wrap">
    <div class="stm-isotope-sorting stm-isotope-sorting-list" data-per-page="<?php echo esc_attr($posts_per_page); ?>">
    <?php
        if($query->have_posts()) :

            $template = 'partials/vc_loop/inventory-no-filter-loop';

            while($query->have_posts()): $query->the_post();
                get_template_part($template);
            endwhile;

        endif; ?>
    </div>
    <div class="stm_ajax_pagination stm-blog-pagination">
        <?php
        echo paginate_links( array(
            'type'      => 'list',
            'total'     => $query->found_posts / $posts_per_page,
            'prev_text' => '<i class="fa fa-angle-left"></i>',
            'next_text' => '<i class="fa fa-angle-right"></i>',
        ) );
        ?>
    </div>
    <?php wp_reset_postdata(); ?>
</div>

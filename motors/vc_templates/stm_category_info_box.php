<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

stm_motors_enqueue_scripts_styles('stm_category_info_box');

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

$optData = get_option('stm_vehicle_listing_options');

$taxName = get_taxonomy($cat_slug);
$cat = get_the_terms(get_the_ID(), $cat_slug);
$cat = $cat[0];

$font = '';

foreach ($optData as $opt) {
    if(!empty($cat) && $opt['slug'] == $cat->taxonomy) $font = $opt['font'];
}

?>

<div class="stm-cat-info-box">
    <i class="<?php echo esc_attr($font)?>"></i>
    <div class="stm-cat-name heading-font">
        <?php echo esc_html($taxName->label); ?>
    </div>
    <div class="stm-cat-val heading-font">
        <?php echo (!empty($cat->name)) ? esc_html($cat->name) : ''; ?>
    </div>
</div>

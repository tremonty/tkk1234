<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

stm_motors_enqueue_scripts_styles('stm_popular_searches');

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

$inventoryLink = stm_get_listing_archive_link();

$filterLink = $inventoryLink;

$title = '';

$taxList = explode(',', $taxonomy_list);

foreach ($taxList as $k => $tax) {
    if(!empty(trim($tax))) {
        $taxSlug = explode( '|', $tax );

        $slug = trim( $taxSlug[0] );
        $taxonomy = trim( $taxSlug[1] );

        $taxData = get_term_by( 'slug', $slug, $taxonomy );

        $image = get_term_meta($taxData->term_id, 'stm_image', true);

        $filterLink .=  ($k == 0) ? '?' : '&';
        $filterLink .= $taxonomy . '=' . $slug;

        $title = ($k == 0) ? $taxData->name : ' ' . $taxData->name;
    }
}

?>
<div class="stm-ps-item">
    <a href="<?php echo esc_url($filterLink); ?>">
        <span><?php echo esc_html($title); ?></span>
        <i class="fa fa-chevron-right"></i>
    </a>
</div>

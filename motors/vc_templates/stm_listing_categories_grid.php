<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

stm_motors_enqueue_scripts_styles('stm_listing_categories_grid');

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

$inventoryLink = stm_get_listing_archive_link();

?>

<div class="stm-listing-cetegories-grid-wrap <?php echo esc_attr($css_class); ?>">
    <h3><?php stm_dynamic_string_translation_e('Listing Categories Grid Title', $title); ?></h3>
    <div class="stm-lcg-items-wrap">
        <?php
            $taxList = explode(',', $taxonomy_list);

            foreach ($taxList as $tax) {
                if(!empty(trim($tax))) {
                    $taxSlug = explode( '|', $tax );

                    $slug = trim( $taxSlug[0] );
                    $taxonomy = trim( $taxSlug[1] );

                    $taxData = get_term_by( 'slug', $slug, $taxonomy );

                    $image = get_term_meta($taxData->term_id, 'stm_image', true);
                    $image = wp_get_attachment_image_src($image, 'stm-img-190-132');
                    $category_image = ($image) ? $image[0] : stm_get_plchdr(stm_get_current_layout());

                    $filterLink = $inventoryLink . '?' . $taxonomy . '=' . $slug;

                    ?>
                    <div class="stm-lcg-item">
                        <a href="<?php echo esc_url($filterLink); ?>">
                            <img src="<?php echo esc_url($category_image); ?>"/>
                            <span class="normal-font"><?php echo esc_html( $taxData->name ); ?></span>
                        </a>
                    </div>
                    <?php
                }
            }
        ?>
    </div>
</div>

<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' '));

stm_motors_enqueue_scripts_styles('stm_image_filter_by_type');

$randId = 'owl' . rand(1, 10000000);
$items = vc_param_group_parse_atts($atts['items']);

$imgSizes = array(
        'items_3' => array('stm-img-635-255', 'stm-img-635-255', 'stm-img-445-540'),
        'items_4' => array('stm-img-445-255', 'stm-img-635-255', 'stm-img-635-255', 'stm-img-445-255'),
        'items_5' => array('stm-img-635-255', 'stm-img-445-255', 'stm-img-350-255', 'stm-img-350-255', 'stm-img-350-255')
);

?>
<div class="stm-image-filter-wrap <?php echo esc_attr($css_class); ?>">
    <div class="title"><?php echo stm_do_lmth($content); ?></div>
    <div id="<?php echo esc_attr($randId);?>" class="stm-img-filter-container stm-img-<?php echo esc_attr($row_number);?>">
        <?php
        if(is_array($items)) {
            $num = 0;
            $i = 0;
            foreach ($items as $item) {
                if($num == 0) echo '<div class="carousel-container">';
                $term = get_term_by('slug', $item['body_type'], 'body');
                $img = wp_get_attachment_image_src($item['images'], $imgSizes['items_' . $row_number][$num]);

                if($row_number == 3 && ($num == 0 || $num == 2)) echo '<div class="col-wrap">';
        ?>
            <div class="img-filter-item template-<?php echo esc_attr($row_number) . '-' . ($num%$row_number); ?>">
                <a href="<?php echo esc_url( stm_get_listing_archive_link( array( 'body' => $item['body_type']) ) ); ?>">
                    <div class="img-wrap">
                        <img src="<?php echo esc_url($img[0]);?>" />
                    </div>
                </a>
                <div class="body-type-data">
                    <div class="bt-title heading-font"><?php echo esc_html($term->name); ?></div>
                    <div class="bt-count normal_font"><?php echo esc_html($term->count) . ' ' . esc_html__('cars', 'motors');?></div>
                </div>
            </div>
        <?php
                if($row_number == 3 && ($num == 1 || $num == 2)) echo '</div>';
                $num = ($row_number-1 > $num) ? $num + 1 : 0;
                if($num == 0 || (count($items) - 1) == $i) echo '</div>';
                $i++;
            }
        }
        ?>
    </div>
</div>

<script>
    (function($) {
        $(document).ready(function () {
            var owlIcon = $('#<?php echo esc_attr($randId); ?>');
            var owlRtl = false;
            if( $('body').hasClass('rtl') ) {
                owlRtl = true;
            }

            owlIcon.owlCarousel({
                items: 1,
                smartSpeed: 800,
                dots: false,
                margin: 0,
                autoplay: false,
                nav: true,
                loop: false,
                responsiveRefreshRate: 1000,
            })
        });
    })(jQuery);
</script>
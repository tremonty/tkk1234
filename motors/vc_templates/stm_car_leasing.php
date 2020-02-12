<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';
$recaptcha_enabled = get_theme_mod('enable_recaptcha',0);
$recaptcha_public_key = get_theme_mod('recaptcha_public_key');
$recaptcha_secret_key = get_theme_mod('recaptcha_secret_key');
if(!empty($recaptcha_enabled) and $recaptcha_enabled and !empty($recaptcha_public_key) and !empty($recaptcha_secret_key)) {
    wp_enqueue_script( 'stm_grecaptcha' );
}
?>
<div class="stm-car-leasing-wrap">
    <h2 class="carName"><?php echo esc_html($c_l_title); ?></h2>
    <div class="price_wrap">
        <div class="left">
            <div class="price heading-font"><?php echo stm_listing_price_view($c_l_price); ?></div>
        </div>
        <span class="right heading-font">/ <?php echo esc_html($c_l_price_affix); ?></span>
    </div>
    <span class="subtitle heading-font"><?php echo esc_html($c_l_price_subtitle); ?></span>
    <div class="btn-wrap">
        <a href="#" class="c-l-test-drive heading-font" data-toggle="modal" data-target="#test-drive">
            <i class="stm-icon-steering_wheel"></i>
            TEST DRIVE</a>
    </div>
    <div class="shortcode_content" data-sc="<?php echo htmlspecialchars($c_l_shortcode); ?>" style="display: none;"></div>
</div>
<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$css_class = ( !empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$special_car = get_post_meta( get_the_ID(), 'special_car', true );
$badge_text = get_post_meta( get_the_ID(), 'badge_text', true );
$badge_bg_color = get_post_meta( get_the_ID(), 'badge_bg_color', true );

if ( empty( $badge_text ) ) {
    $badge_text = 'Special';
}

$badge_style = '';
if ( !empty( $badge_bg_color ) ) {
    $badge_style = 'style=background-color:' . $badge_bg_color . ';';
}
?>

<div class="stm-boats-featured-image <?php echo esc_attr( $css_class ); ?>">
    <?php if ( !empty( $special_car ) and $special_car == 'on' ): ?>
        <div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
            <?php stm_dynamic_string_translation_e('Special Badge Text', $badge_text ); ?>
        </div>
    <?php endif; ?>
    <?php get_template_part( 'partials/single-car-boats/boat', 'image' ); ?>
</div>
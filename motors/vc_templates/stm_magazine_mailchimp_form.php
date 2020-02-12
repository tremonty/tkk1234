<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

?>

<div class="stm-mc-form-wrap">
    <?php if(!empty($mc_title)) : ?>
    <h2>
        <?php echo esc_html($mc_title); ?>
    </h2>
    <?php endif; ?>
    <?php echo do_shortcode($mc_shortcode); ?>
</div>

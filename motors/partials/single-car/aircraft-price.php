<?php
$price = get_post_meta(get_the_ID(), 'price', true);
$sale_price = get_post_meta(get_the_ID(), 'sale_price', true);

$regular_price_description = (!empty(get_post_meta(get_the_ID(), 'regular_price_description', true))) ? get_post_meta(get_the_ID(), 'regular_price_description', true) : esc_html__('Incl Taxes &amp; Checkup', 'motors');
?>

<div class="aircraft-price-wrap">
    <div class="left">
        <?php if(empty($sale_price)): ?>
            <span class="h3"><?php echo esc_attr(stm_listing_price_view($price)); ?></span>
        <?php else: ?>
            <span class="h4"><?php echo esc_attr(stm_listing_price_view($price)); ?></span>
            <span class="h3"><?php echo esc_attr(stm_listing_price_view($sale_price)); ?></span>
        <?php endif;?>
    </div>
    <div class="right">
        <div class="price-description-single"><?php stm_dynamic_string_translation_e('Regular Price Description', $regular_price_description); ?></div>
    </div>
</div>



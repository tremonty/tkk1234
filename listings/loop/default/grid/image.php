<?php
$placeholder_path = 'plchldr255.png';
if(wp_is_mobile()){
    $placeholder_path = 'plchldr350.png';
}
if (stm_is_boats()) {
    $show_compare = get_theme_mod('show_listing_compare', true);

    if (stm_is_boats()) {
        $placeholder_path = 'boats-placeholders/boats-250.png';
    }
}


$placeholder_path = (stm_is_aircrafts()) ? 'ac_plchldr.jpg' : $placeholder_path;
?>

<div class="image">
    <?php if (has_post_thumbnail()): ?>
        <?php
        $size = 'stm-img-255-135';
        if(wp_is_mobile()){
            $size = 'stm-img-796-466';

        }
        if(stm_is_boats()) $size = 'stm-img-350-205';
        $img_placeholder = $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $size); ?>
        <img
            data-original="<?php echo esc_url($img[0]); ?>"
            src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path); ?>"
            class="lazy img-responsive"
            alt="<?php echo stm_generate_title_from_slugs(get_the_id()); ?>"
        />
    <?php else: ?>
        <img
            src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path); ?>"
            class="img-responsive"
            alt="<?php esc_attr_e('Placeholder', 'motors'); ?>"
        />
    <?php endif; ?>
	<?php get_template_part('partials/listing-cars/listing-directory', 'badges'); ?>
    <?php if (stm_is_boats()) {
        stm_get_boats_image_hover(get_the_ID()); ?>
        <!--Compare-->
        <?php if (!empty($show_compare) and $show_compare): ?>
            <div
                class="stm-listing-compare stm-compare-directory-new"
                data-id="<?php echo esc_attr(get_the_id()); ?>"
                data-title="<?php echo stm_generate_title_from_slugs(get_the_id(), false); ?>"
                data-toggle="tooltip" data-placement="bottom" title="<?php esc_attr_e('Add to compare', 'motors'); ?>">
				<i class="stm-boats-icon-add-to-compare"></i>
            </div>
        <?php endif;
    } ?>
</div>
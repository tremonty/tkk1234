<?php $show_compare = get_theme_mod('show_listing_compare', true); ?>

<div class="image">
    <a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
        <div class="image-inner">

            <?php if (has_post_thumbnail()): ?>
                <?php
                $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'stm-img-350-205');
                ?>
                <img
                    data-original="<?php echo esc_url($img[0]); ?>"
                    src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/boats-250.png'); ?>"
                    class="lazy img-responsive"
                    alt="<?php echo stm_generate_title_from_slugs(get_the_id()); ?>"
                />

            <?php else : ?>
                <img
                    src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/boats-placeholders/boats-250.png'); ?>"
                    class="img-responsive"
                    alt="<?php esc_attr_e('Placeholder', 'motors'); ?>"
                />
            <?php endif; ?>
			<?php get_template_part('partials/listing-cars/listing-directory', 'badges'); ?>
        </div>
        <?php stm_get_boats_image_hover(get_the_ID()); ?>
        <!--Compare-->
        <?php if (!empty($show_compare) and $show_compare): ?>
            <div
                class="stm-listing-compare stm-compare-directory-new"
                data-id="<?php echo esc_attr(get_the_id()); ?>"
                data-title="<?php echo stm_generate_title_from_slugs(get_the_id(), false); ?>"
                data-toggle="tooltip" data-placement="left" title="<?php esc_attr_e('Add to compare', 'motors'); ?>">
                <i class="stm-boats-icon-add-to-compare"></i>
            </div>
        <?php endif; ?>
    </a>
</div>
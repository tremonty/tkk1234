<?php
$show_compare = get_theme_mod('show_listing_compare', true);

$show_favorite = get_theme_mod('enable_favorite_items', true);

$car_media = stm_get_car_medias(get_the_id());

$asSold = get_post_meta(get_the_ID(), 'car_mark_as_sold', true);
?>

<div class="image">

    <!--Hover blocks-->
    <!---Media-->
    <div class="stm-car-medias">
        <?php if(!empty($car_media['car_photos_count'])): ?>
            <div class="stm-listing-photos-unit stm-car-photos-<?php echo get_the_id(); ?>">
                <i class="stm-service-icon-photo"></i>
                <span><?php echo esc_html($car_media['car_photos_count']); ?></span>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function(){

                    jQuery(".stm-car-photos-<?php echo get_the_id(); ?>").on('click', function() {
                        jQuery(this).lightGallery({
                            dynamic: true,
                            dynamicEl: [
                                <?php foreach($car_media['car_photos'] as $car_photo): ?>
                                {
                                    src  : "<?php echo esc_url($car_photo); ?>"
                                },
                                <?php endforeach; ?>
                            ],
                            download: false,
                            mode: 'lg-fade',
                        })
                    });
                });

            </script>
        <?php endif; ?>
        <?php if(!empty($car_media['car_videos_count'])): ?>
            <div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_id(); ?>">
                <i class="fa fa-film"></i>
                <span><?php echo esc_html($car_media['car_videos_count']); ?></span>
            </div>

            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery(".stm-car-videos-<?php echo get_the_id(); ?>").on('click', function() {
                        jQuery(this).lightGallery({
                            dynamic: true,
                            dynamicEl: [
                                <?php foreach($car_media['car_videos'] as $car_video): ?>
                                {
                                    src  : "<?php echo esc_url($car_video); ?>"
                                },
                                <?php endforeach; ?>
                            ],
                            download: false,
                            mode: 'lg-fade',
                        })
                    }); //click
                }); //ready

            </script>
        <?php endif; ?>
    </div>
    <!--Compare-->
    <?php if(!empty($show_compare) and $show_compare): ?>
        <div
            class="stm-listing-compare"
            data-id="<?php echo esc_attr(get_the_id()); ?>"
            data-title="<?php echo stm_generate_title_from_slugs(get_the_id(),false); ?>"
            data-toggle="tooltip" data-placement="left" title="<?php esc_attr_e('Add to compare', 'motors') ?>">
            <i class="stm-service-icon-compare-new"></i>
        </div>
    <?php endif; ?>

    <!--Favorite-->
    <?php if(!empty($show_favorite) and $show_favorite): ?>
        <div
            class="stm-listing-favorite"
            data-id="<?php echo esc_attr(get_the_id()); ?>"
            data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e('Add to favorites', 'motors') ?>">
            <i class="stm-service-icon-staricon"></i>
        </div>
    <?php endif; ?>

    <a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
        <div class="image-inner">
            <?php get_template_part('partials/listing-cars/listing-directory', 'badges'); ?>
            <?php if(has_post_thumbnail()): ?>
                <?php
                $sizeImg = "stm-img-280-165";
                $plchldr = 'plchldr350.png';
                $img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $sizeImg);
                ?>
                <img
                    data-original="<?php echo esc_url($img[0]); ?>"
                    src="<?php echo esc_url(get_stylesheet_directory_uri().'/assets/images/' . $plchldr); ?>"
                    class="lazy img-responsive"
                    alt="<?php the_title(); ?>"
                />

            <?php else :
                $plchldr = "plchldr-275.jpg";
                ?>
                <img
                    src="<?php echo esc_url(get_stylesheet_directory_uri().'/assets/images/' . $plchldr); ?>"
                    class="img-responsive"
                    alt="<?php esc_attr_e('Placeholder', 'motors'); ?>"
                />
            <?php endif; ?>
            <?php if(is_listing() && !empty($asSold)): ?>
                <div class="stm-badge-directory heading-font" <?php echo sanitize_text_field($badge_bg_color); ?>>
                    <?php echo esc_html__('Sold', 'motors'); ?>
                </div>
            <?php endif; ?>
        </div>
    </a>
</div>

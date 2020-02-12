<?php
$show_favorite = get_theme_mod('enable_favorite_items', true);

//Compare
$show_compare = get_theme_mod('show_listing_compare', true);

/*Media*/
$car_media = stm_get_car_medias(get_the_id());

$asSold = get_post_meta(get_the_ID(), 'car_mark_as_sold', true);

$img_size = (!stm_is_listing_two()) ? 'stm-img-255-160' : 'stm-img-255-135';
$placeholder = 'plchldr255.png';

if(is_listing(array('listing_three'))) {
    $img_size = 'stm-img-350-205';
    $placeholder = 'plchldr350.png';
}

if(wp_is_mobile()){
    $img_size = (!stm_is_listing_two()) ? 'stm-img-796-466' : 'stm-img-255-135-x-2';
    $placeholder = 'plchldr350.png';
}

$imgSize = (stm_is_dealer_two()) ? 'stm-img-398-223' : $img_size;

$dynamicClassPhoto = 'stm-car-photos-' . get_the_id() . '-' . rand();
$dynamicClassVideo = 'stm-car-videos-' . get_the_id() . '-' . rand();

?>
<div class="image">
	<?php
	$img = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $imgSize);
    $plchldr = (stm_is_dealer_two()) ? "plchldr-398.jpg" : 'plchldr255.png';
    $plchldr = (stm_is_aircrafts()) ? 'ac_plchldr.jpg' : $plchldr;

	if(has_post_thumbnail()): ?>
		<img
			data-original="<?php echo esc_url($img[0]); ?>"
			src="<?php echo esc_url(get_stylesheet_directory_uri().'/assets/images/' . $plchldr); ?>"
			class="lazy img-responsive"
			alt="<?php the_title(); ?>"
		/>
	<?php else: ?>
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
	<!--Hover blocks-->
	<?php get_template_part('partials/listing-cars/listing-directory', 'badges'); ?>
	<!---Media-->
	<div class="stm-car-medias">
		<?php if(!empty($car_media['car_photos_count'])): ?>
			<div class="stm-listing-photos-unit stm-car-photos-<?php echo get_the_id(); ?> <?php echo esc_attr($dynamicClassPhoto); ?>">
				<i class="stm-service-icon-photo"></i>
				<span><?php echo esc_html($car_media['car_photos_count']); ?></span>
			</div>

			<script>
				jQuery(document).ready(function(){
					jQuery(".<?php echo esc_attr($dynamicClassPhoto); ?>").on('click', function(e) {
						e.preventDefault();
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
			<div class="stm-listing-videos-unit stm-car-videos-<?php echo get_the_id(); ?> <?php echo esc_attr($dynamicClassVideo); ?>">
				<i class="fa fa-film"></i>
				<span><?php echo esc_html($car_media['car_videos_count']); ?></span>
			</div>

			<script>
				jQuery(document).ready(function(){

					jQuery(".<?php echo esc_attr($dynamicClassVideo); ?>").on('click', function(e) {
						e.preventDefault();
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

	<!--Favorite-->
	<?php if(!empty($show_favorite) and $show_favorite): ?>
		<div
			class="stm-listing-favorite"
			data-id="<?php echo esc_attr(get_the_id()); ?>"
			data-toggle="tooltip" data-placement="right" title="<?php esc_attr_e('Add to favorites', 'motors') ?>"
		>
			<i class="stm-service-icon-staricon"></i>
		</div>
	<?php endif; ?>

	<!--Compare-->
	<?php if(!empty($show_compare) and $show_compare): ?>
		<div
			class="stm-listing-compare stm-compare-directory-new"
			data-id="<?php echo esc_attr(get_the_id()); ?>"
			data-title="<?php echo stm_generate_title_from_slugs(get_the_id(),false); ?>"
			data-toggle="tooltip" data-placement="left" title="<?php esc_attr_e('Add to compare', 'motors') ?>"
		>
			<i class="stm-service-icon-compare-new"></i>
		</div>
	<?php endif; ?>
</div>
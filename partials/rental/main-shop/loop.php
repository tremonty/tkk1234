<?php

    global $product;
    $hasGallery = (!empty($product->get_gallery_image_ids())) ? true : false;

    $col_1 = '';
    $col_2 = 'col-md-12 col-sm-12';
    if(has_post_thumbnail()) {
        $col_1 = 'col-md-4 col-sm-4 first';
        $col_2 = 'col-md-8 col-sm-8 second';
    }


    $id = get_the_ID();

    if (class_exists('SitePress')) {
        global $sitepress;
        $id = apply_filters( 'wpml_object_id', get_the_ID(), 'product', false, $sitepress->get_default_language());
    }

    $s_title = get_post_meta($id, 'cars_info', true);
    $car_info = stm_get_car_rent_info($id);
    $excerpt = get_the_excerpt($id);

    $current_car = stm_get_cart_items();

    $current_car_id = 0;

    if(!empty($current_car['car_class'])) {
        if(!empty($current_car['car_class']['id'])) {
            $current_car_id = $current_car['car_class']['id'];
        }
    }

    $current_car = '';
    if($id == $current_car_id) {
        $current_car = 'current_car';
    }

    $dates = (isset($_COOKIE['stm_calc_pickup_date_' . get_current_blog_id()])) ? stm_checkOrderAvailable($id, $_COOKIE['stm_calc_pickup_date_' . get_current_blog_id()], $_COOKIE['stm_calc_return_date_' . get_current_blog_id()]) : array();
    $disableCar = (count($dates) > 0) ? 'stm-disable-car' : '';
?>

<div class="stm_single_class_car <?php echo esc_attr($current_car); ?> <?php echo esc_attr($disableCar)?>" id="product-<?php echo esc_attr($id); ?>">
    <div class="row">
        <div class="<?php echo sanitize_text_field($col_1) ?>">
            <?php if(has_post_thumbnail()): ?>
                <div class="image">
                    <?php the_post_thumbnail('stm-img-350-181', array('class' => 'img-responsive')); ?>
                    <?php if($hasGallery):?>
                        <div class="stm-rental-photos-unit stm-car-photos-<?php echo esc_attr($id); ?>">
                            <i class="stm-boats-icon-camera"></i>
                            <span class="heading-font"><?php echo count($product->get_gallery_image_ids()); ?></span>
                        </div>
                        <script>
                            jQuery(document).ready(function(){

                                jQuery(".stm-car-photos-<?php echo esc_attr($id); ?>").on('click', function() {
                                    jQuery(this).lightGallery({
                                        dynamic: true,
                                        dynamicEl: [
                                            <?php foreach($product->get_gallery_image_ids() as $car_photo):
                                            $image = wp_get_attachment_image_src( $car_photo, 'full', false, array( 'title' => get_post_field( 'post_title', $car_photo ) ) );
                                            ?>
                                            {
                                                src  : "<?php echo esc_url($image[0]); ?>"
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
                </div>
            <?php endif; ?>
        </div>
        <div class="<?php echo sanitize_text_field($col_2) ?>">
            <div class="row">

                <div class="col-md-6 col-sm-6">
                    <div class="top">
                        <div class="heading-font">
                            <h3><?php the_title(); ?></h3>
                            <?php if(!empty($s_title)): ?>
                                <div class="s_title"><?php echo sanitize_text_field($s_title); ?></div>
                            <?php endif; ?>
                            <?php if(!empty($car_info)): ?>
                                <div class="infos">
                                    <?php foreach($car_info as $slug => $info):
                                        $name = $info['value'];
                                        if($info['numeric']) {
                                            $name = $info['value'] . ' ' . esc_html__($info['name'], 'motors');
                                        }
                                        $font = $info['font'];
                                        ?>
                                        <div class="single_info stm_single_info_font_<?php echo esc_attr($font) ?>">
                                            <i class="<?php echo esc_attr($font); ?>"></i>
                                            <span><?php echo sanitize_text_field($name); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if(!empty($excerpt)): ?>
                            <div class="stm-more">
                                <a href="#">
                                    <span><?php esc_html_e('More information', 'motors'); ?></span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <?php get_template_part('partials/rental/main-shop/price'); ?>
                </div>
                <?php if(!empty($excerpt)): ?>
                    <div class="col-md-12 col-sm-12">
                        <div class="more">
                            <div class="lists-inline">
                                <?php echo apply_filters('the_content', $excerpt); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
	<?php if(!empty($disableCar)): ?>
		<div class="stm-enable-car-date">
			<?php
				$formatedDates = array();
				foreach ($dates as $val){
					$formatedDates[] = stm_get_formated_date($val, 'd M');
				}
			?>
			<h3><?php echo esc_html__('This Class is already booked in: ', 'motors') . "<span class='yellow'>" . implode('<span>,</span> ', $formatedDates);?></span>.</h3>
		</div>
	<?php endif; ?>
</div>
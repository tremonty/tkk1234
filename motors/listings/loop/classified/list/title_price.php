<?php
$show_title_two_params_as_labels = get_theme_mod('show_generated_title_as_label', true);

$regular_price_label = get_post_meta(get_the_ID(), 'regular_price_label', true);
$special_price_label = get_post_meta(get_the_ID(),'special_price_label',true);

$price = get_post_meta(get_the_id(),'price',true);
$sale_price = get_post_meta(get_the_id(),'sale_price',true);

$car_price_form_label = get_post_meta(get_the_ID(), 'car_price_form_label', true);

$headingFont = (stm_is_dealer_two()) ? 'normal-font' : 'heading-font';

?>

<div class="meta-top">
	<?php if($hide_labels and !empty($price)): ?>
		<?php
		if(!empty($sale_price)) {
			$price = $sale_price;
		};
		?>
		<div class="price">
			<div class="normal-price">
				<?php if(!empty($car_price_form_label)): ?>
                    <a href="#" class="rmv_txt_drctn archive_request_price" data-toggle="modal" data-target="#get-car-price" data-title="<?php echo esc_html(get_the_title(get_the_ID())); ?>" data-id="<?php echo get_the_ID(); ?>">
                        <span class="<?php echo esc_attr($headingFont); ?>"><?php echo esc_attr($car_price_form_label); ?></span>
                    </a>
				<?php else: ?>
					<span class="<?php echo esc_attr($headingFont); ?>"><?php echo esc_attr(stm_listing_price_view($price)); ?></span>
				<?php endif; ?>
			</div>
		</div>

	<?php else: ?>
		<?php if(!empty($price) and !empty($sale_price) and $price != $sale_price):?>
			<div class="price discounted-price">
				<div class="regular-price">
                    <?php if(!empty($regular_price_label)): ?>
                        <span class="label-price"><?php echo esc_attr($regular_price_label); ?></span>
                    <?php endif; ?>
					<?php echo esc_attr(stm_listing_price_view($price)); ?>
				</div>
				<div class="sale-price">
                    <?php if(!empty($special_price_label)): ?>
                        <span class="label-price"><?php echo esc_attr($special_price_label); ?></span>
                    <?php endif; ?>
					<span class="<?php echo esc_attr($headingFont); ?>"><?php echo esc_attr(stm_listing_price_view($sale_price)); ?></span>
				</div>
			</div>
		<?php elseif(!empty($price)): ?>
			<div class="price">
				<div class="normal-price">
					<?php if(!empty($regular_price_label)): ?>
						<span class="label-price"><?php echo esc_attr($regular_price_label); ?></span>
					<?php endif; ?>
					<?php if(!empty($car_price_form_label)): ?>
                        <a href="#" class="rmv_txt_drctn archive_request_price" data-toggle="modal" data-target="#get-car-price" data-title="<?php echo esc_html(get_the_title(get_the_ID())); ?>" data-id="<?php echo get_the_ID(); ?>">
                            <span class="heading-font"><?php echo esc_attr($car_price_form_label); ?></span>
                        </a>
					<?php else: ?>
						<span class="<?php echo esc_attr($headingFont); ?>"><?php echo esc_attr(stm_listing_price_view($price)); ?></span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<div class="title heading-font">
		<a href="<?php the_permalink() ?>" class="rmv_txt_drctn">
			<?php echo stm_generate_title_from_slugs(get_the_id(),$show_title_two_params_as_labels); ?>
		</a>
	</div>
</div>
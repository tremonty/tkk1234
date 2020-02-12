<div class="stm-form-2-features clearfix">
	<div class="stm-car-listing-data-single stm-border-top-unit ">
		<div class="title heading-font"><?php esc_html_e('Select Your Car Features', 'motors'); ?></div>
		<span class="step_number step_number_2 heading-font"><?php esc_html_e('step','motors'); ?> 2</span>
	</div>
    <?php stm_listings_load_template('add_car/step_2_items', array('items' => $items, 'id' => $id)); ?>
</div>
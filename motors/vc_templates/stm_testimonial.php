<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if(empty($image_size)) {
	$image_size = '213x142';
}

$thumbnail = '';

if (!empty($image)) {
	$image = explode(',', $image);
	if (!empty($image[0])) {
		$image = $image[0];
		$post_thumbnail = wpb_getImageBySize(array(
			'attach_id' => $image,
			'thumb_size' => $image_size
		));

		$thumbnail = $post_thumbnail['thumbnail'];
	}
}

?>
<div class="testimonial-unit <?php echo esc_html($style_view); ?>">
    <?php if($style_view == 'style_1') { ?>
	<div class="clearfix">
		<?php if(!empty($thumbnail)): ?>
			<div class="image">
				<?php echo stm_do_lmth($thumbnail); ?>
			</div>
		<?php endif; ?>

		<?php if(stm_is_rental()): ?>
			<div class="testimonial-info">
				<?php if(!empty($author)): ?>
					<div class="author heading-font">
						<?php echo esc_attr($author); ?>
					</div>
				<?php endif; ?>


				<?php if(!empty($author_car)): ?>
					<div class="author-car">
						<i class="stm-icon-car"></i>
						<?php echo esc_attr($author_car); ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="content">
			<?php echo wpb_js_remove_wpautop($content); ?>
		</div>

	</div>

	<?php if(!stm_is_rental()): ?>
		<div class="testimonial-meta">
			<?php if(!empty($author)): ?>
				<div class="author heading-font">
					<?php echo esc_attr($author); ?>
				</div>
			<?php endif; ?>


			<?php if(!empty($author_car)): ?>
				<div class="author-car">
					<i class="stm-icon-car"></i>
					<?php echo esc_attr($author_car); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
    <?php
    } else {
    ?>
    <div class="clearfix">
        <?php if(!empty($thumbnail)): ?>
            <div class="image">
                <?php echo stm_do_lmth($thumbnail); ?>
            </div>
        <?php endif; ?>
        <div class="author_info">
            <div class="author_name heading-font"><?php echo esc_html($author);?></div>
            <div class="author_position heading-font"><?php echo (!empty($author_position)) ? esc_html($author_position) : '';?></div>
        </div>
        <div class="content normal_font">
            <?php echo wpb_js_remove_wpautop($content); ?>
        </div>
        <?php if(!empty($icon)): ?>
        <div class="icon">
            <i class="<?php echo esc_attr($icon); ?>"></i>
        </div>
        <?php endif; ?>
    </div>
    <?php
    }
    ?>
</div>

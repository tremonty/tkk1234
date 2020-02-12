<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'stm-countUp.min.js' );

if(empty($stm_counter_time)) {
	$stm_counter_time = '2.5';
}

$css_class = (!empty($css)) ? apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class($css, ' ')) : '';
$id = rand(9,9999);

$box_bg_color = (!empty($box_bg_color)) ? 'color:'.$box_bg_color . '; ' : '';
$textALign = 'text-align: ' . $counter_text_align . ';';
$numberFS = 'font-size: ' . $stm_counter_value_font_size . 'px;';
$labelFS = 'font-size: ' . $stm_counter_label_font_size . 'px;';


?>

<?php if(!empty($stm_counter_value)): ?>

	<div class="stm-icon-counter" <?php echo esc_attr($css_class); ?>>

		<div class="dp-in">
			<div class="clearfix">
				<?php if(!empty($icon)): ?>
					<div class="stm-icon-counter-left">
						<i class="<?php echo esc_attr($icon); ?>" style="<?php echo esc_attr($box_bg_color); ?>"></i>
					</div>
				<?php endif; ?>

				<div class="stm-counter-meta heading-font" style="<?php echo esc_attr($box_bg_color . $textALign); ?>">
					<div class="stm-value-wrapper">
						<div class="stm-value" id="counter_<?php echo esc_attr( $id ); ?>" style="<?php echo esc_attr($numberFS); ?>"></div>
						<?php if(!empty($stm_counter_affix)): ?>
							<div class="stm-value-affix"><?php echo esc_attr($stm_counter_affix); ?></div>
						<?php endif; ?>
					</div>
					<?php if(!empty($stm_counter_label)): ?>
						<div class="stm-label" style="<?php echo esc_attr($labelFS); ?>"><?php echo esc_attr($stm_counter_label); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>

	</div>



	<script>
		jQuery(document).ready(function($) {
			var counter_<?php echo esc_attr( $id ); ?> = new countUp("counter_<?php echo esc_attr( $id ); ?>", 0, <?php echo esc_attr( $stm_counter_value ); ?>, 0, <?php echo esc_attr( $stm_counter_time ); ?>, {
				useEasing : true,
				useGrouping: true,
				separator : ''
			});

			$(window).scroll(function(){
				if( $("#counter_<?php echo esc_attr( $id ); ?>").is_on_screen() ){
					counter_<?php echo esc_attr( $id ); ?>.start();
				}
			});
		});
	</script>

<?php endif; ?>
<?php
$badge_text = get_post_meta(get_the_ID(),'badge_text',true);
$special_car = get_post_meta(get_the_ID(),'special_car', true);

if(empty($badge_text)) {
    $badge_text = 'Special';
}

if(!empty($special_car) and $special_car == 'on'):
	$badge_style = '';
	$badge_bg_color = get_post_meta(get_the_ID(),'badge_bg_color',true);
	if(!empty($badge_bg_color)) {
		$badge_style = 'style=background-color:'.$badge_bg_color.';';
	} ?>
	<div class="special-label special-label-small h6" <?php echo esc_attr($badge_style); ?>>
		<?php stm_dynamic_string_translation_e('Special Badge Text', $badge_text ); ?>
	</div>
<?php endif; ?>
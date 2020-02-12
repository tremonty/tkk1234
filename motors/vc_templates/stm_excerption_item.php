<?php
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
?>

<div class="stm_review_excerption">
	<?php echo wpb_js_remove_wpautop($content); ?>
</div>
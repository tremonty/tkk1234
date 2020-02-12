<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

global $wp_embed;
$embed = '';
$video_w = 500;
$video_h = $video_w / 1.61;
if ( is_object( $wp_embed ) ) {
    $embed = $wp_embed->run_shortcode( '[embed width="' . $video_w . '"' . $video_h . ']' . $video_url . '[/embed]' );
}
?>
<a href="#" id="youtube-play-video-wrap">
    <div class="youtube-play-circle" style="background: <?php echo stm_do_lmth($color); ?>">
        <i class="fa fa-play"></i>
    </div>
</a>
<div id="video-popup-wrap" class="video-popup-wrap" style="display: none;">
    <div class="video-popup">
        <div class="wpb_video_wrapper"><?php echo stm_do_lmth($embed); ?></div>
    </div>
</div>
<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
?>

<div class="stm-social-follow-wrap">
    <?php if(!empty($ata_title)) : ?>
    <h2><?php echo esc_html($ata_title); ?></h2>
    <?php endif; ?>
    <?php if(class_exists( 'SC_Class' )): ?>
        <?php echo do_shortcode('[aps-counter theme="theme-2"]'); ?>
    <?php else : ?>
        <?php echo esc_html__('Please install plugin Access Press Social Counter', 'motors'); ?>
    <?php endif; ?>
</div>


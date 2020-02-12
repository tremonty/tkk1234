<?php
$id = get_the_ID();
$body = get_the_terms($id, 'body');
$body = ($body) ? $body[0]->name : '';
$serial = get_post_meta($id, 'serial_number', true);
$reg = get_post_meta($id, 'registration_number', true);
?>

<div class="stm-aircraft-title-box heading-font">
    <div class="left">
        <h1><?php echo stm_generate_title_from_slugs( get_the_ID(), get_theme_mod( 'show_generated_title_as_label', false ) ); ?></h1>
        <div class="stm-ac-category">
            <?php echo esc_html__('Type: ', 'motors');?>
            <span><?php echo stm_dynamic_string_translation_e('Category Body Type', $body); ?></span>
        </div>
    </div>
    <div class="right">
        <div class="stm-ac-category">
            <?php echo esc_html__('Serial Number: ', 'motors');?>
            <span><?php echo esc_html($serial); ?></span>
        </div>
        <div class="stm-ac-category">
            <?php echo esc_html__('Reg Number: ', 'motors');?>
            <span><?php echo esc_html($reg); ?></span>
        </div>
    </div>
</div>

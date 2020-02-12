<?php
$policy_page = get_theme_mod('order_received', false);
if(!empty($policy_page)) {
    $policy_page = stm_motors_wpml_is_page($policy_page);
    $page = get_post($policy_page);
    if(!empty($page)) {
        $content = apply_filters('the_content', $page->post_content);
    }
}

if(!empty($content)): ?>
    <div class="stm_policy_content">
        <?php echo stm_do_lmth($content); ?>
    </div>
    <style type="text/css">
        <?php echo get_post_meta( $page->ID, '_wpb_shortcodes_custom_css', true ); ?>
    </style>
<?php endif; ?>
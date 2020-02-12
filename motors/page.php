<?php
if(stm_is_rental()) {
    if(is_checkout() or is_cart()) {
        get_template_part('partials/rental/reservation', 'archive');
        return false;
    }
}

get_header();

if(!stm_is_auto_parts() && !is_front_page()) {
    get_template_part('partials/page_bg');
    get_template_part('partials/title_box');
}

do_action('stm-wcmap-title-box');

//Get compare page
$compare_page = '';
if(!stm_motors_is_unit_test_mod()) {
    $compare_page = get_theme_mod( 'compare_page', 156 );
}

$compare_page = stm_motors_wpml_is_page($compare_page);

if (!empty($compare_page) and get_the_id() == $compare_page): ?>
    <div class="container">
        <?php get_template_part('partials/compare'); ?>
    </div>
<?php else: ?>
    <div class="container">

        <?php if (have_posts()) :
            while (have_posts()) : the_post();
                the_content();
            endwhile;
        endif; ?>

        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'motors') . '</span>',
            'after' => '</div>',
            'link_before' => '<span>',
            'link_after' => '</span>',
            'pagelink' => '<span class="screen-reader-text">' . __('Page', 'motors') . ' </span>%',
            'separator' => '<span class="screen-reader-text">, </span>',
        ));
        ?>

        <div class="clearfix">
            <?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
        </div>
    </div>
<?php endif; ?>
<?php
get_footer();
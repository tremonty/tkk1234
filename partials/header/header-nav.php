<?php

if ( empty( $_COOKIE['compare_ids'] ) ) {
    $_COOKIE['compare_ids'] = array();
}
$compare_page = get_theme_mod( 'compare_page', 156 );
$showCompare = get_theme_mod('header_compare_show', true);

//Get archive shop page id
if ( function_exists( 'WC' ) ) {
    $woocommerce_shop_page_id = wc_get_cart_url();
}

//Get page option
$transparent_header = get_post_meta( get_the_id(), 'transparent_header', true );
$transparent_header_class = 'header-nav-default';

if ( !empty( $transparent_header ) and $transparent_header == 'on' ) {
    $transparent_header_class = 'header-nav-transparent';
} else {
    $transparent_header_class = 'header-nav-default';
}

$fixed_header = get_theme_mod( 'header_sticky', true );
if ( !empty( $fixed_header ) and $fixed_header ) {
    $fixed_header_class = 'header-nav-fixed';
} else {
    $fixed_header_class = '';
}
?>

<div id="header-nav-holder" class="hidden-sm hidden-xs">
    <div class="header-nav <?php echo esc_attr( $transparent_header_class . ' ' . $fixed_header_class ); ?>">
        <div class="container">
            <div class="header-help-bar-trigger">
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="header-help-bar">
                <ul>
                    <?php if ( !empty( $compare_page ) && $showCompare ): ?>
                        <li class="help-bar-compare">
                            <a
                                    href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"
                                    title="<?php esc_attr_e( 'Watch compared', 'motors' ); ?>">
                                <span class="list-label heading-font"><?php esc_html_e( 'Compare', 'motors' ); ?></span>
                                <i class="list-icon stm-icon-speedometr2"></i>
                                <span class="list-badge"><span class="stm-current-cars-in-compare"
                                                               data-contains="compare-count"></span></span>
                            </a>
                        </li>
                    <?php endif; ?>


                    <?php if ( !empty( $woocommerce_shop_page_id ) && !stm_is_listing_four() ): ?>
                        <?php $items = WC()->cart->cart_contents_count; ?>
                        <!--Shop archive-->
                        <li class="help-bar-shop">
                            <a
                                    href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>"
                                    title="<?php esc_attr_e( 'Watch shop items', 'motors' ); ?>"
                            >
                                <span class="list-label heading-font"><?php esc_html_e( 'Cart', 'motors' ); ?></span>
                                <i class="list-icon stm-icon-shop_bag"></i>
                                <span class="list-badge"><span
                                            class="stm-current-items-in-cart"><?php if ( $items != 0 ) {
                                            echo esc_attr( $items );
                                        } ?></span></span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ( stm_is_listing_four() ) : ?>
                        <?php
                        $header_listing_btn_link = get_theme_mod('header_listing_btn_link', '/add-a-car');
                        $header_listing_btn_text = get_theme_mod('header_listing_btn_text', esc_html__('Add your item', 'motors'));
                        ?>
                        <?php if(!empty($header_listing_btn_link) and !empty($header_listing_btn_text)): ?>
                        <li>
                            <a href="<?php echo esc_url($header_listing_btn_link); ?>" class="listing_add_cart heading-font">
                                    <span class="list-label heading-font">
                                        <?php stm_dynamic_string_translation_e('Listing Button Text', $header_listing_btn_text); ?>
                                    </span>
                                    <i class="<?php echo 'stm-service-icon-listing_car_plus'; ?>"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <div class="lOffer-account-unit">
                                <a href="<?php echo esc_url( stm_get_author_link( 'register' ) ); ?>"
                                   class="lOffer-account">
                                    <?php
                                    if ( is_user_logged_in() ): $user_fields = stm_get_user_custom_fields( '' );
                                        if ( !empty( $user_fields['image'] ) ):
                                            ?>
                                            <div class="stm-dropdown-user-small-avatar">
                                                <img src="<?php echo esc_url( $user_fields['image'] ); ?>"
                                                     class="im-responsive"/>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <i class="stm-service-icon-user"></i>
                                </a>
                                <?php get_template_part( 'partials/user/user', 'dropdown' ); ?>
                                <?php get_template_part( 'partials/user/private/mobile/user' ); ?>
                            </div>
                        </li>
                    <?php endif; ?>

                    <!--Live chat-->
                    <li class="help-bar-live-chat">
                        <a
                                id="chat-widget"
                                title="<?php esc_attr_e( 'Open Live Chat', 'motors' ); ?>"
                        >
                            <span class="list-label heading-font"><?php esc_html_e( 'Live chat', 'motors' ); ?></span>
                            <i class="list-icon stm-icon-chat2"></i>
                        </a>
                    </li>

                    <?php if(!stm_is_listing_four()): ?>
                    <li class="nav-search">
                        <a href="" data-toggle="modal" data-target="#searchModal"><i class="stm-icon-search"></i></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="main-menu">
                <ul class="header-menu clearfix">
                    <?php
                    $location = ( has_nav_menu( 'primary' ) ) ? 'primary' : '';

                    wp_nav_menu( array(
                        'menu' => $location,
                        'theme_location' => $location,
                        'depth' => 5,
                        'container' => false,
                        'menu_class' => 'header-menu clearfix',
                        'items_wrap' => '%3$s',
                        'fallback_cb' => false
                    ) ); ?>
                </ul>
            </div>
        </div>
    </div>
</div>
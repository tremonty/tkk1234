<?php $logo_main = get_theme_mod( 'logo', get_template_directory_uri() . '/assets/images/tmp/air_craft_logo.svg' );

$fixed_header = get_theme_mod( 'header_sticky', true );
if ( !empty( $fixed_header ) and $fixed_header ) {
    $fixed_header_class = 'header-listing-fixed';
} else {
    $fixed_header_class = '';
}

//Get archive shop page id
if ( function_exists( 'WC' ) ) {
    $woocommerce_shop_page_id = wc_get_cart_url();
}

$transparent_header = get_post_meta( get_the_id(), 'transparent_header', true );

if ( empty( $transparent_header ) ) {
    $transparent_header_class = 'listing-nontransparent-header';
} else {
    $transparent_header_class = '';
}

if ( empty( $_COOKIE['compare_ids'] ) ) {
    $_COOKIE['compare_ids'] = array();
}

$compare_page = get_theme_mod( 'compare_page', 156 );
$showCompare = get_theme_mod( 'header_compare_show', true );
$shopping_cart_boats = get_theme_mod( 'header_cart_show', true );

$bodyFixed = (is_front_page() || get_the_ID() == get_theme_mod( 'listing_archive', '' )) ? 'stm-body-fixed' : '';

?>

<div class="header-listing <?php echo esc_attr( $fixed_header_class . ' ' . $transparent_header_class ); ?>">

    <div class="container header-inner-content">
        <!--Logo-->
        <div class="listing-logo-main" style="margin-top: <?php echo get_theme_mod( 'menu_top_margin', '4' ); ?>px;">
            <?php if ( empty( $logo_main ) ): ?>
                <a class="blogname" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                   title="<?php _e( 'Home', 'motors' ); ?>">
                    <h1><?php echo esc_attr( get_bloginfo( 'name' ) ) ?></h1>
                </a>
            <?php else: ?>
                <a class="bloglogo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img
                            src="<?php echo esc_url( $logo_main ); ?>"
                            style="width: <?php echo get_theme_mod( 'logo_width', '160' ); ?>px;"
                            title="<?php esc_attr_e( 'Home', 'motors' ); ?>"
                            alt="<?php esc_attr_e( 'Logo', 'motors' ); ?>"
                    />
                </a>
            <?php endif; ?>
        </div>

        <div class="listing-service-right clearfix">

            <div class="listing-right-actions">

                <?php if ( $shopping_cart_boats ): ?>
                    <div class="pull-right">
                        <?php if ( !empty( $woocommerce_shop_page_id ) ): ?>
                            <?php $items = WC()->cart->cart_contents_count; ?>
                            <!--Shop archive-->
                            <div class="help-bar-shop">
                                <a
                                        href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>"
                                        title="<?php esc_attr_e( 'Watch shop items', 'motors' ); ?>"
                                >
                                    <i class="stm-boats-icon-cart"></i>
                                    <span class="list-badge"><span
                                                class="stm-current-items-in-cart"><?php echo esc_attr( $items ); ?></span></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( !empty( $compare_page ) && $showCompare ): ?>
                    <div class="pull-right">
                        <a
                                class="lOffer-compare"
                                href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"
                                title="<?php esc_attr_e( 'Watch compared', 'motors' ); ?>">
                            <span class="heading-font"><?php esc_html_e( 'Compare', 'motors' ); ?></span>
                            <i class="list-icon stm-boats-icon-compare-boats"></i>
                            <span class="list-badge"><span
                                        class="stm-current-cars-in-compare"><?php if ( !empty( $_COOKIE['compare_ids'] ) and count( $_COOKIE['compare_ids'] ) ) {
                                        echo esc_attr( count( $_COOKIE['compare_ids'] ) );
                                    } ?></span></span>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( wp_is_mobile() ): ?>
                    <div class="listing-menu-mobile-wrapper">
                        <div class="stm-menu-trigger <?php echo esc_attr($bodyFixed);?>">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="stm-opened-menu-listing">
                            <ul class="listing-menu-mobile heading-font visible-xs visible-sm clearfix">
                                <?php
                                wp_nav_menu( array(
                                        'menu' => 'primary',
                                        'theme_location' => 'primary',
                                        'depth' => 3,
                                        'container' => false,
                                        'menu_class' => 'service-header-menu clearfix',
                                        'items_wrap' => '%3$s',
                                        'fallback_cb' => false
                                    )
                                );
                                ?>

                                <?php if ( !empty( $compare_page ) && get_theme_mod( 'header_compare_show', true ) ): ?>
                                    <li class="stm_compare_mobile"><a
                                                href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>"><?php _e( 'Compare', 'motors' ); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <?php get_template_part( 'partials/top', 'bar' ); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <ul class="listing-menu clearfix">
                <?php
                wp_nav_menu( array(
                        'menu' => 'primary',
                        'theme_location' => 'primary',
                        'depth' => 3,
                        'container' => false,
                        'menu_class' => 'service-header-menu clearfix',
                        'items_wrap' => '%3$s',
                        'fallback_cb' => false
                    )
                );
                ?>
            </ul>
        </div>
    </div>
</div>

<?php
if(is_single() && stm_listings_post_type() == get_post_type(get_the_ID())) {
    get_template_part( 'partials/single-aircrafts/header-search' );
}
?>

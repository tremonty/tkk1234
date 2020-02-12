<?php

function motors_layout_plugins($layout, $get_layouts = false)
{
    $required = array(
        'stm-post-type',
        'stm-motors-extends',
        'custom_icons_by_stylemixthemes',
        'stm_importer',
        'js_composer',
        'revslider',
        'add-to-any',
        'breadcrumb-navxt',
        'contact-form-7',
        'mailchimp-for-wp',
    );

    $plugins = array(
        'car_magazine' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'accesspress-social-counter',
            'stm_motors_events',
            'stm_motors_review'
        ),
        'service' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'bookly-responsive-appointment-booking-tool',
        ),
        'listing' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
            'woocommerce'
        ),
        'listing_two' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
            'woocommerce',
            'stm_motors_review'
        ),
        'listing_three' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
            'woocommerce',
            'stm_motors_review'
        ),
        'listing_four' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'subscriptio',
            'wordpress-social-login',
            'woocommerce'
        ),
        'car_dealer' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'woocommerce',
        ),
        'car_dealer_two' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'woocommerce',
        ),
        'motorcycle' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'woocommerce',
        ),
        'boats' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'woocommerce',
        ),
        'car_rental' => array(
            'stm_vehicles_listing',
            'instagram-feed',
            'woocommerce',
        ),
        'auto_parts' => array(
            'stm-woocommerce-motors-auto-parts',
            'pearl-header-builder',
            'woo-multi-currency',
            'yith-woocommerce-compare',
            'yith-woocommerce-wishlist',
            'woocommerce',
        ),
        'aircrafts' => array(
            'stm_vehicles_listing',
            'woocommerce'
        ),
    );

    if ($get_layouts) return $plugins;

    return array_merge($required, $plugins[$layout]);
}
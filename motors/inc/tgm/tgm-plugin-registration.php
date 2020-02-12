<?php

require_once dirname(__FILE__) . '/tgm-plugin-activation.php';

add_action('tgmpa_register', 'stm_require_plugins_popup');

function stm_require_plugins_popup($return = false)
{
    $plugins_path = get_template_directory() . '/inc/tgm/plugins';
    $plugins = array(
		'stm-post-type' => array(
            'name' => 'STM Post Type',
            'slug' => 'stm-post-type',
            'icon' => 'stm-configurations',
            'source' => get_package( 'stm-post-type', 'zip' ),
            'required' => true,
            'version' => '4.3'
        ),
        'stm-motors-extends' => array(
            'name' => 'STM Motors Extends',
            'slug' => 'stm-motors-extends',
            'icon' => 'stm-configurations',
            'source' => get_package( 'stm-motors-extends', 'zip' ),
            'required' => true,
            'version' => '1.3.3'
        ),
        'stm-gdpr-compliance' => array(
            'name' => 'GDPR Compliance & Cookie Consent',
            'slug' => 'stm-gdpr-compliance',
            'icon' => 'stm-configurations',
            'source' => get_package( 'stm-gdpr-compliance', 'zip' ),
            'required' => false,
            'version' => '1.0'
        ),
        'stm_vehicles_listing' => array(
            'name' => 'Motors - Classified Listings',
            'slug' => 'stm_vehicles_listing',
            'icon' => 'default',
            'source' => get_package( 'stm_vehicles_listing', 'zip' ),
            'required' => true,
            'version' => '6.5.7'
        ),
        'custom_icons_by_stylemixthemes' => array(
            'name' => 'Custom Icons by Stylemixthemes',
            'slug' => 'custom_icons_by_stylemixthemes',
            'icon' => 'default',
            'source' => get_package( 'custom_icons_by_stylemixthemes', 'zip' ),
            'required' => true,
            'version' => '1.5'
        ),
        'stm_importer' => array(
            'name' => 'STM Importer',
            'slug' => 'stm_importer',
            'icon' => 'stm-configurations',
            'source' => get_package( 'stm_importer', 'zip' ),
            'required' => true,
            'version' => '4.5.6'
        ),
        'js_composer' => array(
            'name' => 'WPBakery Visual Composer',
            'slug' => 'js_composer',
            'icon' => 'js_composer',
            'source' => get_package( 'js_composer', 'zip' ),
            'required' => true,
            'version' => '6.0.5',
            'external_url' => 'http://vc.wpbakery.com'
        ),
        'revslider' => array(
            'name' => 'Revolution Slider',
            'slug' => 'revslider',
            'icon' => 'revslider',
            'source' => get_package( 'revslider', 'zip' ),
            'required' => false,
            'version' => '6.0.9',
            'external_url' => 'http://www.themepunch.com/revolution/'
        ),
        'add-to-any' => array(
            'name' => 'AddToAny Share Buttons',
            'slug' => 'add-to-any',
            'icon' => 'add-to-any',
            'required' => false,
            'force_activation' => false,
        ),
        'breadcrumb-navxt' => array(
            'name' => 'Breadcrumb NavXT',
            'slug' => 'breadcrumb-navxt',
            'icon' => 'breadcrumb-navxt',
            'required' => false,
            'force_activation' => false,
        ),
        'contact-form-7' => array(
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'icon' => 'contact-form-7',
            'required' => false,
            'force_activation' => false,
        ),
        'instagram-feed' => array(
            'name' => 'Instagram Feed',
            'slug' => 'instagram-feed',
            'icon' => 'instagram-feed',
            'required' => false,
            'external_url' => 'http://smashballoon.com/instagram-feed/'
        ),
        'mailchimp-for-wp' => array(
            'name' => 'MailChimp for WordPress',
            'slug' => 'mailchimp-for-wp',
            'icon' => 'mailchimp-for-wp',
            'required' => false,
            'external_url' => 'https://mc4wp.com/'
        ),
        'accesspress-social-counter' => array(
            'name' => 'AccessPress Social Counter',
            'slug' => 'accesspress-social-counter',
            'icon' => 'accesspress-social-counter',
            'required' => true,
            'external_url' => 'http://accesspressthemes.com'
        ),
        'stm_motors_events' => array(
            'name' => 'Motors - events',
            'slug' => 'stm_motors_events',
            'icon' => 'default',
            'source' => get_package( 'stm_motors_events', 'zip' ),
            'required' => true,
            'version' => '1.2'
        ),
        'stm_motors_review' => array(
            'name' => 'Motors - review',
            'slug' => 'stm_motors_review',
            'icon' => 'default',
            'source' => get_package( 'stm_motors_review', 'zip' ),
            'required' => true,
            'version' => '1.3'
        ),
        'woocommerce' => array(
            'name' => 'Woocommerce',
            'slug' => 'woocommerce',
            'icon' => 'woocommerce',
            'required' => false,
            'force_activation' => false,
        ),
        'pearl-header-builder' => array(
            'name' => 'Pearl Header Builder',
            'slug' => 'pearl-header-builder',
            'icon' => 'pearl-header-builder',
            'required' => false,
            'force_activation' => false,
        ),
        'stm-woocommerce-motors-auto-parts' => array(
            'name' => 'STM Woocommerce Motors Auto Parts',
            'slug' => 'stm-woocommerce-motors-auto-parts',
            'icon' => 'default',
            'source' => get_package( 'stm-woocommerce-motors-auto-parts', 'zip' ),
            'required' => false,
            'force_activation' => false,
            'version' => '1.0.3',
        ),
        'woo-multi-currency' => array(
            'name' => 'Multi Currency for WooCommerce',
            'slug' => 'woo-multi-currency',
            'icon' => 'woo-multi-currency',
            'required' => false,
            'force_activation' => false,
        ),
        'yith-woocommerce-wishlist' => array(
            'name' => 'YITH WooCommerce Wishlist',
            'slug' => 'yith-woocommerce-wishlist',
            'icon' => 'yith-woocommerce-wishlist',
            'required' => false,
            'force_activation' => false,
        ),
        'yith-woocommerce-compare' => array(
            'name' => 'YITH WooCommerce Compare',
            'slug' => 'yith-woocommerce-compare',
            'icon' => 'yith-woocommerce-compare',
            'required' => false,
            'force_activation' => false,
        ),
        'bookly-responsive-appointment-booking-tool' => array(
            'name' => 'Bookly Lite',
            'slug' => 'bookly-responsive-appointment-booking-tool',
            'icon' => 'bookly-responsive-appointment-booking-tool',
            'required' => false,
            'force_activation' => false,
        ),
        'subscriptio' => array(
            'name' => 'Subscriptio',
            'slug' => 'subscriptio',
            'icon' => 'subscriptio',
            'source' => get_package( 'subscriptio', 'zip' ),
            'version' => '2.3.8',
            'required' => true,
        ),
        'wordpress-social-login' => array(
            'name' => 'WordPress Social Login',
            'slug' => 'wordpress-social-login',
            'icon' => 'wordpress-social-login',
            'required' => true,
        ),
    );

    if($return) {
        return $plugins;
    } else {
        $config = array(
            'id' => 'tgm_message_update_new3r',
            'is_automatic' => false,
            'strings' => array(
                'nag_type' => 'update-nag'
            )
        );

        $tgm_layout_plugins = $plugins;

        if(!empty(get_option('stm_motors_chosen_template'))) {
            $layout_plugins = motors_layout_plugins( get_option( 'stm_motors_chosen_template' ) );

            $tgm_layout_plugins = array();
            foreach ( $layout_plugins as $layout_plugin ) {
                $tgm_layout_plugins[$layout_plugin] = $plugins[$layout_plugin];
            }
        }

        tgmpa($tgm_layout_plugins, $config);
    }
}
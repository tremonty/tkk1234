<?php

$theme_info = wp_get_theme();
define( 'STM_THEME_VERSION', ( WP_DEBUG ) ? time() : $theme_info->get( 'Version' ) );

if ( !is_admin() ) {
    add_action( 'wp_enqueue_scripts', 'stm_load_theme_ss' );
}

function stm_load_theme_ss()
{

    $directoryStylesheet = get_template_directory_uri();

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-effects-slide');
    wp_enqueue_script('jquery-ui-droppable');

    $jquery = array( 'jquery' );

    // Styles
    //Fonts
    $typography_body_font_family = get_theme_mod( 'typography_body_font_family' );
    $typography_heading_font_family = get_theme_mod( 'typography_heading_font_family' );

    $layout = stm_get_current_layout();
    $upload_dir = wp_upload_dir();
    $stm_upload_dir = $upload_dir['baseurl'] . '/stm_uploads';

    //Main font if user hasn't chosen anything
    if ( empty( $typography_body_font_family ) or empty( $typography_heading_font_family ) ) {
        wp_enqueue_style( 'stm_default_google_font', stm_default_google_fonts_enqueue(), null, STM_THEME_VERSION, 'all' );
    }

    wp_enqueue_style( 'boostrap', get_theme_file_uri( '/assets/css/bootstrap.min.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/css/font-awesome.min.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-select2', get_theme_file_uri( '/assets/css/select2.min.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-datetimepicker', get_theme_file_uri( '/assets/css/jquery.stmdatetimepicker.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-jquery-ui-css', get_theme_file_uri( '/assets/css/jquery-ui.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style('light-gallery', get_theme_file_uri( '/assets/css/lightgallery.min.css' ), array(), STM_THEME_VERSION, 'all');

    if(!stm_is_use_plugin('custom_icons_by_stylemixthemes/stm-custom-icons.php')) {
        wp_enqueue_style( 'stm-theme-default-icons', get_theme_file_uri( '/assets/fonts/default-icon-font/stm-icon.css' ), null, STM_THEME_VERSION, 'all' );
    }

    if(stm_motors_is_unit_test_mod()) {
        wp_enqueue_style( 'stm-unit-test-styles', $directoryStylesheet . '/assets/css/unit-test-styles.css', null, STM_THEME_VERSION, 'all' );
    }

    wp_enqueue_style( 'stm-theme-service-icons', get_theme_file_uri( '/assets/css/service-icons.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-theme-boat-icons', get_theme_file_uri( '/assets/css/boat-icons.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-theme-moto-icons', get_theme_file_uri( '/assets/css/motorcycle/icons.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-theme-rental-icons', get_theme_file_uri( '/assets/css/rental/icons.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-theme-magazine-icons', get_theme_file_uri( '/assets/css/magazine/magazine-icon-style.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-theme-listing-two-icons', get_theme_file_uri( '/assets/css/listing_two/icons.css' ), null, STM_THEME_VERSION, 'all' );
    wp_enqueue_style( 'stm-theme-auto-parts-icons', get_theme_file_uri( '/assets/css/auto-parts/style.css' ), null, STM_THEME_VERSION, 'all' );

    if ( get_theme_mod( 'site_style', 'site_style_default' ) != 'site_style_default' and is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
        wp_enqueue_style( 'stm-skin-custom', $stm_upload_dir . '/skin-custom.css', null, get_option( 'stm_custom_style', '4' ), 'all' );

        if ( is_listing() ) {
            wp_enqueue_style( 'stm-custom-scrollbar', get_theme_file_uri( '/assets/css/listing/jquery.mCustomScrollbar.css' ), null, STM_THEME_VERSION, 'all' );
        }

    } else {
        if ( file_exists( get_theme_file_path( '/assets/css/app-' . $layout . '.css' ) )) {
            wp_enqueue_style( 'stm-theme-style-sass', get_theme_file_uri( '/assets/css/app.css' ), null, STM_THEME_VERSION, 'all' );
            wp_enqueue_style( 'stm-theme-style-aircrafts-sass', get_theme_file_uri( '/assets/css/app-' . $layout . '.css' ), null, STM_THEME_VERSION, 'all' );
        } else {
            if ( $layout == 'boats' ) {
                wp_enqueue_style( 'stm-theme-style-boats', get_theme_file_uri( '/assets/css/boats/app.css' ), null, STM_THEME_VERSION, 'all' );
            } elseif ( $layout == 'motorcycle' ) {
                wp_enqueue_style( 'stm-theme-style-sass', get_theme_file_uri( '/assets/css/motorcycle/app.css' ), null, STM_THEME_VERSION, 'all' );
            } elseif ( stm_is_auto_parts() ) {
                wp_enqueue_style( 'stm-theme-style-ap-sass', get_theme_file_uri( '/assets/css/auto-parts/app.css' ), null, STM_THEME_VERSION, 'all' );
            } else {
                wp_enqueue_style( 'stm-theme-style-sass', get_theme_file_uri( '/assets/css/app.css' ), null, STM_THEME_VERSION, 'all' );

                if ( is_listing() ) {
                    if ( stm_is_listing_four() ) {
                        wp_enqueue_style( 'stm-theme-style-listing-four-sass', get_theme_file_uri( '/assets/css/listing_four/app.css' ), null, STM_THEME_VERSION, 'all' );
                    } else {
                        wp_enqueue_style( 'stm-theme-style-listing-sass', get_theme_file_uri( '/assets/css/listing/app.css' ), null, STM_THEME_VERSION, 'all' );
                        if ( stm_is_listing_two() ) wp_enqueue_style( 'stm-theme-style-listing-two-sass', get_theme_file_uri( '/assets/css/listing_two/app.css' ), null, STM_THEME_VERSION, 'all' );
                        if ( stm_is_listing_three() ) wp_enqueue_style( 'stm-theme-style-listing-three-sass', get_theme_file_uri( '/assets/css/listing_three/app.css' ), null, STM_THEME_VERSION, 'all' );
                    }
                    wp_enqueue_style( 'stm-custom-scrollbar', get_theme_file_uri( '/assets/css/listing/jquery.mCustomScrollbar.css' ), null, STM_THEME_VERSION, 'all' );
                } elseif ( $layout == 'car_magazine' ) {
                    wp_enqueue_style( 'stm-theme-style-magazine-sass', get_theme_file_uri( '/assets/css/magazine/app.css' ), null, STM_THEME_VERSION, 'all' );
                } elseif ( $layout == 'car_dealer_two' ) {
                    wp_enqueue_style( 'stm-theme-style-dealer-two-sass', get_theme_file_uri( '/assets/css/dealer_two/app.css' ), null, STM_THEME_VERSION, 'all' );
                }
            }

            if ( stm_is_rental() ) {
                wp_enqueue_style( 'stm-theme-style-rental', get_theme_file_uri( '/assets/css/rental/app.css' ), null, STM_THEME_VERSION, 'all' );
            }
        }
    }

    wp_enqueue_style( 'stm-theme-frontend-customizer', get_theme_file_uri( '/assets/css/frontend_customizer.css' ), null, STM_THEME_VERSION, 'all' );

    // Animations
    wp_enqueue_style( 'stm-theme-style-animation', get_theme_file_uri( '/assets/css/animation.css' ), null, STM_THEME_VERSION, 'all' );

    if ( get_theme_mod( 'site_style' ) && get_theme_mod( 'site_style' ) != 'site_style_default' && get_theme_mod( 'site_style' ) != 'site_style_custom' ) {
        wp_enqueue_style( STM_THEME_SLUG . '-' . get_theme_mod( 'site_style' ) );
    }

    // Theme main stylesheet
    wp_enqueue_style( 'stm-theme-style', $directoryStylesheet . '/style.css', null, STM_THEME_VERSION, 'all' );


    // Scripts
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    $google_api_key = get_theme_mod( 'google_api_key', '' );
    $google_marker_cluster = 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js';
    if ( !empty( $google_api_key ) ) {
        $google_api_map = 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&';
    } else {
        $google_api_map = 'https://maps.googleapis.com/maps/api/js?';
    }

    if ( stm_is_boats() || stm_is_dealer_two() or is_listing() || stm_is_car_dealer() ) {

        $google_api_map .= 'libraries=places';
    }

    $callback = '';

    if ( stm_is_use_plugin( 'stm_motors_events/stm_motors_events.php' ) ) {
        $callback = '&callback=initGoogleScripts';
    }

    wp_register_script( 'stm_marker_cluster', $google_marker_cluster, $jquery, STM_THEME_VERSION, true );
    wp_register_script( 'stm_gmap', $google_api_map . $callback . '&language=' . get_bloginfo( 'language' ), $jquery, STM_THEME_VERSION, true );
    wp_register_script( 'custom_scrollbar', get_theme_file_uri( '/assets/js/jquery.mCustomScrollbar.concat.min.js' ), $jquery, STM_THEME_VERSION, true );

    wp_register_script( 'stm_grecaptcha', 'https://www.google.com/recaptcha/api.js?onload=stmMotorsCaptcha&render=explicit', $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'stm-classie', get_theme_file_uri( '/assets/js/classie.js' ), $jquery, STM_THEME_VERSION, false );
    wp_enqueue_script( 'stm-jquerymigrate', get_theme_file_uri( '/assets/js/jquery-migrate-1.2.1.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'bootstrap', get_theme_file_uri( '/assets/js/bootstrap.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'isotope', get_theme_file_uri( '/assets/js/isotope.pkgd.min.js' ), array( 'jquery', 'imagesloaded' ), STM_THEME_VERSION, true );
    wp_enqueue_script( 'lazyload', get_theme_file_uri( '/assets/js/jquery.lazyload.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'stm-jquery-touch-punch', get_theme_file_uri( '/assets/js/jquery.touch.punch.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'stm-select2-js', get_theme_file_uri( '/assets/js/select2.full.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'uniform-js', get_theme_file_uri( '/assets/js/jquery.uniform.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'stm-datetimepicker-js', get_theme_file_uri( '/assets/js/stm_dt_picker.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script( 'vivus', get_theme_file_uri( '/assets/js/vivus.min.js' ), $jquery, STM_THEME_VERSION, false );
    wp_enqueue_script( 'jquery-cookie', get_theme_file_uri( '/assets/js/jquery.cookie.js' ), $jquery, STM_THEME_VERSION, false );
    wp_enqueue_script( 'typeahead', get_theme_file_uri( '/assets/js/typeahead.jquery.min.js' ), $jquery, STM_THEME_VERSION, true );
    wp_enqueue_script('light-gallery', get_theme_file_uri( '/assets/js/lightgallery-all.js' ), array('jquery'), STM_THEME_VERSION, true);
    wp_enqueue_script('lg-video', get_theme_file_uri( '/assets/js/lg-video.js' ), array('jquery'), STM_THEME_VERSION, true);

    if ( !stm_is_auto_parts() ) {
        if ( file_exists( get_theme_file_path( '/assets/js/app-' . $layout . '.js' ) ) ) {
            wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app-' . $layout . '.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
        } else {
            wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
        }
    } else {
        wp_enqueue_script( 'stm-theme-scripts', get_theme_file_uri( '/assets/js/app-auto-parts.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
    }

    if ( stm_is_magazine() ) wp_enqueue_script( 'stm-magazine-theme-scripts', get_theme_file_uri( '/assets/js/magazine_scripts.js' ), $jquery, STM_THEME_VERSION, true );

    wp_add_inline_script( 'stm-theme-scripts', get_theme_mod( 'footer_custom_scripts', '' ) );

    wp_register_script( 'stm-countUp.min.js', get_theme_file_uri( '/assets/js/countUp.min.js' ), $jquery, STM_THEME_VERSION, true );

    //Enable scroll js only if user wants header be fixed
    $fixed_header = get_theme_mod( 'header_sticky', true );
    if ( !empty( $fixed_header ) and $fixed_header ) {
        wp_enqueue_script( 'stm-theme-scripts-header-scroll', get_theme_file_uri( '/assets/js/app-header-scroll.js' ), $jquery, STM_THEME_VERSION, true );
    }

    if ( stm_pricing_enabled() ) {
        wp_enqueue_script( 'stm-theme-user-sidebar', get_theme_file_uri( '/assets/js/app-user-sidebar.js' ), $jquery, STM_THEME_VERSION, true );
        wp_enqueue_script( 'jquery.countdown.js', get_theme_file_uri( '/assets/js/jquery.countdown.min.js' ), $jquery, STM_THEME_VERSION, true );
    }

    if ( stm_is_magazine() ) {
        wp_enqueue_script( 'jquery.countdown.js', get_theme_file_uri( '/assets/js/jquery.countdown.min.js' ), $jquery, STM_THEME_VERSION, true );
        wp_enqueue_script( 'vue_min', get_theme_file_uri( '/assets/js/vue.min.js' ), array( 'typeahead' ), STM_THEME_VERSION, false );
        wp_enqueue_script( 'vue_resource', get_theme_file_uri( '/assets/js/vue-resource.js' ), array( 'typeahead' ), STM_THEME_VERSION, false );
        wp_enqueue_script( 'vue_app', get_theme_file_uri( '/assets/js/vue-app.js' ), array( 'typeahead' ), STM_THEME_VERSION, false );
    }

    if ( stm_is_rental() ) {
        wp_enqueue_script( 'moment-localize', get_theme_file_uri( '/assets/js/moment.min.js' ), $jquery, STM_THEME_VERSION, false );
    }

    $smooth_scroll = get_theme_mod( 'smooth_scroll', false );
    if ( !empty( $smooth_scroll ) and $smooth_scroll ) {
        wp_enqueue_script( 'stm-smooth-scroll', get_theme_file_uri( '/assets/js/smoothScroll.js' ), $jquery, STM_THEME_VERSION, true );
    }

    if ( !stm_is_auto_parts() ) {
        wp_enqueue_script( 'stm-theme-scripts-ajax', get_theme_file_uri( '/assets/js/app-ajax.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
    }

    wp_enqueue_script( 'stm-load-image', get_theme_file_uri( '/assets/js/load-image.all.min.js' ), array(), STM_THEME_VERSION, true );

    if ( !stm_is_auto_parts() ) {
        wp_enqueue_script( 'stm-theme-sell-a-car', get_theme_file_uri( '/assets/js/sell-a-car.js' ), array( 'jquery', 'stm-load-image' ), STM_THEME_VERSION, true );
        wp_enqueue_script( 'stm-theme-script-filter', get_theme_file_uri( '/assets/js/filter.js' ), array( 'jquery' ), STM_THEME_VERSION, true );
    }

    if ( stm_is_boats() || stm_is_dealer_two() || is_listing() || stm_is_car_dealer() ) {
        wp_enqueue_script( 'stm_marker_cluster' );

        $handle = 'stm_gmap';
        $list = 'enqueued';
        if ( wp_script_is( $handle, $list ) ) {
        } else {
            wp_enqueue_script( 'stm_gmap' );
        }

        wp_enqueue_script( 'stm-google-places', get_theme_file_uri( '/assets/js/stm-google-places.js' ), 'stm_gmap', STM_THEME_VERSION, true );
        wp_enqueue_script( 'custom_scrollbar' );
        wp_enqueue_script( 'stm-cascadingdropdown', get_theme_file_uri( '/assets/js/jquery.cascadingdropdown.js' ), $jquery, STM_THEME_VERSION );
    } elseif ( stm_is_magazine() ) {
        wp_enqueue_script( 'stm_gmap' );
        wp_enqueue_script( 'stm-google-places', get_theme_file_uri( '/assets/js/stm-google-places.js' ), 'stm_gmap', STM_THEME_VERSION, true );
    }

    wp_localize_script( 'stm-theme-scripts', 'stm_i18n', array(
        'remove_from_compare' => __( 'Remove from compare', 'motors' ),
        'remove_from_favorites' => __( 'Remove from favorites', 'motors' ),
    ) );
}

// Admin styles
function stm_admin_styles()
{

    if(stm_motors_is_unit_test_mod()) {
        wp_enqueue_style( 'stm-editor-font', stm_default_google_fonts_enqueue(), null, STM_THEME_VERSION, 'all' );
        wp_enqueue_style( 'stm-theme-default-admin-icons', get_theme_file_uri( '/assets/fonts/default-icon-font/stm-icon.css' ), null, STM_THEME_VERSION, 'all' );
    }

    wp_enqueue_style( 'stm-theme-admin-service-icons', get_theme_file_uri( '/assets/css/service-icons.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-admin-styles', get_theme_file_uri( '/assets/css/admin.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-fonticonpicker-css', get_theme_file_uri( '/assets/css/jquery.fonticonpicker.min.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_script( 'stm-theme-fonticonpicker', get_theme_file_uri( '/assets/js/jquery.fonticonpicker.min.js' ), 'jquery', STM_THEME_VERSION, true );

    wp_enqueue_style( 'stm-theme-service-icons', get_theme_file_uri( '/assets/css/service-icons.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-boats-icons', get_theme_file_uri( '/assets/css/boat-icons.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-moto-icons', get_theme_file_uri( '/assets/css/motorcycle/icons.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-rental-icons', get_theme_file_uri( '/assets/css/rental/icons.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-magazine-icons', get_theme_file_uri( '/assets/css/magazine/magazine-icon-style.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-lisitng-two-icons', get_theme_file_uri( '/assets/css/listing_two/icons.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-auto-parts-icons', get_theme_file_uri( '/assets/css/auto-parts/style.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-etm-style', get_theme_file_uri( '/inc/email_template_manager/assets/css/etm-style.css' ), null, STM_THEME_VERSION, 'all' );

    wp_enqueue_style( 'stm-theme-gutenberg-style', get_theme_file_uri( '/assets/css/admin-gutenberg-styles.css' ), null, STM_THEME_VERSION, 'all' );
}

add_action( 'admin_enqueue_scripts', 'stm_admin_styles' );

if ( !function_exists( 'stm_motors_enqueue_scripts_styles' ) ) {
    function stm_motors_enqueue_scripts_styles( $fileName )
    {
        if ( file_exists( get_theme_file_path( '/assets/css/vc_template_styles/' . $fileName . '.css' ) ) ) {
            wp_enqueue_style( $fileName, get_theme_file_uri( '/assets/css/vc_template_styles/' . $fileName . '.css' ), null, STM_THEME_VERSION, 'all' );
        }

        if ( file_exists( get_theme_file_path( '/assets/js/vc_template_scripts/' . $fileName . '.js' ) ) ) {
            wp_enqueue_script( $fileName, get_theme_file_uri( '/assets/js/vc_template_scripts/' . $fileName . '.js' ), 'jquery', STM_THEME_VERSION, true );
        }
    }
}

// Default Google fonts enqueue
if ( !function_exists( 'stm_default_google_fonts_enqueue' ) ) {
    function stm_default_google_fonts_enqueue()
    {
        $fonts_url = '';

        if ( stm_is_motorcycle() ) {
            $montserrat = _x( 'on', 'Exo 2 font: on or off', 'motors' );
        } else {
            $montserrat = _x( 'on', 'Montserrat font: on or off', 'motors' );
        }
        $open_sans = _x( 'on', 'Open Sans font: on or off', 'motors' );

        if ( 'off' !== $montserrat || 'off' !== $open_sans ) {
            $font_families = array();

            if ( 'off' !== $montserrat ) {
                if ( stm_is_motorcycle() ) {
                    $font_families[] = 'Exo 2:400,300,500,600,700';
                } else {
                    $font_families[] = 'Montserrat:400,500,600,700';
                }
            }

            if ( 'off' !== $open_sans ) {
                $font_families[] = 'Open Sans:300,400,700';
            }

            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' )
            );

            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return esc_url_raw( $fonts_url );
    }
}
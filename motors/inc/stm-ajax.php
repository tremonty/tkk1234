<?php
//Ajax filter cars remove unfiltered cars
function stm_ajax_filter_remove_hidden()
{

    check_ajax_referer( 'stm_ajax_filter_remove_hidden', 'security' );

    $stm_listing_filter = stm_get_filter();

    $response = array();
    $response['binding'] = $stm_listing_filter['binding'];
    $response['length'] = count( $stm_listing_filter['posts'] );

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_filter_remove_hidden', 'stm_ajax_filter_remove_hidden' );
add_action( 'wp_ajax_nopriv_stm_ajax_filter_remove_hidden', 'stm_ajax_filter_remove_hidden' );

//Ajax add to compare
function stm_ajax_add_to_compare()
{

    check_ajax_referer( 'stm_ajax_add_to_compare', 'security' );

    $response['response'] = '';
    $response['status'] = '';
    $response['empty'] = '';
    $response['empty_table'] = '';
    $response['add_to_text'] = esc_html__( 'Add to compare', 'motors' );
    $response['in_com_text'] = esc_html__( 'In compare list', 'motors' );
    $response['remove_text'] = esc_html__( 'Remove from list', 'motors' );

    if ( empty( $_COOKIE['compare_ids'] ) ) {
        $_COOKIE['compare_ids'] = array();
    }
    if ( !empty( $_POST['post_action'] ) and $_POST['post_action'] == 'remove' ) {
        if ( !empty( $_POST['post_id'] ) ) {
            $new_post = intval( $_POST['post_id'] );
            setcookie( 'compare_ids[' . $new_post . ']', '', time() - 3600, '/' );
            unset( $_COOKIE['compare_ids'][$new_post] );

            $response['status'] = 'success';
            $response['response'] = get_the_title( $_POST['post_id'] ) . ' ' . esc_html__( 'was removed from compare', 'motors' );
        }
    } else {
        if ( !empty( $_POST['post_id'] ) ) {
            $new_post = intval($_POST['post_id']);
            if ( !in_array( $new_post, $_COOKIE['compare_ids'] ) ) {
                if ( count( $_COOKIE['compare_ids'] ) < 3 ) {
                    setcookie( 'compare_ids[' . $new_post . ']', $new_post, time() + ( 86400 * 30 ), '/' );
                    $_COOKIE['compare_ids'][$new_post] = $new_post;
                    $response['status'] = 'success';
                    $response['response'] = get_the_title( $_POST['post_id'] ) . ' - ' . esc_html__( 'Added to compare', 'motors' );
                } else {
                    $response['status'] = 'danger';
                    $response['response'] = esc_html__( 'You have already added', 'motors' ) . ' ' . count( $_COOKIE['compare_ids'] ) . esc_html__( ' cars', 'motors' );
                }
            } else {
                $response['status'] = 'warning';
                $response['response'] = get_the_title( $_POST['post_id'] ) . ' ' . esc_html__( 'has already added', 'motors' );
            }
        }
    }

    $response['length'] = count( $_COOKIE['compare_ids'] );

    $response['ids'] = $_COOKIE['compare_ids'];

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_add_to_compare', 'stm_ajax_add_to_compare' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_to_compare', 'stm_ajax_add_to_compare' );

//Ajax request test drive
function stm_ajax_add_test_drive()
{

    check_ajax_referer( 'stm_ajax_add_test_drive', 'security' );

    $response['errors'] = array();

    if ( !filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
        $response['response'] = esc_html__( 'Please fill all fields', 'motors' );
        $response['errors']['name'] = true;
    }
    if ( !is_email( $_POST['email'] ) ) {
        $response['response'] = esc_html__( 'Please enter correct email', 'motors' );
        $response['errors']['email'] = true;
    }
    if ( !is_numeric( $_POST['phone'] ) ) {
        $response['response'] = esc_html__( 'Please enter correct phone number', 'motors' );
        $response['errors']['phone'] = true;
    }
    if ( empty( $_POST['date'] ) ) {
        $response['response'] = esc_html__( 'Please fill all fields', 'motors' );
        $response['errors']['date'] = true;
    }

    if ( !filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) && !is_email( $_POST['email'] ) && !is_numeric( $_POST['phone'] ) && empty( $_POST['date'] ) ) {
        $response['response'] = esc_html__( 'Please fill all fields', 'motors' );
    }

    $recaptcha = true;

    $recaptcha_enabled = get_theme_mod( 'enable_recaptcha', 0 );
    $recaptcha_public_key = get_theme_mod( 'recaptcha_public_key' );
    $recaptcha_secret_key = get_theme_mod( 'recaptcha_secret_key' );
    if ( !empty( $recaptcha_enabled ) and $recaptcha_enabled and !empty( $recaptcha_public_key ) and !empty( $recaptcha_secret_key ) ) {
        $recaptcha = false;
        if ( !empty( $_POST['g-recaptcha-response'] ) ) {
            $recaptcha = true;
        }
    }

    if ( $recaptcha ) {
        if ( empty( $response['errors'] ) and ( !empty( $_POST['vehicle_id'] ) || !empty( $_POST['vehicle_name'] ) ) ) {
            $vehicle_id = ( isset( $_POST['vehicle_id'] ) && !empty( $_POST['vehicle_id'] ) ) ? intval( $_POST['vehicle_id'] ) : '';
            $title = ( !empty( $vehicle_id ) ) ? get_the_title( $vehicle_id ) : '';
            $title = ( isset( $_POST['vehicle_name'] ) && !empty( $_POST['vehicle_name'] ) ) ? sanitize_text_field( $_POST['vehicle_name'] ) : $title;
            $test_drive['post_title'] = esc_html__( 'New request for test drive', 'motors' ) . ' ' . $title;
            $test_drive['post_type'] = 'test_drive_request';
            $test_drive['post_status'] = 'draft';
            $test_drive_id = wp_insert_post( $test_drive );
            update_post_meta( $test_drive_id, 'name', $_POST['name'] );
            update_post_meta( $test_drive_id, 'email', $_POST['email'] );
            update_post_meta( $test_drive_id, 'phone', $_POST['phone'] );
            update_post_meta( $test_drive_id, 'date', $_POST['date'] );
            $response['response'] = esc_html__( 'Your request was sent', 'motors' );
            $response['status'] = 'success';

            //Sending Mail to admin
            stm_me_set_html_content_type();

            $to = get_bloginfo( 'admin_email' );

            $args = array(
                'car' => $title,
                'name' => sanitize_text_field( $_POST['name'] ),
                'email' => sanitize_email( $_POST['email'] ),
                'phone' => sanitize_text_field( $_POST['phone'] ),
                'best_time' => sanitize_text_field( $_POST['date'] ),
            );

            $subject = generateSubjectView( 'test_drive', $args );
            $body = generateTemplateView( 'test_drive', $args );

            do_action( 'stm_wp_mail', $to, $subject, $body, '' );

            if ( stm_is_listing() ) {
                $car_owner = get_post_meta( $vehicle_id, 'stm_car_user', true );
                if ( !empty( $car_owner ) ) {
                    $user_fields = stm_get_user_custom_fields( $car_owner );
                    if ( !empty( $user_fields ) and !empty( $user_fields['email'] ) ) {
                        do_action( 'stm_wp_mail', $user_fields['email'], $subject, $body, '' );
                    }
                }
            }

            do_action( 'stm_remove_mail_content_type_filter' );

        } else {
            //$response['response'] = esc_html__('Please fill all fields', 'motors');
            $response['status'] = 'danger';
        }

        $response['recaptcha'] = true;
    } else {
        $response['recaptcha'] = false;
        $response['status'] = 'danger';
        $response['response'] = esc_html__( 'Please prove you\'re not a robot', 'motors' );
    }

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_add_test_drive', 'stm_ajax_add_test_drive' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_test_drive', 'stm_ajax_add_test_drive' );

//Ajax request trade offer
function stm_ajax_add_trade_offer()
{

    check_ajax_referer( 'stm_ajax_add_trade_offer', 'security' );

    $response['errors'] = array();

    if ( !filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
        $response['errors']['name'] = true;
    }
    if ( !is_email( $_POST['email'] ) ) {
        $response['errors']['email'] = true;
    }
    if ( !is_numeric( $_POST['phone'] ) ) {
        $response['errors']['phone'] = true;
    }
    if ( !is_numeric( $_POST['trade_price'] ) ) {
        $response['errors']['trade_price'] = true;
    }

    $recaptcha = true;

    $recaptcha_enabled = get_theme_mod( 'enable_recaptcha', 0 );
    $recaptcha_public_key = get_theme_mod( 'recaptcha_public_key' );
    $recaptcha_secret_key = get_theme_mod( 'recaptcha_secret_key' );
    if ( !empty( $recaptcha_enabled ) and $recaptcha_enabled and !empty( $recaptcha_public_key ) and !empty( $recaptcha_secret_key ) ) {
        $recaptcha = false;
        if ( !empty( $_POST['g-recaptcha-response'] ) ) {
            $recaptcha = true;
        }
    }

    if ( $recaptcha ) {
        if ( empty( $response['errors'] ) and !empty( $_POST['vehicle_id'] ) ) {
            $response['response'] = esc_html__( 'Your request was sent', 'motors' );
            $response['status'] = 'success';

            //Sending Mail to admin
            $to = get_bloginfo( 'admin_email' );

            $args = array(
                'car' => get_the_title( $_POST['vehicle_id'] ),
                'name' => sanitize_text_field( $_POST['name'] ),
                'email' => sanitize_email( $_POST['email'] ),
                'phone' => sanitize_text_field( $_POST['phone'] ),
                'price' => intval( $_POST['trade_price'] )
            );

            $subject = generateSubjectView( 'trade_offer', $args );
            $body = generateTemplateView( 'trade_offer', $args );

            do_action( 'stm_wp_mail', $to, $subject, $body, '' );
        } else {
            $response['response'] = esc_html__( 'Please fill all fields', 'motors' );
            $response['status'] = 'danger';
        }

        $response['recaptcha'] = true;
    } else {
        $response['recaptcha'] = false;
        $response['status'] = 'danger';
        $response['response'] = esc_html__( 'Please prove you\'re not a robot', 'motors' );
    }

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_add_trade_offer', 'stm_ajax_add_trade_offer' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_trade_offer', 'stm_ajax_add_trade_offer' );

//Load more cars
function stm_ajax_load_more_cars()
{

    check_ajax_referer( 'stm_ajax_load_more_cars', 'security' );

    $response = array();
    $response['button'] = '';
    $response['content'] = '';
    $response['appendTo'] = '#car-listing-category-' . sanitize_text_field( $_POST['category'] );
    $category = sanitize_text_field( $_POST['category'] );
    $taxonomy = sanitize_text_field( $_POST['taxonomy'] );
    $offset = intval( $_POST['offset'] );
    $per_page = intval( $_POST['per_page'] );
    $new_offset = $offset + $per_page;

    $args = array(
        'post_type' => stm_listings_post_type(),
        'post_status' => 'publish',
        'offset' => $offset,
        'posts_per_page' => $per_page,
    );
    $args['tax_query'][] = array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
        'terms' => array( $category )
    );
    $listings = new WP_Query( $args );
    if ( $listings->have_posts() ) {
        ob_start();
        while ( $listings->have_posts() ) {
            $listings->the_post();
            get_template_part( 'partials/car-filter', 'loop' );
        }
        $response['content'] = ob_get_contents();
        ob_end_clean();

        if ( $listings->found_posts > $new_offset ) {
            $response['button'] = 'stm_loadMoreCars(jQuery(this),\'' . esc_js( $category ) . '\',\'' . esc_js( $taxonomy ) . '\',' . esc_js( $new_offset ) . ', ' . esc_js( $per_page ) . '); return false;';
        } else {
            $response['button'] = '';
        }

        $response['test'] = $listings->found_posts . ' > ' . $new_offset;

        wp_reset_postdata();
    }

    echo json_encode( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_load_more_cars', 'stm_ajax_load_more_cars' );
add_action( 'wp_ajax_nopriv_stm_ajax_load_more_cars', 'stm_ajax_load_more_cars' );

//Ajax request car price
function stm_ajax_get_car_price()
{
    check_ajax_referer( 'stm_ajax_get_car_price', 'security' );

    $response['errors'] = array();

    if ( !filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) ) {
        $response['errors']['name'] = true;
    }
    if ( !is_email( $_POST['email'] ) ) {
        $response['errors']['email'] = true;
    }
    if ( !is_numeric( $_POST['phone'] ) ) {
        $response['errors']['phone'] = true;
    }


    if ( empty( $response['errors'] ) and !empty( $_POST['vehicle_id'] ) ) {
        $response['response'] = esc_html__( 'Your request was sent', 'motors' );
        $response['status'] = 'success';

        //Sending Mail to admin
        $to = get_bloginfo( 'admin_email' );

        $args = array(
            'car' => get_the_title( $_POST['vehicle_id'] ),
            'name' => sanitize_text_field( $_POST['name'] ),
            'email' => sanitize_email( $_POST['email'] ),
            'phone' => sanitize_text_field( $_POST['phone'] ),
        );

        $subject = generateSubjectView( 'request_price', $args );
        $body = generateTemplateView( 'request_price', $args );

        do_action( 'stm_wp_mail', $to, $subject, $body, '' );
    } else {
        $response['response'] = esc_html__( 'Please fill all fields', 'motors' );
        $response['status'] = 'danger';
    }

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_car_price', 'stm_ajax_get_car_price' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_car_price', 'stm_ajax_get_car_price' );

//Function add to favourites
function stm_ajax_add_to_favourites()
{
    check_ajax_referer( 'stm_ajax_add_to_favourites', 'security' );

    $response = array();
    $count = 0;

    if ( !empty( $_POST['car_id'] ) ) {
        $car_id = intval( $_POST['car_id'] );
        $post_status = get_post_status( $car_id );

        if ( !$post_status ) {
            $post_status = 'deleted';
        }

        if ( is_user_logged_in() and $post_status == 'publish' or $post_status == 'pending' or $post_status == 'draft' or $post_status == 'deleted' ) {
            $user = wp_get_current_user();
            $user_id = $user->ID;
            $user_added_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
            if ( empty( $user_added_fav ) ) {
                update_user_meta( $user_id, 'stm_user_favourites', $car_id );
            } else {
                $user_added_fav = array_filter( explode( ',', $user_added_fav ) );
                $response['fil'] = $user_added_fav;
                $response['id'] = $car_id;
                if ( in_array( strval( $car_id ), $user_added_fav ) ) {
                    $user_added_fav = array_diff( $user_added_fav, array( $car_id ) );
                } else {
                    $user_added_fav[] = $car_id;
                }
                $user_added_fav = implode( ',', $user_added_fav );

                update_user_meta( $user_id, 'stm_user_favourites', $user_added_fav );
            }

            $user_added_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
            $user_added_fav = count( array_filter( explode( ',', $user_added_fav ) ) );
            $response['count'] = intval( $user_added_fav );
        }
    }

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_add_to_favourites', 'stm_ajax_add_to_favourites' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_to_favourites', 'stm_ajax_add_to_favourites' );


function stm_ajax_get_favourites()
{
    check_ajax_referer( 'stm_ajax_get_favourites', 'security' );

    $ids = array_filter(
        explode( ',', get_the_author_meta( 'stm_user_favourites', get_current_user_id() ) )
    );

    wp_send_json( $ids );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_favourites', 'stm_ajax_get_favourites' );


function stm_ajax_dealer_load_cars()
{

    check_ajax_referer( 'stm_ajax_dealer_load_cars', 'security' );

    $response = array();
    $user_id = intval( $_POST['user_id'] );
    $offset = intval( $_POST['offset'] );
    $popular = false;

    $view_type = 'grid';
    if ( !empty( $_POST['view_type'] ) and $_POST['view_type'] == 'list' ) {
        $view_type = 'list';
    }

    if ( !empty( $_POST['popular'] ) and $_POST['popular'] == 'yes' ) {
        $popular = true;
    }

    $response['offset'] = $offset;


    $new_offset = 6 + $offset;

    $query = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id, 'publish', 6, $popular, $offset ) : null;

    $html = '';
    if ( $query != null && $query->have_posts() ) {
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            if ( $view_type == 'grid' ) {
                get_template_part( 'partials/listing-cars/listing-grid-directory-loop', 'animate' );
            } else {
                get_template_part( 'partials/listing-cars/listing-list-directory-loop', 'animate' );
            }
        }
        $html = ob_get_clean();
    }

    $response['html'] = $html;

    $button = 'show';
    if ( $query->found_posts <= $new_offset ) {
        $button = 'hide';
    } else {
        $response['new_offset'] = $new_offset;
    }

    $response['button'] = $button;

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_cars', 'stm_ajax_dealer_load_cars' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_cars', 'stm_ajax_dealer_load_cars' );

function stm_ajax_dealer_load_reviews()
{

    check_ajax_referer( 'stm_ajax_dealer_load_reviews', 'security' );

    $response = array();
    $user_id = intval( $_POST['user_id'] );
    $offset = intval( $_POST['offset'] );

    $response['offset'] = $offset;


    $new_offset = 6 + $offset;

    $query = stm_get_dealer_reviews( $user_id, 'publish', 6, $offset );

    $html = '';
    if ( $query->have_posts() ) {
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'partials/user/dealer-single', 'review' );
        }
        $html = ob_get_clean();
    }

    $response['html'] = $html;

    $button = 'show';
    if ( $query->found_posts <= $new_offset ) {
        $button = 'hide';
    } else {
        $response['new_offset'] = $new_offset;
    }

    $response['button'] = $button;

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_dealer_load_reviews', 'stm_ajax_dealer_load_reviews' );
add_action( 'wp_ajax_nopriv_stm_ajax_dealer_load_reviews', 'stm_ajax_dealer_load_reviews' );


if ( !function_exists( 'stm_submit_review' ) ) {
    function stm_submit_review()
    {

        check_ajax_referer( 'stm_submit_review', 'security' );

        $response = array();
        $response['message'] = '';
        $error = false;
        $user_id = 0;

        $demo = stm_is_site_demo_mode();

        if ( $demo ) {
            $error = true;
            $response['message'] = esc_html__( 'Site is on demo mode.', 'motors' );
        }

        /*Post parts*/
        $title = '';
        $content = '';
        $recommend = 'yes';
        $ratings = array();

        if ( !empty( $_GET['stm_title'] ) ) {
            $title = sanitize_text_field( $_GET['stm_title'] );
        } else {
            $error = true;
            $response['message'] = esc_html__( 'Please, enter review title.', 'motors' );
        }

        if ( empty( $_GET['stm_user_on'] ) ) {
            $error = true;
            $response['message'] = esc_html__( 'Do not cheat!', 'motors' );
        } else {
            $user_on = intval( $_GET['stm_user_on'] );
        }

        if ( !empty( $_GET['stm_content'] ) ) {
            $content = sanitize_text_field( $_GET['stm_content'] );
        } else {
            $error = true;
            $response['message'] = esc_html__( 'Please, enter review text.', 'motors' );
        }

        if ( empty( $_GET['stm_required'] ) ) {
            $error = true;
            $response['message'] = esc_html__( 'Please, check you are not a dealer.', 'motors' );
        } else {
            if ( $_GET['stm_required'] !== 'on' ) {
                $error = true;
                $response['message'] = esc_html__( 'Please, check you are not a dealer.', 'motors' );
            }
        }

        if ( !empty( $_GET['recommend'] ) and $_GET['recommend'] == 'no' ) {
            $recommend = 'no';
        }

        foreach ( $_GET as $get_key => $get_value ) {
            if ( strpos( $get_key, 'stm_rate' ) !== false ) {
                if ( empty( $get_value ) ) {
                    $error = true;
                    $response['message'] = esc_html__( 'Please add rating', 'motors' );
                } else {
                    if ( $get_value < 6 and $get_value > 0 ) {
                        $ratings[esc_attr( $get_key )] = intval( $get_value );
                    }
                }
            }
        }

        /*Check if user already added comment*/
        $current_user = wp_get_current_user();
        if ( is_wp_error( $current_user ) ) {
            $error = true;
            $response['message'] = esc_html__( 'You are not logged in', 'motors' );
        } else {
            if ( !empty( $user_on ) ) {
                $user_id = $current_user->ID;
                $get_user_reviews = stm_get_user_reviews( $user_id, $user_on );

                $response['q'] = $get_user_reviews;

                if ( !empty( $get_user_reviews->posts ) ) {
                    foreach ( $get_user_reviews->posts as $user_post ) {
                        wp_delete_post( $user_post->ID, true );
                    }
                }
            } else {
                $error = true;
                $response['message'] = esc_html__( 'Do not cheat', 'motors' );
            }
        }

        if ( !$error ) {

            if ( get_theme_mod( 'dealer_review_moderation', false ) )
                $post_status = 'pending';
            else
                $post_status = 'publish';

            $post_data = array(
                'post_type' => 'dealer_review',
                'post_title' => sanitize_text_field( $title ),
                'post_content' => sanitize_text_field( $content ),
                'post_status' => $post_status
            );

            $insert_post = wp_insert_post( $post_data, true );
            //$insert_post = 0;

            if ( is_wp_error( $insert_post ) ) {
                $response['message'] = $insert_post->get_error_message();
            } else {

                /*Ratings*/
                if ( !empty( $ratings['stm_rate_1'] ) ) {
                    update_post_meta( $insert_post, 'stm_rate_1', intval( $ratings['stm_rate_1'] ) );
                }
                if ( !empty( $ratings['stm_rate_2'] ) ) {
                    update_post_meta( $insert_post, 'stm_rate_2', intval( $ratings['stm_rate_2'] ) );
                }
                if ( !empty( $ratings['stm_rate_3'] ) ) {
                    update_post_meta( $insert_post, 'stm_rate_3', intval( $ratings['stm_rate_3'] ) );
                }

                /*Recommended*/
                update_post_meta( $insert_post, 'stm_recommended', esc_attr( $recommend ) );

                update_post_meta( $insert_post, 'stm_review_added_by', $user_id );
                update_post_meta( $insert_post, 'stm_review_added_on', $user_on );

                $response['updated'] = stm_get_author_link( $user_on ) . '#stm_d_rev';


            }

        }

        wp_send_json( $response );
        exit;
    }
}

add_action( 'wp_ajax_stm_submit_review', 'stm_submit_review' );
add_action( 'wp_ajax_nopriv_stm_submit_review', 'stm_submit_review' );

//Ajax filter cars remove unfiltered cars
function stm_restore_password()
{
    check_ajax_referer( 'stm_restore_password', 'security' );

    $response = array();

    $errors = array();

    if ( empty( $_POST['stm_user_login'] ) ) {
        $errors['stm_user_login'] = true;
    } else {
        $username = sanitize_text_field( $_POST['stm_user_login'] );
    }

    $stm_link_send_to = '';

    if ( !empty( $_POST['stm_link_send_to'] ) ) {
        $stm_link_send_to = esc_url( $_POST['stm_link_send_to'] );
    }

    $demo = stm_is_site_demo_mode();

    if ( $demo ) {
        $errors['demo'] = true;
    }

    if ( empty( $errors ) ) {
        if ( filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
            $user = get_user_by( 'email', $username );
        } else {
            $user = get_user_by( 'login', $username );
        }

        if ( !$user ) {
            $response['message'] = esc_html__( 'User not found', 'motors' );
        } else {
            $hash = stm_media_random_affix( 20 );
            $user_id = $user->ID;
            $stm_link_send_to = add_query_arg( array( 'user_id' => $user_id, 'hash_check' => $hash ), $stm_link_send_to );
            update_user_meta( $user_id, 'stm_lost_password_hash', $hash );

            /*Sending mail*/
            $to = $user->data->user_email;

            $args = array(
                'password_content' => $stm_link_send_to
            );

            $subject = generateSubjectView( 'password_recovery', $args );
            $body = generateTemplateView( 'password_recovery', $args );

            do_action( 'stm_wp_mail', $to, $subject, $body, '' );

            $response['message'] = esc_html__( 'Instructions send on your email', 'motors' );
        }

    } else {
        if ( $demo ) {
            $response['message'] = esc_html__( 'Site is on demo mode.', 'motors' );
        } else {
            $response['message'] = esc_html__( 'Please fill required fields', 'motors' );
        }
    }

    $response['errors'] = $errors;

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_restore_password', 'stm_restore_password' );
add_action( 'wp_ajax_nopriv_stm_restore_password', 'stm_restore_password' );

if ( !function_exists( 'stm_report_review' ) ) {
    function stm_report_review()
    {

        check_ajax_referer( 'stm_report_review', 'security' );

        $response = array();

        if ( !empty( $_POST['id'] ) ) {
            $report_id = intval( $_POST['id'] );

            $user_added_on = get_post_meta( $report_id, 'stm_review_added_on', true );
            if ( !empty( $user_added_on ) ) {
                $user_added_on = get_user_by( 'id', $user_added_on );
            }

            $title = get_the_title( $report_id );

            if ( !empty( $title ) and !empty( $user_added_on ) ) {

                /*Sending mail */
                $to = array();
                $to[] = get_bloginfo( 'admin_email' );
                $to[] = $user_added_on->data->user_email;

                $args = array(
                    'report_id' => $report_id,
                    'review_content' => get_post_field( 'post_content', $report_id )
                );

                $subject = generateSubjectView( 'report_review', $args );
                $body = generateTemplateView( 'report_review', $args );

                do_action( 'stm_wp_mail', $to, $subject, $body, '' );

                $response['message'] = esc_html__( 'Reported', 'motors' );

            }
        }

        wp_send_json( $response );
        exit;
    }
}

add_action( 'wp_ajax_stm_report_review', 'stm_report_review' );
add_action( 'wp_ajax_nopriv_stm_report_review', 'stm_report_review' );

function stm_load_dealers_list()
{

    check_ajax_referer( 'stm_load_dealers_list', 'security' );

    $response = array();

    $per_page = 12;

    $remove_button = '';
    $new_offset = 0;

    if ( !empty( $_GET['offset'] ) ) {
        $offset = intval( $_GET['offset'] );
    }

    if ( !empty( $offset ) ) {
        $dealers = stm_get_filtered_dealers( $offset, $per_page );
        if ( $dealers['button'] == 'show' ) {
            $new_offset = $offset + $per_page;
        } else {
            $remove_button = 'hide';
        }
        if ( !empty( $dealers['user_list'] ) ) {
            ob_start();
            $user_list = $dealers['user_list'];
            if ( !empty( $user_list ) ) {
                foreach ( $user_list as $user ) {
                    stm_get_single_dealer( $user );
                }
            }
            $response['user_html'] = ob_get_clean();
        }
    }

    $response['remove'] = $remove_button;
    $response['new_offset'] = $new_offset;

    wp_send_json( $response );
    exit;
}

add_action( 'wp_ajax_stm_load_dealers_list', 'stm_load_dealers_list' );
add_action( 'wp_ajax_nopriv_stm_load_dealers_list', 'stm_load_dealers_list' );


//ADD A CAR
function stm_add_a_car()
{
    check_ajax_referer( 'stm_add_a_car', 'security', false );

    $response = array();
    $first_step = array(); //needed fields
    $second_step = array(); //secondary fields
    $car_features = array(); //array of features car
    $videos = array(); /*videos links*/
    $notes = esc_html__( 'N/A', 'motors' );
    $registered = '';
    $vin = '';
    $history = array(
        'label' => '',
        'link' => ''
    );
    $location = array(
        'label' => '',
        'lat' => '',
        'lng' => '',
    );

    if ( !is_user_logged_in() ) {
        $response['message'] = esc_html__( 'Please, log in', 'motors' );
        wp_send_json( $response );
    } else {
        $user = stm_get_user_custom_fields( '' );
        $restrictions = stm_get_post_limits( $user['user_id'] );
    }


    $response['message'] = '';
    $error = false;

    $demo = stm_is_site_demo_mode();
    if ( $demo ) {
        $error = true;
        $response['message'] = esc_html__( 'Site is on demo mode', 'motors' );
    }

    $update = false;
    if ( !empty( $_POST['stm_current_car_id'] ) ) {
        $post_id = intval( $_POST['stm_current_car_id'] );
        $car_user = get_post_meta( $post_id, 'stm_car_user', true );
        $update = true;

        /*Check if current user edits his car*/
        if ( intval( $car_user ) != intval( $user['user_id'] ) ) {
            wp_die();
        }
    }

    /*Get first step*/
    if ( !empty( $_POST['stm_f_s'] ) ) {
        foreach ( $_POST['stm_f_s'] as $post_key => $post_value ) {
            if ( $_POST['stm_f_s'][$post_key] != "" ) {
                $postKey = str_replace( "_pre_", "-", $post_key );
                $first_step[sanitize_title( $postKey )] = sanitize_title( $_POST['stm_f_s'][$post_key] );
            } else {
                $error = true;
                $response['message'] = esc_html__( 'Enter required fields', 'motors' );
            }
        }
    }

    if ( empty( $first_step ) ) {
        $error = true;
        $response['message'] = esc_html__( 'Enter required fields', 'motors' );
    }


    /*Getting second step*/
    foreach ( $_POST as $second_step_key => $second_step_value ) {
        if ( strpos( $second_step_key, 'stm_s_s_' ) !== false ) {
            if ( $_POST[$second_step_key] != "" ) {
                $original_key = str_replace( 'stm_s_s_', '', $second_step_key );
                $second_step[sanitize_title( $original_key )] = sanitize_text_field( $_POST[$second_step_key] );
            }
        }
    }

    /*Getting car features*/
    if ( !empty( $_POST['stm_car_features_labels'] ) ) {
        foreach ( $_POST['stm_car_features_labels'] as $car_feature ) {
            $car_features[] = esc_attr( $car_feature );
        }
    }

    /*Videos*/
    if ( !empty( $_POST['stm_video'] ) ) {
        foreach ( $_POST['stm_video'] as $video ) {

            if ( ( strpos( $video, 'youtu' ) ) > 0 ) {
                $is_youtube = array();
                parse_str( parse_url( $video, PHP_URL_QUERY ), $is_youtube );
                if ( !empty( $is_youtube['v'] ) ) {
                    $video = 'https://www.youtube.com/embed/' . $is_youtube['v'];
                }
            }

            $videos[] = esc_url( $video );
            $videos = array_filter( $videos );
        }
    }

    /*Note*/
    if ( !empty( $_POST['stm_seller_notes'] ) ) {
        $notes = sanitize_textarea_field( $_POST['stm_seller_notes'] );
    }

    /*Registration date*/
    if ( !empty( $_POST['stm_registered'] ) ) {
        $registered = sanitize_text_field( $_POST['stm_registered'] );
    }

    /*Vin*/
    if ( !empty( $_POST['stm_vin'] ) ) {
        $vin = sanitize_text_field( $_POST['stm_vin'] );
    }

    /*History*/
    if ( !empty( $_POST['stm_history_label'] ) ) {
        $history['label'] = sanitize_text_field( $_POST['stm_history_label'] );
    }

    if ( !empty( $_POST['stm_history_link'] ) ) {
        $history['link'] = sanitize_text_field( $_POST['stm_history_link'] );
    }

    /*Location*/
    if ( !empty( $_POST['stm_location_text'] ) ) {
        $location['label'] = sanitize_text_field( $_POST['stm_location_text'] );
    }

    if ( !empty( $_POST['stm_lat'] ) ) {
        $location['lat'] = sanitize_text_field( $_POST['stm_lat'] );
    }

    if ( !empty( $_POST['stm_lng'] ) ) {
        $location['lng'] = sanitize_text_field( $_POST['stm_lng'] );
    }

    if ( empty( $_POST['stm_car_price'] ) ) {
        $error = true;
        $response['message'] = esc_html__( 'Please add car price', 'motors' );
        $price = '';
        $normalPrice = '';

    } else {
        $normalPrice = abs( intval( $_POST['stm_car_price'] ) );
        $price = ( function_exists( 'stm_convert_to_normal_price' ) ) ? stm_convert_to_normal_price( $normalPrice ) : $normalPrice;
    }

    if ( isset( $_POST['car_price_form_label'] ) && !empty( $_POST['car_price_form_label'] ) ) {

        if ( empty( $_POST['stm_car_price'] ) ) {
            $error = false;
            unset( $response['message'] );
        }

        $location['car_price_form_label'] = sanitize_text_field( $_POST['car_price_form_label'] );
    } else {
        $location['car_price_form_label'] = '';
    }

    if ( isset( $_POST['stm_car_sale_price'] ) ) {
        $salePrice = abs( sanitize_text_field( $_POST['stm_car_sale_price'] ) );
        $location['stm_car_sale_price'] = ( function_exists( 'stm_convert_to_normal_price' ) ) ? stm_convert_to_normal_price( $salePrice ) : $salePrice;
    }

    $generic_title = '';
    if ( !empty( $_POST['stm_car_main_title'] ) ) {
        $generic_title = sanitize_text_field( $_POST['stm_car_main_title'] );
    }

    $motors_gdpr_agree = ( isset( $_POST['motors-gdpr-agree'] ) && !empty( $_POST['motors-gdpr-agree'] ) ) ? $_POST['motors-gdpr-agree'] : false;

    if ( $motors_gdpr_agree && $motors_gdpr_agree == 'not_agree' ) {
        $error = true;
        $gdpr = get_option( 'stm_gdpr_compliance', '' );
        $ppLink = ( $gdpr['stmgdpr_privacy'][0]['privacy_page'] != 0 ) ? get_the_permalink( $gdpr['stmgdpr_privacy'][0]['privacy_page'] ) : '';
        $ppLinkText = ( !empty( $gdpr ) && !empty( $gdpr['stmgdpr_privacy'][0]['link_text'] ) ) ? $gdpr['stmgdpr_privacy'][0]['link_text'] : '';
        $mess = sprintf( __( "Providing consent to our <a href='%s'>%s</a> is necessary in order to use our services and products.", 'motors' ), $ppLink, $ppLinkText );


        $response['html'] = 'html';
        $response['message'] = $mess;
    }

    /*Generating post*/
    if ( !$error ) {

        if ( $restrictions['premoderation'] ) {
            $status = 'pending';
            $user = stm_get_user_custom_fields( '' );
        } else {
            $status = 'publish';
        }

        if ( $_POST['btn-type'] == 'pay' ) {
            $status = 'pending';
        }

        $post_data = array(
            'post_type' => stm_listings_post_type(),
            'post_title' => '',
            'post_content' => $notes,
            'post_status' => $status,
        );

        foreach ( $first_step as $taxonomy => $title_part ) {
            $term = get_term_by( 'slug', $title_part, $taxonomy );
            $post_data['post_title'] .= $term->name . ' ';
        }

        if ( !empty( $generic_title ) ) {
            $post_data['post_title'] = $generic_title;
        }


        if ( !$update ) {
            $post_id = wp_insert_post( apply_filters( 'stm_listing_save_post_data', $post_data ), true );
        }

        if ( !is_wp_error( $post_id ) ) {

            if ( $update ) {
                $post_data_update = array(
                    'ID' => $post_id,
                    'post_content' => $notes,
                    'post_status' => $status,
                );

                if ( !empty( $generic_title ) ) {
                    $post_data_update['post_title'] = $generic_title;
                }

                wp_update_post( apply_filters( 'stm_listing_save_post_data', $post_data_update ) );

            }

            $meta = array(
                'stock_number' => $post_id,
                'stm_car_user' => $user['user_id'],
                'price' => $price,
                'stm_genuine_price' => $price,
                'title' => 'hide',
                'breadcrumbs' => 'show',
            );

            $terms = array();

            /*Set categories*/
            foreach ( $first_step as $tax => $term ) {
                $tax_info = stm_get_all_by_slug( $tax );
                if ( !empty( $tax_info['numeric'] ) and $tax_info['numeric'] ) {
                    $meta[$tax] = abs( sanitize_title( $term ) );
                } else {
                    $terms[$tax] = $term;
                    $meta[$tax] = sanitize_title( $term );
                }
            }

            /*Set categories*/
            foreach ( $second_step as $tax => $term ) {

                $term = apply_filters( 'stm_change_value', $term );

                if ( !empty( $tax ) ) {
                    $tax_info = stm_get_all_by_slug( $tax );
                    if ( !empty( $tax_info['numeric'] ) and $tax_info['numeric'] ) {
                        update_post_meta( $post_id, $tax, $term );
                        $meta[$tax] = $term;
                    } else {
                        $terms[$tax] = $term;
                        $meta[$tax] = $term;
                    }
                }
            }

            if ( !empty( $videos ) ) {
                $meta['gallery_video'] = $videos[0];

                if ( count( $videos ) > 1 ) {
                    array_shift( $videos );
                    $meta['gallery_videos'] = array_filter( array_unique( $videos ) );
                }
            }

            $meta['vin_number'] = $vin;
            $meta['registration_date'] = $registered;
            $meta['history'] = $history['label'];
            $meta['history_link'] = $history['link'];
            $meta['stm_car_location'] = $location['label'];
            $meta['stm_lat_car_admin'] = $location['lat'];
            $meta['stm_lng_car_admin'] = $location['lng'];
            $meta['additional_features'] = implode( ',', $car_features );
            $terms['stm_additional_features'] = $car_features;

            update_post_meta( $post_id, 'price', $price );
            update_post_meta( $post_id, 'stm_genuine_price', $price );
            update_post_meta( $post_id, 'motors_gdpr_agree', get_the_date( 'd-m-Y', $post_id ) );

            if ( isset( $location['car_price_form_label'] ) ) {
                $meta['car_price_form_label'] = $location['car_price_form_label'];
            }

            if ( isset( $location['stm_car_sale_price'] ) && !empty( $location['stm_car_sale_price'] ) ) {
                $meta['sale_price'] = $location['stm_car_sale_price'];
                $meta['stm_genuine_price'] = $location['stm_car_sale_price'];
            } else {
                $meta['sale_price'] = '';
            }

            foreach ( apply_filters( 'stm_listing_save_post_meta', $meta, $post_id, $update ) as $key => $value ) {
                update_post_meta( $post_id, $key, $value );
            }


            foreach ( apply_filters( 'stm_listing_save_post_terms', $terms, $post_id, $update ) as $tax => $term ) {
                wp_delete_object_term_relationships( $post_id, $tax );
                wp_add_object_terms( $post_id, $term, $tax );
                update_post_meta( $post_id, $tax, sanitize_title( $term ) );
            }

            update_post_meta( $post_id, 'title', 'hide' );
            update_post_meta( $post_id, 'breadcrumbs', 'show' );

            $response['post_id'] = $post_id;
            $response['redirect_type'] = sanitize_text_field( $_POST['btn-type'] );
            if ( ( $update ) ) {
                $response['message'] = esc_html__( 'Car Updated, uploading photos', 'motors' );
            } else {
                $response['message'] = esc_html__( 'Car Added, uploading photos', 'motors' );
            }

            if ( !$update ) {
                $title_from = get_theme_mod( 'listing_directory_title_frontend', '' );
                if ( !empty( $title_from ) ) {
                    wp_update_post( array( 'ID' => $post_id, 'post_title' => stm_generate_title_from_slugs( $post_id ) ) );
                }
            }

            do_action( 'stm_after_listing_saved', $post_id, $response, $update );

        } else {
            $response['message'] = $post_id->get_error_message();
        }
    }

    wp_send_json( apply_filters( 'stm_filter_add_a_car', $response ) );
}

remove_action( 'wp_ajax_stm_ajax_add_a_car', 'stm_ajax_add_a_car' );
remove_action( 'wp_ajax_nopriv_stm_ajax_add_a_car', 'stm_ajax_add_a_car' );
add_action( 'wp_ajax_stm_ajax_add_a_car', 'stm_add_a_car' );
add_action( 'wp_ajax_nopriv_stm_ajax_add_a_car', 'stm_add_a_car' );


function stm_ajax_get_cars_for_inventory_map()
{

    check_ajax_referer( 'stm_ajax_get_cars_for_inventory_map', 'security' );

    wp_reset_query();
    $response = new WP_Query( _stm_listings_build_query_args( array(
        'nopaging' => true,
        'posts_per_page' => -1
    ) ) );

    $mapLocationCar = array();
    $carsData = array();
    $markers = array();
    $i = 0;

    $carsInfo = array();

    foreach ( stm_get_map_listings() as $k => $val ) {
        if ( isset( $val["use_on_map_page"] ) && $val["use_on_map_page"] == 1 ) {
            $carsInfo[count( $carsInfo )] = array( 'key' => $val["slug"], "icon" => $val["font"] );
        }
    }

    foreach ( $response->get_posts() as $k => $val ) {

        if ( get_post_meta( $val->ID, "stm_lat_car_admin" ) != null && $val->post_status == "publish" || $val->post_status == "private" ) {

            $carMeta = get_post_meta( $val->ID, "" );
            $img = "<img src='" . get_template_directory_uri() . "/assets/images/plchldr255.png" . "'/>";
            if ( has_post_thumbnail( $val->ID ) ) {
                $img = ( !empty( get_the_post_thumbnail( $val->ID, 'full' ) ) ) ? get_the_post_thumbnail( $val->ID, 'full' ) : '';
            }

            $price = ( isset( $carMeta["price"] ) ) ? stm_listing_price_view( $carMeta["price"][0] ) : 0 . stm_get_price_currency();
            if ( isset( $carMeta["sale_price"] ) && $carMeta["sale_price"][0] != null && !empty( $carMeta["sale_price"][0] ) ) $price = stm_listing_price_view( $carMeta["sale_price"][0] );

            $car_price_form_label = get_post_meta( $val->ID, 'car_price_form_label', true );
            if ( !empty( $car_price_form_label ) ) $price = $car_price_form_label;

            $carsData[$i]["id"] = $val->ID;
            $carsData[$i]["link"] = get_the_permalink( $val->ID );
            $carsData[$i]["title"] = $val->post_title;
            $carsData[$i]["image"] = $img;
            $carsData[$i]["price"] = $price;
            $carsData[$i]["year"] = ( isset( $carMeta["ca-year"] ) ) ? $carMeta["ca-year"][0] : "";
            $carsData[$i]["condition"] = ( isset( $carMeta["condition"] ) ) ? mb_strtoupper( str_replace( "-cars", "", $carMeta["condition"][0] ) ) : "";
            $carsData[$i]["mileage"] = ( isset( $carsInfo[0] ) && isset( $carMeta[$carsInfo[0]["key"]] ) ) ? $carMeta[$carsInfo[0]["key"]][0] : "";
            $carsData[$i]["engine"] = ( isset( $carsInfo[1] ) && isset( $carMeta[$carsInfo[1]["key"]] ) ) ? $carMeta[$carsInfo[1]["key"]][0] : "";
            $carsData[$i]["transmission"] = ( isset( $carsInfo[2] ) && isset( $carMeta[$carsInfo[2]["key"]] ) ) ? $carMeta[$carsInfo[2]["key"]][0] : "";
            $carsData[$i]["mileage_font"] = ( isset( $carsInfo[0] ) && isset( $carsInfo[0]["icon"] ) ) ? $carsInfo[0]["icon"] : "";
            $carsData[$i]["engine_font"] = ( isset( $carsInfo[1] ) && isset( $carsInfo[1]["icon"] ) ) ? $carsInfo[1]["icon"] : "";
            $carsData[$i]["transmission_font"] = ( isset( $carsInfo[2] ) && isset( $carsInfo[2]["icon"] ) ) ? $carsInfo[2]["icon"] : "";

            $markers[$i]["lat"] = (double)round( $carMeta["stm_lat_car_admin"][0], 7 );
            $markers[$i]["lng"] = (double)round( $carMeta["stm_lng_car_admin"][0], 7 );
            $markers[$i]["location"] = ( !empty( $carMeta["stm_car_location"] ) ) ? $carMeta["stm_car_location"][0] : 'no location';

            $mapLocationCar[(string)round( $markers[$i]["lat"], 7 )][] = $i;
            $i++;
        }
    }

    wp_reset_query();

    $GLOBALS['listings_query'] = $response;
    $data = apply_filters( 'stm_ajax_cars_for_map', array( "carsData" => $carsData, "markers" => $markers, "mapLocationCar" => $mapLocationCar ) );
    echo json_encode( $data );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_cars_for_inventory_map', 'stm_ajax_get_cars_for_inventory_map' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_cars_for_inventory_map', 'stm_ajax_get_cars_for_inventory_map' );

function stm_ajax_get_seller_phone()
{

    check_ajax_referer( 'stm_ajax_get_seller_phone', 'security' );
    $user_added_by = get_user_meta( $_GET["phone_owner_id"], 'stm_phone', true );

    wp_send_json( $user_added_by );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_seller_phone', 'stm_ajax_get_seller_phone' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_seller_phone', 'stm_ajax_get_seller_phone' );


function stm_ajax_rental_check_car_in_current_office()
{

    check_ajax_referer( 'stm_ajax_rental_check_car_in_current_office', 'security' );

    $cart_items = stm_get_cart_items();
    $car_rent = $cart_items['car_class'];
    $id = $car_rent['id'];

    $pickUpLocationMeta = explode( ',', get_post_meta( $id, 'stm_rental_office', true ) );

    wp_send_json( ( array_search( $_GET['rental_office_id'], $pickUpLocationMeta ) === false && !empty( $id ) ) ? $responce['responce'] = "EMPTY" : $responce['responce'] = "INSTOCK" );
    exit;
}

add_action( 'wp_ajax_stm_ajax_rental_check_car_in_current_office', 'stm_ajax_rental_check_car_in_current_office' );
add_action( 'wp_ajax_nopriv_stm_ajax_rental_check_car_in_current_office', 'stm_ajax_rental_check_car_in_current_office' );

function stm_ajax_check_is_available_car_date()
{

    check_ajax_referer( 'stm_ajax_check_is_available_car_date', 'security' );

    $startDate = sanitize_text_field($_GET['startDate']);
    $endDate = sanitize_text_field($_GET['endDate']);

    $cart_items = stm_get_cart_items();
    $car_rent = $cart_items['car_class'];
    $id = $car_rent['id'];

    $checkOrderAvailable = stm_checkOrderAvailable( $id, $startDate, $endDate );

    $formatedDates = array();
    foreach ( $checkOrderAvailable as $val ) {
        $formatedDates[] = stm_get_formated_date( $val, 'd M' );
    }

    wp_send_json( ( count( $checkOrderAvailable ) > 0 ) ? $responce['responce'] = esc_html__( 'This Class is already booked in: ', 'motors' ) . "<span>" . implode( ', ', $formatedDates ) . "</span>." : $responce['responce'] = '' );
    exit;
}

add_action( 'wp_ajax_stm_ajax_check_is_available_car_date', 'stm_ajax_check_is_available_car_date' );
add_action( 'wp_ajax_nopriv_stm_ajax_check_is_available_car_date', 'stm_ajax_check_is_available_car_date' );

function stm_ajax_get_recent_posts_magazine()
{

    check_ajax_referer( 'stm_ajax_get_recent_posts_magazine', 'security' );

    $posts_per_page = sanitize_text_field($_GET['posts_per_page']);

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'ignore_sticky_posts' => true,
    );

    if ( isset( $_GET['category'] ) && $_GET['category'] != 'all' ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $_GET['category'],
            ),
        );
    }

    $r = new WP_Query( $args );

    ob_start();

    if ( $r->have_posts() ) {
        while ( $r->have_posts() ) {
            $r->the_post();
            get_template_part( 'partials/blog/content-list-magazine-loop' );
        }
    }

    $result['html'] = ob_get_clean();

    wp_send_json( $result );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_recent_posts_magazine', 'stm_ajax_get_recent_posts_magazine' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_recent_posts_magazine', 'stm_ajax_get_recent_posts_magazine' );

function stm_ajax_sticky_posts_magazine()
{

    check_ajax_referer( 'stm_ajax_sticky_posts_magazine', 'security' );

    $sticky = get_option( 'sticky_posts' );

    $args = array(
        'post_type' => 'any',
        'post__in' => $sticky,
        'post_status' => 'publish'
    );

    if ( isset( $_GET['category'] ) && $_GET['category'] != 'all' ) {
        $args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $_GET['category'],
            ),
            array(
                'taxonomy' => 'review_category',
                'field' => 'slug',
                'terms' => $_GET['category'],
            ),
            array(
                'taxonomy' => 'event_category',
                'field' => 'slug',
                'terms' => $_GET['category'],
            )
        );
    }

    $r = new WP_Query( $args );

    ob_start();
    if ( $r->have_posts() ) {
        $num = 0;
        while ( $r->have_posts() ) {
            $r->the_post();
            if ( $num == 0 ) {
                get_template_part( 'partials/vc_loop/features_posts_big_loop' );
            } else {
                if ( $_GET['adsense_position'] == $num && $_GET['use_adsense'] == 'yes' ) {
                    ?>
                    <div class="adsense-200-200">
                        <?php //if(!empty($content)) echo stm_do_lmth($content); ?>
                    </div>
                    <?php
                }
                if ( $num > $_GET['hidenWrap'] ) echo '<div class="features_hiden">';
                get_template_part( 'partials/vc_loop/features_posts_small_loop' );
                if ( $num > $_GET['hidenWrap'] ) echo '</div>';
            }

            $num++;
        }
    }
    $result['html'] = ob_get_clean();

    wp_reset_postdata();
    wp_send_json( $result );
    exit;
}

add_action( 'wp_ajax_stm_ajax_sticky_posts_magazine', 'stm_ajax_sticky_posts_magazine' );
add_action( 'wp_ajax_nopriv_stm_ajax_sticky_posts_magazine', 'stm_ajax_sticky_posts_magazine' );

function stm_ajax_get_events()
{

    check_ajax_referer( 'stm_ajax_get_events', 'security' );

    $id = intval($_GET['post_id']);
    $date = get_the_date( 'd M Y', $id );

    $date_start = get_post_meta( $id, 'date_start', true );
    $date_start_time = get_post_meta( $id, 'date_start_time', true );
    $date_end = get_post_meta( $id, 'date_end', true );
    $date_end_time = get_post_meta( $id, 'date_end_time', true );
    $address = get_post_meta( $id, 'address', true );
    $participants = get_post_meta( $id, 'cur_participants', true );
    $category = event_get_terms_array( $id, 'event_category', 'name', false );

    if ( empty( $participants ) ) $participants = 0;

    $time = '';
    $date_prev = ( !empty( $date_start ) ) ? stm_motors_get_formatted_date( $date_start, 'd M Y' ) : '';

    /*Countdown*/

    $timeFormatCountdown = stm_motors_get_formatted_date( strtotime( $date_end_time ), 'H:i:s' );
    $dateCountdown = stm_motors_get_formatted_date( $date_end, 'Y-m-d ' ) . $timeFormatCountdown;

    /*Countdown*/

    if ( !empty( $date_start_time ) ) $time .= $date_start_time;
    if ( !empty( $date_end_time ) ) $time .= ' - ' . $date_end_time;

    ob_start();

    $url = ( !empty( get_the_post_thumbnail( $id, 'stm-img-690-410' ) ) ) ? get_the_post_thumbnail( $id, 'stm-img-690-410' ) : '';

    echo '
            <div class="title">
                <h3>' . get_the_title( $id ) . '</h3>
            </div>
            <div class="event-data">
                <div class="address">
                    <i class="me-ico_event_pin"></i>
                    <div>' . $address . '</div>
                </div>
                <div class="date">
                    <i class="stm-icon-ico_mag_calendar"></i>
                    <div>' . $date_prev . '</div>
                </div>
                <div class="time">
                    <i class="me-ico_event_clock"></i>
                    <div>' . $time . '</div>
                </div>
            </div>
            <div class="event-single-wrap">
                <div class="img">
                    ' . $url . '
                </div>
                <div class="timer">
                    <div class="stm-countdown-wrapper">
                        <time class="heading-font" datetime="' . $dateCountdown . '"  data-countdown="' . esc_attr( str_replace( "-", "/", $dateCountdown ) ) . '" ></time>
                    </div>
                </div>
                <div class="timer timerFullHeight">
                    <div class="stm-countdown-wrapper">
                        <time class="heading-font" datetime="' . $dateCountdown . '"  data-countdown="' . esc_attr( str_replace( "-", "/", $dateCountdown ) ) . '" ></time>
                    </div>
                </div>
                <div class="participants">
                    <i class="me-ico_profile"></i>
                    <div class="prticipants_count heading-font">
                        ' . esc_html( $participants ) . '
                    </div>
                </div>
                <div class="event_more_btn">
                    <a href="' . esc_url( get_the_permalink( $id ) ) . '" class="stm-button">' . esc_html__( 'More Details', 'motors' ) . '</a>
                </div>
            </div>
    ';

    $result['html'] = ob_get_clean();

    wp_send_json( $result );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_events', 'stm_ajax_get_events' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_events', 'stm_ajax_get_events' );

function stm_ajax_get_test_drive_modal()
{

    $shortCode = sanitize_text_field( $_POST['shortCode'] );
    $carName = sanitize_text_field( $_POST['carName'] );
    ob_start();

    echo '
            <div class="modal leasing-modal" id="leasing-test-drive" tabindex="-1" role="dialog" aria-labelledby="myModalLabelTestDrive">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header modal-header-iconed">
                            <i class="stm-icon-steering_wheel"></i>
                            <h3 class="modal-title" id="myModalLabelTestDrive">' . esc_html__( "Test Drive", "motors" ) . '</h3>
                            <div class="test-drive-car-name">' . $carName . '</div>
                        </div>
                        <div class="modal-body">
                            ' . do_shortcode( stripslashes( wp_specialchars_decode( $shortCode ) ) ) . '
                        </div>
                    </div>
                </div>
            </div>
    ';

    $result['html'] = ob_get_clean();

    wp_send_json( $result );
    exit;
}

add_action( 'wp_ajax_stm_ajax_get_test_drive_modal', 'stm_ajax_get_test_drive_modal' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_test_drive_modal', 'stm_ajax_get_test_drive_modal' );

function stm_ajax_clear_data()
{
    check_ajax_referer( 'stm_ajax_clear_data', 'security' );
    WC()->cart->empty_cart();
    WC()->session->destroy_session();
}

add_action( 'wp_ajax_stm_ajax_clear_data', 'stm_ajax_clear_data' );
add_action( 'wp_ajax_nopriv_stm_ajax_clear_data', 'stm_ajax_clear_data' );


function stm_ajax_inventory_no_filter()
{

    check_ajax_referer( 'stm_ajax_inventory_no_filter', 'security' );

    $args = array(
        'post_type' => stm_listings_post_type(),
        'post_status' => 'publish',
        'posts_per_page' => $_GET['posts_per_page'],
        'suppress_filters' => 0,
        'order_by' => 'ID',
        'order' => 'DESC',
        'paged' => $_GET['paged']
    );

    if ( is_listing() ) {
        $args['meta_query'][] = array(
            'relation' => 'OR',
            array(
                'key' => 'car_mark_as_sold',
                'value' => '',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => 'car_mark_as_sold',
                'value' => '',
                'compare' => '='
            )
        );
    }

    $listings = new WP_Query( $args );

    if ( $listings->have_posts() ) {
        ob_start();
        while ( $listings->have_posts() ) {
            $listings->the_post();
            get_template_part( 'partials/vc_loop/inventory-no-filter-loop' );
        }
        $response['content'] = ob_get_contents();
        $response['pagina'] = paginate_links( array(
            'type' => 'list',
            'current' => $_GET['paged'],
            'total' => $listings->found_posts / $_GET['posts_per_page'],
            'prev_text' => '<i class="fa fa-angle-left"></i>',
            'next_text' => '<i class="fa fa-angle-right"></i>',
        ) );
        ob_end_clean();

        wp_reset_postdata();
    }

    echo json_encode( $response );
    exit;
}

add_action( 'wp_ajax_stm_ajax_inventory_no_filter', 'stm_ajax_inventory_no_filter' );
add_action( 'wp_ajax_nopriv_stm_ajax_inventory_no_filter', 'stm_ajax_inventory_no_filter' );
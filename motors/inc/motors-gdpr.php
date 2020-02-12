<?php

function stm_motors_export_dealer_data($email_address, $page = 1)
{
    $user = get_user_by('email', $email_address);

    $args = array(
        'post_type'     => 'listings',
        'post_status'   => 'publish',
        'orderby'       =>  'post_date',
        'order'         =>  'ASC',
        'posts_per_page' => -1,
        'meta_query'    => array (
            array (
                'key'     => 'stm_car_user',
                'value'   => array( $user->ID ),
                'compare' => 'IN',
            )
        )
    );

    $posts = new WP_Query($args);

    $export_items = array();

    foreach ($posts->posts as $post) {
        $item_id = "listing-{$post->ID}";
        $group_id = 'listing';
        $group_label = esc_html__('Listing', 'motors');

        $data = array (
            array(
                'name'  => esc_html__('ID', 'motors'),
                'value' => $post->ID
            ),
            array(
                'name'  => esc_html__('Title', 'motors'),
                'value' => $post->post_title
            ),
            array(
                'name'  => esc_html__('URL', 'motors'),
                'value' => get_the_permalink($post->ID)
            ),
        );

        $export_items[] = array(
            'group_id' => $group_id,
            'group_label' => $group_label,
            'item_id' => $item_id,
            'data' => $data,
        );
    }

    wp_reset_postdata();

    $done = count($posts) > 0;

    return array(
        'data' => $export_items,
        'done' => $done,
    );
}

function stm_register_motors_dealer_exporter( $exporters ) {
    $exporters['stm-motors-gdpr'] = array(
        'exporter_friendly_name' => __( 'Motors Dealer Data', 'motors' ),
        'callback' => 'stm_motors_export_dealer_data',
    );
    return $exporters;
}

add_filter('wp_privacy_personal_data_exporters', 'stm_register_motors_dealer_exporter', 10);


function stm_motors_eraser_dealer_data ( $email_address, $page = 1 ) {
    $user = get_user_by('email', 'dm.ni@stylemix.net');

    $args = array(
        'post_type'     => 'listings',
        'post_status'   => 'publish',
        'orderby'       =>  'post_date',
        'order'         =>  'ASC',
        'posts_per_page' => -1,
        'meta_query'    => array (
            array (
                'key'     => 'stm_car_user',
                'value'   => array( $user->ID ),
                'compare' => 'IN',
            )
        )
    );

    $posts = new WP_Query($args);

    $items_removed = false;

    foreach ($posts->posts as $post) {
        wp_delete_post($post->ID);

        $items_removed = true;
    }

    wp_reset_postdata();

    $done = count( $posts ) > 0;
    return array( 'items_removed' => $items_removed,
        'items_retained' => false,
        'messages' => array(),
        'done' => $done,
    );
}

function stm_register_motors_dealer_eraser( $erasers ) {
    $erasers['stm-motors-gdpr'] = array(
        'eraser_friendly_name' => __( 'Motors Dealer Data', 'motors'),
        'callback'             => 'stm_motors_eraser_dealer_data',
    );
    return $erasers;
}

add_filter( 'wp_privacy_personal_data_erasers', 'stm_register_motors_dealer_eraser', 10 );

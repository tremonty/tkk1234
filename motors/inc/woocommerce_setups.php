<?php
// Declare Woo support
add_action('after_setup_theme', 'stm_woocommerce_support');
function stm_woocommerce_support()
{
    add_theme_support('woocommerce');
}

//Remove Woo Breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

if (version_compare(WOOCOMMERCE_VERSION, "2.1") >= 0) {
    add_filter('woocommerce_enqueue_styles', '__return_false');
} else {
    define('WOOCOMMERCE_USE_CSS', false);
}

if(!stm_is_auto_parts()) {
    add_filter('woocommerce_show_page_title', '__return_false');
}

add_filter('loop_shop_per_page', 'stm_cols', 20, 1);

function stm_cols ($cols) {
    return 12;
}

add_filter('woocommerce_add_to_cart_fragments', 'stm_woocommerce_header_add_to_cart_fragment');

function stm_woocommerce_header_add_to_cart_fragment($fragments)
{
    global $woocommerce;
    $cart_count = $woocommerce->cart->cart_contents_count;
    if ($cart_count == 0) {
        $cart_count = '';
        if (stm_is_boats()) {
            $cart_count = '0';
        }
    }
    ob_start();
    ?>
    <span class="stm-current-items-in-cart"><?php echo esc_attr($cart_count); ?></span>
    <?php
    $fragments['.stm-current-items-in-cart'] = ob_get_clean();

    return $fragments;
}

add_filter('woocommerce_output_related_products_args', 'stm_related_products_args');

function stm_related_products_args($args)
{
    $args['posts_per_page'] = 3; // 3 related products
    return $args;
}

if (stm_pricing_enabled()) {
    /*Remove Fields*/
    add_filter('woocommerce_checkout_fields', 'stm_override_checkout_fields');

    if (!function_exists('stm_override_checkout_fields')) {
        function stm_override_checkout_fields($fields)
        {
            ($fields['billing']['billing_address_1']);
            ($fields['billing']['billing_address_2']);
            ($fields['billing']['billing_city']);
            ($fields['billing']['billing_postcode']);
            ($fields['billing']['billing_country']);
            ($fields['billing']['billing_state']);

            return $fields;
        }
    }

    add_filter('woocommerce_add_to_cart_redirect', 'stm_woocommerce_add_to_cart_redirect');
    function stm_woocommerce_add_to_cart_redirect($url)
    {
        if(is_shop()) {
            return $url;
        } else {
            return wc_get_checkout_url();
        }
    }

    if (class_exists('STM_PostType')) {

        STM_PostType::addMetaBox('stm_pricing_plans', esc_html__('Pricing Plan Options (Works only with "Subscription" Product)', 'motors'), array('product'), '', '', '', array(
            'fields' => array(
                'stm_price_plan_quota' => array(
                    'label' => __('Price Plan Cars Quota', 'motors'),
                    'type' => 'text',
                ),
                'stm_price_plan_media_quota' => array(
                    'label' => __('Price Plan Car Images Quota', 'motors'),
                    'type' => 'text',
                ),
                'stm_price_plan_role' => array(
                    'label' => __('Price Plan User Role', 'motors'),
                    'type' => 'select',
                    'options' => array(
                        'user' => __('User', 'motors'),
                        'dealer' => __('Dealer', 'motors'),
                    )
                ),
            )
        ));
    }


    if (!function_exists('stm_user_active_subscriptions')) {
        function stm_user_active_subscriptions($get_paused = false, $userId = 0)
        {
            $user_subscriptions = Subscriptio_User::find_subscriptions(true, $userId);

            $active_subscription = '';
            $has_active = false;

            if ($get_paused) {
                $statuses = array('overdue', 'suspended');
            } else {
                $statuses = array('active', 'trial');
            }

            foreach ($user_subscriptions as $user_subscription) {
                if (in_array($user_subscription->status, $statuses) and !$has_active) {
                    $active_subscription = $user_subscription;
                    $has_active = true;
                }
            }

            $user_subscriptions = $active_subscription;

            $user_subscription_quota = array();
          

            if (!empty($user_subscriptions)) {
	            
	            $product_id = $user_subscriptions->product_id;
	            
	            if(empty($product_id) and !empty($user_subscriptions->products_multiple) and is_array($user_subscriptions->products_multiple)) {
					$products = $user_subscriptions->products_multiple;
					if(!empty($products[0]) and !empty($products[0]['product_id']))  {
						$product_id = $products[0]['product_id'];
					}
	            }
	           
                $post_limit = intval(get_post_meta($product_id, 'stm_price_plan_quota', true));
                $image_limit = intval(get_post_meta($product_id, 'stm_price_plan_media_quota', true));

                if (!empty($post_limit) and !empty($image_limit)) {
                    $user_subscription_quota['user_id'] = $user_subscriptions->user_id;
                    $user_subscription_quota['sub_id'] = $user_subscriptions->id;
                    $user_subscription_quota['product_id'] = $user_subscriptions->product_id;
                    $user_subscription_quota['plan_name'] = (!empty($user_subscription->products_multiple)) ? $user_subscription->products_multiple[0]['product_name'] : $user_subscriptions->product_name;
                    $user_subscription_quota['post_limit'] = $post_limit;
                    $user_subscription_quota['image_limit'] = $image_limit;
                    $user_subscription_quota['status'] = $user_subscriptions->status;
                    $user_subscription_quota['last_order_id'] = $user_subscriptions->last_order_id;
                    $user_subscription_quota['expires'] = $user_subscriptions->payment_due_readable;
                }

            }

            return $user_subscription_quota;
        }
    }

    add_action('init', 'stm_user_active_subscriptions');

    if (!function_exists('stm_move_draft_over_limit')) {
        function stm_move_draft_over_limit($subscription, $old_status, $new_status)
        {

            $product_id = $subscription->product_id;
            $user_id = $subscription->user_id;


            $role = get_post_meta($product_id, 'stm_price_plan_role', true);

            if (!in_array($new_status, array('active', 'trial'))) {
                $user_limits = stm_get_post_limits($user_id);

                $posts_args = array(
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => stm_listings_post_type(),
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'stm_car_user',
                            'value' => $user_id,
                            'compare' => '='
                        ),
                        array(
                            'key' => 'pay_per_listing',
                            'compare' => 'NOT EXISTS',
                            'value' => ''
                        )
                    ),
                    'fields' => 'ids'
                );

                $user_posts = get_posts($posts_args);

                if (count($user_posts) > $user_limits['posts_allowed']) {
                    array_splice($user_posts, 0, $user_limits['posts_allowed']);
                    foreach ($user_posts as $user_post) {
                        $draft_post = array(
                            'ID' => $user_post,
                            'post_status' => 'draft'
                        );
                        wp_update_post($draft_post);
                    }
                }

                /*Change user back to private if not admin*/
                if (!user_can($user_id, 'manage_options')) {
                    wp_update_user(array(
                        'ID' => $user_id,
                        'role' => 'subscriber'
                    ));
                }

            } else {
                /*If plan includes dealeship, change user role to dealer*/
                if ($role == 'dealer') {
                    wp_update_user(array(
                        'ID' => $user_id,
                        'role' => 'stm_dealer'
                    ));
                }
            }
        }
    }

    add_action('subscriptio_status_changed', 'stm_move_draft_over_limit', 10, 3);


    function stm_save_customer_note_meta($subscription_id, $post)
    {
        $slug = 'subscription';

        // If this isn't a 'subscription' post, don't update it.
        if ($slug != $post->post_type) {
            return;
        }

        $note = get_post_meta($subscription_id, 'renewal_customer_note', true);
        if (empty($note)) {
            update_post_meta($subscription_id, 'renewal_customer_note', '');
        }
    }

    add_action('save_post', 'stm_save_customer_note_meta', 10, 2);
}

add_action( 'after_setup_theme', 'stm_woo_setup' );

function stm_woo_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}

function stm_add_link_to_order ($item_id, $item, $order) {
    $id = get_post_meta($order->get_id(), 'order_pay_per_listing_id', true);
    $featured = get_post_meta($order->get_id(), 'car_make_featured_id', true);

    if(!empty($id)) {
        echo '<tbody><tr><td colspan="6"><b>' . esc_html__('Please publish this Listing manually', 'motors') . '</b> <a href="' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '">' . admin_url( 'post.php?post=' . $id . '&action=edit' ) . '</a></td></tr></tbody>';
    }

    if(!empty($featured)) {
        echo '<tbody><tr><td colspan="6"><b>' . esc_html__('Please make featured this Listing manually', 'motors') . '</b> <a href="' . admin_url( 'post.php?post=' . $featured . '&action=edit' ) . '">' . admin_url( 'post.php?post=' . $featured . '&action=edit' ) . '</a></td></tr></tbody>';
    }
}

add_action('woocommerce_order_item_line_item_html', 'stm_add_link_to_order', 10, 3);


add_action('woocommerce_order_status_changed','stm_make_featured_status_changed', 10, 3);
function stm_make_featured_status_changed( $order_id, $oldStatus, $newStatus ) {
    $listingPerPay = get_post_meta($order_id, 'order_pay_per_listing_id', true);

    if(!empty($listingPerPay) && $newStatus == 'completed') {
        $listing = array(
            'ID'           => $listingPerPay,
            'post_status' => 'publish',
        );

        wp_update_post( $listing );
    } else {
        $listingId = get_post_meta( $order_id, 'car_make_featured_id', true );
        if ( !empty( $listingId ) && $newStatus == 'completed' ) {
            update_post_meta( $listingId, 'car_make_featured_status', $newStatus );
            update_post_meta( $listingId, 'special_car', 'on' );
            update_post_meta( $listingId, 'badge_text', 'Featured' );
        } elseif ( !empty( $listingId ) && $newStatus == 'processing' ) {
            update_post_meta( $listingId, 'car_make_featured_status', $newStatus );
            delete_post_meta( $listingId, 'special_car', '' );
            delete_post_meta( $listingId, 'badge_text', '' );
        }
    }
}

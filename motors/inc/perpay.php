<?php

class STM_Listing_Data_Store_CPT extends WC_Product_Data_Store_CPT {
	
	/**
	 * Method to read a product from the database.
	 * @param WC_Product
	 */
	 
	 
	public function read( &$product ) {

        add_filter( 'woocommerce_is_purchasable', function () { return true; }, 10, 1);

		$product->set_defaults();

		$post_object = get_post( $product->get_id() );

		if ( ! $product->get_id() || ! ( $post_object = get_post( $product->get_id() ) ) || ! ( ('product' === $post_object->post_type) || ('listings' === $post_object->post_type) ) ) {
			throw new Exception( __( 'Invalid product.', 'motors' ) );
		}

		$product->set_props( array(
			'name'              => $post_object->post_title,
			'slug'              => $post_object->post_name,
			'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
			'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
			'status'            => $post_object->post_status,
			'description'       => $post_object->post_content,
			'short_description' => $post_object->post_excerpt,
			'parent_id'         => $post_object->post_parent,
			'menu_order'        => $post_object->menu_order,
			'reviews_allowed'   => 'open' === $post_object->comment_status,
		) );

		$this->read_attributes( $product );
		$this->read_downloads( $product );
		$this->read_visibility( $product );
		$this->read_product_data( $product );
		$this->read_extra_data( $product );
		$product->set_object_read( true );

	}
	
	/**
	 * Get the product type based on product ID.
	 *
	 * @since 3.0.0
	 * @param int $product_id
	 * @return bool|string
	 */
	
	public function get_product_type( $product_id ) {
		$post_type = get_post_type( $product_id );
		if ( 'product_variation' === $post_type ) {
			return 'variation';
		} elseif ( ( $post_type === 'product' ) || ('listings' === $post_type) ) {
			$terms = get_the_terms( $product_id, 'product_type' );
			return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
		} else {
			return false;
		}
	}
}

function stm_woocommerce_payment_complete ( $id ) {

    $order = wc_get_order($id);
    $orderId = 0;
    $order_status = $order->get_status();
    $order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
    $listingTitle = '';
    $listingId = get_post_meta($id, 'order_pay_per_listing_id', true);

    $sendAccess = false;

    foreach ($order_items as $item) {
        if(get_post_type($item->get_data()['product_id']) != 'product') {
            $listingTitle = $item->get_data()['name'];
            $sendAccess = true;
        }
    }

    $to = get_bloginfo('admin_email');
    $args = array (
        'first_name' => $order->get_billing_first_name(),
        'last_name' => $order->get_billing_last_name(),
        'email' => $order->get_billing_email(),
        'order_id' => $orderId,
        'order_status' => $order_status,
        'listing_title' => $listingTitle,
        'car_id' => $listingId
    );
    $subject = generateSubjectView('pay_per_listing', $args);
    $body = generateTemplateView('pay_per_listing', $args);

    if($sendAccess) do_action('stm_wp_mail', $to, $subject, $body, '');
}

function stm_before_create_order ($order_id, $data) {
    $cart = WC()->cart->get_cart();

    foreach ($cart as $cart_item) {
        $id = $cart_item['product_id'];
        if(!empty(get_post_meta($id, 'car_make_featured_status', true))) {
            update_post_meta($id, 'car_make_featured_status', 'processing');
            update_post_meta($order_id, 'car_make_featured_id', $id);
        } else {
            update_post_meta($order_id, 'order_pay_per_listing_id', $id);
        }
    }

    //stm_woocommerce_payment_complete($order_id);

    return true;
}

if(is_listing()) {
    add_filter('woocommerce_data_stores', 'woocommerce_data_stores');
    add_action('woocommerce_checkout_update_order_meta', 'stm_before_create_order', 200, 2);
}

function woocommerce_data_stores ( $stores ) {
	$stores['product'] = 'STM_Listing_Data_Store_CPT';
	return $stores;
}


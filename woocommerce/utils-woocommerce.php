<?php

// Get product object
if(!function_exists('dtlms_get_product_object')) {
	function dtlms_get_product_object ( $wc_product_id = 0 ) {

		if ( class_exists( 'WooCommerce' ) ) {

			$wc_product_object = wc_get_product( $wc_product_id );
			return $wc_product_object;

		}

		return false;

	}
}


// Check item is in cart
if(!function_exists('dtlms_check_item_is_in_cart')) {
	function dtlms_check_item_is_in_cart( $product_id ){

		if ( $product_id > 0 ) {

			foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$cart_product = $values['data'];
				if( $product_id == $cart_product->get_id() ) {
					return true;
				}
			}

		}

		return false;

	}
}

if(!function_exists('dtlms_get_item_price_html')) {
	function dtlms_get_item_price_html($product) {

		$woo_price = '';

		if(!empty($product)) {

			$woo_regular_price = $product->get_regular_price();
			$woo_sale_price = $product->get_sale_price();

			if($woo_regular_price != '' && $woo_sale_price != '') {

				$woo_price .= '<del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.esc_html( $woo_regular_price ).'</span></del>';

				$woo_price .= '<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.esc_html( $woo_sale_price ).'</span></ins>';

			} else if($woo_regular_price != '') {

				$woo_price .= '<ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">'.get_woocommerce_currency_symbol().'</span>'.esc_html( $woo_regular_price ).'</span></ins>';

			}

		}

		return $woo_price;

	}
}

if(!function_exists('dtlms_check_item_has_price')) {
	function dtlms_check_item_has_price($product) {

		if(!empty($product)) {

			$woo_regular_price = $product->get_regular_price();
			$woo_sale_price = $product->get_sale_price();

			if($woo_regular_price > 0 || $woo_sale_price > 0) {
				return true;
			}

		}

		return false;

	}
}


// Prevent courses and classes from adding more than one item
add_action( 'woocommerce_add_to_cart', 'dtlms_update_quantity_on_addtocart', 10, 6 );
function dtlms_update_quantity_on_addtocart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

	$woo_purchase_cpt = apply_filters( 'dtlms_woo_purchase_cpt', array () );

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$post_type = get_post_type($cart_item['product_id']);
		if(in_array($post_type, $woo_purchase_cpt)) {
			if($cart_item['quantity'] > 1) {
				WC()->cart->set_quantity($cart_item_key, 1);
			}
		}
	}

}

// Prevent courses and classes from adding more than one item
add_filter( 'woocommerce_cart_item_quantity', 'dtlms_change_quantity_on_cartpage', 10, 3);
function dtlms_change_quantity_on_cartpage( $product_quantity, $cart_item_key, $cart_item ) {

    $product_id = $cart_item['product_id'];
    $post_type = get_post_type($product_id);

	$woo_purchase_cpt = apply_filters( 'dtlms_woo_purchase_cpt', array () );

	if(in_array($post_type, $woo_purchase_cpt)) {
    	return 1;
    }

    return $product_quantity;

}
<?php
/**
 * WooCommerce cart hooks.
 *
 * @package Kava
 */

// Remove cross sells products from default position
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
// Add cross sells products under the cart total
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

// Display custom column count
add_filter( 'woocommerce_cross_sells_columns', 'kava_woocommerce_change_cross_sells_columns_count' );

if ( ! function_exists( 'kava_woocommerce_change_cross_sells_columns_count' ) ) {

	/**
	 * Display cross sells products on 1 column instead of default 2
	 *
	 * @param number $columns
	 * @return number
	 */
	function kava_woocommerce_change_cross_sells_columns_count( $columns ) {
		return 1;
	}

}

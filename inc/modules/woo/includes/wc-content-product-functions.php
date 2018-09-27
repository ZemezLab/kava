<?php
/**
 * WooCommerce content product hooks.
 *
 * @package Kava
 */

add_action( 'woocommerce_before_shop_loop_item', 'kava_wc_loop_product_content_open', 1 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 20 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 20 );

add_action( 'woocommerce_after_shop_loop_item', 'kava_wc_loop_product_content_close', 20 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'kava_wc_template_loop_product_title', 10 );

add_filter( 'woocommerce_loop_add_to_cart_link', 'kava_wc_loop_add_to_cart_link', 10, 3 );

add_action( 'woocommerce_before_subcategory', 'kava_wc_loop_category_content_open', 1 );
add_action( 'woocommerce_after_subcategory', 'kava_wc_loop_category_content_close', 40 );

if ( ! function_exists( 'kava_wc_loop_product_content_open' ) ) {

	/**
	 * Content product wrapper open
	 */
	function kava_wc_loop_product_content_open() {
		echo '<div class="product-content">';
	}

}

if ( ! function_exists( 'kava_wc_loop_product_content_close' ) ) {

	/**
	 * Content product wrapper close
	 */
	function kava_wc_loop_product_content_close() {
		echo '</div>';
	}

}

if ( ! function_exists( 'kava_wc_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function kava_wc_template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h2>';
	}
}


if ( ! function_exists( 'kava_wc_loop_add_to_cart_link' ) ) {

	/**
	 *  Override product loop add to cart button
	 *
	 * @param $html
	 * @param $product
	 * @param $args
	 *
	 * @return string
	 */
	function kava_wc_loop_add_to_cart_link( $html, $product, $args ) {
		$html = sprintf( '<a href="%s" data-quantity="%s" class="%s" %s><span class="button-text">%s</span></a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
			esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
			isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
			esc_html( $product->add_to_cart_text() )
		);

		return $html;
	}

}

if ( ! function_exists( 'kava_wc_loop_category_content_open' ) ) {

	/**
	 * Content category wrapper open
	 */
	function kava_wc_loop_category_content_open() {
		echo '<div class="category-content">';
	}

}

if ( ! function_exists( 'kava_wc_loop_category_content_close' ) ) {

	/**
	 * Content category wrapper close
	 */
	function kava_wc_loop_category_content_close() {
		echo '</div>';
	}

}
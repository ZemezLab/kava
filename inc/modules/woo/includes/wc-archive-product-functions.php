<?php
/**
 * WooCommerce archive product hooks.
 *
 * @package Kava
 */

add_action( 'woocommerce_before_shop_loop', 'wc_loop_products_panel_open', 15 );
add_action( 'woocommerce_before_shop_loop', 'wc_loop_products_panel_close', 50 );
add_filter( 'loop_shop_columns', 'kava_wc_loop_columns' );
add_filter( 'woocommerce_product_loop_start', 'kava_wc_product_loop_start' );

if ( ! function_exists( 'wc_loop_products_panel_open' ) ) {

	/**
	 * Archive products panel wrapper open
	 */
	function wc_loop_products_panel_open() {
		echo '<div class="woocommerce-products__panel">';
	}

}

if ( ! function_exists( 'wc_loop_products_panel_close' ) ) {

	/**
	 * Archive products panel wrapper close
	 */
	function wc_loop_products_panel_close() {
		echo '</div>';
	}

}

if ( ! function_exists( 'kava_wc_loop_columns' ) ) {
	/**
	 * Change number or products per row
	 *
	 * @return int
	 */
	function kava_wc_loop_columns() {
		$sidebar_position = kava_theme()->customizer->get_value( 'blog_sidebar_position' );

		if ( 'none' === $sidebar_position ) {
			return 4;
		}

		return 3;
	}
}

if ( ! function_exists( 'kava_wc_product_loop_start' ) ) {

	/**
	 * Rewrite loop start columns
	 *
	 * @param $ob_get_clean
	 *
	 * @return string
	 */
	function kava_wc_product_loop_start( $ob_get_clean ) {

		$context = wc_get_loop_prop( 'name' );
		$columns = array(
			'xs' => 1,
			'sm' => 2,
			'md' => 2,
			'lg' => wc_get_loop_prop( 'columns' ),
			'xl' => wc_get_loop_prop( 'columns' ),
		);

		switch ( $context ) {
			case 'related':
				$columns['xl'] = 4;
				$columns['lg'] = 4;
				break;
			case 'up-sells':
				$columns['xl'] = 4;
				$columns['lg'] = 4;
				break;
			case 'cross-sells':
				$columns['xl'] = 4;
				$columns['lg'] = 4;
				break;
			default:
				break;
		}

		$columns = apply_filters( 'kava-theme/woo/products_loop_columns', $columns );

		if ( is_shop() || is_product_taxonomy() || is_product() ) {
			$ob_get_clean = sprintf(
				'<ul class="products products-grid columns-xs-%1$s columns-sm-%2$s columns-md-%3$s columns-lg-%4$s columns-xl-%5$s">',
				esc_attr( $columns['xs'] ),
				esc_attr( $columns['sm'] ),
				esc_attr( $columns['md'] ),
				esc_attr( $columns['lg'] ),
				esc_attr( $columns['xl'] )
			);
		}

		$ob_get_clean = woocommerce_maybe_show_product_subcategories( $ob_get_clean );

		return $ob_get_clean;
	}

}

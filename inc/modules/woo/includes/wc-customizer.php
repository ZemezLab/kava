<?php
/**
 * WooCommerce customizer options
 *
 * @package Kava
 */

if ( ! function_exists( 'kava_set_wc_dynamic_css_options' ) ) {

	/**
	 * Add dynamic WooCommerce styles
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	function kava_set_wc_dynamic_css_options( $options ) {

		array_push( $options['css_files'], get_theme_file_path( 'inc/modules/woo/assets/css/dynamic/woo-module-dynamic.css' ) );

		return $options;

	}

}
add_filter( 'kava-theme/dynamic_css/options', 'kava_set_wc_dynamic_css_options' );


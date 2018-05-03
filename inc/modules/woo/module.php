<?php
/**
 * WooCommerce integration module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Woo_Module' ) ) {

	/**
	 * Define Kava_Woo_Module class
	 */
	class Kava_Woo_Module extends Kava_Module_Base {

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'woo';
		}

		/**
		 * Module filters
		 *
		 * @return void
		 */
		public function filters() {

			/**
			 * Disable the default WooCommerce stylesheet.
			 *
			 * Removing the default WooCommerce stylesheet and enqueing your own will
			 * protect you during WooCommerce core updates.
			 *
			 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
			 */
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

			add_filter( 'kava-theme/assets-depends/script', array( $this, 'assets_depends_script' ) );
		}

		/**
		 * Include appropriate module files.
		 *
		 * @return void
		 */
		public function includes() {
			require_once get_theme_file_path( 'inc/modules/woo/includes/wc-content-product-functions.php' );
			require_once get_theme_file_path( 'inc/modules/woo/includes/wc-single-product-functions.php' );
			require_once get_theme_file_path( 'inc/modules/woo/includes/wc-archive-product-functions.php' );
			require_once get_theme_file_path( 'inc/modules/woo/includes/wc-customizer.php' );
			require_once get_theme_file_path( 'inc/modules/woo/includes/wc-integration.php' );
		}

		/**
		 * Module condition callback.
		 *
		 * @return bool|callable
		 */
		public function condition_cb() {
			return class_exists( 'WooCommerce' );
		}

		/**
		 * Add module script dependencies
		 *
		 * @param $scripts_depends
		 *
		 * @return int
		 */
		public function assets_depends_script( $scripts_depends ) {
			array_push( $scripts_depends, 'kava-woo-module-script' );

			return $scripts_depends;
		}

		/**
		 * Enqueue module scripts.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			// register scripts
			wp_register_script(
				'kava-woo-module-script',
				get_theme_file_uri( 'inc/modules/woo/assets/js/woo-module-script.js' ),
				array( 'jquery' ),
				kava_theme()->version(),
				true
			);
		}

		/**
		 * Enqueue module styles.
		 *
		 * @return void
		 */
		public function enqueue_styles() {
			$font_path   = WC()->plugin_url() . '/assets/fonts/';
			$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
			}';

			wp_add_inline_style(
				'kava-woocommerce-style',
				$inline_font
			);

			wp_enqueue_style(
				'kava-woocommerce-style',
				get_template_directory_uri() . '/inc/modules/woo/assets/css/woo-module' . ( is_rtl() ? '-rtl' : '' ) . '.css',
				false,
				kava_theme()->version()
			);

		}

	}

}

<?php
/**
 * WooCommerce page title integration module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Woo_Page_Title_Module' ) ) {

	/**
	 * Define Kava_Woo_Page_Title_Module class
	 */
	class Kava_Woo_Page_Title_Module extends Kava_Module_Base {

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'woo-page-title';
		}

		/**
		 * Module actions
		 *
		 * @return void
		 */
		public function actions() {
			remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
			remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
			add_action( 'woocommerce_before_main_content', array( $this, 'get_woo_page_title' ), 1 );
		}

		/**
		 * Return page title for WooCommerce
		 */
		public function get_woo_page_title() {
			include get_theme_file_path( 'inc/modules/woo-page-title/template/page-title.php' );
		}

		/**
		 * Add or remove module-related filters
		 *
		 * @return void
		 */
		public function filters() {
			add_filter( 'woocommerce_show_page_title', '__return_false' );
		}

	}

}

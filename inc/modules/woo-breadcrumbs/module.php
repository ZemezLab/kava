<?php
/**
 * WooCommerce Breadcrumbs integration module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Woo_Breadcrumbs_Module' ) ) {

	/**
	 * Define Kava_Woo_Breadcrumbs_Module class
	 */
	class Kava_Woo_Breadcrumbs_Module extends Kava_Module_Base {

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'woo-breadcrumbs';
		}

		/**
		 * Module filters
		 *
		 * @return void
		 */
		public function filters() {
			add_filter( 'cx_breadcrumbs/custom_trail', array( $this, 'get_wc_breadcrumbs' ), 10, 2 );
		}

		/**
		 * Module actions
		 *
		 * @return void
		 */
		public function actions() {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		}
		/**
		 * Include appropriate module files.
		 *
		 * @return void
		 */
		public function includes() {
			require_once get_theme_file_path( 'inc/modules/woo-breadcrumbs/classes/class-wc-breadcrumbs.php' );
		}

		/**
		 * Replace default breadcrumbs trail with WooCommerce-related.
		 *
		 * @param  bool $is_custom_breadcrumbs Default cutom breadcrumbs trigger.
		 * @param  array $args Breadcrumb arguments.
		 *
		 * @return bool|array
		 */
		public function get_wc_breadcrumbs( $is_custom_breadcrumbs, $args ) {
			if ( ! is_woocommerce() ){
				return $is_custom_breadcrumbs;
			}

			$wc_breadcrumbs = new Kava_WC_Breadcrumbs( $args );

			return array( 'items' => $wc_breadcrumbs->items, 'page_title' => $wc_breadcrumbs->page_title );

		}

	}

}

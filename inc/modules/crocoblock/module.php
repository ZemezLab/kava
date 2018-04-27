<?php
/**
 * CrocoBlock integration module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Crocoblock_Module' ) ) {

	/**
	 * Define Kava_Crocoblock_Module class
	 */
	class Kava_Crocoblock_Module extends Kava_Module_Base {

		/**
		 * API base URL
		 * @var string
		 */
		public $api = 'https://account.crocoblock.com/wp-content/uploads/static/';

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'crocoblock';
		}

		/**
		 * Module filters
		 *
		 * @return void
		 */
		public function actions() {

			if ( ! is_admin() ) {
				return;
			}

			add_action( 'init', array( $this, 'plugins_wizard_config' ), 0 );
			add_action( 'init', array( $this, 'data_importer_config' ), 0 );
		}

		/**
		 * Load data importer configuration
		 *
		 * @return void
		 */
		public function data_importer_config() {

			if ( ! function_exists( 'jet_data_importer_register_config' ) ) {
				return;
			}

			$advanced_import = array(
				'from_path' => $this->api . 'wizard-skins.json'
			);

			jet_data_importer_register_config( array(
				'xml'             => false,
				'advanced_import' => $advanced_import,
			) );

		}

		/**
		 * Load wizard configuration
		 *
		 * @return void
		 */
		public function plugins_wizard_config() {

			if ( ! function_exists( 'jet_plugins_wizard_register_config' ) ) {
				return;
			}

			$plugins = array(
				'get_from' => $this->api . 'wizard-plugins.json'
			);

			$skins = array(
				'get_from' => $this->api . 'wizard-skins.json',
			);

			jet_plugins_wizard_register_config( array(
				'plugins' => $plugins,
				'skins'   => $skins,
			) );

		}

	}

}

<?php
/**
 * Base module class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Module_Base' ) ) {

	/**
	 * Define Kava_Module_Base class
	 */
	abstract class Kava_Module_Base {

		/**
		 * Returns module ID.
		 * Should be equal to module folder name inside modules directory.
		 *
		 * @return string
		 */
		abstract public function module_id();

		/**
		 * Constructor for the class
		 */
		public function __construct( $childs = array() ) {

			if ( ! $this->is_enabled() ) {
				return;
			}

			if ( ! empty( $childs ) ) {
				$this->load_child_modules( $childs );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			$this->includes();

			$this->actions();
			$this->filters();

			$this->custom_init();

		}

		/**
		 * Load children modules
		 *
		 * @param  array $childs Modules IDs
		 * @return void
		 */
		public function load_child_modules( $childs = array() ) {

			foreach ( $childs as $child => $childs_of_child ) {
				kava_theme()->load_module( $child, $childs_of_child );
			}

		}

		/**
		 * Check if module is enabled
		 *
		 * @return boolean
		 */
		public function is_enabled() {

			if( null === $this->condition_cb() ) {
				return true;
			} else {
				return $this->condition_cb();
			}

		}

		/**
		 * Enqueue module scripts.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {}

		/**
		 * Enqueue module styles.
		 *
		 * @return void
		 */
		public function enqueue_styles() {}

		/**
		 * Module condition callback.
		 * If returns callable function - this function will be executed before module initialization.
		 * If empty - module will be allways initialized
		 *
		 * @return callback name|null
		 */
		public function condition_cb() {
			return null;
		}

		/**
		 * Include appropriate module files.
		 *
		 * @return void
		 */
		public function includes() {}

		/**
		 * Add or remove module-related actions
		 *
		 * @return void
		 */
		public function actions() {}

		/**
		 * Add or remove module-related filters
		 *
		 * @return void
		 */
		public function filters() {}

		/**
		 * Run any custom initializtion code
		 *
		 * @return void
		 */
		public function custom_init() {}


	}

}

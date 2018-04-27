<?php
/**
 * UI controls manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Controls_Manager' ) ) {

	/**
	 * Define CX_Controls_Manager class
	 */
	class CX_Controls_Manager {

		/**
		 * Path to controls folder for current Inteface Builder instance
		 *
		 * @var string
		 */
		private $base_path = '';

		/**
		 * Path to controls folder for current Inteface Builder instance
		 *
		 * @var string
		 */
		private $base_url = '';

		/**
		 * Constructor for the class
		 */
		public function __construct( $base_path = null, $base_url = null ) {

			$this->base_path = trailingslashit( $base_path );
			$this->base_url  = trailingslashit( $base_url );

			require $this->base_path . 'inc/class-cx-controls-base.php';
			$this->load_controls();

		}

		/**
		 * Automatically load found conrols
		 *
		 * @return void
		 */
		public function load_controls() {
			foreach ( glob( $this->base_path . 'inc/controls/*.php' ) as $file ) {
				require $file;
			}
		}

		/**
		 * Register new control instance
		 *
		 * @return object
		 */
		public function register_control( $type = 'text', $args = array() ) {

			$prefix    = 'CX_Control_';
			$classname = $prefix . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $type ) ) );

			if ( ! class_exists( $classname ) ) {
				return false;
			}

			$instance = new $classname( $args );

			$instance->set_base_url( $this->base_url );

			return $instance;
		}

	}

}

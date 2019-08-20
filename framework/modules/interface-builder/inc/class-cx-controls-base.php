<?php
/**
 * Control base class
 */

/**
 * CX_Controls_Base abstract class
 */
if ( ! class_exists( 'CX_Controls_Base' ) ) {

	/**
	 * CX_Controls_Base Abstract Class
	 *
	 * @since 1.0.0
	 */
	abstract class CX_Controls_Base {

		/**
		 * Base URL
		 *
		 * @var null
		 */
		public $base_url = null;

		/**
		 * Settings list
		 *
		 * @since 1.0.0
		 * @var array
		 */
		protected $settings = array();

		/**
		 * Default settings array
		 *
		 * @var array
		 */
		public $defaults_settings = array();

		/**
		 * Constructor method for the CX_Controls_Base class.
		 *
		 * @since 1.0.0
		 */
		public function __construct( $args = array() ) {

			$this->defaults_settings['id'] = 'cx-control-' . uniqid();
			$this->settings = wp_parse_args( $args, $this->defaults_settings );

			$this->init();

			add_action( 'wp_enqueue_scripts', array( $this, 'register_depends' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_depends' ) );
		}

		/**
		 * Returns control settings
		 *
		 * @return array
		 */
		public function get_settings() {
			return $this->settings;
		}

		/**
		 * Get required attribute.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function get_required() {

			if ( $this->settings['required'] ) {
				return 'required="required"';
			}

			return '';
		}

		/**
		 * Render methos. Each UI element must implement own method
		 * @return [type] [description]
		 */
		abstract public function render();

		/**
		 * Optional additional initializtion for control. Can be overriden from child class if needed.
		 * @return [type] [description]
		 */
		public function init() {}

		/**
		 * Retrun scripts dependencies list for current control.
		 *
		 * @return array
		 */
		public function get_script_depends() {
			return array();
		}

		/**
		 * Register required dependencies
		 *
		 * @return void
		 */
		public function register_depends() {}

		/**
		 * Retrun styles dependencies list for current control.
		 *
		 * @return array
		 */
		public function get_style_depends() {
			return array();
		}

		/**
		 * Set up base URL for next usage
		 *
		 * @param string $url array
		 */
		public function set_base_url( $url = '' ) {
			$this->base_url = $url;
		}

		/**
		 * Get control value
		 *
		 * @since 1.0.0
		 * @return string control value.
		 */
		public function get_value() {
			return $this->settings['value'];
		}

		/**
		 * Set control value
		 *
		 * @since 1.0.0
		 * @param [type] $value new.
		 */
		public function set_value( $value ) {
			$this->settings['value'] = $value;
		}

		/**
		 * Get control name
		 *
		 * @since 1.0.0
		 * @return string control name.
		 */
		public function get_name() {
			return $this->settings['name'];
		}

		/**
		 * Set control name
		 *
		 * @since 1.0.0
		 * @param [type] $name new control name.
		 * @throws Exception Invalid control name.
		 */
		public function set_name( $name ) {
			$name = (string) $name;
			if ( '' !== $name ) {
				$this->settings['name'] = $name;
			} else {
				throw new Exception( "Invalid control name '" . $name . "'. Name can't be empty." );
			}
		}

		/**
		 * Returns attributes string from attributes array
		 *
		 * @return string
		 */
		public function get_attr_string( $attr = array() ) {

			$result = array();

			foreach ( $attr as $key => $value ) {

				if ( $key === $value ) {
					$result[] = $key;
				} else {
					$result[] = sprintf( '%1$s="%2$s"', $key, $value );
				}

			}

			return implode( ' ', $result );

		}
	}
}

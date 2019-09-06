<?php
/**
 * Post meta class.
 *
 * @package Kava
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Post_Meta' ) ) {

	/**
	 * Define Kava_Post_Meta class
	 */
	class Kava_Post_Meta {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Kava_Post_Meta
		 */
		private static $instance = null;

		/**
		 * Mata options
		 *
		 * @var array
		 */
		private $options = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'init_post_meta' ) );
		}

		/**
		 * Add meta options
		 *
		 * @param $options
		 */
		public function add_options( array $options = array() ) {
			$this->options[] = $options;
		}

		/**
		 * Init meta
		 */
		public function init_post_meta() {

			foreach ( $this->options as $options ) {

				if ( ! isset( $options['builder_cb'] ) ) {
					$options['builder_cb'] = array( $this, 'get_interface_builder' );
				}

				new Cherry_X_Post_Meta( $options );
			}

			new Cherry_X_Post_Meta( array(
				'id'            => 'kava-extra-page-settings',
				'title'         => esc_html__( 'Page Settings', 'kava' ),
				'page'          => array( 'page', 'post' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
				'builder_cb'    => array( $this, 'get_interface_builder' ),
				'fields'        => array(
					'kava_extra_enable_breadcrumbs' => array(
						'type'        => 'select',
						'title'       => esc_html__( 'Use Breadcrumbs', 'kava' ),
						'description' => esc_html__( 'Enable Breadcrumbs global settings redefining.', 'kava' ),
						'value'       => 'inherit',
						'options'     => array(
							'inherit' => esc_html__( 'Inherit', 'kava' ),
							'true'    => esc_html__( 'Enable', 'kava' ),
							'false'   => esc_html__( 'Disable', 'kava' ),
						),
					),
				),
			) );
		}

		public function get_interface_builder() {

			$builder_data = kava_theme()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			return new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Kava_Post_Meta
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

function kava_post_meta() {
	return Kava_Post_Meta::get_instance();
}

kava_post_meta();

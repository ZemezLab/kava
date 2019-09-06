<?php
/**
 * Breadcrumbs module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Breadcrumbs_Module' ) ) {

	/**
	 * Define Kava_Breadcrumbs_Module class
	 */
	class Kava_Breadcrumbs_Module extends Kava_Module_Base {

		/**
		 * Holder for current Breadcrumbs module instance.
		 *
		 * @since 1.0.0
		 * @var   CX_Dynamic_CSS
		 */
		public $breadcrumbs = null;

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'breadcrumbs';
		}

		/**
		 * Module actions
		 *
		 * @return void
		 */
		public function actions() {
			add_action( 'wp_head',           array( $this, 'init_breadcrumbs' ) );
			add_action( 'after_setup_theme', array( $this, 'add_meta_options' ), 6 );

			add_action( 'kava-theme/site/breadcrumbs-area', array( $this, 'print_breadcrumbs_area' ) );
		}

		/**
		 * Module filters
		 *
		 * @return void
		 */
		public function filters() {
			// Breadcrumbs visibility on specific page/post
			add_filter( 'kava-theme/breadcrumbs/breadcrumbs-visibillity', array( $this, 'breadcrumbs_visibility' ) );
		}

		/**
		 * Run initialization of breadcrumbs.
		 *
		 * @since 1.0.0
		 */
		public function init_breadcrumbs() {
			$this->breadcrumbs = new CX_Breadcrumbs( $this->get_breadcrumbs_options() );
		}

		/**
		 * Print Breadcrumbs area.
		 */
		public function print_breadcrumbs_area() {
			get_template_part( 'inc/modules/breadcrumbs/template-parts/breadcrumbs' );
		}

		/**
		 * Retrieve a holder for breadcrumbs options.
		 *
		 * @since  1.0.0
		 * @return array
		 */

		function get_breadcrumbs_options() {
			/**
			 * Filter a holder for breadcrumbs options.
			 *
			 * @since 1.0.0
			 */
			return apply_filters( 'kava-theme/breadcrumbs/options' , array(
				'show_browse'       => false,
				'show_on_front'     => kava_theme()->customizer->get_value( 'breadcrumbs_front_visibillity' ),
				'show_title'        => kava_theme()->customizer->get_value( 'breadcrumbs_page_title' ),
				'path_type'         => kava_theme()->customizer->get_value( 'breadcrumbs_path_type' ),
				'css_namespace'     => array(
					'module'    => 'breadcrumbs',
					'content'   => 'breadcrumbs_content',
					'wrap'      => 'breadcrumbs_wrap',
					'browse'    => 'breadcrumbs_browse',
					'item'      => 'breadcrumbs_item',
					'separator' => 'breadcrumbs_item_sep',
					'link'      => 'breadcrumbs_item_link',
					'target'    => 'breadcrumbs_item_target',
				),
			) );
		}

		/**
		 * Add meta options
		 */
		public function add_meta_options() {
			kava_post_meta()->add_options( array(
				'id'            => 'kava-extra-page-settings',
				'title'         => esc_html__( 'Page Settings', 'kava' ),
				'page'          => array( 'page', 'post' ),
				'context'       => 'normal',
				'priority'      => 'high',
				'callback_args' => false,
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

		/**
		 * Breadcrumbs visibility on specific page/post
		 *
		 * @param $visibility
		 *
		 * @return bool
		 */
		function breadcrumbs_visibility( $visibility ) {
			$post_id = get_the_ID();

			$meta_value = get_post_meta( $post_id, 'kava_extra_enable_breadcrumbs', true );

			if ( ! $meta_value || 'inherit' === $meta_value ) {
				return $visibility;
			}

			return filter_var( $meta_value, FILTER_VALIDATE_BOOLEAN );
		}
	}

}

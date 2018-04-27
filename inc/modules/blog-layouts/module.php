<?php
/**
 * Blog layouts module
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Blog_Layouts_Module' ) ) {

	/**
	 * Define Kava_Blog_Layouts_Module class
	 */
	class Kava_Blog_Layouts_Module extends Kava_Module_Base {

		/**
		 * Module ID
		 *
		 * @return string
		 */
		public function module_id() {
			return 'blog-layouts';
		}

		/**
		 * Module filters
		 *
		 * @return void
		 */
		public function filters() {
			add_filter( 'kava-theme/customizer/options', array( $this, 'customizer_options' ) );
			add_filter( 'kava-theme/posts/template-part-slug', array( $this, 'apply_layout_template' ) );
			add_filter( 'kava-theme/posts/post_style', array( $this, 'apply_style_template' ) );
		}

		/**
		 * Apply new blog layout
		 *
		 * @return array
		 */
		public function apply_layout_template( $layout ) {

			$blog_layout_type   = kava_theme()->customizer->get_value( 'blog_layout_type' );
			$blog_post_template = 'template-parts/grid/content';

			if ( 'default' === $blog_layout_type ) {
				$blog_post_template = 'template-parts/default/content';
			}

			if ( 'creative' === $blog_layout_type ) {
				$blog_post_template = 'template-parts/creative/content';
			}

			if ( 'vertical-justify' === $blog_layout_type ) {
				$blog_post_template = 'template-parts/vertical-justify/content';
			}

			if ( 'masonry' === $blog_layout_type ) {
				$blog_post_template = 'template-parts/masonry/content';
			}

			return 'inc/modules/blog-layouts/' . $blog_post_template;

		}

		/**
		 * Apply style template
		 *
		 * @param  string $style Current style template suuffix
		 *
		 * @return [type]        [description]
		 */
		public function apply_style_template( $style ) {

			$blog_layout_style = kava_theme()->customizer->get_value( 'blog_style' );

			if( 'default' === $blog_layout_style ) {
				$blog_layout_style = false;
			}

		}

		/**
		 * Add blog related customizer options
		 *
		 * @param  array $options Options list
		 * @return array
		 */
		public function customizer_options( $options ) {

			$new_options = array(
				'blog_layout_type' => array(
					'title'    => esc_html__( 'Layout', 'kava' ),
					'priotity' => 1,
					'section'  => 'blog',
					'default'  => 'default',
					'field'    => 'select',
					'choices'  => array(
						'default'          => esc_html__( 'Listing', 'kava' ),
						'grid'             => esc_html__( 'Grid', 'kava' ),
						'masonry'          => esc_html__( 'Masonry', 'kava' ),
						'vertical-justify' => esc_html__( 'Vertical Justify', 'kava' ),
						'creative'         => esc_html__( 'Creative', 'kava' ),
					),
					'type' => 'control',
				),
				'blog_style' => array(
					'title'    => esc_html__( 'Style', 'kava' ),
					'section'  => 'blog',
					'priotity' => 2,
					'default'  => 'default',
					'field'    => 'select',
					'choices'  => array(
						'default' => esc_html__( 'Style 1', 'kava' ),
						'v2'      => esc_html__( 'Style 2', 'kava' ),
						'v3'      => esc_html__( 'Style 3', 'kava' ),
						'v4'      => esc_html__( 'Style 4', 'kava' ),
						'v5'      => esc_html__( 'Style 5', 'kava' ),
						'v6'      => esc_html__( 'Style 6', 'kava' ),
						'v7'      => esc_html__( 'Style 7', 'kava' ),
						'v8'      => esc_html__( 'Style 8', 'kava' ),
						'v9'      => esc_html__( 'Style 9', 'kava' ),
						'v10'     => esc_html__( 'Style 10', 'kava' ),
					),
					'type' => 'control',
				),
			);

			$options['options'] = array_merge( $new_options, $options['options'] );

			return $options;

		}

		/**
		 * Blog layouts styles
		 *
		 * @return void
		 */
		public function enqueue_styles() {
			
		}

	}

}

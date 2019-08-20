<?php
/**
 * Search for post type.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Posts' ) ) {

	/**
	 * Class for the building CX_Control_Posts elements.
	 */
	class CX_Control_Posts extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'           => 'cx-ui-select-id',
			'name'         => 'cx-ui-select-name',
			'size'         => 1,
			'inline_style' => '',
			'value'        => '',
			'post_type'    => 'post',
			'action'       => '',
			'multiple'     => false,
			'placeholder'  => null,
			'label'        => '',
			'class'        => '',
			'clear_label'  => 'Clear',
		);

		/**
		 * Register control dependencies
		 *
		 * @return [type] [description]
		 */
		public function register_depends() {
			wp_register_script(
				'cx-select2',
				$this->base_url . 'assets/lib/select2/select2.min.js',
				array( 'jquery' ),
				'4.0.5',
				true
			);

			wp_register_style(
				'cx-select2',
				$this->base_url . 'assets/lib/select2/select2.min.css',
				array(),
				'4.0.5',
				'all'
			);
		}

		/**
		 * Retrun scripts dependencies list for current control.
		 *
		 * @return array
		 */
		public function get_script_depends() {
			return array( 'cx-select2' );
		}

		/**
		 * Retrun styles dependencies list for current control.
		 *
		 * @return array
		 */
		public function get_style_depends() {
			return array( 'cx-select2' );
		}

		/**
		 * Render html UI_Select.
		 *
		 * @since 1.0.0
		 */
		public function render() {

			$html = '';
			$class = implode( ' ',
				array(
					$this->settings['class'],
				)
			);

			$action    = $this->settings['action'];
			$post_type = $this->settings['post_type'];

			if ( ! is_array( $post_type ) ) {
				$post_type = array( $post_type );
			}

			$post_type = implode( ',', $post_type );

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';
				$html .= '<div class="cx-ui-select-wrapper">';

					( $this->settings['multiple'] ) ? $multi_state = 'multiple="multiple"' : $multi_state = '' ;
					( $this->settings['multiple'] ) ? $name = $this->settings['name'] . '[]' : $name = $this->settings['name'] ;

					if ( '' !== $this->settings['label'] ) {
						$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . $this->settings['label'] . '</label> ';
					}

					$inline_style = $this->settings['inline_style'] ? 'style="' . esc_attr( $this->settings['inline_style'] ) . '"' : '' ;

					if ( ! is_array( $this->settings['value'] ) ) {
						$this->settings['value'] = array( $this->settings['value'] );
					}

					$values = array_filter( $this->settings['value'] );

					if ( ! empty( $values ) ) {
						$stingify_values = implode( ',', $values );
					} else {
						$stingify_values = '';
					}

					$html .= '<select id="' . esc_attr( $this->settings['id'] ) . '" class="cx-ui-select" name="' . esc_attr( $name ) . '" size="' . esc_attr( $this->settings['size'] ) . '" ' . $multi_state . ' data-filter="true" data-placeholder="' . esc_attr( $this->settings['placeholder'] ) . '" ' . $inline_style . ' data-post-type="' . esc_attr( $post_type ) . '" data-action="' . esc_attr( $action ) . '" data-exclude="' . $stingify_values . '">';

						foreach ( $values as $value ) {

							$cpost = get_post( $value );

							if ( ! $cpost ) {
								continue;
							}

							$label = $cpost->post_title;

							$html .= '<option value="' . esc_attr( $value ) . '" selected>' . esc_html( $label ) . '</option>';
					}
					$html .= '</select>';
					$html .= '<a href="#" class="cx-ui-select-clear">' . $this->settings['clear_label'] . '</a>';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

	}
}

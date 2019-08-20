<?php
/**
 * Class for the building ui-select elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Select' ) ) {

	/**
	 * Class for the building CX_Control_Select elements.
	 */
	class CX_Control_Select extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'           => 'cx-ui-select-id',
			'name'         => 'cx-ui-select-name',
			'multiple'     => false,
			'filter'       => false,
			'size'         => 1,
			'inline_style' => 'width: 100%',
			'value'        => 'select-8',
			'placeholder'  => null,
			'required'     => false,
			'options'      => array(
				'select-1' => 'select 1',
				'select-2' => 'select 2',
				'select-3' => 'select 3',
				'select-4' => 'select 4',
				'select-5' => array(
					'label' => 'Group 1',
				),
				'optgroup-1' => array(
					'label'         => 'Group 1',
					'group_options' => array(
						'select-6' => 'select 6',
						'select-7' => 'select 7',
						'select-8' => 'select 8',
					),
				),
				'optgroup-2' => array(
					'label'         => 'Group 2',
					'group_options' => array(
						'select-9'  => 'select 9',
						'select-10' => 'select 10',
						'select-11' => 'select 11',
					),
				),
			),
			'label'  => '',
			'class'  => '',
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

			if ( isset( $this->settings['options_callback'] ) ) {
				$this->settings['options'] = call_user_func( $this->settings['options_callback'] );
			}

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';
				$html .= '<div class="cx-ui-select-wrapper">';
					( $this->settings['filter'] ) ? $filter_state = 'data-filter="true"' : $filter_state = 'data-filter="false"' ;

					( $this->settings['multiple'] ) ? $multi_state = 'multiple="multiple"' : $multi_state = '' ;
					( $this->settings['multiple'] ) ? $name = $this->settings['name'] . '[]' : $name = $this->settings['name'] ;

					if ( '' !== $this->settings['label'] ) {
						$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . $this->settings['label'] . '</label> ';
					}

					$inline_style = $this->settings['inline_style'] ? 'style="' . esc_attr( $this->settings['inline_style'] ) . '"' : '' ;

					$html .= '<select id="' . esc_attr( $this->settings['id'] ) . '" class="cx-ui-select" name="' . esc_attr( $name ) . '" size="' . esc_attr( $this->settings['size'] ) . '" ' . $multi_state . ' ' . $filter_state . ' data-placeholder="' . esc_attr( $this->settings['placeholder'] ) . '" ' . $inline_style . ' ' . $this->get_required() . '>';

					if ( $this->settings['options'] && ! empty( $this->settings['options'] ) && is_array( $this->settings['options'] ) ) {
						foreach ( $this->settings['options'] as $option => $option_value ) {

							if ( ! is_array( $this->settings['value'] ) ) {
								$this->settings['value'] = array( $this->settings['value'] );
							}

							if ( false === strpos( $option, 'optgroup' ) ) {
								$selected_state = '';
								if ( $this->settings['value'] && ! empty( $this->settings['value'] ) ) {
									foreach ( $this->settings['value'] as $key => $value ) {
										$selected_state = selected( $value, $option, false );
										if ( " selected='selected'" == $selected_state ) {
											break;
										}
									}
								}

								if ( is_array( $option_value ) ) {
									$label = $option_value['label'];
								} else {
									$label = $option_value;
								}

								$html .= '<option value="' . esc_attr( $option ) . '" ' . $selected_state . '>' . esc_html( $label ) . '</option>';
							} else {
								$html .= '<optgroup label="' . esc_attr( $option_value['label'] ) . '">';
									$selected_state = '';
									foreach ( $option_value['group_options'] as $group_item => $group_value ) {
										foreach ( $this->settings['value'] as $key => $value ) {
											$selected_state = selected( $value, $group_item, false );
											if ( " selected='selected'" == $selected_state ) {
												break;
											}
										}
										$html .= '<option value="' . esc_attr( $group_item ) . '" ' . $selected_state . '>' . esc_html( $group_value ) . '</option>';
									}
								$html .= '</optgroup>';
							}
						}
					}
					$html .= '</select>';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

	}
}

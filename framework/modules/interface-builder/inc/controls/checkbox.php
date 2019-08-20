<?php
/**
 * Class for the building checkbox elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Checkbox' ) ) {

	/**
	 * Class for the building CX_Control_Checkbox elements.
	 */
	class CX_Control_Checkbox extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'       => 'cx-checkbox-id',
			'name'     => 'cx-checkbox-name',
			'required' => false,
			'value'    => array(
				'checkbox-1' => 'true',
				'checkbox-2' => 'true',
				'checkbox-3' => 'true',
			),
			'options' => array(
				'checkbox-1' => 'checkbox 1',
				'checkbox-2' => 'checkbox 2',
				'checkbox-3' => 'checkbox 3',
			),
			'label'  => '',
			'class'  => '',
		);

		/**
		 * Render html UI_Checkbox.
		 *
		 * @since 1.0.0
		 */
		public function render() {

			$html  = '';

			$class = implode( ' ',
				array(
					$this->settings['class'],
				)
			);

			$html .= '<div class="cx-ui-control-container ' . esc_attr( $class ) . '">';

			$counter = 0;

			if ( isset( $this->settings['options_callback'] ) ) {
				$this->settings['options'] = call_user_func( $this->settings['options_callback'] );
			}

			if ( ! empty( $this->settings['options'] ) && is_array( $this->settings['options'] ) ) {

				if ( ! is_array( $this->settings['value'] ) ) {
					$this->settings['value'] = array( $this->settings['value'] );
				}

				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}

				foreach ( $this->settings['options'] as $option => $option_value ) {

					if ( ! empty( $this->settings['value'] ) ) {
						$option_checked = array_key_exists( $option, $this->settings['value'] ) ? $option : '';
						$item_value     = ! empty( $option_checked ) ? $this->settings['value'][ $option ] : 'false';
					} else {
						$option_checked = '';
						$item_value     = 'false';
					}

					$checked      = ( ! empty( $option_checked ) && filter_var( $item_value, FILTER_VALIDATE_BOOLEAN ) ) ? 'checked' : '';
					$item_value   = filter_var( $item_value, FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
					$option_label = isset( $option_value ) && is_array( $option_value ) ? $option_value['label'] : $option_value;

					$html .= '<div class="cx-checkbox-item-wrap">';
						$html .= '<span class="cx-label-content">';
						$html .= '<input type="hidden" id="' . esc_attr( $this->settings['id'] ) . '-' . $counter . '" class="cx-checkbox-input" name="' . esc_attr( $this->settings['name'] ) . '[' . $option . ']" ' . $checked . ' value="' . $item_value . '" ' . $this->get_required() . '>';
						$html .= '<span class="cx-checkbox-item"><span class="marker dashicons dashicons-yes"></span></span>';
						$html .= '<label class="cx-checkbox-label" for="' . esc_attr( $this->settings['id'] ) . '-' . $counter . '"><span class="cx-label-content">' . esc_html( $option_label ) . '</span></label> ';
						$html .= '</span>';
					$html .= '</div>';

					$counter++;
				}
			}

			$html .= '</div>';

			return $html;
		}
	}
}

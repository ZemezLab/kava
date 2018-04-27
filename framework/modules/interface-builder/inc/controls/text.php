<?php
/**
 * Class for the building ui-text elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Text' ) ) {

	/**
	 * Class for the building ui-text elements.
	 */
	class CX_Control_Text extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'type'        => 'text',
			'id'          => 'cx-ui-input-id',
			'name'        => 'cx-ui-input-name',
			'value'       => '',
			'placeholder' => '',
			'label'       => '',
			'class'       => '',
			'required'    => false,
		);

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
		 * Render html UI_Text.
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

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';
				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}
				$html .= '<input type="' . esc_attr( $this->settings['type'] ) . '" id="' . esc_attr( $this->settings['id'] ) . '" class="widefat cx-ui-text" name="' . esc_attr( $this->settings['name'] ) . '" value="' . esc_html( $this->settings['value'] ) . '" placeholder="' . esc_attr( $this->settings['placeholder'] ) . '" ' . $this->get_required() . '>';
			$html .= '</div>';
			return $html;
		}
	}
}

<?php
/**
 * Class for the building ui-textarea elements
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Textarea' ) ) {

	/**
	 * Class for the building CX_Control_Textarea elements.
	 */
	class CX_Control_Textarea extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'          => 'cx-ui-textarea-id',
			'name'        => 'cx-ui-textarea-name',
			'value'       => '',
			'placeholder' => '',
			'rows'        => '10',
			'cols'        => '20',
			'label'       => '',
			'class'       => '',
			'maxlength'   => false,
			'required'    => false,
		);

		/**
		 * Get maxlength attribute
		 *
		 * @return [type] [description]
		 */
		public function get_maxlength() {

			if ( empty( $this->settings['maxlength'] ) ) {
				return;
			}

			$maxlength = absint( $this->settings['maxlength'] );

			if ( ! $maxlength ) {
				return;
			}

			return 'maxlength="' . $maxlength . '"';
		}

		/**
		 * Render html UI_Textarea.
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
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . $this->settings['label'] . '</label> ';
				}
				$html .= '<textarea id="' . esc_attr( $this->settings['id'] ) . '" class="cx-ui-textarea" name="' . esc_attr( $this->settings['name'] ) . '" rows="' . esc_attr( $this->settings['rows'] ) . '" cols="' . esc_attr( $this->settings['cols'] ) . '" placeholder="' . esc_attr( $this->settings['placeholder'] ) . '" ' . $this->get_required() . ' ' . $this->get_maxlength() . '>' . esc_html( $this->settings['value'] ) . '</textarea>';
			$html .= '</div>';

			return $html;
		}
	}
}

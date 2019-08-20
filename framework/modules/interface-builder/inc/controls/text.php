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
			'type'         => 'text',
			'input_type'   => '', // 'type' alias to avoid conflicts in Post Meta and Term Meta modules
			'id'           => 'cx-ui-input-id',
			'name'         => 'cx-ui-input-name',
			'autocomplete' => 'on',
			'value'        => '',
			'placeholder'  => '',
			'label'        => '',
			'class'        => '',
			'maxlength'    => false,
			'required'     => false,
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

			if ( ! empty( $this->settings['input_type'] ) ) {
				$type = esc_attr( $this->settings['input_type'] );
			} else {
				$type = esc_attr( $this->settings['type'] );
			}

			$autocomplete = ! empty( $this->settings['autocomplete'] ) ? $this->settings['autocomplete'] : 'on';
			$autocomplete = sprintf( 'autocomplete="%s"', $autocomplete );

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';
				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}

				$html .= '<input type="' . $type . '" id="' . esc_attr( $this->settings['id'] ) . '" class="widefat cx-ui-text" name="' . esc_attr( $this->settings['name'] ) . '" value="' . esc_html( $this->settings['value'] ) . '" placeholder="' . esc_attr( $this->settings['placeholder'] ) . '" ' . $this->get_required() . ' ' . $this->get_maxlength() . ' ' . $autocomplete . '>';
			$html .= '</div>';
			return $html;
		}
	}
}

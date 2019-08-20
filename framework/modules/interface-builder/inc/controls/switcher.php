<?php
/**
 * Class for the building ui swither elements .
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Switcher' ) ) {

	/**
	 * Class for the building CX_Control_Switcher elements.
	 */
	class CX_Control_Switcher extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'     => 'cx-ui-swither-id',
			'name'   => 'cx-ui-swither-name',
			'value'  => true,
			'toggle' => array(
				'true_toggle'  => 'On',
				'false_toggle' => 'Off',
			),
			'label'  => '',
			'class'  => '',
		);

		/**
		 * Render html UI_Switcher.
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

				$value = filter_var( $this->settings['value'], FILTER_VALIDATE_BOOLEAN );

				$html .= '<div class="cx-switcher-wrap">';
					$html .= '<input type="radio" id="' . esc_attr( $this->settings['id'] ) . '-true" class="cx-input-switcher cx-input-switcher-true" name="' . esc_attr( $this->settings['name'] ) . '" ' . checked( true, $value, false ) . ' value="true">';
					$html .= '<input type="radio" id="' . esc_attr( $this->settings['id'] ) . '-false" class="cx-input-switcher cx-input-switcher-false" name="' . esc_attr( $this->settings['name'] ) . '" ' . checked( false, $value, false ) . ' value="false">';
					$html .= '<span class="bg-cover"></span>';
					$html .= '<label class="sw-enable"><span>' . esc_html( $this->settings['toggle']['true_toggle'] ) . '</span></label>';
					$html .= '<label class="sw-disable"><span>' . esc_html( $this->settings['toggle']['false_toggle'] ) . '</span></label>';
					$html .= '<span class="state-marker"></span>';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

	}
}

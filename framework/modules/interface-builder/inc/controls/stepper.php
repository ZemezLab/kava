<?php
/**
 * Class for the building ui stepper elements.
 *
 * @package    Cherry_Framework
 * @subpackage Class
 * @author     Cherry Team <support@cxframework.com>
 * @copyright  Copyright (c) 2012 - 2015, Cherry Team
 * @link       http://www.cxframework.com/
 * @license    http://www.gnu.org/licenses/gpl-3.0.en.html
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Stepper' ) ) {

	/**
	 * Class for the building CX_Control_Stepper elements.
	 */
	class CX_Control_Stepper extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'          => 'cx-ui-stepper-id',
			'name'        => 'cx-ui-stepper-name',
			'value'       => '0',
			'max_value'   => '100',
			'min_value'   => '0',
			'step_value'  => '1',
			'label'       => '',
			'class'       => '',
			'placeholder' => '',
		);

		/**
		 * Render html UI_Stepper.
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

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';

				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}
				$html .= '<div class="cx-ui-stepper">';
					$html .= '<input type="number" id="' . esc_attr( $this->settings['id'] ) . '" class="cx-ui-stepper-input" pattern="[0-5]+([\.,][0-5]+)?" name="' . esc_attr( $this->settings['name'] ) . '" value="' . esc_html( $this->settings['value'] ) . '" min="' . esc_html( $this->settings['min_value'] ) . '" max="' . esc_html( $this->settings['max_value'] ) . '" step="' . esc_html( $this->settings['step_value'] ) . '" placeholder="' . esc_attr( $this->settings['placeholder'] ) . '">';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

	}
}

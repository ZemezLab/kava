<?php
/**
 * Class for the building ui slider elements .
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Slider' ) ) {

	/**
	 * Class for the building UI_Slider elements.
	 */
	class CX_Control_Slider extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'         => 'cx-ui-slider-id',
			'name'       => 'cx-ui-slider-name',
			'max_value'  => 100,
			'min_value'  => 0,
			'value'      => 50,
			'step_value' => 1,
			'label'      => '',
			'class'      => '',
		);

		/**
		 * Render html UI_Slider.
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

			$ui_stepper = new CX_Control_Stepper(
				array(
					'id'         => $this->settings['id'] . '-stepper',
					'name'       => $this->settings['name'],
					'max_value'  => $this->settings['max_value'],
					'min_value'  => $this->settings['min_value'],
					'value'      => $this->settings['value'],
					'step_value' => $this->settings['step_value'],
				)
			);

			$ui_stepper_html = $ui_stepper->render();

				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}
				$html .= '<div class="cx-slider-wrap">';
					$html .= '<div class="cx-slider-holder">';
						$html .= '<input type="range" class="cx-slider-unit" step="' . esc_attr( $this->settings['step_value'] ) . '" min="' . esc_attr( $this->settings['min_value'] ) . '" max="' . esc_attr( $this->settings['max_value'] ) . '" value="' . esc_attr( $this->settings['value'] ) . '">';
					$html .= '</div>';
					$html .= '<div class="cx-slider-input">';
						$html .= $ui_stepper_html;
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		/**
		 * Enqueue javascript and stylesheet UI_Slider.
		 *
		 * @since 1.0.0
		 */
		public static function enqueue_assets() {
			wp_enqueue_script(
				'ui-slider',
				esc_url( Cherry_Core::base_url( 'inc/ui-elements/ui-slider/assets/min/ui-slider.min.js', Cherry_UI_Elements::$module_path ) ),
				array( 'jquery' ),
				Cherry_UI_Elements::$core_version,
				true
			);

			wp_enqueue_style(
				'ui-slider',
				esc_url( Cherry_Core::base_url( 'inc/ui-elements/ui-slider/assets/min/ui-slider.min.css', Cherry_UI_Elements::$module_path ) ),
				array(),
				Cherry_UI_Elements::$core_version,
				'all'
			);
		}
	}
}

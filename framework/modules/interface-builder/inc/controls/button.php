<?php
/**
 * Class for the building ui-button elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Button' ) ) {

	/**
	 * Class for the building ui-button elements.
	 */
	class CX_Control_Button extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'          => 'cherry-ui-button-id',
			'name'        => 'cherry-ui-button-name',
			'value'       => 'button',
			'disabled'    => false,
			'form'        => '',
			'formaction'  => '',
			'button_type' => 'button',
			'style'       => 'normal',
			'content'     => 'Button',
			'class'       => '',
		);

		/**
		 * Render html UI_Button.
		 *
		 * @since 1.0.0
		 */
		public function render() {

			$classes = array(
				'cx-button',
				'cx-button-' . $this->settings['style'] . '-style',
				$this->settings['class'],
			);

			$classes = array_filter( $classes );
			$class   = trim( implode( ' ', $classes ) );
			$attrs   = array(
				'type'       => esc_attr( $this->settings['button_type'] ),
				'id'         => esc_attr( $this->settings['id'] ),
				'name'       => esc_attr( $this->settings['name'] ),
				'class'      => esc_attr( $class ),
				'form'       => esc_attr( $this->settings['form'] ),
				'formaction' => esc_attr( $this->settings['formaction'] ),
			);

			if ( filter_var( $this->settings['disabled'], FILTER_VALIDATE_BOOLEAN ) ) {
				$attrs['disabled'] = 'disabled';
			}

			$html = sprintf(
				'<button %1$s>%2$s</button>',
				$this->get_attr_string( $attrs ),
				$this->settings['content']
			);

			return $html;
		}
	}
}

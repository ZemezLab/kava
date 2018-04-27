<?php
/**
 * Class for the building dimensions elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Dimensions' ) ) {

	/**
	 * Class for the building ui-dimensions elements.
	 */
	class CX_Control_Dimensions extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'          => 'cx-dimensions-id',
			'name'        => 'cx-dimensions-name',
			'value'       => array(),
			'range'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			),
			'dimension_labels' => array(
				'top'    => 'Top',
				'right'  => 'Right',
				'bottom' => 'Bottom',
				'left'   => 'Left',
			),
			'label'            => '',
			'class'            => '',
			'required'         => false,
		);

		protected $default_value = array(
			'units'     => 'px',
			'is_linked' => true,
			'top'       => '',
			'right'     => '',
			'bottom'    => '',
			'left'      => '',
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
		 * Render html UI_Dimension.
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

			if ( empty( $this->settings['value'] ) ) {
				$this->settings['value'] = $this->default_value;
			} else {
				$this->settings['value'] = array_merge( $this->default_value, $this->settings['value'] );
			}

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';

				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}

				$html .= $this->get_fields();
			$html .= '</div>';
			return $html;
		}

		/**
		 * Return UI fileds
		 * @return [type] [description]
		 */
		public function get_fields() {

			$hidden = '<input type="hidden" name="%1$s" id="%3$s" value="%2$s">';
			$number = '<div class="cx-ui-dimensions__value-item"><input type="number" name="%1$s" id="%3$s" value="%2$s" min="%4$s" max="%5$s" step="%6$s" class="cx-ui-dimensions__val%7$s"><span class="cx-ui-dimensions__value-label">%8$s</span></div>';

			$value = $this->settings['value'];
			$value = array_merge( $this->default_value, $value );

			$result = sprintf(
				'<div class="cx-ui-dimensions" data-range=\'%s\'>',
				json_encode( $this->settings['range'] )
			);

			foreach ( array( 'units', 'is_linked' ) as $field ) {
				$result .= sprintf(
					$hidden,
					$this->get_name_attr( $field ), $value[ $field ], $this->get_id_attr( $field )
				);
			}
			$result .= $this->get_units();
			$result .= '<div class="cx-ui-dimensions__values">';

			$value['is_linked'] = filter_var( $value['is_linked'], FILTER_VALIDATE_BOOLEAN );

			foreach ( array( 'top', 'right', 'bottom', 'left' ) as $field ) {
				$result .= sprintf(
					$number,
					$this->get_name_attr( $field ),
					$value[ $field ],
					$this->get_id_attr( $field ),
					$this->settings['range'][ $value['units'] ]['min'],
					$this->settings['range'][ $value['units'] ]['max'],
					$this->settings['range'][ $value['units'] ]['step'],
					( true === $value['is_linked'] ? ' is-linked' : '' ),
					$this->settings['dimension_labels'][ $field ]
				);
			}
			$result .= sprintf(
				'<div class="cx-ui-dimensions__is-linked%s"><span class="dashicons dashicons-admin-links link-icon"></span><span class="dashicons dashicons-editor-unlink unlink-icon"></span></div>',
				( true === $value['is_linked'] ? ' is-linked' : '' )
			);
			$result .= '</div>';
			$result .= '</div>';

			return $result;
		}

		/**
		 * Returns units selector
		 *
		 * @return string
		 */
		public function get_units() {

			$units    = array_keys( $this->settings['range'] );
			$switcher = 'can-switch';

			if ( 1 === count( $units ) ) {
				$switcher = '';
			}

			$item   = '<span class="cx-ui-dimensions__unit%2$s" data-unit="%1$s">%1$s</span>';
			$result = '';

			foreach ( $units as $unit ) {
				$result .= sprintf(
					$item,
					$unit,
					( $this->settings['value']['units'] === $unit ? ' is-active' : '' )
				);
			}

			return sprintf( '<div class="cx-ui-dimensions__units">%s</div>', $result );
		}

		/**
		 * Retrurn full name attibute by name
		 *
		 * @param  [type] $name [description]
		 * @return [type]       [description]
		 */
		public function get_name_attr( $name = '' ) {
			return sprintf( '%s[%s]', esc_attr( $this->settings['name'] ), esc_attr( $name ) );
		}

		/**
		 * Retrurn full ID attibute by name
		 *
		 * @param  [type] $name [description]
		 * @return [type]       [description]
		 */
		public function get_id_attr( $name = '' ) {
			return sprintf( '%s_%s', esc_attr( $this->settings['name'] ), esc_attr( $name ) );
		}

	}
}

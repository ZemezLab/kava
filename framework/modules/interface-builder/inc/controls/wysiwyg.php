<?php
/**
 * Class for the building ui-wysiwyg elements
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Wysiwyg' ) ) {

	/**
	 * Class for the building CX_Control_Wysiwyg elements.
	 */
	class CX_Control_Wysiwyg extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'id'          => 'cx-ui-wysiwyg-id',
			'name'        => 'cx-ui-wysiwyg-name',
			'value'       => '',
			'placeholder' => '',
			'rows'        => '10',
			'cols'        => '20',
			'label'       => '',
			'class'       => '',
		);

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

				$editor_id  = str_replace( array( '_', '-' ), '', strtolower( $this->settings['id'] ) );
				$editor_id .= $this->get_rand_str();

				ob_start();
				wp_editor( $this->settings['value'], $editor_id, array(
					'textarea_name' => esc_attr( $this->settings['name'] ),
					'textarea_rows' => esc_attr( $this->settings['cols'] ),
				) );

				$html .= ob_get_clean();

			$html .= '</div>';

			return $html;
		}

		/**
		 * Get random string
		 *
		 * @return string
		 */
		public function get_rand_str() {

			$letters = array( 'a', 'b', 'c', 'd', 'e', 'f', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z' );

			$res = '';

			for ( $i = 0; $i < 4; $i++ ) {
				$index = rand( 0, count( $letters ) - 1 );
				$res  .= isset( $letters[ $index ] ) ? $letters[ $index ] : '';
			}

			return $res;

		}

	}
}

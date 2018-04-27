<?php
/**
 * Class for the building iconpicker elements.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Control_Iconpicker' ) ) {

	/**
	 * Class for the building ui-iconpicker elements.
	 */
	class CX_Control_Iconpicker extends CX_Controls_Base {

		/**
		 * Default settings.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public $defaults_settings = array(
			'type'        => 'iconpicker',
			'id'          => 'cx-iconpicker-id',
			'name'        => 'cx-iconpicker-name',
			'value'       => '',
			'placeholder' => '',
			'icon_data'   => array(),
			'auto_parse'  => false,
			'label'       => '',
			'class'       => '',
			'master'      => '',
			'width'       => 'fixed',
			'required'    => false,
		);

		/**
		 * Default icon data settings.
		 *
		 * @var array
		 */
		private $default_icon_data = array(
			'icon_set'    => '',
			'icon_css'    => '',
			'icon_base'   => 'icon',
			'icon_prefix' => '',
			'icons'       => '',
		);

		/**
		 * Icons sets
		 *
		 * @var array
		 */
		public static $sets = array();

		/**
		 * Check if sets already printed
		 *
		 * @var boolean
		 */
		public static $printed = false;

		/**
		 * Array of already printed sets to check it before printing current
		 *
		 * @var array
		 */
		public static $printed_sets = array();

		/**
		 * Temporary icons holder
		 *
		 * @var null
		 */
		public $temp_icons = null;

		/**
		 * Init
		 * @return [type] [description]
		 */
		public function init() {
			add_action( 'admin_footer', array( $this, 'print_icon_set' ), 1 );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_icon_set' ), 9999 );
			add_filter( 'cx_handler_response_data', array( $this, 'send_icon_set' ), 10, 1 );
		}

		/**
		 * Register control dependencies
		 *
		 * @return [type] [description]
		 */
		public function register_depends() {
			wp_register_script(
				'cx-iconpicker',
				$this->base_url . 'assets/lib/iconpicker/jquery-iconpicker.js',
				array( 'jquery' ),
				'1.0.0',
				true
			);
		}

		/**
		 * Retrun scripts dependencies list for current control.
		 *
		 * @return array
		 */
		public function get_script_depends() {
			return array( 'cx-iconpicker' );
		}

		/**
		 * Get required attribute
		 *
		 * @return string required attribute
		 */
		public function get_required() {
			if ( $this->settings['required'] ) {
				return 'required="required"';
			}
			return '';
		}

		/**
		 * Render html UI_Iconpicker.
		 *
		 * @since 1.0.0
		 */
		public function render() {

			$html  = '';
			$class = implode( ' ',
				array(
					$this->settings['class'],
					$this->settings['width'],
				)
			);

			$html .= '<div class="cx-ui-container ' . esc_attr( $class ) . '">';
				if ( '' !== $this->settings['label'] ) {
					$html .= '<label class="cx-label" for="' . esc_attr( $this->settings['id'] ) . '">' . esc_html( $this->settings['label'] ) . '</label> ';
				}

				$this->settings['icon_data'] = wp_parse_args(
					$this->settings['icon_data'],
					$this->default_icon_data
				);

				$html .= '<div class="cx-ui-iconpicker-group">';

				if ( $this->validate_icon_data( $this->settings['icon_data'] ) ) {
					$html .= $this->render_picker();
				} else {
					$html .= 'Incorrect Icon Data Settings';
				}

				$html .= '</div>';
			$html .= '</div>';

			/**
			 * Maybe add js repeater template to response
			 *
			 * @var bool
			 */
			$add_js_to_response = apply_filters( 'cx_control/add_data_to_element', false );

			if ( $add_js_to_response ) {

				ob_start();
				$this->print_icon_set();
				$icons = ob_get_clean();

				$in_repeater = apply_filters( 'cx_control/is_repeater', false );

				if ( $in_repeater ) {
					$this->temp_icons = $icons;
					add_filter( 'cx_control/add_repeater_data', array( $this, 'store_icons' ) );
				} else {
					$html .= $icons;
				}

			}

			return $html;
		}

		public function store_icons( $data = array() ) {

			if ( ! is_array( $data ) ) {
				$data = array();
			}

			$data[] = $this->temp_icons;

			return $data;
		}

		/**
		 * Returns iconpicker html markup
		 *
		 * @return string
		 */
		private function render_picker() {

			$format = '<span class="input-group-addon"></span><input type="text" name="%1$s" id="%2$s" value="%3$s" class="widefat cx-ui-text cx-ui-iconpicker %4$s" data-set="%5$s">';

			$this->prepare_icon_set();

			return sprintf(
				$format,
				$this->settings['name'],
				$this->settings['id'],
				$this->settings['value'],
				$this->settings['class'],
				$this->settings['icon_data']['icon_set']
			);

		}

		/**
		 * Return JS markup for icon set variable.
		 *
		 * @return void
		 */
		public function prepare_icon_set() {

			if ( empty( $this->settings['icon_data']['icons'] ) ) {
				$this->maybe_parse_set_from_css();
			}

			if ( ! array_key_exists( $this->settings['icon_data']['icon_set'], self::$sets ) ) {
				self::$sets[ $this->settings['icon_data']['icon_set'] ] = array(
					'iconCSS'    => $this->settings['icon_data']['icon_css'],
					'iconBase'   => $this->settings['icon_data']['icon_base'],
					'iconPrefix' => $this->settings['icon_data']['icon_prefix'],
					'icons'      => $this->settings['icon_data']['icons'],
				);
			}
		}

		/**
		 * Check if 'parse_set' is true and try to get icons set from CSS file
		 *
		 * @return void
		 */
		private function maybe_parse_set_from_css() {

			if ( true !== $this->settings['auto_parse'] || empty( $this->settings['icon_data']['icon_css'] ) ) {
				return;
			}

			ob_start();

			$path = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $this->settings['icon_data']['icon_css'] );
			if ( file_exists( $path ) ) {
				include $path;
			}

			$result = ob_get_clean();

			preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $matches );

			if ( ! is_array( $matches ) || empty( $matches[1] ) ) {
				return;
			}

			if ( is_array( $this->settings['icon_data']['icons'] ) ) {
				$this->settings['icon_data']['icons'] = array_merge(
					$this->settings['icon_data']['icons'],
					$matches[1]
				);
			} else {
				$this->settings['icon_data']['icons'] = $matches[1];
			}

		}

		/**
		 * Checks if all required icon data fields are passed
		 *
		 * @param  array $data Icon data.
		 * @return bool
		 */
		private function validate_icon_data( $data ) {

			$validate = array_diff( $this->default_icon_data, array( 'icon_base', 'icon_prefix' ) );

			foreach ( $validate as $key => $field ) {

				if ( empty( $data[ $key ] ) ) {
					return false;
				}

				return true;
			}

		}

		/**
		 * Function sends the icons into ajax response.
		 *
		 * @param  array $data Icon data.
		 * @return array
		 */
		public function send_icon_set( $data ) {

			if ( empty( $data['CxIconSets'] ) ) {
				$data['CxIconSets'] = array();
			}

			foreach ( self::$sets as $key => $value ) {
				$data['CxIconSets'][ $key ] = $value;
			}

			return $data;
		}

		/**
		 * Print icon sets
		 *
		 * @return void
		 */
		public function print_icon_set() {

			if ( empty( self::$sets ) || true === self::$printed ) {
				return;
			}

			self::$printed = true;

			foreach ( self::$sets as $set => $data ) {

				if ( in_array( $set, self::$printed_sets ) ) {
					continue;
				}

				self::$printed_sets[] = $set;
				$json = json_encode( $data );

				printf(
					'<script> if ( ! window.CxIconSets ) { window.CxIconSets = {} } window.CxIconSets.%1$s = %2$s</script>',
					$set,
					$json
				);
			}

		}

	}
}

<?php
/**
 * Module allows to work with dynamically generated CSS.
 *
 * Version: 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Dynamic_CSS' ) ) {

	/**
	 * Dynamic CSS main class
	 */
	class CX_Dynamic_CSS {

		/**
		 * Module arguments
		 *
		 * @var array
		 */
		public $args = array();

		/**
		 * Holder for processed variables array
		 *
		 * @var array
		 */
		public $variables = null;

		/**
		 * Variable pattern
		 *
		 * @var array
		 */
		public $var_pattern = '/\$(([-_a-zA-Z0-9]+)(\[[\'\"]*([-_a-zA-Z0-9]+)[\'\"]*\])?({([a-z%]+)})?)/';

		/**
		 * Function pattern
		 *
		 * @var array
		 */
		public $func_pattern = '/@(([a-zA-Z_]+)\(([^@\)]*)?\))/';

		/**
		 * Collector instance holder
		 *
		 * @since 1.2.0
		 * @var   object
		 */
		public $collector = null;

		/**
		 * Reserved words list
		 *
		 * @since 1.4.0
		 * @var   array
		 */
		public $css_reserved = array(
			'media',
			'supports',
		);

		/**
		 * Constructor for the module
		 */
		function __construct( $args ) {

			$this->args = wp_parse_args( $args, array(
				'prefix'         => 'blank',
				'type'           => 'theme_mod',
				'parent_handles' => array(
					'css' => false,
					'js'  => false,
				),
				'single'         => true,
				'css_files'      => null,
				'options_cb'     => false,
				'options'        => array(),
			) );

			$this->init_dynamic_css();
			$this->init_collector();
		}

		/**
		 * Initalize dynamic CSS with fallback compatibility.
		 *
		 * @since  1.3.0
		 * @return void
		 */
		public function init_dynamic_css() {

			$handle = isset( $this->args['parent_handles']['css'] ) ? $this->args['parent_handles']['css'] : false;

			/**
			 * Not actual for now, required only for fallback compatibility.
			 */
			if ( ! $handle ) {
				add_action( 'wp_head', array( $this, 'print_inline_css' ), 99 );
			} else {
				add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_css' ), 99 );
			}

		}

		/**
		 * Adds inline CSS into queue
		 *
		 * @since  1.3.0
		 * @return void
		 */
		public function add_inline_css() {
			$handle = isset( $this->args['parent_handles']['css'] ) ? $this->args['parent_handles']['css'] : false;
			wp_add_inline_style( $handle, $this->get_inline_css() );
		}

		/**
		 * Initalize CSS collector class
		 *
		 * @since  1.2.0
		 * @return bool
		 */
		public function init_collector() {

			if ( null !== $this->collector ) {
				return true;
			}

			require_once 'inc/class-cx-dynamic-css-collector.php';

			ob_start();
			include 'assets/min/cx-css-collector.min.js';

			$handler = ob_get_clean();
			$handle  = isset( $this->args['parent_handles']['js'] ) ? $this->args['parent_handles']['js'] : null;

			$this->collector = new CX_Dynamic_CSS_Collector( $handler, $handle );

			add_action( 'wp_footer', array( $this->collector, 'print_style' ), 11 );
			add_action( 'wp_footer', array( $this->collector, 'add_js_handler' ), 11 );

			return true;
		}

		/**
		 * Add new style to collector
		 *
		 * @since  1.2.0
		 * @param  string $selector CSS selector to add styles for.
		 * @param  array  $style    Styles array to add.
		 * @param  array  $media    Media breakpoints.
		 * @return void
		 */
		public function add_style( $selector, $style = array(), $media = array() ) {

			if ( ! $selector ) {
				return;
			}

			self::$collector->add_style( $selector, $style, $media );
		}

		/**
		 * Fix customizer preview variables.
		 *
		 * @param  [type] $variables [description]
		 * @return [type]            [description]
		 */
		public function fix_customizer_preview_vars( $variables ) {

			$result = array();

			foreach ( $variables as $name => $value ) {
				$result[ $name ] = get_theme_mod( $name );
			}

			return $result;
		}

		/**
		 * Get CSS variables into array
		 *
		 * @since  1.0.0
		 * @return array  dynamic CSS variables
		 */
		public function get_css_varaibles() {

			if ( null !== $this->variables ) {
				return $this->variables;
			}

			if ( ! empty( $this->args['options_cb'] ) && is_callable( $this->args['options_cb'] ) ) {

				$this->variables = call_user_func( $this->args['options_cb'] );

				if ( 'theme_mod' === $this->args['type'] && is_customize_preview() ) {
					$this->variables = $this->fix_customizer_preview_vars( $this->variables );
				}

				/**
				 * Filter result variables list with values
				 *
				 * @since 1.0.0
				 * @param array $variables  default variables list.
				 * @param array $this->args module arguments.
				 */
				$this->variables = apply_filters( 'cx_dynamic_css/variables', $this->variables, $this->args );

				return $this->variables;

			}

			$variables = $this->get_standard_vars();
			$var_list  = ! empty( $this->args['options'] ) ? $this->args['options'] : array();

			/**
			 * Filter options names list to use it as varaibles
			 *
			 * @since 1.0.0
			 * @param array $var_list   default variables list.
			 * @param array $this->args module arguments.
			 */
			$var_list = apply_filters( 'cx_dynamic_css/var_list', $var_list, $this->args );

			if ( empty( $var_list ) ) {
				return $variables;
			}

			$custom_vars = array();
			foreach ( $var_list as $var ) {
				$custom_vars[ $var ] = $this->get_setting( $var );
			}

			$variables = array_merge( $variables, $custom_vars );

			/**
			 * Filter result variables list with values
			 *
			 * @since 1.0.0
			 * @param array $variables  default variables list.
			 * @param array $this->args module arguments.
			 */
			$this->variables = apply_filters( 'cx_dynamic_css/variables', $variables, $this->args );

			return $this->variables;
		}

		/**
		 * Get current setting by name
		 *
		 * @since  1.0.0
		 * @return mixed
		 */
		public function get_setting( $name ) {

			$type = $this->args['type'];

			if ( 'theme_mod' == $type ) {
				$setting = get_theme_mod( $name );
				return $setting;
			}

			if ( true != $this->args['single'] ) {
				$setting = get_option( $name );
				return $setting;
			}

			$settings = get_option( $this->args['prefix'] );

			if ( ! empty( $settings ) && isset( $settings[ $name ] ) ) {
				return $settings[ $name ];
			}

			return false;
		}

		/**
		 * Get standard WordPress variables from customizer - header image, background image etc.
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_standard_vars() {

			$standard_vars = array(
				'header_image',
				'background_image',
				'background_repeat',
				'background_position_x',
				'background_attachment',
			);

			$result = array();

			foreach ( $standard_vars as $var ) {
				$result[ $var ] = get_theme_mod( $var );
			}

			return $result;
		}

		/**
		 * Get avaliable functions into array
		 *
		 * @since  1.0.0
		 * @return array  dynamic CSS variables
		 */
		public function get_css_functions() {

			require_once 'inc/class-cx-dynamic-css-utilities.php';
			$utilities = CX_Dynamic_CSS_Utilities::get_instance();

			$func_list = array(
				'darken'               => array( $utilities, 'color_darken' ),
				'lighten'              => array( $utilities, 'color_lighten' ),
				'contrast'             => array( $utilities, 'color_contrast' ),
				'alpha'                => array( $utilities, 'color_alpha' ),
				'background'           => array( $utilities, 'background_css' ),
				'background_url'       => array( $utilities, 'background_url' ),
				'typography'           => array( $utilities, 'get_typography_css' ),
				'box'                  => array( $utilities, 'get_box_model_css' ),
				'emph'                 => array( $utilities, 'element_emphasis' ),
				'font_family'          => array( $utilities, 'typography_font_family' ),
				'font_size'            => array( $utilities, 'typography_size' ),
				'container_compare'    => array( $utilities, 'container_width_compare' ),
				'sum'                  => array( $utilities, 'simple_sum' ),
				'diff'                 => array( $utilities, 'simple_diff' ),
				'menu_toogle_endpoint' => array( $utilities, 'menu_toogle_endpoint' ),
			);
			/**
			 * Filter available CSS functions list
			 *
			 * @since 1.0.0
			 * @param array $func_list  default functions list.
			 * @param array $this->args module arguments.
			 */
			return apply_filters( 'cx_dynamic_css/func_list', $func_list, $this->args );
		}

		/**
		 * Parse CSS string and replasce varaibles and functions
		 *
		 * @since  1.0.0
		 * @param  [type] $css CSS to parse.
		 * @return string
		 */
		public function parse( $css ) {

			$replce_vars  = preg_replace_callback( $this->var_pattern, array( $this, 'replace_vars' ), $css );
			$replace_func = preg_replace_callback( $this->func_pattern, array( $this, 'replace_func' ), $replce_vars );
			$result       = preg_replace( '/\t|\r|\n|\s{2,}/', '', $replace_func );

			return $result;
		}

		/**
		 * Returns complied dynamic CSS string.
		 *
		 * @since  1.3.0
		 * @return string|bool false
		 */
		public function get_inline_css() {

			if ( ! $this->args['css_files'] ) {
				return false;
			}
			if ( ! is_array( $this->args['css_files'] ) ) {
				$this->args['css_files'] = array( $this->args['css_files'] );
			}
			/**
			 * Filter CSS reserved words list
			 *
			 * @since 1.4.0
			 * @var   array
			 */
			$this->css_reserved = apply_filters( 'cx_dynamic_css/reserved_words_list', $this->css_reserved );

			ob_start();
			foreach ( $this->args['css_files'] as $file ) {
				if ( ! file_exists( $file ) ) {
					continue;
				}
				include $file;
			}

			/**
			 * Allow to include custom dynamic CSS files
			 *
			 * @since 1.0.0
			 * @param array $this->args Current dynamic CSS arguments array.
			 */
			do_action( 'cx_dynamic_css/include_custom_files', $this->args );

			$css        = ob_get_clean();
			$parsed_css = $this->parse( $css );
			/**
			 * Filter parsed dynamic CSS
			 *
			 * @since 1.0.0
			 * @param string $parsed_css default functions list.
			 * @param array  $this->args module arguments.
			 */
			$parsed_css = apply_filters( 'cx_dynamic_css/parsed_styles', $parsed_css, $this->args );

			return $parsed_css;
		}

		/**
		 * Print inline CSS after current theme stylesheet
		 *
		 * @since  1.0.0
		 * @return void|bool false
		 */
		public function print_inline_css() {

			$parsed_css = $this->get_inline_css();

			if ( empty( $parsed_css ) ) {
				return false;
			}
			printf( '<style type="text/css">%s</style>', $parsed_css );
		}

		/**
		 * Callback function to replace CSS vars
		 *
		 * @since 1.0.0
		 * @param [string] $matches  founded vars.
		 */
		function replace_vars( $matches ) {

			$not_found = '/* Variable not found */';

			// check if variable name found
			if ( empty( $matches[2] ) ) {
				return $not_found;
			}

			$variables = $this->get_css_varaibles();
			// check if var exists
			if ( ! array_key_exists( $matches[2], $variables ) ) {
				return $not_found;
			}

			$val = $variables[ $matches[2] ];
			$maybe_units = '';

			// check if we need to add units after value
			if ( ! empty( $matches[6] ) ) {
				$maybe_units = $matches[6];
			}

			// check if we search for array val
			if ( ! empty( $matches[4] ) && is_array( $val ) && isset( $val[ $matches[4] ] ) ) {
				return $val[ $matches[4] ] . $maybe_units;
			}

			if ( ! is_array( $val ) ) {
				return $val . $maybe_units;
			} else {
				return $matches[0];
			}
		}

		/**
		 * Callback function to replace CSS functions
		 *
		 * @since 1.0.0
		 * @param [string] $matches  founded dunction.
		 */
		function replace_func( $matches ) {

			$not_found = '/* Function does not exist */';
			// check if functions name found
			if ( empty( $matches[2] ) ) {
				return $not_found;
			}

			$functions = $this->get_css_functions();
			// check if function exists and is not CSS-reserved word
			if ( ! array_key_exists( $matches[2], $functions ) ) {
				if ( is_array( $this->css_reserved ) && in_array( $matches[2], $this->css_reserved ) ) {
					return $matches[0];
				} else {
					return $not_found;
				}
			}

			$function = $functions[ $matches[2] ];
			$args     = isset( $matches[3] ) ? $matches[3] : array();
			if ( empty( $args ) ) {
				$result = call_user_func( $function );
				return $result;
			}

			if ( 'font_family' == $matches[2] ) {
				$result = call_user_func( $function, $args );
				return $result;
			}
			$args = str_replace( ' ', '', $args );
			$args = explode( ',', $args );

			if ( ! is_callable( $function ) ) {
				return $not_found;
			}

			if ( ! empty( $args ) ) {
				$args = array_map( array( $this, 'prepare_args' ), $args );
			}

			$result = call_user_func_array( $function, $args );
			return $result;
		}

		/**
		 * Filter user function arguments
		 *
		 * @since 1.0.0
		 */
		function prepare_args( $item ) {
			$name      = str_replace( '$', '', $item );
			$variables = $this->get_css_varaibles();

			if ( ! array_key_exists( $name, $variables ) ) {
				return $item;
			}

			return $variables[ $name ];
		}

	}

}

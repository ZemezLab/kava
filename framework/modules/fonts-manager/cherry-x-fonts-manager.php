<?php
/**
 * Module allows to load Google fonts.
 *
 * Version: 1.0.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Fonts_Manager' ) ) {

	/**
	 * Google fonts loader main class
	 */
	class CX_Fonts_Manager {

		/**
		 * Module arguments
		 *
		 * @var array
		 */
		public $args = array();

		/**
		 * Define fonts server URL
		 *
		 * @var string
		 */
		public $fonts_host = '//fonts.googleapis.com/css';

		/**
		 * Google fonts set
		 *
		 * @var array
		 */
		public $google_fonts = null;

		/**
		 * Array of stored google fonts data
		 *
		 * @var array
		 */
		public $fonts_data = array();

		/**
		 * Constructor for the class
		 */
		function __construct( array $args = array() ) {

			if ( ! empty( $args ) ) {
				$this->set_args( $args );
			}

			$this->fonts_host = apply_filters( 'cx_fonts_manager/cdn_url', $this->fonts_host );

			add_action( 'customize_preview_init', array( $this, 'reset_fonts_cache' ) );
			add_action( 'customize_save_after', array( $this, 'reset_fonts_cache' ) );
			add_action( 'switch_theme', array( $this, 'reset_fonts_cache' ) );

			if ( is_admin() ) {
				return;
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'prepare_fonts' ) );

		}

		/**
		 * Set module arguments
		 *
		 * @param array $args Arguments list.
		 */
		public function set_args( $args = array() ) {

			$this->args = wp_parse_args( $args, array(
				'prefix'    => '',
				'single'    => false,
				'type'      => 'theme_mod',
				'get_fonts' => '',
				'options'   => array(),
			) );

		}

		/**
		 * Get fonts data and enqueue URL
		 *
		 * @since 1.0.0
		 */
		public function prepare_fonts() {

			$font_url = $this->get_fonts_url();
			wp_enqueue_style( 'cx-google-fonts-' . $this->args['prefix'], $font_url );
		}

		/**
		 * Returns transient key.
		 *
		 * @return string
		 */
		public function transient_key() {
			return 'cx_google_fonts_url_' . $this->args['prefix'];
		}

		/**
		 * Return theme Google fonts URL to enqueue it
		 *
		 * @since  1.0.0
		 * @return string
		 */
		public function get_fonts_url() {

			$font_url = get_transient( $this->transient_key() );

			if ( ! $font_url ) {

				// Get typography options list
				$options_set = $this->get_options_set();

				// build Google fonts data array
				foreach ( $options_set as $option ) {
					$this->add_font( $option );
				}

				$font_url = $this->build_fonts_url();

				if ( false == $font_url ) {
					return;
				}

				global $wp_customize;
				if ( ! isset( $wp_customize ) ) {
					set_transient( $this->transient_key(), $font_url, WEEK_IN_SECONDS );
				}
			}

			return $font_url;

		}

		/**
		 * Get options set from module arguments
		 *
		 * @return array
		 */
		public function get_options_set() {
			return $this->args['options'];
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
		 * Build Google fonts stylesheet URL from stored data
		 *
		 * @since  1.0.0
		 */
		public function build_fonts_url() {

			$font_families = array();
			$subsets       = array();

			if ( empty( $this->fonts_data ) ) {
				return false;
			}

			/**
			 * Filter fonts data.
			 *
			 * @since 1.0.1
			 * @param array $this->fonts_data Fonts data.
			 * @param array $this->args       module arguments.
			 */
			$this->fonts_data = apply_filters( 'cx_fonts_manager/fonts_data', $this->fonts_data, $this->args );

			foreach ( $this->fonts_data as $family => $data ) {
				$styles = implode( ',', array_unique( array_filter( $data['style'] ) ) );
				$font_families[] = $family . ':' . $styles;
				$subsets = array_merge( $subsets, $data['character'] );
			}

			$subsets = array_unique( array_filter( $subsets ) );

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( implode( ',', $subsets ) ),
			);

			$fonts_url = add_query_arg( $query_args, $this->fonts_host );

			return $fonts_url;
		}

		/**
		 * Get single typography option value from database and store it in object property
		 *
		 * @since  1.0.0
		 * @param  [type] $font option name to get from database.
		 */
		public function add_font( $font ) {

			$font = wp_parse_args( $font, array(
				'family'  => '',
				'style'   => 'normal',
				'weight'  => '400',
				'charset' => 'latin',
			) );

			$family = $this->get_setting( $font['family'] );
			$family = explode( ',', $family );
			$family = trim( $family[0], "'" );

			if ( ! $this->is_google_font( $family ) ) {
				return;
			}

			$load_style = $this->get_setting( $font['weight'] );
			$font_style = $this->get_setting( $font['style'] );

			if ( 'italic' === $font_style ) {
				$load_style .= $font_style;
			}

			if ( ! isset( $this->fonts_data[ $family ] ) ) {

				$this->fonts_data[ $family ] = array(
					'style'     => array( $load_style ),
					'character' => array( $this->get_setting( $font['charset'] ) ),
				);

			} else {

				$this->fonts_data[ $family ] = array(
					'style' => $this->add_font_prop(
						$load_style,
						$this->fonts_data[ $family ]['style']
					),
					'character' => $this->add_font_prop(
						$this->get_setting( $font['charset'] ),
						$this->fonts_data[ $family ]['character']
					),
				);

			}

		}

		/**
		 * Add new font property to existaing properties array
		 *
		 * @since 1.0.0
		 * @param [type] $new      property to add.
		 * @param array  $existing existing properties.
		 */
		public function add_font_prop( $new, $existing ) {

			if ( ! is_array( $existing ) ) {
				return array( $new );
			}

			if ( ! in_array( $new, $existing ) ) {
				$existing[] = $new;
			}

			return $existing;

		}

		/**
		 * Check if selected font is google font
		 *
		 * @since  1.0.0
		 * @param  array $font_family font family to check.
		 * @return boolean
		 */
		public function is_google_font( $font_family ) {

			$google_fonts = $this->get_google_fonts();

			if ( empty( $google_fonts ) ) {
				return false;
			}

			$font_family = explode( ',', $font_family );
			$font_family = trim( $font_family[0], "'" );

			return in_array( $font_family, $google_fonts );

		}

		/**
		 * Get google fonts array
		 *
		 * @since  1.0.0
		 * @return array
		 */
		public function get_google_fonts() {

			if ( null === $this->google_fonts ) {

				$callback = isset( $this->args['get_fonts'] ) && is_callable( $this->args['get_fonts'] ) ? $this->args['get_fonts'] : false;

				if ( $callback ) {
					$this->google_fonts = call_user_func( $callback );
				} else {
					$this->google_fonts = false;
				}
			}

			return $this->google_fonts;
		}

		/**
		 * Reset fonts cache
		 *
		 * @since 1.0.0
		 */
		public function reset_fonts_cache() {
			delete_transient( $this->transient_key() );
		}

	}

}

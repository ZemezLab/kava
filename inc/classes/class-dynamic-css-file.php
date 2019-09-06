<?php
/**
 * Kava_Dynamic_CSS_File class
 *
 * @package kava
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Dynamic_CSS_File' ) ) {

	/**
	 * Define Kava_Dynamic_CSS_File class
	 */
	class Kava_Dynamic_CSS_File {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    Kava_Dynamic_CSS_File
		 */
		private static $instance = null;

		/**
		 * Check cache dynamic css is enabled.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    null|bool
		 */
		private $is_cache_dynamic_css = null;

		/**
		 * Dynamic CSS directory path.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    null|string
		 */
		private $dynamic_dir = null;

		/**
		 * Dynamic CSS directory url.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    null|string
		 */
		private $dynamic_url = null;

		/**
		 * Constructor for the class
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {
			add_action( 'after_setup_theme',  array( $this, 'maybe_create_css_file' ), 11 );
			add_action( 'after_setup_theme',  array( $this, 'remove_print_inline_style' ), 20 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dynamic_css' ) );

			add_action( 'customize_save_after', array( $this, 'remove_css_file' ) );
		}

		/**
		 * Check cache dynamic css is enabled.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return bool|mixed|null
		 */
		public function is_cache_dynamic_css() {

			if ( null !== $this->is_cache_dynamic_css ) {
				return $this->is_cache_dynamic_css;
			}

			$enqueue_dynamic_css = kava_settings()->get( 'enqueue_dynamic_css', 'true' );
			$cache_dynamic_css   = kava_settings()->get( 'cache_dynamic_css', 'false' );

			$this->is_cache_dynamic_css =
				filter_var( $enqueue_dynamic_css, FILTER_VALIDATE_BOOLEAN )
				&& filter_var( $cache_dynamic_css, FILTER_VALIDATE_BOOLEAN )
				&& ! is_customize_preview();

			return $this->is_cache_dynamic_css;
		}

		/**
		 * Maybe create Dynamic CSS file
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function maybe_create_css_file() {

			if ( ! $this->is_cache_dynamic_css() ) {
				return;
			}

			if ( ! $this->ensure_dynamic_dir() ) {
				return;
			}

			if ( $this->dynamic_css_exists() ) {
				return;
			}

			$css = kava_theme()->dynamic_css->get_inline_css();

			file_put_contents( $this->dynamic_css_path(), htmlspecialchars_decode( $css ) );
		}

		/**
		 * Enqueue Dynamic CSS File
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_dynamic_css() {

			if ( ! $this->is_cache_dynamic_css() ) {
				return;
			}

			if ( ! $this->dynamic_css_exists() ) {
				return;
			}

			wp_enqueue_style(
				'kava-theme-dynamic-style',
				$this->dynamic_css_url(),
				array( 'kava-theme-style' ),
				filemtime( $this->dynamic_css_path() )
			);
		}

		/**
		 * Remove print inline Dynamic CSS.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function remove_print_inline_style() {

			if ( ! $this->is_cache_dynamic_css() ) {
				return;
			}

			if ( ! $this->dynamic_css_exists() ) {
				return;
			}

			remove_action( 'wp_enqueue_scripts', array( kava_theme()->dynamic_css, 'add_inline_css' ), 99 );
		}

		/**
		 * Remove CSS file on options save
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function remove_css_file() {
			if ( $this->dynamic_css_exists() ) {
				unlink( $this->dynamic_css_path() );
			}
		}

		/**
		 * Check if Dynamic CSS file exists
		 *
		 * @since  1.0.0
		 * @access public
		 * @return bool
		 */
		public function dynamic_css_exists() {
			return file_exists( $this->dynamic_css_path() );
		}

		/**
		 * Return path to Dynamic CSS file
		 *
		 * @since  1.0.0
		 * @access public
		 * @return string
		 */
		public function dynamic_css_path() {
			return $this->dynamic_dir() . 'dynamic-style.css';
		}

		/**
		 * Return url to Dynamic CSS file
		 *
		 * @since  1.0.0
		 * @access public
		 * @return string
		 */
		public function dynamic_css_url() {
			return $this->dynamic_url() . 'dynamic-style.css';
		}

		/**
		 * Returns Dynamic CSS directory URL
		 *
		 * @since  1.0.0
		 * @access public
		 * @return string
		 */
		public function dynamic_url() {

			if ( null !== $this->dynamic_url ) {
				return $this->dynamic_url;
			}

			$upload_dir        = wp_upload_dir();
			$upload_base_dir   = $upload_dir['baseurl'];
			$this->dynamic_url = trailingslashit( $upload_base_dir ) . 'kava/';

			if ( is_ssl() ) {
				$this->dynamic_url = set_url_scheme( $this->dynamic_url );
			}

			return $this->dynamic_url;
		}

		/**
		 * Returns Dynamic CSS directory path
		 *
		 * @since  1.0.0
		 * @access public
		 * @return string
		 */
		public function dynamic_dir() {

			if ( null !== $this->dynamic_dir ) {
				return $this->dynamic_dir;
			}

			$upload_dir        = wp_upload_dir();
			$upload_base_dir   = $upload_dir['basedir'];
			$this->dynamic_dir = trailingslashit( $upload_base_dir ) . 'kava/';

			return $this->dynamic_dir;
		}

		/**
		 * Ensure that CSS directory exists and try to create if not.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return bool
		 */
		public function ensure_dynamic_dir() {

			if ( file_exists( $this->dynamic_dir() ) ) {
				return true;
			} else {
				return mkdir( $this->dynamic_dir() );
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return Kava_Dynamic_CSS_File
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}

}

if ( ! function_exists( 'kava_dynamic_css_file' ) ) {

	/**
	 * Returns instance of Kava_Dynamic_CSS_File
	 *
	 * @since  1.0.0
	 * @return Kava_Dynamic_CSS_File
	 */
	function kava_dynamic_css_file() {
		return Kava_Dynamic_CSS_File::get_instance();
	}
}

kava_dynamic_css_file();

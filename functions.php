<?php
if ( ! class_exists( 'Kava_Theme_Setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 1.0.0
	 */
	class Kava_Theme_Setup {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Kava_Theme_Setup
		 */
		private static $instance = null;

		/**
		 * True if the page is a blog or archive.
		 *
		 * @since 1.0.0
		 * @var   Boolean
		 */
		private $is_blog = false;

		/**
		 * Sidebar position.
		 *
		 * @since 1.0.0
		 * @var   String
		 */
		public $sidebar_position = 'none';

		/**
		 * Loaded modules
		 *
		 * @var array
		 */
		public $modules = array();

		/**
		 * Theme version
		 *
		 * @var string
		 */
		public $version;

		/**
		 * Framework component
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    object
		 */
		public $framework;

		/**
		 * Holder for current Customizer module instance.
		 *
		 * @since 1.0.0
		 * @var   CX_Customizer
		 */
		public $customizer = null;

		/**
		 * Holder for current Dynamic CSS module instance.
		 *
		 * @since 1.0.0
		 * @var   CX_Dynamic_CSS
		 */
		public $dynamic_css = null;

		/**
		 * Sets up needed actions/filters for the theme to initialize.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$template      = get_template();
			$theme_obj     = wp_get_theme( $template );
			$this->version = $theme_obj->get( 'Version' );

			// Load the theme modules.
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Init properties.
			add_action( 'wp_head', array( $this, 'init_theme_properties' ) );

			// Language functions and translations setup.
			add_action( 'after_setup_theme', array( $this, 'l10n' ), 2 );

			// Handle theme supported features.
			add_action( 'after_setup_theme', array( $this, 'theme_support' ), 3 );

			// Load the theme includes.
			add_action( 'after_setup_theme', array( $this, 'includes' ), 4 );

			// Load theme modules.
			add_action( 'after_setup_theme', array( $this, 'load_modules' ), 5 );

			// Initialization of customizer.
			add_action( 'after_setup_theme', array( $this, 'init_customizer' ) );

			// Register public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 9 );

			// Enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

			// Enqueue styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );

			// Enqueue admin assets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );

			// Maybe register Elementor Pro locations.
			add_action( 'elementor/theme/register_locations', array( $this, 'elementor_locations' ) );

		}

		/**
		 * Retuns theme version
		 *
		 * @return string
		 */
		public function version() {
			return apply_filters( 'kava-theme/version', $this->version );
		}

		/**
		 * Load the theme modules.
		 *
		 * @since  1.0.0
		 */
		public function framework_loader() {

			require get_theme_file_path( 'framework/loader.php' );

			$this->framework = new Kava_CX_Loader(
				array(
					get_theme_file_path( 'framework/modules/customizer/cherry-x-customizer.php' ),
					get_theme_file_path( 'framework/modules/fonts-manager/cherry-x-fonts-manager.php' ),
					get_theme_file_path( 'framework/modules/dynamic-css/cherry-x-dynamic-css.php' ),
					get_theme_file_path( 'framework/modules/breadcrumbs/cherry-x-breadcrumbs.php' ),
					get_theme_file_path( 'framework/modules/post-meta/cherry-x-post-meta.php' ),
					get_theme_file_path( 'framework/modules/interface-builder/cherry-x-interface-builder.php' ),
					get_theme_file_path( 'framework/modules/vue-ui/cherry-x-vue-ui.php' ),
				)
			);

		}

		/**
		 * Run initialization of customizer.
		 *
		 * @since 1.0.0
		 */
		public function init_customizer() {

			$enable_customize_options = kava_settings()->get( 'enable_theme_customize_options', true );
			$enqueue_dynamic_css      = kava_settings()->get( 'enqueue_dynamic_css', true );

			// Init CX_Customizer
			$customizer_options = kava_get_customizer_options();

			if ( ! filter_var( $enable_customize_options, FILTER_VALIDATE_BOOLEAN ) ) {
				$customizer_options['just_fonts'] = true;
			}

			$this->customizer = new CX_Customizer( $customizer_options );

			// Init CX_Dynamic_CSS
			if ( filter_var( $enqueue_dynamic_css, FILTER_VALIDATE_BOOLEAN ) ) {
				$this->dynamic_css = new CX_Dynamic_CSS( kava_get_dynamic_css_options() );
			}

		}

		/**
		 * Run init init properties.
		 *
		 * @since 1.0.0
		 */
		public function init_theme_properties() {

			$this->is_blog = is_home() || ( is_archive() && ! is_tax() && ! is_post_type_archive() ) ? true : false;

			// Blog list properties init
			if ( $this->is_blog ) {
				$this->sidebar_position = kava_theme()->customizer->get_value( 'blog_sidebar_position' );
			}

			// Single blog properties init
			if ( is_singular( 'post' ) ) {
				$this->sidebar_position = kava_theme()->customizer->get_value( 'single_sidebar_position' );
			}

		}

		/**
		 * Loads the theme translation file.
		 *
		 * @since 1.0.0
		 */
		public function l10n() {

			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 */
			load_theme_textdomain( 'kava', get_theme_file_path( 'languages' ) );

		}

		/**
		 * Adds theme supported features.
		 *
		 * @since 1.0.0
		 */
		public function theme_support() {

			global $content_width;

			if ( ! isset( $content_width ) ) {
				$content_width = 1200;
			}

			// Add support for core custom logo.
			add_theme_support( 'custom-logo', array(
				'height'      => 35,
				'width'       => 135,
				'flex-width'  => true,
				'flex-height' => true
			) );

			// Enable support for Post Thumbnails on posts and pages.
			add_theme_support( 'post-thumbnails' );

			// Enable HTML5 markup structure.
			add_theme_support( 'html5', array(
				'comment-list', 'comment-form', 'search-form', 'gallery', 'caption',
			) );

			// Enable default title tag.
			add_theme_support( 'title-tag' );

			// Enable custom background.
			add_theme_support( 'custom-background', array( 'default-color' => 'ffffff', ) );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

		}

		/**
		 * Loads the theme files supported by themes and template-related functions/classes.
		 *
		 * @since 1.0.0
		 */
		public function includes() {

			/**
			 * Configurations.
			 */
			require_once get_theme_file_path( 'config/layout.php' );
			require_once get_theme_file_path( 'config/menus.php' );
			require_once get_theme_file_path( 'config/sidebars.php' );
			require_once get_theme_file_path( 'config/modules.php' );

			require_if_theme_supports( 'post-thumbnails', get_theme_file_path( 'config/thumbnails.php' ) );

			require_once get_theme_file_path( 'inc/modules/base.php' );

			/**
			 * Classes.
			*/
			require_once get_theme_file_path( 'inc/classes/class-widget-area.php' );
			require_once get_theme_file_path( 'inc/classes/class-post-meta.php' );
			require_once get_theme_file_path( 'inc/classes/class-settings.php' );
			require_once get_theme_file_path( 'inc/classes/class-dynamic-css-file.php' );

			/**
			 * Functions.
			 */
			require_once get_theme_file_path( 'inc/template-tags.php' );
			require_once get_theme_file_path( 'inc/template-menu.php' );
			require_once get_theme_file_path( 'inc/template-comment.php' );
			require_once get_theme_file_path( 'inc/template-related-posts.php' );
			require_once get_theme_file_path( 'inc/extras.php' );
			require_once get_theme_file_path( 'inc/customizer.php' );
			require_once get_theme_file_path( 'inc/context.php' );
			require_once get_theme_file_path( 'inc/hooks.php' );

		}

		/**
		 * Modules base path
		 *
		 * @return string
		 */
		public function modules_base() {
			return 'inc/modules/';
		}

		/**
		 * Returns module class by name
		 * @return string
		 */
		public function get_module_class( $name ) {

			$module = str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $name ) ) );
			return 'Kava_' . $module . '_Module';

		}

		/**
		 * Load theme and child theme modules
		 *
		 * @return void
		 */
		public function load_modules() {
			foreach ( kava_get_allowed_modules() as $module => $childs ) {
				$this->load_module( $module, $childs );
			}
		}

		/**
		 * Load theme and child theme module
		 *
		 * @param string $module
		 * @param array  $childs
		 */
		public function load_module( $module = '', $childs = array() ) {

			$available_modules = kava_settings()->get( 'available_modules' );
			$enabled = isset( $available_modules[ $module ] ) ? $available_modules[ $module ] : true;

			if ( ! filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) ) {
				return;
			}

			if ( ! file_exists( get_theme_file_path( $this->modules_base() . $module . '/module.php' ) ) ) {
				return;
			}

			require_once get_theme_file_path( $this->modules_base() . $module . '/module.php' );
			$class = $this->get_module_class( $module );

			if ( ! class_exists( $class ) ) {
				return;
			}

			$instance = new $class( $childs );

			$this->modules[ $instance->module_id() ] = $instance;
		}

		/**
		 * Register assets.
		 *
		 * @since 1.0.0
		 */
		public function register_assets() {
			// Register style
			wp_register_style(
				'font-awesome',
				get_theme_file_uri( 'assets/lib/font-awesome/font-awesome.min.css' ),
				array(),
				'4.7.0'
			);
		}

		/**
		 * Enqueue scripts.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * Filter the depends on main theme script.
			 *
			 * @since 1.0.0
			 * @var   array
			 */
			$scripts_depends = apply_filters( 'kava-theme/assets-depends/script', array(
				'jquery'
			) );

			$enqueue_js_scripts = kava_settings()->get( 'enqueue_theme_js_scripts', true );

			if ( filter_var( $enqueue_js_scripts, FILTER_VALIDATE_BOOLEAN ) ) {
				wp_enqueue_script(
					'kava-theme-script',
					get_theme_file_uri( 'assets/js/theme-script.js' ),
					$scripts_depends,
					$this->version(),
					true
				);

				wp_localize_script( 'kava-theme-script', 'kavaConfig', array(
					'toTop' => kava_theme()->customizer->get_value( 'totop_visibility' ),
				) );
			}

			// Threaded Comments.
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

		}

		/**
		 * Enqueue styles.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * Filter the depends on main theme styles.
			 *
			 * @since 1.0.0
			 * @var   array
			 */
			$styles_depends = apply_filters( 'kava-theme/assets-depends/styles', array(
				'font-awesome',
			) );

			wp_enqueue_style(
				'kava-theme-style',
				get_stylesheet_uri(),
				$styles_depends,
				$this->version()
			);

			$enqueue_styles = kava_settings()->get( 'enqueue_theme_styles', true );

			if ( filter_var( $enqueue_styles, FILTER_VALIDATE_BOOLEAN ) ) {
				wp_enqueue_style(
					'kava-theme-main-style',
					get_theme_file_uri( 'theme.css' ),
					array( 'kava-theme-style' ),
					$this->version()
				);

				if ( is_rtl() ) {
					wp_enqueue_style(
						'kava-theme-main-rtl-style',
						get_theme_file_uri( 'theme-rtl.css' ),
						false,
						$this->version()
					);
				}
			}
		}

		/**
		 * Enqueue admin assets
		 *
		 * @return void
		 */
		public function enqueue_admin_assets() {
			wp_enqueue_style(
				'kava-theme-admin-css',
				get_parent_theme_file_uri( 'assets/css/admin.css' ),
				false,
				$this->version()
			);
		}

		/**
		 * Do Elementor or Jet Theme Core location
		 *
		 * @param string $location
		 * @param string $fallback
		 *
		 * @return bool
		 */
		public function do_location( $location = null, $fallback = null ) {

			$handler = false;
			$done    = false;

			// Choose handler
			if ( function_exists( 'jet_theme_core' ) ) {
				$handler = array( jet_theme_core()->locations, 'do_location' );
			} elseif ( function_exists( 'elementor_theme_do_location' ) ) {
				$handler = 'elementor_theme_do_location';
			}

			// If handler is found - try to do passed location
			if ( false !== $handler ) {
				$done = call_user_func( $handler, $location );
			}

			if ( true === $done ) {
				// If location successfully done - return true
				return true;
			} elseif ( null !== $fallback ) {
				// If for some reasons location coludn't be done and passed fallback template name - include this template and return
				if ( is_array( $fallback ) ) {
					// fallback in name slug format
					get_template_part( $fallback[0], $fallback[1] );
				} else {
					// fallback with just a name
					get_template_part( $fallback );
				}
				return true;
			}

			// In other cases - return false
			return false;

		}

		/**
		 * Register Elementor Pro locations
		 *
		 * @param object $elementor_theme_manager
		 */
		public function elementor_locations( $elementor_theme_manager ) {

			// Do nothing if Jet Theme Core is active.
			if ( function_exists( 'jet_theme_core' ) ) {
				return;
			}

			$elementor_theme_manager->register_all_core_location();
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Kava_Theme_Setup
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

/**
 * Returns instance of main theme configuration class.
 *
 * @since  1.0.0
 * @return Kava_Theme_Setup
 */
function kava_theme() {
	return Kava_Theme_Setup::get_instance();
}

kava_theme();

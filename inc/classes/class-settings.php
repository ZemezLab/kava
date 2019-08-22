<?php
/**
 * Theme Settings class.
 *
 * @package Kava
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Kava_Settings' ) ) {

	/**
	 * Define Kava_Settings class
	 */
	class Kava_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Setting key
		 *
		 * @var string
		 */
		public $key = 'kava-extra-settings';

		/**
		 * Settings
		 *
		 * @var null
		 */
		public $settings = null;

		/**
		 * Available Modules array
		 *
		 * @access public
		 * @var    array
		 */
		public $available_modules = array();

		/**
		 * Page config
		 *
		 * @var array
		 */
		public $settings_page_config = array();

		/**
		 * Init page
		 */
		public function __construct() {

			if ( ! $this->is_enabled() ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 0 );
			add_action( 'admin_menu',            array( $this, 'register_page' ), 99 );

			add_action( 'wp_ajax_save_settings', array( $this, 'save_settings' ) );
		}

		/**
		 * Is default settings page enabled or not.
		 *
		 * @return boolean
		 */
		public function is_enabled() {
			return apply_filters( 'kava-theme/settings-page/is-enabled', true );
		}

		/**
		 * Initialize page builder module if required
		 *
		 * @return void
		 */
		public function admin_enqueue_scripts() {

			if ( isset( $_REQUEST['page'] ) && $this->key === $_REQUEST['page'] ) {

				$module_data = kava_theme()->framework->get_included_module_data( 'cherry-x-vue-ui.php' );
				$ui          = new CX_Vue_UI( $module_data );

				$ui->enqueue_assets();

				$this->generate_config_data();

				wp_enqueue_script(
					'kava-admin-script',
					get_parent_theme_file_uri( 'assets/js/admin.js' ),
					array( 'cx-vue-ui' ),
					kava_theme()->version(),
					true
				);

				wp_localize_script(
					'kava-admin-script',
					'KavaSettingsPageConfig',
					apply_filters( 'kava-theme/admin/settings-page-config', $this->settings_page_config )
				);
			}
		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->key,
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		/**
		 * Get setting value.
		 *
		 * @param  string  $setting
		 * @param  boolean $default
		 * @return mixed
		 */
		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;
		}

		/**
		 * Register add/edit page
		 *
		 * @return void
		 */
		public function register_page() {

			add_menu_page(
				esc_html__( 'Theme', 'kava' ),
				esc_html__( 'Theme', 'kava' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' ),
				get_theme_file_uri( 'assets/images/kava-theme-icon.svg' ),
				100
			);
		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {
			include get_parent_theme_file_path( 'admin-templates/settings-page.php' );
		}

		/**
		 * Generate config data.
		 */
		public function generate_config_data() {

			$default_disable_container_archive_cpt = $this->prepare_default_values_list( $this->get_post_types( true ), 'false' );
			$default_disable_container_single_cpt  = $this->prepare_default_values_list( $this->get_post_types(), 'false' );

			$this->settings_page_config = array(
				'messages' => array(
					'saveSuccess' => esc_html__( 'Saved', 'kava' ),
					'saveError'   => esc_html__( 'Error', 'kava' ),
				),
				'settingsData' => array(
					'disable_content_container_archive_cpt' => array(
						'value'   => $this->get( 'disable_content_container_single_cpt', $default_disable_container_archive_cpt ),
						'options' => $this->prepare_options_list( $this->get_post_types( true ) ),
					),
					'disable_content_container_single_cpt' => array(
						'value'   => $this->get( 'disable_content_container_single_cpt', $default_disable_container_single_cpt ),
						'options' => $this->prepare_options_list( $this->get_post_types() ),
					),
					'single_post_template' => array(
						'value'   => $this->get( 'single_post_template', 'default' ),
						'options' => $this->prepare_options_list( $this->get_single_post_templates() ),
					),
				),
			);
		}

		public function save_settings() {
			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			if ( ! isset( $_REQUEST['action'] ) || 'save_settings' !== $_REQUEST['action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {

				wp_send_json_error( array(
					'message' => 'current_user_can'
				) );

				return ;
			}

			$current = get_option( $this->key, array() );

		}

		/**
		 * Returns parent-independent controls list
		 *
		 * @param string $parent
		 *
		 * @return array
		 */
		public function get_controls_list( $parent = 'settings_top' ) {

			$disabled_modules = apply_filters( 'kava-theme/disabled-modules', array() );

			foreach ( kava_get_allowed_modules() as $module => $childs ) {
				if ( ! in_array( $module, $disabled_modules ) ) {
					$this->available_modules[ $module ] = ucwords( str_replace( '-', ' ', $module ) );
				}
			}

			$default_available_modules = array();

			foreach ( $this->available_modules as $key => $value ) {
				$default_available_modules[ $key ] = 'true';
			}

			$controls = array(
				'available_modules' => array(
					'type'        => 'checkbox',
					'id'          => 'available_modules',
					'name'        => 'available_modules',
					'value'       => $this->get( 'available_modules', $default_available_modules ),
					'options'     => $this->available_modules,
					'parent'      => $parent,
					'title'       => esc_html__( 'Available Theme Modules', 'kava' ),
					'description' => esc_html__( 'List of modules that will be available', 'kava' ),
					'class'       => 'kava_extra_settings_form__checkbox-group'
				),

				'disable_content_container_archive_cpt' => array(
					'type'        => 'checkbox',
					'id'          => 'disable_content_container_archive_cpt',
					'name'        => 'disable_content_container_archive_cpt',
					'parent'      => $parent,
					'value'       => $this->get( 'disable_content_container_archive_cpt' ),
					'options'     => $this->get_post_types( true ),
					'title'       => esc_html__( 'Disable Container of Content on Archive Pages', 'kava' ),
					'description' => esc_html__( 'List of CPT that will be a disabled container of content', 'kava' ),
					'class'       => 'kava_extra_settings_form__checkbox-group'
				),

				'disable_content_container_single_cpt' => array(
					'type'        => 'checkbox',
					'id'          => 'disable_content_container_single_cpt',
					'name'        => 'disable_content_container_single_cpt',
					'parent'      => $parent,
					'value'       => $this->get( 'disable_content_container_single_cpt' ),
					'options'     => $this->get_post_types(),
					'title'       => esc_html__( 'Disable Container of Content on Singular Pages', 'kava' ),
					'description' => esc_html__( 'List of CPT that will be a disabled container of content', 'kava' ),
					'class'       => 'kava_extra_settings_form__checkbox-group'
				),

				'single_post_template' => array(
					'type'    => 'select',
					'id'      => 'single_post_template',
					'name'    => 'single_post_template',
					'parent'  => $parent,
					'value'   => $this->get( 'single_post_template', 'default' ),
					'options' => $this->get_single_post_templates(),
					'title'   => esc_html__( 'Default Single Post Template', 'kava' ),
				),
			);

			return apply_filters( 'kava-extra/settings-page/controls-list', $controls );

		}

		/**
		 * Prepare options list
		 *
		 * @param  array $options
		 * @return array
		 */
		public function prepare_options_list( $options = array() ) {

			$result = array();

			foreach ( $options as $slug => $label ) {
				$result[] = array(
					'value' => $slug,
					'label' => $label,
				);
			}

			return $result;
		}

		/**
		 * Prepare default values list
		 *
		 * @param  array $options
		 * @param  mixed $default
		 * @return array
		 */
		public function prepare_default_values_list( $options = array(), $default = 'false' ) {

			$result = array();

			foreach ( $options as $slug => $label ) {
				$result[ $slug ] = $default;
			}

			return $result;
		}

		/**
		 * Get public post types options list
		 *
		 * @param  boolean $is_archive_list
		 * @return array
		 */
		public function get_post_types( $is_archive_list = false ) {
			$args = array(
				'show_in_nav_menus' => true,
			);

			$post_types = get_post_types( $args, 'objects' );

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {
				$result[ $slug ] = $post_type->label;
			}

			if ( $is_archive_list ) {
				$result['search_results'] = esc_html__( 'Search Results', 'kava' );
				unset( $result['page'] );
			} else {
				$result['404_page'] = esc_html__( '404 Page', 'kava' );
			}

			return $result;
		}

		/**
		 * Get single post templates.
		 *
		 * @return array
		 */
		public function get_single_post_templates() {
			$default_template = array( 'default' => apply_filters( 'default_page_template_title', esc_html__( 'Default Template', 'kava' ) ) );

			if ( ! function_exists( 'get_page_templates' ) ) {
				return $default_template;
			}

			$post_templates = get_page_templates( null, 'post' );

			ksort( $post_templates );

			$templates = array_combine(
				array_values( $post_templates ),
				array_keys( $post_templates )
			);

			$templates = array_merge( $default_template, $templates );

			return $templates;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
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
 * Returns instance of Kava_Settings
 *
 * @return object
 */
function kava_settings() {
	return Kava_Settings::get_instance();
}

kava_settings();

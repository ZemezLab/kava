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
		 * Interface builder
		 *
		 * @var null
		 */
		public $interface_builder  = null;

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
		 * Init page
		 */
		public function __construct() {

			if ( ! $this->is_enabled() ) {
				return;
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'init_interface_builder' ), 0 );

			add_action( 'admin_menu',    array( $this, 'register_page' ), 99 );
			add_action( 'init',          array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );
		}

		/**
		 * Is default settings page enabled or not.
		 *
		 * @return boolean
		 */
		public function is_enabled() {
			return apply_filters( 'kava-extra/settings-page/is-enabled', true );
		}

		/**
		 * Init Interface Builder
		 */
		public function init_interface_builder() {

			if ( isset( $_REQUEST['page'] ) && $this->key === $_REQUEST['page'] ) {

				$builder_data = kava_theme()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

				$this->interface_builder = new CX_Interface_Builder(
					array(
						'path' => $builder_data['path'],
						'url'  => $builder_data['url'],
					)
				);
			}

		}

		/**
		 * Show saved notice
		 *
		 * @return bool
		 */
		public function saved_notice() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return false;
			}

			if ( ! isset( $_GET['settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'kava' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		/**
		 * Save settings
		 *
		 * @return void
		 */
		public function save() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			if ( ! isset( $_REQUEST['action'] ) || 'save-settings' !== $_REQUEST['action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current = get_option( $this->key, array() );
			$data    = $_REQUEST;

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {
				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
			}

			update_option( $this->key, $current );

			$redirect = add_query_arg(
				array( 'dialog-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Update single option key in options array
		 *
		 * @param $key
		 * @param $value
		 *
		 * @return void
		 */
		public function save_key( $key, $value ) {

			$current         = get_option( $this->key, array() );
			$current[ $key ] = $value;

			update_option( $this->key, $current );

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

			$this->interface_builder->register_section(
				array(
					'kava_extra_settings' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'Kava Theme Settings', 'kava' ),
					),
				)
			);

			$this->interface_builder->register_form(
				array(
					'kava_extra_settings_form' => array(
						'type'   => 'form',
						'parent' => 'kava_extra_settings',
						'action' => add_query_arg(
							array( 'page' => $this->key, 'action' => 'save-settings' ),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->interface_builder->register_settings(
				array(
					'settings_top' => array(
						'type'   => 'settings',
						'parent' => 'kava_extra_settings_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'kava_extra_settings_form',
					),
				)
			);

			$controls = $this->get_controls_list( 'settings_top' );

			$this->interface_builder->register_control( $controls );

			$this->interface_builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cherry-control dialog-save',
						'html'   => '<button type="submit" class="cx-button cx-button-primary-style">' . esc_html__( 'Save', 'kava' ) . '</button>',
					),
				)
			);

			echo '<div class="kava-extra-settings-page">';
				$this->interface_builder->render();
			echo '</div>';
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
				return array();
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

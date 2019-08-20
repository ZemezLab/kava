<?php
/**
 * Interface Builder module
 *
 * Version: 1.5.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'CX_Interface_Builder' ) ) {

	/**
	 * Class Cherry Interface Builder.
	 *
	 * @since 1.0.0
	 */
	class CX_Interface_Builder {

		/**
		 * Module directory path.
		 *
		 * @since 1.5.0
		 * @access protected
		 * @var srting.
		 */
		protected $path;

		/**
		 * Module directory URL.
		 *
		 * @since 1.5.0
		 * @access protected
		 * @var srting.
		 */
		protected $url;

		/**
		 * Module version
		 *
		 * @var string
		 */
		protected $version = '1.5.4';

		/**
		 * Conditions
		 *
		 * @var array
		 */
		public static $conditions = array();

		/**
		 * [$conditions description]
		 * @var array
		 */
		public static $fields_value = array();

		/**
		 * Module settings.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    array
		 */
		private $args = array(
			'path'  => '',
			'url'   => '',
			'views' => array(
				'section'                  => 'views/section.php',
				'component-tab-vertical'   => 'views/component-tab-vertical.php',
				'component-tab-horizontal' => 'views/component-tab-horizontal.php',
				'component-toggle'         => 'views/component-toggle.php',
				'component-accordion'      => 'views/component-accordion.php',
				'component-repeater'       => 'views/component-repeater.php',
				'settings'                 => 'views/settings.php',
				'control'                  => 'views/control.php',
				'settings-children-title'  => 'views/settings-children-title.php',
				'tab-children-title'       => 'views/tab-children-title.php',
				'toggle-children-title'    => 'views/toggle-children-title.php',
				'form'                     => 'views/form.php',
				'html'                     => 'views/html.php',
			),
			'views_args' => array(
				'parent'        => '',
				'type'          => '',
				'view'          => '',
				'view_wrapping' => true,
				'html'          => '',
				'scroll'        => false,
				'title'         => '',
				'description'   => '',
				'condition'     => array(),
			),
		);

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var object
		 */
		private static $instance = null;

		/**
		 * UI element instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    object
		 */
		public $controls = null;

		/**
		 * The structure of the interface elements.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    array
		 */
		private $structure = array();

		/**
		 * Dependencies array
		 * @var array
		 */
		private static $deps = array(
			'css' => array(),
			'js'  => array( 'jquery' ),
		);

		/**
		 * Cherry_Interface_Builder constructor.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct( array $args = array() ) {

			$this->path = $args['path'];
			$this->url  = $args['url'];

			$this->args = array_merge(
				$this->args,
				$args
			);

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			require trailingslashit( $this->path ) . 'inc/class-cx-controls-manager.php';

			$this->controls = new CX_Controls_Manager( $this->path, $this->url );

		}

		/**
		 * Register element type section.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array $args Options section.
		 * @return void
		 */
		public function register_section( array $args = array() ) {
			$this->add_new_element( $args, 'section' );
		}

		/**
		 * Register element type component.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array $args Options component.
		 * @return void
		 */
		public function register_component( array $args = array() ) {
			$this->add_new_element( $args, 'component' );
		}

		/**
		 * Register element type settings.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array $args Options settings.
		 * @return void
		 */
		public function register_settings( array $args = array() ) {
			$this->add_new_element( $args, 'settings' );
		}

		/**
		 * Register element type control.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array $args Options control.
		 * @return void
		 */
		public function register_control( array $args = array() ) {
			$this->add_new_element( $args, 'control' );
		}

		/**
		 * Register element type form.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array $args Options form.
		 * @return void
		 */
		public function register_form( array $args = array() ) {
			$this->add_new_element( $args, 'form' );
		}

		/**
		 * Register element type html.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  array $args Options control.
		 * @return void
		 */
		public function register_html( array $args = array() ) {
			$this->add_new_element( $args, 'html' );
		}

		/**
		 * This function adds a new element to the structure.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @param  array  $args Options new element.
		 * @param  string $type Type new element.
		 * @return void
		 */
		protected function add_new_element( array $args = array(), $type = 'section' ) {

			if ( ! isset( $args[0] ) && ! is_array( current( $args ) ) ) {
					if ( 'control' !== $type && 'component' !== $type ) {
						$args['type'] = $type;
					}

					if ( ! isset( $args['name'] ) && isset( $args['id'] ) ) {
						$args['name'] = $args['id'];
					}

					if ( 'control' ===  $type ) {
						$instance         = $this->controls->register_control( $args['type'], $args );
						$args['instance'] = $instance;

						$this->add_dependencies( $instance );

					}

					if ( array_key_exists( 'conditions', $args ) ) {
						self::$conditions[ $args['id'] ] = $args['conditions'];
					}

					if ( array_key_exists( 'value', $args ) ) {
						self::$fields_value[ $args['id'] ] = $args['value'];
					}

					$this->structure[ $args['id'] ] = $args;

			} else {
				foreach ( $args as $key => $value ) {

					if ( 'control' !== $type && 'component' !== $type ) {
						$value['type'] = $type;
					}

					if ( ! isset( $value['id'] ) ) {
						$value['id'] = $key;
					}
					if ( ! isset( $value['name'] ) ) {
						$value['name'] = $key;
					}

					if ( 'control' ===  $type ) {
						$instance          = $this->controls->register_control( $value['type'], $value );
						$value['instance'] = $instance;

						$this->add_dependencies( $instance );
					}

					if ( array_key_exists( 'conditions', $value ) ) {
						self::$conditions[ $key ] = $value['conditions'];
					}

					if ( array_key_exists( 'value', $value ) ) {
						self::$fields_value[ $key ] = $value['value'];
					}

					$this->structure[ $key ] = $value;
				}
			}
		}

		/**
		 * Add control dependencies to global builder deps
		 *
		 * @param [type] $control [description]
		 */
		protected function add_dependencies( $control ) {

			if ( ! $control instanceof CX_Controls_Base ) {
				return;
			}

			self::$deps['js']  = array_merge( self::$deps['js'], $control->get_script_depends() );
			self::$deps['css'] = array_merge( self::$deps['css'], $control->get_style_depends() );

			$constrol_settings = $control->get_settings();

			if ( 'repeater' === $constrol_settings['type'] && ! empty( $constrol_settings['fields'] ) ) {
				foreach ( $constrol_settings['fields'] as $field ) {
					$child_instance = $this->controls->register_control( $field['type'], $field );

					if ( $child_instance ) {

						self::$deps['js']  = array_merge( self::$deps['js'], $child_instance->get_script_depends() );
						self::$deps['css'] = array_merge( self::$deps['css'], $child_instance->get_style_depends() );

					}

				}
			}

		}

		/**
		 * Sorts the elements of the structure, adding child items to the parent.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @param  array  $structure  The original structure of the elements.
		 * @param  string $parent_key The key of the parent element.
		 * @return array
		 */
		protected function sort_structure( array $structure = array(), $parent_key = null ) {

			$new_array = array();

			foreach ( $structure as $key => $value ) {
				if (
					( null === $parent_key && ! isset( $value['parent'] ) )
					|| null === $parent_key && ! isset( $structure[ $value['parent'] ] )
					|| ( isset( $value['parent'] ) && $value['parent'] === $parent_key )
				) {

					$new_array[ $key ] = $value;

					$children = $this->sort_structure( $structure, $key );

					if ( ! empty( $children ) ) {
						$new_array[ $key ]['children'] = $children;
					}
				}
			}

			return $new_array;
		}

		/**
		 * Reset structure array.
		 * Call this method only after render.
		 *
		 * @since  1.0.1
		 * @return void
		 */
		public function reset_structure() {
			$this->structure = array();
		}

		/**
		 * Get view for interface elements.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @param  string $type View type.
		 * @param  array  $args Input data.
		 * @return string
		 */
		protected function get_view( $type = 'control', array $args = array() ) {

			if ( empty( $args['view'] ) ) {
				$path = ( array_key_exists( $type, $this->args['views'] ) ) ? $this->args['views'][ $type ] : $this->args['views']['control'];
				$path = is_array( $path ) ? $path[0] : $path;
				$path = file_exists( $path ) ? $path : $this->path . $path;
			} else {
				$path = $args['view'];
			}

			ob_start();
			include $path;
			return ob_get_clean();
		}

		/**
		 * Render HTML elements.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  bool  $echo Input data.
		 * @param  array $args The original structure of the elements.
		 * @return string
		 */
		public function render( $echo = true, array $args = array() ) {

			if ( empty( $args ) ) {
				$args = $this->structure;
			}

			if ( empty( $args ) ) {
				return false;
			}

			$sorted_structure = $this->sort_structure( $args );

			$output = $this->build( $sorted_structure );
			$output = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $output );

			$this->reset_structure();

			return $this->output_method( $output, $echo );
		}

		/**
		 * Render HTML elements.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @param  array $args Input data.
		 * @return string
		 */
		protected function build( array $args = array() ) {
			$output = '';
			$views  = $this->args['views'];

			foreach ( $args as $key => $value ) {
				$value = wp_parse_args(
					$value,
					$this->args['views_args']
				);

				$value['class'] = isset( $value['class'] ) ? $value['class'] . ' ' : '';
				$value['class'] .= $value['id'] . ' ';

				if ( $value['scroll'] ) {
					$value['class'] .= 'cx-scroll ';
				}

				$type      = array_key_exists( $value['type'], $views ) ? $value['type'] : 'field';
				$has_child = isset( $value['children'] ) && is_array( $value['children'] ) && ! empty( $value['children'] );

				switch ( $type ) {
					case 'component-tab-vertical':
					case 'component-tab-horizontal':
						if ( $has_child ) {
							$value['tabs'] = '';

							foreach ( $value['children'] as $key_children => $value_children ) {
								$value['tabs'] .= $this->get_view( 'tab-children-title', $value_children );

								unset( $value['children'][ $key_children ]['title'] );
							}
						}
					break;

					case 'component-toggle':
					case 'component-accordion':
						if ( $has_child ) {
							foreach ( $value['children'] as $key_children => $value_children ) {
								$value['children'][ $key_children ]['title_in_view'] = $this->get_view( 'toggle-children-title', $value_children );
							}
						}
					break;

					case 'settings':
						if ( isset( $value['title'] ) && $value['title'] ) {
							$value['title'] = isset( $value['title_in_view'] ) ? $value['title_in_view'] : $this->get_view( 'settings-children-title', $value );
						}
					break;

					case 'html':
						$value['children'] = $value['html'];
					break;

					case 'form':
						$value['accept-charset'] = isset( $value['accept-charset'] ) ? $value['accept-charset'] : 'utf-8';
						$value['action']         = isset( $value['action'] ) ? $value['action'] : '' ;
						$value['autocomplete']   = isset( $value['autocomplete'] ) ? $value['autocomplete'] : 'on';
						$value['enctype']        = isset( $value['enctype'] ) ? $value['enctype'] : 'application/x-www-form-urlencoded';
						$value['method']         = isset( $value['method'] ) ? $value['method'] : 'post';
						$value['novalidate']     = ( isset( $value['novalidate'] ) && $value['novalidate'] ) ? 'novalidate' : '';
						$value['target']         = isset( $value['target'] ) ? $value['target'] : '';
					break;

					case 'field':
						$ui_args = $value;

						$ui_args['class'] = isset( $ui_args['child_class'] ) ? $ui_args['child_class'] : '' ;

						$control = isset( $ui_args['instance'] ) ? $ui_args['instance'] : false;

						if ( $control ) {
							$value['children'] = $control->render();
						} else {
							$value['children'] = 'Control not found';
						}

					break;
				}

				if ( $has_child ) {
					$value['children'] = $this->build( $value['children'] );
				}

				$output .= ( $value['view_wrapping'] ) ? $this->get_view( $type, $value ) : $value['children'];
			}

			return $output;
		}

		/**
		 * Output HTML.
		 *
		 * @since  1.0.0
		 * @access protected
		 * @param  string  $output Output HTML.
		 * @param  boolean $echo   Output type.
		 * @return string
		 */
		protected function output_method( $output = '', $echo = true ) {
			if ( ! filter_var( $echo, FILTER_VALIDATE_BOOLEAN ) ) {
				return $output;
			} else {
				echo $output;
			}
		}

		/**
		 * Enqueue javascript and stylesheet interface builder.
		 *
		 * @since  4.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_assets() {

			$suffix = '';

			if ( defined( 'SCRIPT_DEBUG' ) && false === SCRIPT_DEBUG ) {
				$suffix = '.min';
			}

			$js_deps  = array_unique( self::$deps['js'] );
			$css_deps = array_unique( self::$deps['css'] );

			wp_enqueue_script(
				'cx-interface-builder',
				$this->url . 'assets/js/cx-interface-builder' . $suffix . '.js',
				$js_deps,
				$this->version,
				true
			);

			wp_localize_script( 'cx-interface-builder', 'cxInterfaceBuilder',
				array(
					'conditions' => self::$conditions,
					'fields'     => self::$fields_value,
				)
			);

			wp_enqueue_style(
				'cx-interface-builder',
				$this->url . 'assets/css/cx-interface-builder.css',
				$css_deps,
				$this->version,
				'all'
			);
		}

	}
}

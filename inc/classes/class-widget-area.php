<?php
/**
 * Class for widget areas registration.
 *
 * @package    Kava
 * @subpackage Class
 */

if ( ! class_exists( 'Kava_Widget_Area' ) ) {

	class Kava_Widget_Area {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Settings.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $widgets_settings = array();

		/**
		 * Public holder thats save widgets state during page loading.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $active_sidebars = array();

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 * @since 1.0.1 Removed argument in constructor.
		 */
		function __construct() {
			add_action( 'widgets_init',            array( $this, 'register' ) );
			add_action( 'kava-theme/widget-area/render', array( $this, 'render' ) );
		}

		/**
		 * Register widget areas.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function register() {
			global $wp_registered_sidebars;

			foreach ( $this->widgets_settings as $id => $settings ) {

				register_sidebar( array(
					'name'          => $settings['name'],
					'id'            => $id,
					'description'   => $settings['description'],
					'before_widget' => $settings['before_widget'],
					'after_widget'  => $settings['after_widget'],
					'before_title'  => $settings['before_title'],
					'after_title'   => $settings['after_title'],
				) );

				if ( isset( $settings['is_global'] ) ) {
					$wp_registered_sidebars[ $id ]['is_global'] = $settings['is_global'];
				}
			}
		}

		/**
		 * Render widget areas.
		 *
		 * @since  1.0.0
		 * @param  string $area_id Widget area ID.
		 * @return void
		 */
		public function render( $area_id ) {

			if ( ! is_active_sidebar( $area_id ) ) {
				$this->active_sidebars[ $area_id ] = false;
				return;
			}

			$this->active_sidebars[ $area_id ] = true;

			// Conditional page tags checking.
			if ( isset( $this->widgets_settings[ $area_id ]['conditional'] )
				&& ! empty( $this->widgets_settings[ $area_id ]['conditional'] )
				) {

				$visibility = false;

				foreach ( $this->widgets_settings[ $area_id ]['conditional'] as $conditional ) {
					if ( is_callable( $conditional ) ) {
						$visibility = call_user_func( $conditional ) ? true : false;
					}

					if ( true === $visibility ) {
						break;
					}
				}

				if ( false === $visibility ) {
					return;
				}
			}

			$area_id        = apply_filters( 'kava-theme/widget_area/rendering_current', $area_id );
			$before_wrapper = isset( $this->widgets_settings[ $area_id ]['before_wrapper'] ) ? $this->widgets_settings[ $area_id ]['before_wrapper'] : '<div id="%1$s" %2$s>';
			$after_wrapper  = isset( $this->widgets_settings[ $area_id ]['after_wrapper'] ) ? $this->widgets_settings[ $area_id ]['after_wrapper'] : '</div>';

			$classes = array( $area_id, 'widget-area' );
			$classes = apply_filters( 'kava-theme/widget_area/classes', $classes, $area_id );

			if ( is_array( $classes ) ) {
				$classes = 'class="' . join( ' ', $classes ) . '"';
			}

			printf( $before_wrapper, $area_id, $classes );
				dynamic_sidebar( $area_id );
			printf( $after_wrapper );
		}

		/**
		 * Check if passed sidebar was already rendered and it's active.
		 *
		 * @since  1.0.0
		 * @param  string    $index Sidebar ID.
		 * @return bool|null
		 */
		public function is_active_sidebar( $index ) {

			if ( isset( $this->active_sidebars[ $index ] ) ) {
				return $this->active_sidebars[ $index ];
			}

			return null;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

	function kava_widget_area() {
		return Kava_Widget_Area::get_instance();
	}

	kava_widget_area();
}

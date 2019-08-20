<?php
/**
 * Post Meta module
 *
 * Version: 1.2.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Cherry_X_Post_Meta' ) ) {

	/**
	 * Post meta management module.
	 */
	class Cherry_X_Post_Meta {

		/**
		 * Module arguments.
		 *
		 * @var array
		 */
		public $args = array();

		/**
		 * Interface builder instance.
		 *
		 * @var object
		 */
		public $builder = null;

		/**
		 * Current nonce name to check.
		 *
		 * @var null
		 */
		public $nonce = 'cherry-x-meta-nonce';

		/**
		 * Storage of meta values.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $meta_values = array();

		/**
		 * Constructor for the module.
		 *
		 * @since 1.0.0
		 */
		public function __construct( $args = array() ) {

			$this->args = wp_parse_args(
				$args,
				array(
					'id'            => 'cherry-post-metabox',
					'title'         => '',
					'page'          => array( 'post' ),
					'context'       => 'normal',
					'priority'      => 'high',
					'single'        => false,
					'callback_args' => false,
					'builder_cb'    => false,
					'fields'        => array(),
				)
			);

			if ( empty( $this->args['fields'] ) ) {
				return;
			}

			if ( ! is_array( $this->args['page'] ) ) {
				$this->args['page'] = array( $this->args['page'] );
			}

			$this->init_columns_actions();

			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
			add_action( 'save_post', array( $this, 'save_meta' ), 10, 2 );

		}

		/**
		 * Initialize builder
		 *
		 * @return [type] [description]
		 */
		public function init_builder( $hook ) {
			global $post;

			if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ) ) ) {
				return;
			}

			$post_type = get_post_type();

			if ( ! in_array( $post_type, $this->args['page'] ) ) {
				return;
			}

			if ( ! isset( $this->args['builder_cb'] ) || ! is_callable( $this->args['builder_cb'] ) ) {
				return;
			}

			$this->builder = call_user_func( $this->args['builder_cb'] );

			$this->get_fields( $post );

		}

		/**
		 * Initalize admin columns
		 *
		 * @return void
		 */
		public function init_columns_actions() {

			if ( empty( $this->args['admin_columns'] ) ) {
				return;
			}

			if ( ! is_array( $this->args['page'] ) ) {
				$pages = array( $this->args['page'] );
			} else {
				$pages = $this->args['page'];
			}

			foreach ( $pages as $page ) {
				add_filter( 'manage_edit-' . $page . '_columns', array( $this, 'edit_columns' ) );
				add_action( 'manage_' . $page . '_posts_custom_column', array( $this, 'manage_columns' ), 10, 2 );
			}

		}

		/**
		 * Edit admin columns
		 *
		 * @since  1.1.3
		 * @param  array $columns current post table columns.
		 * @return array
		 */
		public function edit_columns( $columns ) {

			foreach ( $this->args['admin_columns'] as $column_key => $column_data ) {

				if ( empty( $column_data['label'] ) ) {
					continue;
				}

				if ( ! empty( $column_data['position'] ) && 0 !== (int) $column_data['position'] ) {

					$length = count( $columns );

					if ( (int) $column_data['position'] > $length ) {
						$columns[ $column_key ] = $column_data['label'];
					}

					$columns_before = array_slice( $columns, 0, (int) $column_data['position'] );
					$columns_after  = array_slice( $columns, (int) $column_data['position'], $length - (int) $column_data['position'] );

					$columns = array_merge(
						$columns_before,
						array(
							$column_key => $column_data['label'],
						),
						$columns_after
					);
				} else {
					$columns[ $column_key ] = $column_data['label'];
				}
			}

			return $columns;

		}

		/**
		 * Add output for custom columns.
		 *
		 * @since  1.1.3
		 * @param  string $column  current post list categories.
		 * @param  int    $post_id current post ID.
		 * @return void
		 */
		public function manage_columns( $column, $post_id ) {

			if ( empty( $this->args['admin_columns'][ $column ] ) ) {
				return;
			}

			if ( ! empty( $this->args['admin_columns'][ $column ]['callback'] ) && is_callable( $this->args['admin_columns'][ $column ]['callback'] ) ) {
				call_user_func( $this->args['admin_columns'][ $column ]['callback'], $column, $post_id );
			} else {
				echo get_post_meta( $post_id, $column, true );
			}

		}

		/**
		 * Check if defined metabox is allowed on current page
		 *
		 * @since  1.0.0
		 * @return boolean
		 */
		public function is_allowed_page() {

			global $current_screen;

			if ( empty( $current_screen ) ) {
				return false;
			}

			if ( is_array( $this->args['page'] ) && ! in_array( $current_screen->id, $this->args['page'] ) ) {
				return false;
			}

			if ( is_string( $this->args['page'] ) && $current_screen->id !== $this->args['page'] ) {
				return false;
			}

			return true;
		}

		/**
		 * Add meta box handler
		 *
		 * @since  1.0.0
		 * @param  [type] $post_type The post type of the current post being edited.
		 * @param  object $post      The current post object.
		 * @return void
		 */
		public function add_meta_boxes( $post_type, $post ) {

			if ( ! $this->is_allowed_page() ) {
				return;
			}

			add_meta_box(
				$this->args['id'],
				$this->args['title'],
				array( $this, 'render_metabox' ),
				$this->args['page'],
				$this->args['context'],
				$this->args['priority'],
				$this->args['callback_args']
			);
		}

		/**
		 * Render metabox funciton
		 *
		 * @since  1.0.0
		 * @param  object $post    The post object currently being edited.
		 * @param  array  $metabox Specific information about the meta box being loaded.
		 * @return void
		 */
		public function render_metabox( $post, $metabox ) {

			if ( ! $this->builder ) {
				return;
			}

			/**
			 * Filter custom metabox output. Prevent from showing main box, if user output passed
			 *
			 * @var string
			 */
			$custom_box = apply_filters( 'cx_post_meta/custom_box', false, $post, $metabox );

			if ( false !== $custom_box ) {
				echo $custom_box;
				return;
			}

			wp_nonce_field( $this->nonce, $this->nonce );

			/**
			 * Hook fires before metabox output started.
			 */
			do_action( 'cx_post_meta/meta_box/before' );

			$this->builder->render();

			/**
			 * Hook fires after metabox output finished.
			 */
			do_action( 'cx_post_meta/meta_box/after' );

		}

		/**
		 * Safely get attribute from field settings array.
		 *
		 * @since  1.0.0
		 * @param  array            $field   arguments array.
		 * @param  string|int|float $arg     argument key.
		 * @param  mixed            $default default argument value.
		 * @return mixed
		 */
		public function get_arg( $field, $arg, $default = '' ) {
			if ( is_array( $field ) && isset( $field[ $arg ] ) ) {
				return $field[ $arg ];
			}
			return $default;
		}

		/**
		 * Get registered control fields
		 *
		 * @since  1.0.0
		 * @since  1.2.0 Use interface builder for HTML rendering.
		 * @param  mixed $post Current post object.
		 * @return void
		 */
		public function get_fields( $post ) {

			if ( is_array( $this->args['single'] ) && isset( $this->args['single']['key'] ) ) {
				$this->meta_values = get_post_meta( $post->ID, $this->args['single']['key'], true );
			}

			$zero_allowed = apply_filters(
				'cx_post_meta/zero_allowed_controls',
				array(
					'stepper',
					'slider',
				)
			);

			foreach ( $this->args['fields'] as $key => $field ) {

				$default = $this->get_arg( $field, 'value', '' );
				$value   = $this->get_meta( $post, $key, $default, $field );

				if ( isset( $field['options_callback'] ) ) {
					$field['options'] = call_user_func( $field['options_callback'] );
				}

				$element        = $this->get_arg( $field, 'element', 'control' );
				$field['id']    = $this->get_arg( $field, 'id', $key );
				$field['name']  = $this->get_arg( $field, 'name', $key );
				$field['type']  = $this->get_arg( $field, 'type', '' );
				$field['value'] = $value;

				// Fix zero values for stepper and slider
				if ( ! $value && in_array( $field['type'], $zero_allowed ) ) {
					$field['value'] = 0;
				}

				$register_callback = 'register_' . $element;

				if ( method_exists( $this->builder, $register_callback ) ) {
					call_user_func( array( $this->builder, $register_callback ), $field );
				}
			}
		}

		/**
		 * Save additional taxonomy meta on edit or create tax
		 *
		 * @since  1.0.0
		 * @param  int    $post_id The ID of the current post being saved.
		 * @param  object $post    The post object currently being saved.
		 * @return void|int
		 */
		public function save_meta( $post_id, $post = '' ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST[ $this->nonce ] ) || ! wp_verify_nonce( $_POST[ $this->nonce ], $this->nonce ) ) {
				return;
			}

			$posts = ! empty( $this->args['page'] ) ? $this->args['page'] : array( 'post' );
			$posts = is_array( $posts ) ? $posts : array( $posts );

			$maybe_break = false;

			foreach ( $posts as $post_type ) {

				if ( get_post_type( $post_id ) !== $post_type ) {
					$maybe_break = true;
					continue;
				}

				$maybe_break = false;
				$obj         = get_post_type_object( $post_type );

				if ( ! isset( $obj->cap->edit_posts ) || ! current_user_can( $obj->cap->edit_posts ) ) {
					$maybe_break = true;
					continue;
				}

				break;
			}

			if ( true === $maybe_break ) {
				return;
			}

			if ( ! $this->is_allowed_page() ) {
				return;
			}

			if ( ! is_object( $post ) ) {
				$post = get_post();
			}

			/**
			 * Hook on before current metabox saving
			 */
			do_action( 'cx_post_meta/before_save/' . $this->args['id'], $post_id, $post );

			if ( is_array( $this->args['single'] ) && isset( $this->args['single']['key'] ) ) {
				$this->save_meta_mod( $post_id );
			} else {
				$this->save_meta_option( $post_id );
			}

			/**
			 * Hook on after current metabox saving
			 */
			do_action( 'cx_post_meta/after_save/' . $this->args['id'], $post_id, $post );

		}

		/**
		 * Save all meta values as a one array value in `wp_postmeta` table.
		 *
		 * @since 1.1.0
		 * @param int $post_id Post ID.
		 */
		public function save_meta_mod( $post_id ) {
			$meta_key = $this->args['single']['key'];

			// Array of new post meta value.
			$new_meta_value = array();

			if ( empty( $_POST[ $meta_key ] ) ) {
				return;
			}

			foreach ( $_POST[ $meta_key ] as $key => $value ) {

				$new_meta_value[ $key ] = $this->sanitize_meta( $key, $value );
			}

			// Get current post meta data.
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			if ( $new_meta_value && '' == $meta_value ) {
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );
			} elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
				update_post_meta( $post_id, $meta_key, $new_meta_value );
			} elseif ( empty( $new_meta_value ) && $meta_value ) {
				delete_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		/**
		 * Save each meta value as a single value in `wp_postmeta` table.
		 *
		 * @since 1.1.0
		 * @param int $post_id Post ID.
		 */
		public function save_meta_option( $post_id ) {

			foreach ( $this->args['fields'] as $key => $field ) {

				if ( isset( $field['element'] ) && 'control' !== $field['element'] ) {
					continue;
				}

				$pre_processed = apply_filters( 'cx_post_meta/pre_process_key/' . $key, false, $post_id, $key );

				if ( $pre_processed ) {
					continue;
				}

				if ( empty( $_POST[ $key ] ) ) {

					/**
					 * Fires before specific key will be deleted
					 */
					do_action( 'cx_post_meta/before_delete_meta/' . $key, $post_id, $key );

					update_post_meta( $post_id, $key, false );

					continue;
				}

				if ( $this->to_timestamp( $field ) ) {
					$value = strtotime( $_POST[ $key ] );
				} else {
					$value = $this->sanitize_meta( $key, $_POST[ $key ] );
				}

				/**
				 * Fires on specific key saving
				 */
				do_action( 'cx_post_meta/before_save_meta/' . $key, $post_id, $value, $key );

				update_post_meta( $post_id, $key, $value );
			}

		}

		/**
		 * Is date field
		 *
		 * @param  [type]  $input_type [description]
		 * @return boolean             [description]
		 */
		public function to_timestamp( $field ) {

			if ( empty( $field['input_type'] ) ) {
				return false;
			}

			if ( empty( $field['is_timestamp'] ) ) {
				return false;
			}

			if ( ! in_array( $field['input_type'], array( 'date', 'datetime-local' ) ) ) {
				return false;
			}

			return ( true === $field['is_timestamp'] );

		}

		/**
		 * Sanitize passed meta value
		 *
		 * @since  1.1.3
		 * @param  string $key   Meta key to sanitize.
		 * @param  mixed  $value Meta value.
		 * @return mixed
		 */
		public function sanitize_meta( $key, $value ) {

			if ( empty( $this->args['fields'][ $key ]['sanitize_callback'] ) ) {
				return $this->sanitize_deafult( $value );
			}

			if ( ! is_callable( $this->args['fields'][ $key ]['sanitize_callback'] ) ) {
				return $this->sanitize_deafult( $value );
			}

			return call_user_func(
				$this->args['fields'][ $key ]['sanitize_callback'],
				$value,
				$key,
				$this->args['fields'][ $key ]
			);

		}

		/**
		 * Cleare value with sanitize_text_field if not is array
		 *
		 * @since  1.1.3
		 * @param  mixed $value Passed value.
		 * @return mixed
		 */
		public function sanitize_deafult( $value ) {
			return is_array( $value ) ? $value : sanitize_text_field( $value );
		}

		/**
		 * Retrieve post meta field.
		 *
		 * @since  1.1.0
		 * @since  1.2.0 Process default value.
		 *
		 * @param  object $post    Current post object.
		 * @param  string $key     The meta key to retrieve.
		 * @param  mixed  $default Default value.
		 * @param  array  $field   Meta field apropriate to current key.
		 * @return string
		 */
		public function get_meta( $post, $key, $default = false, $field = array() ) {

			if ( ! is_object( $post ) ) {
				return '';
			}

			$pre_value = apply_filters( 'cx_post_meta/pre_get_meta/' . $key, false, $post, $key, $default, $field );

			if ( false !== $pre_value ) {
				return $pre_value;
			}

			if ( is_array( $this->args['single'] ) && isset( $this->args['single']['key'] ) ) {
				return isset( $this->meta_values[ $key ] ) ? $this->meta_values[ $key ] : $default;
			}

			$meta = get_post_meta( $post->ID, $key, false );

			if ( ! empty( $meta[0] ) && $this->to_timestamp( $field ) ) {

				switch ( $field['input_type'] ) {
					case 'date':
						return date( 'Y-m-d', $meta[0] );

					case 'datetime-local':
						return date( 'Y-m-d\TH:i', $meta[0] );
				}

			}

			return ( empty( $meta ) ) ? $default : $meta[0];
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $core, $args ) {

			if ( ! is_admin() ) {
				return;
			}

			return new self( $core, $args );
		}
	}
}

<?php
/**
 * Theme hooks.
 *
 * @package Kava
 */

// Adds the meta viewport to the header.
add_action( 'wp_head', 'kava_meta_viewport', 0 );

// Additional body classes.
add_filter( 'body_class', 'kava_extra_body_classes' );

// Enqueue sticky menu if required.
add_filter( 'kava-theme/assets-depends/script', 'kava_enqueue_misc' );

// Additional image sizes for media gallery.
add_filter( 'image_size_names_choose', 'kava_image_size_names_choose' );

// Modify a comment form.
add_filter( 'comment_form_defaults', 'kava_modify_comment_form' );


/**
 * Adds the meta viewport to the header.
 *
 * @since  1.0.0
 * @return string `<meta>` tag for viewport.
 */
function kava_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
}

/**
 * Add extra body classes
 *
 * @param  array $classes Existing classes.
 * @return array
 */
function kava_extra_body_classes( $classes ) {

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( ! kava_is_top_panel_visible() ) {
		$classes[] = 'top-panel-invisible';
	}

	// Adds a options-based classes.
	$options_based_classes = array();

	$layout      = kava_theme()->customizer->get_value( 'container_type' );
	$blog_layout = kava_theme()->customizer->get_value( 'blog_layout_type' );
	$sb_position = kava_theme()->sidebar_position;
	$sidebar     = kava_theme()->customizer->get_value( 'sidebar_width' );

	array_push( $options_based_classes, 'layout-' . $layout, 'blog-' . $blog_layout );
	if( 'none' !== $sb_position ) {
		array_push( $options_based_classes, 'sidebar_enabled', 'position-' . $sb_position, 'sidebar-' . str_replace( '/', '-', $sidebar ) );
	}
	
	return array_merge( $classes, $options_based_classes );
}

/**
 * Add misc to theme script dependencies if required.
 *
 * @param  array $depends Default dependencies.
 * @return array
 */
function kava_enqueue_misc( $depends ) {
	$totop_visibility = kava_theme()->customizer->get_value( 'totop_visibility' );

	if ( $totop_visibility ) {
		$depends[] = 'jquery-totop';
	}

	return $depends;
}

/**
 * Add image sizes for media gallery
 *
 * @param  array $classes Existing classes.
 * @return array
 */
function kava_image_size_names_choose( $image_sizes ) {
	$image_sizes['post-thumbnail'] = __( 'Post Thumbnail' );

	return $image_sizes;
}

/**
 * Add placeholder attributes for comment form fields.
 *
 * @param  array $args Argumnts for comment form.
 * @return array
 */
function kava_modify_comment_form( $args ) {
	$args = wp_parse_args( $args );

	if ( ! isset( $args['format'] ) ) {
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
	}

	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html_req  = ( $req ? " required='required'" : '' );
	$html5     = 'html5' === $args['format'];
	$commenter = wp_get_current_commenter();

	$args['label_submit'] = esc_html__( 'Submit Comment', 'kava' );

	$args['fields']['author'] = '<p class="comment-form-author"><input id="author" class="comment-form__field" name="author" type="text" placeholder="' . esc_html__( 'Name', 'kava' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['email'] = '<p class="comment-form-email"><input id="email" class="comment-form__field" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'E-mail', 'kava' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>';

	$args['fields']['url'] = '<p class="comment-form-url"><input id="url" class="comment-form__field" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . esc_html__( 'Website', 'kava' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	$args['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" class="comment-form__field" name="comment" placeholder="' . esc_html__( 'Comments *', 'kava' ) . '" cols="45" rows="7" aria-required="true" required="required"></textarea></p>';

	return $args;
}




/* Fore Kava Extras Plugin */

add_action( 'elementor/controls/controls_registered', 'kava_add_theme_icons_to_icon_control', 20 );
add_action( 'elementor/editor/after_enqueue_styles', 'kava_enqueue_icon_font' );
add_action( 'wp_enqueue_scripts', 'kava_enqueue_icon_font' );

/**
 * Add theme icons to the icon control.
 *
 * @param object $controls_manager Object Controls manager.
 */
function kava_add_theme_icons_to_icon_control( $controls_manager ) {
	$default_icons = $controls_manager->get_control( 'icon' )->get_settings( 'options' );
	$nc_mini_icons_data = array(
		'icons'  => kava_get_nc_mini_icons_set(),
		'format' => 'nc-icon-mini %s',
	);
	$nc_mini_icons_array = array();
	foreach ( $nc_mini_icons_data['icons'] as $icon ) {
		$key = sprintf( $nc_mini_icons_data['format'], $icon );
		$nc_mini_icons_array[ $key ] = $icon;
	}
	$new_icons = array_merge( $default_icons, $nc_mini_icons_array );
	$controls_manager->get_control( 'icon' )->set_settings( 'options', $new_icons );
}

/**
 * Enqueue icon font.
 */
function kava_enqueue_icon_font() {
	wp_enqueue_style(
		'nucleo-mini',
		get_parent_theme_file_uri( 'nucleo-mini-icon-font/nucleo-mini.css' ),
		array(),
		'1.0.0'
	);
}

/**
 * Get nc_mini icons set.
 *
 * @return array
 */
function kava_get_nc_mini_icons_set() {
	static $nc_mini_icons;
	if ( ! $nc_mini_icons ) {
		ob_start();
		include get_parent_theme_file_path( 'nucleo-mini-icon-font/nucleo-mini.css' );
		$result = ob_get_clean();
		
		preg_match_all( '/\.([-_a-zA-Z0-9]+):before[, {]/', $result, $matches );
		if ( ! is_array( $matches ) || empty( $matches[1] ) ) {
			return;
		}
		$nc_mini_icons = $matches[1];
	}
	
	return $nc_mini_icons;
}
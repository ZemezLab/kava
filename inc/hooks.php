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

// Additional image sizes for media gallery.
add_filter( 'image_size_names_choose', 'kava_image_size_names_choose' );

// Modify a comment form.
add_filter( 'comment_form_defaults', 'kava_modify_comment_form' );

// Modify fonts list.
add_filter( 'cx_customizer/fonts_list', 'kava_modify_fonts_list' );

// Disable site content container on specific page/post
add_filter( 'kava-theme/site-content/container-enabled', 'kava_disable_site_content_container', 20 );

// Set default single post template
add_filter( 'get_post_metadata', 'kava_set_default_single_post_template', 10, 4 );


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
 * Add image sizes for media gallery
 *
 * @param  array $image_sizes
 * @return array
 */
function kava_image_size_names_choose( $image_sizes ) {
	$image_sizes['post-thumbnail'] = __( 'Post Thumbnail', 'kava' );

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

	$args['fields']['author'] = '<p class="comment-form-author"><input id="author" class="comment-form__field" name="author" type="text" placeholder="' . esc_attr__( 'Name', 'kava' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' /></p>';

	$args['fields']['email'] = '<p class="comment-form-email"><input id="email" class="comment-form__field" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' placeholder="' . esc_attr__( 'E-mail', 'kava' ) . ( $req ? ' *' : '' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></p>';

	$args['fields']['url'] = '<p class="comment-form-url"><input id="url" class="comment-form__field" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' placeholder="' . esc_attr__( 'Website', 'kava' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	$args['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" class="comment-form__field" name="comment" placeholder="' . esc_attr__( 'Comments *', 'kava' ) . '" cols="45" rows="7" aria-required="true" required="required"></textarea></p>';

	return $args;
}

/**
 * Modify fonts list.
 *
 * @param  array $fonts Fonts List.
 * @return array
 */
function kava_modify_fonts_list( $fonts = array() ) {

	$fonts = array_merge(
		array(
			'-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen-Sans,Ubuntu,Cantarell,Helvetica Neue,sans-serif' => esc_html__( 'Default System Font', 'kava' ),
		),
		$fonts
	);

	return $fonts;
}

/**
 * Disable site content container
 *
 * @param  boolean $enabled
 * @return boolean
 */
function kava_disable_site_content_container( $enabled = true ) {
	$disable_content_container_archive_cpt = kava_settings()->get( 'disable_content_container_archive_cpt' );
	$disable_content_container_single_cpt  = kava_settings()->get( 'disable_content_container_single_cpt' );

	$post_type = get_post_type();

	if ( ( is_archive() || ( is_home() && 'post' === $post_type ) ) && isset( $disable_content_container_archive_cpt[ $post_type ] )
	     && filter_var( $disable_content_container_archive_cpt[ $post_type ], FILTER_VALIDATE_BOOLEAN )
	) {
		return false;
	}

	if ( is_search() && isset( $disable_content_container_archive_cpt['search_results'] )
	     && filter_var( $disable_content_container_archive_cpt['search_results'], FILTER_VALIDATE_BOOLEAN )
	) {
		return false;
	}

	if ( is_singular() && isset( $disable_content_container_single_cpt[ $post_type ] )
	     && filter_var( $disable_content_container_single_cpt[ $post_type ], FILTER_VALIDATE_BOOLEAN )
	) {
		return false;
	}

	if ( is_404() && isset( $disable_content_container_single_cpt['404_page'] )
	     && filter_var( $disable_content_container_single_cpt['404_page'], FILTER_VALIDATE_BOOLEAN )
	) {
		return false;
	}

	return $enabled;
}

/**
 * Set default single post template.
 *
 * @param $value
 * @param $post_id
 * @param $meta_key
 * @param $single
 *
 * @return mixed
 */
function kava_set_default_single_post_template( $value, $post_id, $meta_key, $single ) {

	if ( '_wp_page_template' !== $meta_key ) {
		return $value;
	}

	if ( is_admin() ) {
		return $value;
	}

	if ( ! is_singular( 'post' ) ) {
		return $value;
	}

	remove_filter( 'get_post_metadata', 'kava_set_default_single_post_template', 10 );

	$current_template = get_post_meta( $post_id, '_wp_page_template', true );

	add_filter( 'get_post_metadata', 'kava_set_default_single_post_template', 10, 4 );

	if ( '' !== $current_template && 'default' !== $current_template ) {
		return $value;
	}

	$global_post_template = kava_settings()->get( 'single_post_template', 'default' );

	if ( empty( $global_post_template ) || 'default' === $global_post_template ) {
		return $value;
	}

	return $global_post_template;
}

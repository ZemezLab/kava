<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Kava
 */

/**
 * Set post specific meta value.
 *
 * @param  string $value Default meta-value.
 * @return string
 */
function kava_set_post_meta_value( $value ) {
	$queried_obj = kava_get_queried_obj();

	if ( ! $queried_obj ) {
		return $value;
	}

	$meta_key   = 'kava_' . str_replace( 'theme_mod_', '', current_filter() );
	$meta_value = get_post_meta( $queried_obj, $meta_key, true );

	if ( ! $meta_value || 'inherit' === $meta_value ) {
		return $value;
	}

	return $meta_value;
}

/**
 * Get queried object.
 *
 * @return string|boolean
 */
function kava_get_queried_obj() {
	$queried_obj = apply_filters( 'kava-theme/posts/queried_object_id', false );

	if ( ! $queried_obj && ! kava_maybe_need_rewrite_mod() ) {
		return false;
	}

	$queried_obj = is_home() ? get_option( 'page_for_posts' ) : false;
	$queried_obj = ! $queried_obj ? get_the_id() : $queried_obj;

	return $queried_obj;
}

/**
 * Check if we need to try rewrite theme mod or not
 *
 * @return boolean
 */
function kava_maybe_need_rewrite_mod() {

	if ( is_front_page() && 'page' !== get_option( 'show_on_front' ) ) {
		return false;
	}

	if ( is_home() && 'page' == get_option( 'show_on_front' ) ) {
		return true;
	}

	if ( ! is_singular() ) {
		return false;
	}

	return true;
}

/**
 * Render existing macros in passed string.
 *
 * @since  1.0.0
 * @param  string $string String to parse.
 * @return string
 */
function kava_render_macros( $string ) {

	$macros = apply_filters( 'kava-theme/data_macros', array(
		'/%%year%%/' => date( 'Y' ),
		'/%%date%%/' => date( get_option( 'date_format' ) ),
	) );

	return preg_replace( array_keys( $macros ), array_values( $macros ), $string );
}

/**
 * Render font icons in content
 *
 * @param  string $content content to render
 * @return string
 */
function kava_render_icons( $content ) {
	$icons     = kava_get_render_icons_set();
	$icons_set = implode( '|', array_keys( $icons ) );

	$regex = '/icon:(' . $icons_set . ')?:?([a-zA-Z0-9-_]+)/';

	return preg_replace_callback( $regex, 'kava_render_icons_callback', $content );
}

/**
 * Callback for icons render.
 *
 * @param  array $matches Search matches array.
 * @return string
 */
function kava_render_icons_callback( $matches ) {

	if ( empty( $matches[1] ) && empty( $matches[2] ) ) {
		return $matches[0];
	}

	if ( empty( $matches[1] ) ) {
		return sprintf( '<i class="fa fa-%s"></i>', $matches[2] );
	}

	$icons = kava_get_render_icons_set();

	if ( ! isset( $icons[ $matches[1] ] ) ) {
		return $matches[0];
	}

	return sprintf( $icons[ $matches[1] ], $matches[2] );
}

/**
 * Get list of icons to render.
 *
 * @return array
 */
function kava_get_render_icons_set() {
	return apply_filters( 'kava-theme/icons/icons-set', array(
		'fa'       => '<i class="fa fa-%s"></i>',
		'material' => '<i class="material-icons">%s</i>',
	) );
}

/**
 * Replace %s with theme URL.
 *
 * @param  string $url Formatted URL to parse.
 * @return string
 */
function kava_render_theme_url( $url ) {
	return sprintf( $url, get_template_directory_uri() );
}

/**
 * Get justify thumbnail size.
 *
 * @return string
 */
function kava_justify_thumbnail_size( $mask = 0, $thumbnail_size = 'post-thumbnail', $justify_size='kava-thumb-justify', $justify_size_1 = 'kava-thumb-justify', $justify_size_2 = 'kava-thumb-justify-2') {
	$mask_list = array(
		array( $justify_size_1, $justify_size_2, $justify_size_2, $justify_size_1, $justify_size_1, $justify_size_1, $justify_size_1 ),
		array( $justify_size_1, $justify_size_1, $justify_size_2, $justify_size_2, $justify_size_1, $justify_size_1, $justify_size_1, $justify_size_2, $justify_size_1 )
	);

	global $wp_query;
	$image_size_index = $wp_query->current_post % count( $mask_list[$mask] );

	return $mask_list[$mask][$image_size_index];
}

/**
 * Get post template part slug.
 *
 * @return string
 */
function kava_get_post_template_part_slug() {
	return apply_filters( 'kava-theme/posts/template-part-slug', 'template-parts/content' );
}

/**
 * Get post template part slug.
 *
 * @return string
 */
function kava_get_post_style() {
	return apply_filters( 'kava-theme/posts/post-style', false );
}

/**
 * Return a list of allowed tags and attributes.
 *
 * @param array $additional_allowed_html Additional allowed tags and attributes
 *
 * @return array
 */
function kava_kses_post_allowed_html( $additional_allowed_html = array() ) {
	$allowed_html = wp_kses_allowed_html( 'post' );

	if ( ! empty( $additional_allowed_html ) ) {
		foreach ( $additional_allowed_html as $tag => $attr ) {
			if ( array_key_exists( $tag, $allowed_html ) ) {
				$allowed_html[ $tag ] = array_merge( $allowed_html[ $tag ], $attr );
			} else {
				$allowed_html[ $tag ] = $attr;
			}
		}
	}

	return $allowed_html;
}

/**
 * Support `wp_body_open` action, available since WordPress 5.2.
 */
function kava_body_open() {
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
}

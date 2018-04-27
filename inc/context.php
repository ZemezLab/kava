<?php
/**
 * Contextual functions for the header, footer, content and sidebar classes.
 *
 * @package Kava
 */

/**
 * Prints site header container CSS classes
 *
 * @since   1.0.0
 * @param   string  $classes Additional classes.
 * @return  void
 */
function kava_header_container_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	$sticky = kava_theme()->customizer->get_value( 'is_sticky_mode' );

	if ( $sticky ) {
		$classes .= 'header-sticky';
	}

	echo 'class="' . $classes . '"';
}

/**
 * Retrieve a CSS class attribute for container based on `Page Layout Type` option.
 *
 * @since  1.0.0
 * @param  string  $classes Additional classes.
 * @return string
 */
function kava_get_container_classes( $classes = null, $fullwidth = false ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	if ( ! $fullwidth ) {
		$layout_type = kava_theme()->customizer->get_value( 'container_type' );

		if ( 'boxed' == $layout_type ) {
			$classes .= 'container';
		} else {
			//$classes .= 'container-fluid';
		}
	}

	return 'class="' . $classes . '"';
}

/**
 * Retrieve a site content wrapper class.
 *
 * @since  1.0.0
 * @return string
 */
function kava_get_site_content_wrapper_classes( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	$classes .= 'site-content__wrap';

	$fullwidth = kava_theme()->content_fullwidth;

	if ( $fullwidth ) {
		return 'class="' . $classes . '"';
	}

	$classes .= ' container';

	return 'class="' . apply_filters( 'kava-theme/wrapper/site-content-classes', $classes ) . '"';
}

/**
 * Prints primary content wrapper CSS classes.
 *
 * @since  1.0.0
 * @param  array $classes Additional classes.
 * @return void
 */
function kava_primary_content_class( $classes = array() ) {
	$fullwidth_page = kava_theme()->content_fullwidth;
	if ( ! $fullwidth_page ) {
		echo kava_get_layout_classes( 'content', $classes );
	} else {
		array_push( $classes, 'col-xs-12' );

		echo 'class="' . join( ' ', $classes ) . '"';
	}
}

/**
 * Prints secondary content wrapper CSS classes.
 *
 * @since  1.0.0
 * @param  array $classes Additional classes.
 * @return void
 */
function kava_secondary_content_class( $classes = array() ) {
	echo kava_get_layout_classes( 'sidebar', $classes );
}

/**
 * Get CSS class attribute for passed layout context.
 *
 * @since  1.0.0
 * @param  string $layout  Layout context.
 * @param  array  $classes Additional classes.
 * @return string
 */
function kava_get_layout_classes( $layout = 'content', $classes = array() ) {
	$sidebar          = kava_theme()->sidebar_enabled;
	$sidebar_position = kava_theme()->sidebar_position;
	$sidebar_width    = kava_theme()->customizer->get_value( 'sidebar_width' );

	if ( ! $sidebar || 'fullwidth' === $sidebar_position ) {
		$sidebar_position = is_singular( 'post' ) ? 'single-post-fullwidth' : 'fullwidth';
		$sidebar_width = 0;
	}

	$layout_classes = ! empty( kava_theme()->layout[ $sidebar_position ][ $sidebar_width ][ $layout ] ) ? kava_theme()->layout[ $sidebar_position ][ $sidebar_width ][ $layout ] : array();

	if ( ! empty( $classes ) ) {
		$layout_classes = array_merge( $layout_classes, $classes );
	}

	if ( empty( $layout_classes ) ) {
		return '';
	}

	$layout_classes = apply_filters( "kava-theme/wrapper/{$layout}_classes", $layout_classes );

	return 'class="' . join( ' ', $layout_classes ) . '"';
}

/**
 * Retrieve or print `class` attribute for Post List wrapper.
 *
 * @since  1.0.0
 * @param  array       $classes Additional classes.
 * @param  boolean     $echo    True for print. False - return.
 * @return string|void
 */
function kava_posts_list_class( $classes = array(), $echo = true ) {
	$layout_type      = kava_theme()->customizer->get_value( 'blog_layout_type' );
	$layout_type      = ! is_search() ? $layout_type : 'default';
	$layout_style     = kava_theme()->customizer->get_value( 'blog_style' );

	$classes[] = 'posts-list';
	$classes[] = 'posts-list--' . sanitize_html_class( $layout_type );
	$classes[] = 'list-style-' . sanitize_html_class( $layout_style );

	$classes = apply_filters( 'kava-theme/posts/list_class', $classes );

	$output = 'class="' . join( ' ', $classes ) . '"';

	if ( ! $echo ) {
		return $output;
	}

	echo $output;
}


/**
 * Prints site header CSS classes.
 *
 * @since  1.0.0
 * @param  array $classes Additional classes.
 * @return void
 */
function kava_site_branding_class( $classes = array() ) {
	$classes[] = 'site-branding';

	echo 'class="' . join( ' ', $classes ) . '"';
}

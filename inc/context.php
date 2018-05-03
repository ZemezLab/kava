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

	if ( ! apply_filters( 'kava-theme/site/fullwidth', $fullwidth ) ) {
		$layout_type = kava_theme()->customizer->get_value( 'container_type' );

		if ( 'boxed' == $layout_type ) {
			$classes .= 'container';
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
	$site_content_container = apply_filters( 'kava-theme/wrapper/site-content-container-enabled', true );
	if ( $site_content_container ) {
		$classes .= ' container';
	}

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
	echo kava_get_layout_classes( 'content', $classes );
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
	$sidebar_position = kava_theme()->sidebar_position;
	$sidebar_width    = kava_theme()->customizer->get_value( 'sidebar_width' );

	if ( 'none' === $sidebar_position ) {
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
 * @param  string       $classes Additional classes.
 * @return string|void
 */
function kava_posts_list_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	$classes .= 'posts-list';

	echo 'class="' . apply_filters( 'kava-theme/posts/list-class', $classes ) . '"';
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

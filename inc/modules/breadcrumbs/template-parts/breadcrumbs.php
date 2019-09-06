<?php
/**
 * Template part for breadcrumbs.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kava
 */

$breadcrumbs_visibillity = kava_theme()->customizer->get_value( 'breadcrumbs_visibillity' );
$breadcrumbs_visibillity = apply_filters( 'kava-theme/breadcrumbs/breadcrumbs-visibillity', $breadcrumbs_visibillity );

if ( ! $breadcrumbs_visibillity ) {
	return;
}

$breadcrumbs_front_visibillity = kava_theme()->customizer->get_value( 'breadcrumbs_front_visibillity' );

if ( ! $breadcrumbs_front_visibillity && is_front_page() ) {
	return;
}

do_action( 'kava-theme/breadcrumbs/breadcrumbs-before-render' );

?><div <?php echo kava_get_container_classes( 'site-breadcrumbs' ); ?>>
	<div <?php kava_breadcrumbs_class(); ?>>
		<?php do_action( 'kava-theme/breadcrumbs/before' ); ?>
		<?php do_action( 'cx_breadcrumbs/render' ); ?>
		<?php do_action( 'kava-theme/breadcrumbs/after' ); ?>
	</div>
</div><?php

do_action( 'kava-theme/breadcrumbs/breadcrumbs-after-render' );

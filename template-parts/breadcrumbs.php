<?php
/**
 * Template part for breadcrumbs.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kava
 */

$breadcrumbs_visibillity = kava_theme()->customizer->get_value( 'breadcrumbs_visibillity' );

if ( !$breadcrumbs_visibillity ) {
	return;
}

?><div class="breadcrumbs-area container">
	<?php do_action( 'kava-theme/breadcrumbs/before' ); ?>
	<?php do_action( 'cx_breadcrumbs/render' ); ?>
	<?php do_action( 'kava-theme/breadcrumbs/after' ); ?>
</div><?php

<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

echo '<div class="post-navigation-container">';

the_post_navigation( array(
	'prev_text' => sprintf( '
		<div class="screen-reader-text">%1$s</div>
		<i class="fa fa-chevron-left" aria-hidden="true"></i>
		<div class="nav-text">%1$s</div>
		<h4 class="post-title">%2$s</h4>',
		esc_html__( 'Previous', 'kava' ),
		'%title'
	),
	'next_text' => sprintf( '
		<div class="screen-reader-text">%1$s</div>
		<i class="fa fa-chevron-right" aria-hidden="true"></i>
		<div class="nav-text">%1$s</div>
		<h4 class="post-title">%2$s</h4>',
		esc_html__( 'Next', 'kava' ),
		'%title'
	),
) );

echo '</div>';
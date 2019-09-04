<?php
/**
 * Menus configuration.
 *
 * @package Kava
 */

add_action( 'after_setup_theme', 'kava_register_menus', 5 );
function kava_register_menus() {

	register_nav_menus( array(
		'main'   => esc_html__( 'Main', 'kava' ),
		//'footer' => esc_html__( 'Footer', 'kava' ),
		'social' => esc_html__( 'Social', 'kava' ),
	) );
}

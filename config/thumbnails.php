<?php
/**
 * Thumbnails configuration.
 *
 * @package kava
 */

add_action( 'after_setup_theme', 'kava_register_image_sizes', 5 );
function kava_register_image_sizes() {
	set_post_thumbnail_size( 370, 265, true );

	// Registers a new image sizes.
	add_image_size( 'kava-thumb-s', 150, 85, true );
	add_image_size( 'kava-thumb-s-2', 230, 230, true );
	add_image_size( 'kava-thumb-m', 400, 400, true );
	add_image_size( 'kava-thumb-m-vertical', 370, 500, true );
	add_image_size( 'kava-thumb-m-2', 570, 450, true );
	add_image_size( 'kava-thumb-l', 1170, 650, true );
	add_image_size( 'kava-thumb-xl', 1920, 1080, true );
	add_image_size( 'kava-thumb-masonry', 600, 999, false );
	add_image_size( 'kava-thumb-justify', 640, 640, true );
	add_image_size( 'kava-thumb-justify-2', 1280, 640, true );
}
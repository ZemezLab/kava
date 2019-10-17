<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

get_header(); 

	do_action( 'kava-theme/site/site-content-before', 'page' );

	kava_theme()->do_location( 'single', 'template-parts/page' );

	do_action( 'kava-theme/site/site-content-after', 'page' );

get_footer();

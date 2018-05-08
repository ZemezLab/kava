<?php
/**
 * Template Name: Full Width Content Layout
 * Template Post Type: post, page, event
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Kava
 */

get_header();

	while ( have_posts() ) : the_post();

		?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php
			the_content();
		?></article><!-- #post-<?php the_ID(); ?> --><?php

	endwhile; // End of the loop.

get_footer();
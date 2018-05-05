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

	do_action( 'kava-theme/site/site-content-before', 'page' ); ?>

	<div class="site-content__wrap">

		<?php do_action( 'kava-theme/site/main-before', 'page' ); ?>

		<main id="main" class="site-main"><?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
		?></main><!-- #main -->

		<?php do_action( 'kava-theme/site/main-after', 'page' ); ?>

	</div>

	<?php do_action( 'kava-theme/site/site-content-after', 'page' );

get_footer();
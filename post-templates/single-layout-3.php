<?php
/**
 * Template Name: Post Layout 03
 * Template Post Type: post
 *
 * The template for displaying layout 3 single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Kava
 */

get_header(); 
	?><div class="site-content__wrap"><?php
		get_template_part( 'template-parts/post/single/headers/header-v3', get_post_format() );
		?><div class="container">
			<div class="row">
				<div id="primary" <?php kava_primary_content_class(); ?>>
					<main id="main" class="site-main">
						<?php while ( have_posts() ) : the_post();

							?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php
								
								get_template_part( 'template-parts/post/single/content', get_post_format() );
								get_template_part( 'template-parts/post/single/footer' );

							?></article><?php

								get_template_part( 'template-parts/post/single/author-bio' );
								get_template_part( 'template-parts/post/single/post_navigation' );
								kava_related_posts();
								get_template_part( 'template-parts/post/single/comments' );

						endwhile; // End of the loop. ?>
					</main><!-- #main -->
				</div><!-- #primary -->
				<?php get_sidebar(); // Loads the sidebar.php template.  ?>
			</div>
		</div>
	</div><?php
get_footer();
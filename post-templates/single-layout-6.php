<?php
/**
 * Template Name: Post Layout 06
 * Template Post Type: post
 *
 * The template for displaying layout 6 single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Kava
 */

$author_block_enabled = kava_theme()->customizer->get_value( 'single_author_block' );
$primary_colum_class = $author_block_enabled ? 'col-xs-12 col-md-8' : 'col-xs-12';

get_header(); 
	?><div class="site-content__wrap">
		<div class="container">
			<div class="row">
				<?php if ( $author_block_enabled ) : ?>
					<div id="author-block" class="col-xs-12 col-md-3"><?php
						get_template_part( 'template-parts/single-post/author-bio' );
						kava_posted_on( array(
							'prefix'  => __( 'Posted', 'kava' ),
							'before' => '<div class="posted-on">',
							'after'  => '</div>',
						) );
					?></div>
				<?php endif; ?>
				<div id="primary" class="col-xs-12 col-md-9">
					<main id="main" class="site-main">
						<?php while ( have_posts() ) : the_post();

							?><article id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php
								
								get_template_part( 'template-parts/single-post/headers/header-v6', get_post_format() );
								get_template_part( 'template-parts/single-post/content', get_post_format() );
								get_template_part( 'template-parts/single-post/footer' );

							?></article><?php

								get_template_part( 'template-parts/single-post/post_navigation' );
								kava_related_posts();
								get_template_part( 'template-parts/single-post/comments' );

						endwhile; // End of the loop. ?>
					</main><!-- #main -->
				</div><!-- #primary -->
			</div>
		</div>
	</div><?php
get_footer();
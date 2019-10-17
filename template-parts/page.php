<?php
/**
 * The template for displaying all pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */
?>

<div <?php kava_content_class() ?>>
	<div class="row">

		<?php do_action( 'kava-theme/site/primary-before', 'page' ); ?>

		<div id="primary" <?php kava_primary_content_class(); ?>>

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

		</div><!-- #primary -->

		<?php do_action( 'kava-theme/site/primary-after', 'page' ); ?>

		<?php get_sidebar(); // Loads the sidebar.php template.  ?>
	</div>
</div>

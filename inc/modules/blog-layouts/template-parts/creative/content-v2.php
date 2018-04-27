<?php
/**
 * Template part for displaying creative posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item creative-item invert-hover' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="creative-item__thumbnail" <?php kava_post_overlay_thumbnail( 'kava-thumb-m' ); ?>></div>
	<?php endif; ?>

	<header class="entry-header">
		<?php
			kava_posted_in();
			kava_posted_on( array(
				'prefix' => __( 'Posted', 'kava' )
			) );
		?>
		<h4 class="entry-title"><?php 
			kava_sticky_label();
			the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
		?></h4>
	</header><!-- .entry-header -->

	<?php kava_post_excerpt(); ?>

	<footer class="entry-footer">
		<div class="entry-meta">
			<div>
				<?php
					kava_posted_by();
					kava_post_comments( array(
						'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>'
					) );
					kava_post_tags( array(
						'prefix' => __( 'Tags:', 'kava' )
					) );
				?>
			</div>
			<?php
				kava_post_link();
			?>
		</div>
		<?php kava_edit_link(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->

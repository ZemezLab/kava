<?php
/**
 * Template part for displaying creative posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item creative-item' ); ?>>

	<?php $title = get_the_title(); ?>

	<div class="creative-item__title-first-letter">
		<?php echo substr($title, 0, 1); ?>
	</div>

	<div class="creative-item__content">
		<header class="entry-header">
			<?php
				kava_posted_in();
			?>
			<h3 class="entry-title"><?php 
				kava_sticky_label();
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
			?></h3>
		</header><!-- .entry-header -->

		<?php kava_post_excerpt(); ?>
	</div>

	<footer class="entry-footer">
		<div class="entry-meta">
			<div>
				<?php
					kava_posted_by();
					kava_posted_on( array(
						'prefix' => __( 'Posted', 'kava' )
					) );
					kava_post_comments( array(
						'postfix' => __( 'Comment(s)', 'kava' )
					) );
				?>
			</div>
			<?php
				kava_post_tags( array(
					'prefix' => __( 'Tags:', 'kava' )
				) );
				kava_post_link();
			?>
		</div>
		<?php kava_edit_link(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->

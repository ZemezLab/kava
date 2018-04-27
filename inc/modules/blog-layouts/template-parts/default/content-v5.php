<?php
/**
 * Template part for displaying default posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('posts-list__item default-item'); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="default-item__thumbnail" <?php kava_post_overlay_thumbnail( 'kava-thumb-m-2' ); ?>></div>
	<?php endif; ?>

	<div class="default-item__content">

		<header class="entry-header">
			<h3 class="entry-title"><?php 
				kava_sticky_label();
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
			?></h3>
			<div class="entry-meta">
				<?php
					kava_posted_by();
					kava_posted_in( array(
						'prefix' => __( 'In', 'kava' ),
					) );
					kava_posted_on( array(
						'prefix' => __( 'Posted', 'kava' )
					) );
				?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<?php kava_post_excerpt(); ?>

		<footer class="entry-footer">
			<div class="entry-meta">
				<?php
					kava_post_tags( array(
						'prefix' => __( 'Tags:', 'kava' )
					) );
				?>
				<div><?php
					kava_post_link();
					kava_post_comments( array(
						'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>',
						'class'  => 'comments-button'
					) );
				?></div>
			</div>
			<?php kava_edit_link(); ?>
		</footer><!-- .entry-footer -->
	
	</div>

</article><!-- #post-<?php the_ID(); ?> -->

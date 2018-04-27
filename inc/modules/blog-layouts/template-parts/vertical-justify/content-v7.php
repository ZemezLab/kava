<?php
/**
 * Template part for displaying dtyle-7 posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item justify-item' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="justify-item__thumbnail" <?php kava_post_overlay_thumbnail( kava_justify_thumbnail_size(1) );?>></div>
	<?php endif; ?>
	<header class="entry-header">
			<h4 class="entry-title"><?php 
				kava_sticky_label();
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
			?></h4>
			<div class="entry-meta">
				<?php
				kava_posted_by();
				kava_posted_in( array(
					'prefix' => __( 'In', 'kava' ),
					'delimiter' => ', '
				) ); 
				kava_posted_on( array(
					'prefix' => __( 'Posted', 'kava' ),
				) ); 
				?>
			</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<?php kava_post_excerpt(); ?>
	<footer class="entry-footer">
		<div class="entry-meta">
			<?php
			kava_post_tags();

			$post_more_btn_enabled = strlen( kava_theme()->customizer->get_value( 'blog_read_more_text' ) ) > 0 ? true : false;
			$post_comments_enabled = kava_theme()->customizer->get_value( 'blog_post_comments' );

			if( $post_more_btn_enabled || $post_comments_enabled ) {
				?><div class="space-between-content"><?php
				kava_post_link();
				kava_post_comments();
				?></div><?php
			}
			?>
		</div>
	</footer><!-- .entry-footer -->
	<?php kava_edit_link(); ?>
</article><!-- #post-<?php the_ID(); ?> -->

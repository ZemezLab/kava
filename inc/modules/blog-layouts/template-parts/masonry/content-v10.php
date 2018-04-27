<?php
/**
 * Template part for displaying style-10 posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item masonry-item' ); ?>>
	<?php kava_post_thumbnail( 'kava-thumb-masonry' ); ?>
	<div class="masonry-item-inner">
		<header class="entry-header">
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
			<h4 class="entry-title"><?php 
				kava_sticky_label();
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
			?></h4>
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
	</div><!-- .masonry-item-inner -->
	<?php kava_edit_link(); ?>
</article><!-- #post-<?php the_ID(); ?> -->

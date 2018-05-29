<?php
/**
 * Template part for displaying style-v7 posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kava
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item grid-item' ); ?>>
	<div class="grid-item-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="grid-item__thumbnail" <?php kava_post_overlay_thumbnail( 'kava-thumb-l' );?>></div>
		<?php endif; ?>
		<div class="grid-item-wrap invert">
			<?php
				kava_posted_in( array(
					'prefix' => false,
					'delimiter' => false,
				) );
			?>
			<header class="entry-header">
				<div class="entry-meta">
					<?php
					kava_posted_by();
					kava_posted_on( array(
						'prefix' => '',
					) );
					?>
				</div><!-- .entry-meta -->
				<h4 class="entry-title"><?php
					kava_sticky_label();
					the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
				?></h4>
			</header><!-- .entry-header -->
			<div class="grid-item-wrap__animated">
				<?php kava_post_excerpt(); ?>

				<footer class="entry-footer">
					<div class="entry-meta">
						<?php

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
			</div><!-- .grid-item-wrap__animated-->
		</div><!-- .grid-item-wrap-->
	</div><!-- .grid-item-inner-->
	<?php kava_edit_link(); ?>
</article><!-- #post-<?php the_ID(); ?> -->


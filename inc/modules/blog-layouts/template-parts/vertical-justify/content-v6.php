<?php
/**
 * Template part for displaying style-v9 posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item justify-item invert-item' ); ?>>
	<div class="justify-item-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="justify-item__thumbnail" <?php kava_post_overlay_thumbnail( kava_justify_thumbnail_size(0) );?>></div>
		<?php endif; ?>
		<div class="justify-item-inner__top"><?php
			kava_post_comments( array(
				'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>',
				'class'  => 'comments-button'
			) );
		?></div>
		<div class="justify-item-wrap invert">
			<header class="entry-header">
				<div class="entry-meta">
					<?php
					kava_posted_by();
					kava_posted_in( array(
						'prefix' => __( 'In', 'kava' ),
						'delimiter' => ', '
					) );
					kava_posted_on( array(
						'prefix' => '',
					) );
					kava_post_tags();
					?>
				</div><!-- .entry-meta -->
				<h4 class="entry-title"><?php
					kava_sticky_label();
					the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
				?></h4>
			</header><!-- .entry-header -->
			<div class="justify-item-wrap__animated">
				<?php kava_post_excerpt(); ?>

				<footer class="entry-footer">
					<div class="entry-meta">
						<?php

						$post_more_btn_enabled = strlen( kava_theme()->customizer->get_value( 'blog_read_more_text' ) ) > 0 ? true : false;

						if( $post_more_btn_enabled ) {
							?><div class="space-between-content"><?php
							kava_post_link();
							?></div><?php
						}
						?>
					</div>
				</footer><!-- .entry-footer -->
			</div><!-- .justify-item-wrap__animated-->
		</div><!-- .justify-item-wrap-->
	</div><!-- .justify-item-inner-->
	<?php kava_edit_link(); ?>
</article><!-- #post-<?php the_ID(); ?> -->

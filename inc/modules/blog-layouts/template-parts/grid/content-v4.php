<?php
/**
 * Template part for displaying style-v4 posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package kava
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item grid-item' ); ?>>
	<?php kava_post_thumbnail( 'kava-thumb-m-2' ); ?>
	<div class="grid-item-wrap">
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
			<?php kava_edit_link(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .grid-item-wrap-->
</article><!-- #post-<?php the_ID(); ?> -->

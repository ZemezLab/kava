<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

$has_post_thumbnail = has_post_thumbnail();
$has_post_thumbnail_class = $has_post_thumbnail ? 'invert' : '';
?>

<div class="single-header-5 <?php echo esc_attr( $has_post_thumbnail_class ); ?>">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<header class="entry-header">
					<?php if ( $has_post_thumbnail ) : ?>
						<div class="overlay-thumbnail" <?php kava_post_overlay_thumbnail( 'kava-thumb-xl' ); ?>></div>
					<?php endif; ?>
					<div class="entry-header-top">
						<div class="entry-meta"><?php
							kava_posted_in( array(
								'delimiter' => '',
								'before'    => '<span class="cat-links btn-style">',
								'after'     => '</span>'
							) );
							kava_posted_on( array(
								'prefix'  => __( 'Posted', 'kava' ),
								'before'  => '<span class="posted-on">',
								'after'   => '</span>',
							) );
							kava_post_comments( array(
								'postfix' => __( 'Comment(s)', 'kava' ),
							) );
						?></div>
						<?php if ( kava_theme()->customizer->get_value( 'single_post_author' ) ) : ?>
							<div class="post-author">
								<div class="post-author__content">
									<?php
										global $post;
										$author_id = $post->post_author;
										$author_meta = get_userdata($author_id);
										$author_role = $author_meta->roles;

										kava_posted_by( array(
											'before'  => '<div class="byline">',
											'after'   => '</div>'
										) );
									?>
									<div class="post-author__role"><?php echo wp_kses_post( $author_role[0] ); ?></div>
								</div>
								<div class="post-author__avatar"><?php
									kava_get_post_author_avatar( array(
										'size' => 50
									) );
								?></div>
							</div>
						<?php endif; ?>
					</div>
					<?php the_title( '<h1 class="entry-title h2-style">', '</h1>' ); ?>
					<?php if ( has_excerpt() ) :
						the_excerpt();
					endif; ?>
				</header><!-- .entry-header -->
			</div>
		</div>
	</div>
</div>
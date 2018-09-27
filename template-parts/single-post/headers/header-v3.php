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

<div class="single-header-3 <?php echo esc_attr( $has_post_thumbnail_class ); ?>">
	<?php if ( $has_post_thumbnail ) : ?>
		<div class="overlay-thumbnail" <?php kava_post_overlay_thumbnail( 'kava-thumb-xl' ); ?>></div>
	<?php endif; ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-8">
				<header class="entry-header">
					<?php kava_posted_in( array(
						'delimiter' => '',
						'before'    => '<div class="cat-links btn-style">',
						'after'     => '</div>',
					) ); ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<?php if ( has_excerpt() ) :
						the_excerpt();
					endif; ?>
					<div class="entry-header-bottom">
						<div class="post-author">
							<?php if ( kava_theme()->customizer->get_value( 'single_post_author' ) ) : ?>
								<div class="post-author__avatar"><?php
									kava_get_post_author_avatar( array(
										'size' => 50
									) );
									?></div>
							<?php endif; ?>
							<div class="post-author__content">
								<?php
								kava_posted_by( array(
									'before'  => '<div class="byline">',
									'after'   => '</div>'
								) );
								kava_posted_on( array(
									'prefix'  => __( 'Posted', 'kava' ),
									'before'  => '<div class="posted-on">',
									'after'   => '</div>',
								) );
								?>
							</div>
						</div>
						<?php
							kava_post_comments( array(
								'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>',
								'class'  => 'comments-button'
							) );
						?>
					</div>
				</header><!-- .entry-header -->
			</div>
		</div>
	</div>
</div>
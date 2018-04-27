<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<?php do_action( 'kava-theme/comments/comments-area-before' ); ?>

<div id="comments" class="comments-area"><?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>

		<?php do_action( 'kava-theme/comments/title-before' ); ?>

		<h4 class="comments-title">
			<?php
				printf( // WPCS: XSS OK.
					esc_html( _nx( 'One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'kava' ) ),
					number_format_i18n( get_comments_number() )
				);
			?>
		</h4><!-- .comments-title -->

		<?php do_action( 'kava-theme/comments/comment-list-before' ); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'avatar_size' => 50,
					'short_ping'  => true,
					'callback'    => 'kava_rewrite_comment_item',
				) );
			?>
		</ol><!-- .comment-list -->

		<?php do_action( 'kava-theme/comments/comments-navigation-before' ); ?>

		<?php the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'kava' ); ?></p>
		<?php
		endif;

	endif; // Check for have_comments().

	do_action( 'kava-theme/comments/comment-form-before' );

	comment_form();
?></div><!-- #comments -->

<?php do_action( 'kava-theme/comments/comments-area-after' ); ?>

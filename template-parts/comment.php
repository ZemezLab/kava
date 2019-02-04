<?php do_action( 'kava-theme/comments/comment-before' ); ?>

<?php if ( ! empty( kava_comment_author_avatar() ) ) : ?>
<div class="comment-author vcard">
	<?php echo kava_comment_author_avatar(); ?>
</div>
<?php endif; ?>
<div class="comment-content-wrapper">
	<div class="comment-meta">
		<?php echo kava_get_comment_author_link(); ?>
		<?php echo kava_get_comment_date(); ?>
	</div>
	<div class="comment-content">
		<?php echo kava_get_comment_text(); ?>
	</div>
	<div class="reply">
		<?php echo kava_get_comment_reply_link( array( 'reply_text' => '<i class="fa fa-reply" aria-hidden="true"></i>' . esc_html__( 'reply', 'kava' ) ) ); ?>
	</div>
</div>

<?php do_action( 'kava-theme/comments/comment-after' ); ?>

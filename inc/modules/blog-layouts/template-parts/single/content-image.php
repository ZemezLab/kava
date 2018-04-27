<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

?>

<?php do_action( 'kava_extra_post_format_image', array( 'size' => 'kava-thumb-l' ) ); ?>

<div class="entry-content">
	<?php the_content(); ?>
</div><!-- .entry-content -->
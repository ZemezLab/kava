<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

?>

<footer class="entry-footer">
	<div class="entry-meta"><?php
		kava_post_tags ( array(
			'prefix'    => __( 'Tags:', 'kava' ),
			'delimiter' => ''
		) );
	?></div>
</footer><!-- .entry-footer -->
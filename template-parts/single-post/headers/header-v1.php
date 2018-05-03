<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Kava
 */

?>

<header class="entry-header">
	<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
	<div class="entry-meta">
		<?php
			kava_posted_by();
			kava_posted_in( array(
				'prefix'  => __( 'In', 'kava' ),
			) );
			kava_posted_on( array(
				'prefix'  => __( 'Posted', 'kava' ),
			) );
		?>
	</div><!-- .entry-meta -->
</header><!-- .entry-header -->

<?php kava_post_thumbnail( 'kava-thumb-l', array( 'link' => false ) ); ?>
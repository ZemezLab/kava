<?php
/**
 * The template for displaying related posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Kava
 * @subpackage single-post
 */
?>
<div class="related-post <?php echo esc_attr( $grid_class ); ?>">
	<?php echo $image; ?>
	<div class="entry-meta">
		<?php echo $date; ?>
		<?php echo $author; ?>
	</div>
	<header class="entry-header">
		<?php echo $title; ?>
	</header>
	<div class="entry-content">
		<?php echo $excerpt; ?>
	</div>
</div>

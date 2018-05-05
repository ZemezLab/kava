<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Kava
 */
?>

<?php do_action( 'kava-theme/widget-area/render', 'footer-area' ); ?>

<div <?php kava_footer_class(); ?>>
	<div class="space-between-content"><?php
		kava_footer_copyright();
		kava_social_list( 'footer' );
	?></div>
</div><!-- .container -->

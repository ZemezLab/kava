<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Kava
 */
?>

<?php do_action( 'kava-theme/widget-area/render', 'footer-area' ); ?>

<div class="footer-container">
	<div class="footer-container__wrap container">
		<div class="space-between-content">
			<?php kava_footer_copyright(); ?>
			<?php kava_social_list( 'footer' ); ?>
		</div>
	</div>
</div><!-- .container -->

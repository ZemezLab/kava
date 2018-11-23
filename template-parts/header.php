<?php
/**
 * Template part for default Header layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Kava
 */
?>

<?php get_template_part( 'template-parts/top-panel' ); ?>

<div <?php kava_header_class(); ?>>
	<?php do_action( 'kava-theme/header/before' ); ?>
	<div class="space-between-content">
		<div <?php kava_site_branding_class(); ?>>
			<?php kava_header_logo(); ?>
		</div>
		<?php kava_main_menu(); ?>
	</div>
	<?php do_action( 'kava-theme/header/after' ); ?>
</div>

<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kava
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php kava_body_open(); ?>
<?php do_action( 'kava-theme/site/page-start' ); ?>
<?php kava_get_page_preloader(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'kava' ); ?></a>
	<header id="masthead" <?php echo kava_get_container_classes( 'site-header' ); ?>>
		<?php kava_theme()->do_location( 'header', 'template-parts/header' ); ?>
	</header><!-- #masthead -->
	<?php do_action( 'kava-theme/site/breadcrumbs-area' ); ?>
	<div id="content" <?php echo kava_get_container_classes( 'site-content' ); ?>>

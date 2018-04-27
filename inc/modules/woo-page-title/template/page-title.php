<?php
/**
 * WooCommerce page title template
 */

$title = woocommerce_page_title( false ) ? '<h1 class="woocommerce-products-header__title page-title">' . woocommerce_page_title( false ) . '</h1>' : '';
?>

<header class="woocommerce-products-header container">
	<?php
			echo $title;
			woocommerce_taxonomy_archive_description();
			woocommerce_product_archive_description();
	?>
</header>

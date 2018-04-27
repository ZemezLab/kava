<?php
/**
 * The template for displaying search form.
 *
 * @package Kava
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'kava' ) ?></span>
		<input type="search" class="search-form__field" placeholder="<?php echo esc_attr_x( 'Enter keyword search', 'placeholder', 'kava' ) ?>" value="<?php echo get_search_query() ?>" name="s">
	</label>
	<button type="submit" class="search-form__submit btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>

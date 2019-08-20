<?php
/**
 * Toggle title template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<button class="cx-toggle__header cx-component__button" role="button" aria-expanded="false" data-content-id="#<?php echo esc_attr( $args['id'] ); ?>">
	<h3 class="cx-ui-kit__title cx-toggle__title" aria-grabbed="true" role="banner" ><?php echo wp_kses_post( $args['title'] ); ?></h3>
	<span class="dashicons dashicons-arrow-down hide-icon"></span>
	<span class="dashicons dashicons-arrow-up show-icon"></span>
</button>

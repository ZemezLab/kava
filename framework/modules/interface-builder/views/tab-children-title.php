<?php
/**
 * Tabs title template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<button class="cx-tab__button cx-component__button" role="button" title="<?php echo esc_attr( $args['title'] ); ?>" aria-expanded="false" data-content-id="#<?php echo esc_attr( $args['id'] ); ?>">
	<h3 class="cx-ui-kit__title cx-tab__title" aria-grabbed="true" role="banner" ><?php echo wp_kses_post( $args['title'] ); ?></h3>
</button>

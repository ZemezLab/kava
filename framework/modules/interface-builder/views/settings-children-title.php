<?php
/**
 * Settings title template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<h3 class="cx-ui-kit__title cx-settings__title" role="banner" ><?php echo wp_kses_post( $args['title'] ); ?></h3>

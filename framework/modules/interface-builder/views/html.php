<?php
/**
 * HTML template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cx-ui-kit <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['children'] ) ) { ?>
		<div class="cx-ui-kit__content" role="group" >
			<?php echo $args['children']; ?>
		</div>
	<?php } ?>
</div>

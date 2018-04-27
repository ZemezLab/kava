<?php
/**
 * Form template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<form class="cx-form <?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" accept-charset="<?php echo esc_attr( $args['accept-charset'] ); ?>" action="<?php echo esc_attr( $args['action'] ); ?>" autocomplete="<?php echo esc_attr( $args['autocomplete'] ); ?>" enctype="<?php echo esc_attr( $args['enctype'] ); ?>" method="<?php echo esc_attr( $args['method'] ); ?>" target="<?php echo esc_attr( $args['target'] ); ?>" <?php echo esc_attr( $args['novalidate'] ); ?> >
	<?php
		if ( ! empty( $args['children'] ) ) {
			echo $args['children'];
		}
	?>
</form>

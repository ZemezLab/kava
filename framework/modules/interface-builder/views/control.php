<?php
/**
 * Control template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cx-ui-kit cx-control cx-control-<?php echo esc_attr( $args['type'] ); ?>" data-control-name="<?php echo esc_attr( $args['id'] ); ?>">
	<?php if ( ! empty( $args['title'] ) || ! empty( $args['description'] ) ) { ?>
		<div class="cx-control__info">
			<?php if ( ! empty( $args['title'] ) ) { ?>
				<h4 class="cx-ui-kit__title cx-control__title" role="banner" ><?php echo wp_kses_post( $args['title'] ); ?></h4>
			<?php } ?>
			<?php if ( ! empty( $args['description'] ) ) { ?>
				<div class="cx-ui-kit__description cx-control__description" role="note" ><?php echo wp_kses_post( $args['description'] ); ?></div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if ( ! empty( $args['children'] ) ) { ?>
		<div class="cx-ui-kit__content cx-control__content" role="group" >
			<?php echo $args['children']; ?>
		</div>
	<?php } ?>
</div>

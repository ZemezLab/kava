<?php
/**
 * Verticall tab template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cx-ui-kit cx-component cx-tab cx-tab--vertical <?php echo esc_attr( $args['class'] ); ?>" data-compotent-id="#<?php echo esc_attr( $args['id'] ); ?>">
	<?php if ( ! empty( $args['title'] ) ) { ?>
		<h2 class="cx-ui-kit__title cx-component__title" role="banner" ><?php echo wp_kses_post( $args['title'] ); ?></h2>
	<?php } ?>
	<?php if ( ! empty( $args['description'] ) ) { ?>
		<div class="cx-ui-kit__description cx-component__description" role="note" ><?php echo wp_kses_post( $args['description'] ); ?></div>
	<?php } ?>
	<?php if ( ! empty( $args['children'] ) ) { ?>
		<div class="cx-tab__body" >
			<div class="cx-tab__tabs" role="navigation" >
				<?php echo $args['tabs']; ?>
			</div>
			<div class="cx-ui-kit__content cx-component__content cx-tab__content" role="group" >
				<?php echo $args['children']; ?>
			</div>
		</div>
	<?php } ?>
</div>

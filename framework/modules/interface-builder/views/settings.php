<?php
/**
 * Settings template.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="cx-ui-kit cx-settings <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['title'] ) ) {
		echo $args['title'];
	} ?>
	<?php if ( ! empty( $args['children'] ) || ! empty( $args['description'] ) ) { ?>
		<div class="cx-ui-kit__content cx-settings__content" role="group" id="<?php echo esc_attr( $args['id'] ); ?>"  >
			<?php if ( ! empty( $args['description'] ) ) { ?>
				<div class="cx-ui-kit__description cx-settings__description" role="note" ><?php echo wp_kses_post( $args['description'] ); ?></div>
			<?php } ?>
			<?php if ( ! empty( $args['children'] ) ) { ?>
				<?php echo $args['children']; ?>
			<?php } ?>
		</div>
	<?php } ?>
</div>

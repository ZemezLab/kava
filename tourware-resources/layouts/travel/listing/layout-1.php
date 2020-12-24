<?php
/**
 * Name: tourware Layout 1
 * goto
 */
?>

<div class="ht-grid-item">
    <div class="tour-item">
        <?php /*HEAD*/ ?>
        <div class="tour-head">
            <?php if ($settings['show_price'] && isset($price)) { ?>
                <span class="price"><?php echo $settings['price_prefix'].number_format($price, 0, ',', '.').$settings['price_suffix']; ?></span>
            <?php } ?>
            <a href="<?php the_permalink(); ?>">
                <img <?php if ($settings['layout'] !== 'carousel') { ?>
                    class="lazyload"
                <?php } else { ?>
                    class="tns-lazy tns-lazy-img"
                <?php } ?>
                        data-src="<?php echo $img_src ?>"
                        alt="<?php esc_html_e($title) ?>">
            </a>
            <?php echo $badge_html ?>
        </div>
        <?php /*CONTENT*/ ?>
        <div class="tour-content">
            <?php echo $title_html ?>
            <?php if ($settings['show_categories'] && isset($categories_str)) { ?>
                <div class="tour-categories"><?php esc_html_e($categories_str); ?></div>
            <?php } ?>
            <?php if ($days || $persons || $destination) { ?>
            <div class="tour-attributes">
                <?php if ($days): ?>
                    <span class="time tour-attribute">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['icon_duration'] ); ?>
                        <strong><?php
                            echo $settings['duration_prefix'];
                            printf(
                                _nx(
                                    '1 Tag',
                                    '%1$s Tage',
                                    $days,
                                    'day of the week',
                                    'tyto'
                                ),
                                $days
                            );
                            ?>
					</strong>
				</span>
                <?php endif; ?>
                <?php if ($persons): ?>
                    <span class="time tour-attribute">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['icon_persons'] ); ?>
                    <strong><?php esc_html_e($persons.$settings['persons_suffix']); ?></strong>
				</span>
                <?php endif; ?>
                <?php if ($destination):  ?>
                    <span class="time tour-attribute">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['icon_destination'] ); ?>
                        <strong><?php esc_html_e($destination); ?></strong>
				</span>
                <?php endif; ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
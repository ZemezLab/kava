<?php
/**
 * Name: tourware Layout 4
 * clean
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
            <?php if (isset($badge) && !empty($badge)) { ?>
                <span class="tour-label"><?php esc_html_e($badge) ?></span>
            <?php } ?>
        </div>
        <?php /*CONTENT*/ ?>
        <div class="tour-content">
            <h3 class="title entry-title">
                <a href="<?php the_permalink(); ?>"><?php esc_html_e($title); ?></a>
            </h3>
            <?php if ($settings['show_categories'] && isset($categories_str)) { ?>
                <div class="tour-categories"><?php esc_html_e($categories_str); ?></div>
            <?php } ?>
            <div class="tour-attributes">
                <?php if (isset($stars) && $stars > 2 && 0): ?>
                    <span class="aver">
                <?php for ($i = 0; $i < $stars; $i++) { ?>
                    <span class="fa fa-star tour-attribute"></span>
                <?php } ?>
				</span>
                <?php endif; ?>
                <?php if ($settings['show_duration'] && isset($days)): ?>
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
                <?php if ($settings['show_persons'] && isset($persons)): ?>
                    <span class="time tour-attribute">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['icon_persons'] ); ?>
                    <strong><?php esc_html_e($persons.$settings['persons_suffix']); ?></strong>
				</span>
                <?php endif; ?>
                <?php if ($settings['show_destination'] && isset($destination)):  ?>
                    <span class="time tour-attribute">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['icon_destination'] ); ?>
                        <strong><?php esc_html_e($destination); ?></strong>
				</span>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
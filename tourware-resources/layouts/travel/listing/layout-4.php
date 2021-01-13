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
            <a href="<?php the_permalink(); ?>" class="tour-image">
                <img <?php if ($settings['layout'] !== 'carousel') { ?>
                    class="lazyload"
                <?php } else { ?>
                    class="tns-lazy tns-lazy-img"
                <?php } ?>
                    data-src="<?php echo $img_src ?>"
                    alt="<?php esc_html_e($title) ?>">
            </a>
        </div>
        <?php /*CONTENT*/ ?>
        <div class="tour-content">
            <?php echo $title_html ?>
            <?php if ($settings['show_duration'] && $days): ?>
                <span class="duration">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['style_duration_icon'] ); ?>
					<span><?php
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
					</span>
				</span>
            <?php endif; ?>
            <?php if ($settings['show_price'] && $price): ?>
                <span class="price"><?php
                    if ($price) {
                        esc_html_e($settings['price_prefix']);
                        echo '<span>' . number_format($price, 0, ',', '.') . '</span>';
                        esc_html_e($settings['price_suffix']);
                    } ?>
				</span>
            <?php endif; ?>
        </div>
    </div>
</div>
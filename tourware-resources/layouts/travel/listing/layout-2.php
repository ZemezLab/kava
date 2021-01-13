<?php
/**
 * Name: tourware Layout 2
 * colibri
 */
?>
<div class="ht-grid-item">
    <div class="tour-item">
        <?php /*HEAD*/ ?>
        <div class="tour-head">
            <?php echo $badge_html ?>
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
            <?php if ($destination) { ?>
            <div class="destination">
                <?php \Elementor\Icons_Manager::render_icon( $settings['style_destination_icon'] ); ?>
                <?php echo $destination; ?>
            </div>
            <?php } ?>
            <?php echo $title_html ?>
            <?php echo $excerpt_html ?>
            <div class="block">
                <div class="block-left">
                    <?php if ($days) { ?>
                    <div class="tour-attribute duration">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['style_duration_icon'] ); ?>
                        <?php echo $settings['duration_prefix'];
                        printf(
                            _nx(
                                '1 Tag',
                                '%1$s Tage',
                                $days,
                                'day of the week',
                                'tyto'
                            ),
                            $days
                        ); ?>
                    </div>
                    <?php } ?>
                    <?php if ($persons): ?>
                        <div class="tour-attribute persons">
				            <?php \Elementor\Icons_Manager::render_icon( $settings['style_persons_icon'] ); ?>
                            <span><?php esc_html_e($persons.$settings['persons_suffix']); ?></span>
				        </div>
                    <?php endif; ?>
                    <?php echo $price_html; ?>
                </div>
                <div class="block-right">
                    <?php echo $read_more_html; ?>
                </div>
            </div>
            <?php if ($categories_str) { ?>
                <div class="categories">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['style_categories_icon'] ); ?>
                    <?php esc_html_e($categories_str); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
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
            <?php echo $price_html; ?>
            <a href="<?php the_permalink(); ?>" class="tour-image">
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
            <?php if ($categories_str) { ?>
                <div class="categories">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['style_categories_icon'] ); ?>
                    <?php esc_html_e($categories_str); ?>
                </div>
            <?php } ?>
            <?php echo $excerpt_html; ?>
            <?php echo $read_more_html; ?>
            <?php if ($days || $persons || $destination) { ?>
            <div class="tour-attributes">
                <?php if ($days): ?>
                    <span class="tour-attribute duration">
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
                <?php if ($persons): ?>
                <span class="tour-attribute persons">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['style_persons_icon'] ); ?>
                    <span><?php esc_html_e($persons.$settings['persons_suffix']); ?></span>
				</span>
                <?php endif; ?>
                <?php if ($destination):  ?>
                <span class="tour-attribute destination">
				    <?php \Elementor\Icons_Manager::render_icon( $settings['style_destination_icon'] ); ?>
                        <span><?php esc_html_e($destination); ?></span>
				</span>
                <?php endif; ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
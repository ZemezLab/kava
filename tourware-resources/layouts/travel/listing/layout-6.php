<?php
/**
 * Name: tourware Layout 6
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
            <?php if ($categories) { ?>
                <div class="categories">
                    <?php foreach ($categories as $category) { ?>
                        <span class="category"><?php esc_html_e($category); ?></span>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="push"></div>
            <?php if (!empty($scores)) { ?>
            <div class="scores">
            <?php foreach ($scores as $score) { ?>
                <div class="score">
                    <?php \Elementor\Icons_Manager::render_icon( $score['icon'] ); ?>
                    <div class="text"><?php esc_html_e($score['tag']); ?></div>
                </div>
            <?php } ?>
            </div>
            <?php } ?>

            <div class="footer">
                <div class="left">
                    <?php echo $price_html; ?>
                    <?php echo $flight_html; ?>
                </div>
                <div class="right">
                    <?php echo $read_more_html; ?>
                </div>
            </div>

        </div>
    </div>
</div>
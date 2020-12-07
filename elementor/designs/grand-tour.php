<div class="ht-grid-item">
    <div class="tour-item-wrapper">
    <div class="tour-item">
        <div class="tour-head">
            <a class="tour-image" href="<?php the_permalink() ?>">
                <div class="image-holder">
                    <img <?php if ($settings['layout'] !== 'carousel') { ?>
                        class="lazyload"
                    <?php } else { ?>
                        class="tns-lazy tns-lazy-img"
                    <?php } ?>
                        data-src="<?php echo $img_src ?>"
                        alt="<?php esc_html_e($title) ?>">
                    <?php if ($badge) { ?>
                        <div class="tour-label"><?php echo $badge ?></div>
                    <?php } ?>
                </div>
                <?php if ($settings['show_price'] && $price) { ?>
                    <div class="price">
                        <?php echo $settings['price_prefix'].number_format($price, 0, ',', '.').$settings['price_suffix'] ?>
                    </div>
                <?php } ?>

            </a>
        </div>
        <div class="tour-content">
            <h4 class="tour-title"><a class="tour-link" href="<?php the_permalink() ?>">
                    <?php echo $title; ?></a></h4>
            <?php if ($settings['show_excerpt'] && $excerpt) { ?>
            <div class="tour-excerpt">
                <?php esc_html_e($excerpt); ?>
            </div>
            <?php } ?>
            <?php if (($settings['show_duration'] && $days) || ($settings['show_categories'] && $categories_str) || $persons) { ?>
            <div class="tour-attributes">
                <?php if ($settings['show_duration'] && $days) { ?>
                    <div class="tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_duration'] ); ?>
                        <?php
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
                    </div>
                <?php } ?>
                <?php if ($settings['show_persons'] && $persons) { ?>
                    <div class="tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_persons'] ); ?>
                        <?php echo $persons ?>
                    </div>
                <?php } ?>
                <?php if ($settings['show_destination'] && $destination) { ?>
                    <div class="tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_destination'] ); ?>
                        <?php echo $destination ?>
                    </div>
                    <br>
                <?php } ?>
                <?php if ($settings['show_categories'] && $categories_str) { ?>
                    <div class="tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_categories'] ); ?>
                        <span class="tour-info-label">
					    <?php echo $categories_str ?></span>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
</div>

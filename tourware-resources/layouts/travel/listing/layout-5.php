<?php
/**
 * Name: tourware Layout 5
 * voyage
 */
?>
<div class="ht-grid-item">
    <div class="tour-item">
        <div class="tour-head item-image-holder">
            <a href="<?php the_permalink() ?>">
                <img <?php if ($settings['layout'] !== 'carousel') { ?>
                    class="lazyload"
                    <?php } else { ?>
                    class="tns-lazy tns-lazy-img"
                    <?php } ?>
                    data-src="<?php echo $img_src ?>"
                    alt="<?php esc_html_e($title) ?>">
                <?php echo $badge_html ?>
            </a>
        </div>

        <div class="tour-content item-content-holder">
            <div class="item-title-price-holder">
                <div class="tour-title">
                    <?php echo $title_html ?>
                    <?php if ($settings['show_price'] && $price) { ?>
                        <span class="item-price-holder">
                        <span class="price-holder">
							<span class="item-price price"><?php echo $settings['price_prefix'].number_format($price, 0, ',', '.').'<br>'.$settings['price_suffix'] ?></span>
						</span>
					</span>
                    <?php } ?>
                </div>
            </div>
            <div class="item-excerpt">
                <?php if ($settings['show_excerpt'] && $excerpt) esc_html_e($excerpt); ?>
            </div>
            <?php if ($days || $persons || $destination || $categories_str) { ?>
            <div class="item-bottom-content tour-attributes">
                <?php if ($days) { ?>
                <div class="item-bottom-item tour-attribute">
                    <?php \Elementor\Icons_Manager::render_icon( $settings['icon_duration'] ); ?>
                    <span class="tour-info-label">
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
                        ?></span>
                </div>
                <?php } ?>
                <?php if ($persons) { ?>
                    <div class="item-bottom-item tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_persons'] ); ?>
                        <span class="tour-info-label">
					    <?php echo $persons.$settings['persons_suffix'] ?></span>
                    </div>
                <?php } ?>
                <?php if ($destination) { ?>
                    <div class="item-bottom-item tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_destination'] ); ?>
                        <span class="tour-info-label">
					    <?php echo $destination ?></span>
                    </div>
                    <br>
                <?php } ?>
                <?php if ($categories_str) { ?>
                    <div class="item-bottom-item tour-attribute">
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
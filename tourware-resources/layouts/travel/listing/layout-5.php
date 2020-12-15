<?php
/**
 * Name: tourware Layout 5
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
                <?php if ($badge) { ?>
		            <span class="tour-label"><?php echo $badge ?></span>
                <?php } ?>
            </a>
        </div>

        <div class="tour-content item-content-holder">
            <div class="item-title-price-holder">
                <h5 class="tour-title">
                    <a href="<?php the_permalink() ?>"><?php esc_html_e($title) ?></a>
                    <?php if ($settings['show_price'] && $price) { ?>
                        <span class="item-price-holder">
                        <span class="price-holder">
							<span class="item-price price"><?php echo $settings['price_prefix'].number_format($price, 0, ',', '.').'<br>'.$settings['price_suffix'] ?></span>
						</span>
					</span>
                    <?php } ?>
                </h5>
            </div>
            <div class="item-excerpt">
                <?php if ($settings['show_excerpt'] && $excerpt) esc_html_e($excerpt); ?>
            </div>
            <?php if (($settings['show_duration'] && $days)
                || ($settings['show_persons'] && $persons)
                || ($settings['show_destination'] && $destination)
                || ($settings['show_categories'] && $categories_str)) { ?>
            <div class="item-bottom-content tour-attributes">
                <?php if ($settings['show_duration'] && $days) { ?>
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
                <?php if ($settings['show_persons'] && $persons) { ?>
                    <div class="item-bottom-item tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_persons'] ); ?>
                        <span class="tour-info-label">
					    <?php echo $persons.$settings['persons_suffix'] ?></span>
                    </div>
                <?php } ?>
                <?php if ($settings['show_destination'] && $destination) { ?>
                    <div class="item-bottom-item tour-attribute">
                        <?php \Elementor\Icons_Manager::render_icon( $settings['icon_destination'] ); ?>
                        <span class="tour-info-label">
					    <?php echo $destination ?></span>
                    </div>
                    <br>
                <?php } ?>
                <?php if ($settings['show_categories'] && $categories_str) { ?>
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
<div class="ht-grid-item">
    <div class="tour-item">
        <?php /*HEAD*/ ?>
        <div class="tour-head">
            <a href="<?php the_permalink(); ?>">
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
            <h3 class="title entry-title">
                <a href="<?php the_permalink(); ?>"><?php echo $title; ?></a>
            </h3>
            <?php if ($stars > 2): ?>
                <span class="aver">
                <?php for ($i = 0; $i < $stars; $i++) { ?>
                    <span class="fa fa-star"></span>
                <?php } ?>
			</span>
            <?php endif; ?>
            <?php if ($settings['show_duration'] && $days): ?>
                <span class="time">
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
            <?php if ($settings['show_price'] && $price): ?>
                <span class="price"><?php
                    if ($price) {
                        esc_html_e($settings['price_prefix']);
                        echo '<strong>' . number_format($price, 0, ',', '.') . '</strong>';
                        esc_html_e($settings['price_suffix']);
                    } ?>
				</span>
            <?php endif; ?>
        </div>
    </div>
</div>
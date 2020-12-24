<?php
/**
 * Name: tourware Layout 2
 * colibri
 */
?>

<?php if ($item_data->tags) {
    foreach ($item_data->tags as $tag) {
        if ($tag->name == 'mit Flug')
            $flight = true;
        else if ($tag->name == 'Gruppenreise')
            $group = true;
        else if ($tag->name == 'Privatreise')
            $privat = true;
    }
} ?>
<div class="ht-grid-item">
    <div class="tour-item">
        <?php /*HEAD*/ ?>
        <div class="tour-head">
            <?php if ($settings['show_categories'] && $categories) { ?>
                <div class="item-categories">
                    <?php foreach ($categories as $category) { ?>
                    <span class="tour-cat-label">
					    <?php echo $category ?></span>
                    <?php } ?>
                </div>
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
        </div>
        <?php /*CONTENT*/ ?>
        <div class="tour-content">
            <div class="item-destination">
                <?php echo $settings['show_destination'] && $destination ? $destination : "&nbsp;" ?>
            </div>
            <?php echo $title_html ?>
            <div class="item-excerpt">
                <?php if ($settings['show_excerpt'] && $excerpt) echo $excerpt; ?>
            </div>
            <div class="block">
                <div class="item-price">
                    <?php if ($days) { ?>
                    <div class="duration">
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
                    <?php if ($price) { ?>
                    <div class="price">
                        <?php echo $settings['price_prefix'].number_format($price, 0, ',', '.').$settings['price_suffix'] ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="item-button">
                    <a class="button" href="<?php the_permalink()?>">
                        Ansehen & Buchen
                    </a>
                </div>
            </div>
            <?php if ($flight || $group || $privat) { ?>
            <div class="additional">
                <?php if ($flight) { ?>
                <div class="item-attribute item-flight">
                    <i class="fas fa-plane"></i>&nbsp;mit Flug
                </div>
                <?php } ?>
                <?php if ($group || $privat) { ?>
                <div class="item-attribute item-type">
                <?php if ($group) { ?>
                    <i class="fas fa-users"></i>&nbsp;Gruppenreise
                <?php }
                    if ($privat) { ?>
                    <i class="fas fa-user"></i>&nbsp;Privatreise
                <?php } ?>
                </div>
                <?php }?>
            </div>
            <?php }?>
        </div>
    </div>
</div>
<div class="ht-grid-item continent-<?php echo $continent ?> <?php if ($settings['destinations_layout'] == 'masonry' && $i%($settings['col']+1) == 0) echo 'w2'?>">
    <div class="wdd-item">
        <a class="wdd-head"
           href="<?php echo get_the_permalink($destination->ID); ?>"></a>
        <img class="wdd-img lazyload"
             data-src="<?php echo esc_attr($img_src); ?>"
             alt="<?php echo esc_attr($img_alt); ?>">
        <div class="wdd-cont">
            <h4 class="wddc-name entry-title"
                itemprop="headline"><?php echo $destination->post_title; ?></h4>
            <?php if ($destination->post_excerpt) { ?>
                <div class="wddc-desc"
                     itemprop="text"><?php echo $destination->post_excerpt; ?></div>
            <?php } ?>
        </div>
    </div>
</div>
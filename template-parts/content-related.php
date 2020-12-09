<?php
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
$theme_mod_prefix = get_query_var('theme_mod_prefix');
$position = get_query_var('related_position');
$grid_classes = $position == '' ? 'ht-grid-1' : 'ht-grid-3 ht-grid-mobile-1';
$group_related_by_type = get_theme_mod($theme_mod_prefix . '_group_related'.$position, false);
?>
<div class="related-items">
    <?php
    if ($group_related_by_type) {
        $types = [];
        $_types = ['travels', 'accommodations', 'travelsbricks'];
        foreach ($_types as $type) {
            $order = get_theme_mod($theme_mod_prefix . '_related_order_'.$type.$position);
            if ($order > 0) $types[$order] = $type;
        }
        ksort($types);
        foreach ($types as $type) {
            $rel_posts = tyto_get_related_posts($record, ['tyto' . $type]);
            if ($rel_posts->post_count) {
                if (!empty($related_title = get_theme_mod($theme_mod_prefix . '_related_'.$type.'_title'.$position))) {
                    echo '<h2>' . $related_title . '</h2>';
                } ?>
                <div class="ht-grid <?php echo $grid_classes ?>">
                    <?php
                    while ($rel_posts->have_posts()):
                        $rel_posts->the_post();
                        get_template_part('template-parts/content', 'related_item');
                    endwhile; ?>
                </div>
            <?php }
        }
    } else {
        $rel_posts = tyto_get_related_posts($record);
        if ($rel_posts->post_count) {
            if (!empty($related_title = get_theme_mod($theme_mod_prefix . '_related_title'.$position, 'Passend dazu:'))) {
                echo '<h2>' . $related_title . '</h2>';
            } ?>
            <div class="ht-grid <?php echo $grid_classes ?>">
                <?php
                while ($rel_posts->have_posts()):
                    $rel_posts->the_post();
                    get_template_part('template-parts/content', 'related_item');
                endwhile; ?>
            </div>
        <?php }
    }
    wp_reset_postdata(); ?>
</div>


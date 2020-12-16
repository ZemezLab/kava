<?php use Tourware\Elementor\Loader;
$parts = explode('##', $settings['template']);
?>
<div class="advanced-tyto-list <?php echo esc_attr( $parts[2] );  ?>" <?php if ($settings['adv_list_id']) echo 'id="'.$settings['adv_list_id'].'"'?>>
    <div class="<?php echo $classes ?>">
        <div class="tours-content <?php echo esc_attr( $layout_name ); echo $this->get_id();?> "
             id="<?php echo esc_attr( $tiny_slider_id ); ?>" <?php echo wp_kses_post( $tiny_slider_data ); ?>>
            <?php
            if ($query->found_posts == 0 && $settings['advanced_search'] == 'yes' && $settings['search_not_found']) {
                echo '<h4 style="margin: 20px auto;">'.$settings['search_not_found'].'</h4>';
            } else {
                while ( $query->have_posts() ):
                    $query->the_post();

                    // @todo: check for item type
                    $record = $repository->findOneByPostId(get_the_ID());
                    Loader::renderListItem($record, $settings);
                endwhile;
                wp_reset_postdata();
            } ?>
        </div>
    </div>
    <?php if ($settings['pagi'] == 'infinity_scroll') { ?>
        <div class="loader">
            <div class="lds-ring" style="display: none"><div></div><div></div><div></div><div></div></div>
        </div>
    <?php } ?>
    <?php
    if ( 'none' !== $settings['pagi'] && 'grid' == $settings['layout'] ) {
        $this->renderPagination($query, $settings);
    }

    if ($settings['pagi'] == 'infinity_scroll') {
        wp_enqueue_script('adv-list-infinity-scroll', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/listing/infinity-scroll.js', ['jquery', 'throttle-debounce']);
    }

    wp_reset_postdata(); ?>
</div>
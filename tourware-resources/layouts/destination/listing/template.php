<?php
use Tourware\Path;
?>
<div class="continents-w-destinations<?php echo $main_class ?>" id="<?php echo $widget_id ?>">
    <div class="continents-destinations-container" <?php if ($settings['list_inline'] == 'inline-block') echo 'style="display:block"' ?>>
        <?php if ($settings['show_continents']) {
            include Path::getResourcesFolder() . 'layouts/destination/listing/continents.php';
        } ?>
        <div class="wd-dest-layout-grid dest-layout-<?php echo $settings['destinations_layout']?> destinations-list">
            <div class="dest-content <?php echo $dest_content_class ?>" id="<?php echo $slider_id ?>"
                 style="width: 100%">
                <?php if ($settings['destinations_layout'] == 'masonry') { ?>
                    <div class="grid-sizer"></div>
                <?php }
                $i = 0;
                foreach ($continents_destinations as $continent => $destinations) {
                    foreach ($destinations as $destination) {
                        $img_id = get_post_thumbnail_id($destination->ID);
                        $img_alt = get_the_title($destination->ID);
                        $img_src = !empty($img_id) ? get_the_post_thumbnail_url($destination->ID, 'medium_large') : get_post_meta($destination->ID, 'header_image', true);
                        if (empty($img_src)) $img_src = 'https://via.placeholder.com/300x400';
                        $i++;
                        include Path::getLayoutPath($settings['template']);
                    }
                } ?>
                <?php ?>
            </div>
        </div>
    </div>
</div>
<?php if ($settings['destinations_layout'] == 'masonry') { ?>
    <style>
        #<?php echo $slider_id ?> .grid-sizer { width: <?php echo 100/$settings['col']?>%; }
        #<?php echo $slider_id ?> .ht-grid-item { width: <?php echo 100/$settings['col']?>%; }
        #<?php echo $slider_id ?> .ht-grid-item.w2 { width: <?php echo 100/$settings['col'] *2 ?>%; }
    </style>
<?php } ?>
<script>
    jQuery(document).ready(function($) {
        <?php if ($settings['destinations_layout'] !== 'carousel') { ?>
        var $grid = $('#<?php echo $slider_id ?>').isotope({
            itemSelector: '.ht-grid-item',
            <?php if ($settings['destinations_layout'] == 'masonry') { ?>
            percentPosition: true,
            masonry: {
                columnWidth: '.grid-sizer'
            }
            <?php } ?>
        });
        <?php } ?>

        <?php if ($settings['destinations_layout'] !== 'carousel' && $settings['rows']) { ?>
        var item_height = $('#<?php echo $widget_id?>').find('.destinations-list').find('.ht-grid-item').first().outerHeight(true);
        var destinations_height = $('#<?php echo $slider_id?>').outerHeight(true);
        var destinations_container_height = item_height*<?php echo $settings['rows']?>;
        $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': destinations_container_height, 'overflow': 'hidden'});
        if (destinations_height > destinations_container_height) {
            $('#<?php echo $widget_id?>>').append('<div class="show-more"><a href="#" data-rows="<?php echo $settings['rows']?>">Weitere anzeigen</a></div>')
        }
        $('#<?php echo $widget_id?>').find('.show-more').find('a').click(function(e) {
            e.preventDefault();
            var showed_rows = $(this).data('rows');
            var settings_rows = <?php echo $settings['rows'] ?>;
            destinations_container_height = item_height*(showed_rows+settings_rows);
            if (destinations_height > destinations_container_height) {
                $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': destinations_container_height});
                $(this).data('rows', showed_rows+settings_rows);
            } else {
                $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': 'auto'});
                $('#<?php echo $widget_id?>>').find('.show-more').hide();
            }
        });
        <?php } ?>

        $('#<?php echo $filter_id ?> a').click(function (e) {
            e.preventDefault();
            $('#<?php echo $filter_id ?> a').removeClass('is-active');
            $(this).addClass('is-active');
            var continent = $(this).data('continent');

            <?php if ($settings['destinations_layout'] == 'carousel') { ?>
            $('#<?php echo $slider_id ?>').slick('slickUnfilter');
            if (continent) $('#<?php echo $slider_id ?>').slick('slickFilter', '.ht-grid-item.continent-' + continent);
            <?php } else { ?>
            if (continent) {
                $grid.isotope({ filter: '.ht-grid-item.continent-' + continent });
                $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': 'auto'});
                $('#<?php echo $widget_id?>').find('.show-more').hide();
                $('#<?php echo $widget_id?>').find('.show-more').find('a').data('rows', <?php echo $settings['rows'] ?>);
            } else {
                $grid.isotope({ filter: '*' });
                <?php if ($settings['rows']) { ?>
                $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': item_height*<?php echo $settings['rows']?>, 'overflow': 'hidden'});
                $('#<?php echo $widget_id?>').find('.show-more').show();
                <?php } ?>
            }
            <?php } ?>
        });
    });
</script>
<?php if ($settings['destinations_layout'] == 'carousel') { ?>
    <style>#<?php echo $slider_id ?> { display: none }</style>
    <script>
        jQuery(document).ready(function ($) {
            $('#<?php echo $slider_id ?>').slick({
                accessibility: false,
                <?php if ($settings['autoplay']) { ?>
                infinite: <?php echo $settings['autoplay'] ? 'true' : 'false' ?>,
                speed: <?php echo $settings['speed']['size']?>,
                autoplay: <?php echo $settings['autoplay'] ? 'true' : 'false' ?>,
                <?php } ?>
                slidesToShow: <?php echo $settings['col']?>,
                slidesToScroll: <?php echo $settings['col']?>,
                arrows: <?php echo $settings['arrows'] == true ? 'true' : 'false' ?>,
                dots: <?php echo $settings['dots'] == true ? 'true' : 'false' ?>,
                easing: 'linear',
                responsive: [
                    {
                        breakpoint: 980,
                        settings: {
                            slidesToShow: <?php echo $settings['col_tablet']?>,
                            slidesToScroll: <?php echo $settings['col_tablet']?>,
                            arrows: <?php echo $settings['hide_arrows_tablet'] == true ? 'false' : 'true' ?>,
                            dots: <?php echo $settings['hide_dots_tablet'] == true ? 'false' : 'true' ?>,
                        },
                    },
                    {
                        breakpoint: 570,
                        settings: {
                            slidesToShow: <?php echo $settings['col_mobile']?>,
                            slidesToScroll: <?php echo $settings['col_mobile']?>,
                            arrows: <?php echo $settings['hide_arrows_mobile'] == true ? 'false' : 'true' ?>,
                            dots: <?php echo $settings['hide_dots_mobile'] == true ? 'false' : 'true' ?>
                        },
                    },
                ],
            });
            $('#<?php echo $slider_id ?>').fadeIn();

            $('#<?php echo $filter_id ?> a').click(function (e) {
                e.preventDefault();
                $('#<?php echo $filter_id ?> a').removeClass('is-active');
                $(this).addClass('is-active');
                var continent = jQuery(this).data('continent');
                $('#<?php echo $slider_id ?>').slick('slickUnfilter');
                if (continent) $('#<?php echo $slider_id ?>').slick('slickFilter', '.ht-grid-item.continent-' + continent);

            });
        });
    </script>
    <?php
}


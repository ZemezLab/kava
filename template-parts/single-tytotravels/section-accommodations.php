<?php
wp_enqueue_script('collapser-script');
wp_enqueue_style('tiny-slider');
wp_enqueue_script('tiny-slider-js');

wp_enqueue_style('fancybox');
wp_enqueue_script('jquery-fancybox');

$accommodations = get_query_var('itinerary_accommodations');
$accommodation_excerpt_lines = get_theme_mod('tour_accommodation_excerpt_lines', 3);
$gallery_images_number = get_theme_mod('tour_accommodation_gallery_images', 2);
$accommodations_title = get_theme_mod('tour_accommodations_title', 'Unterkuenfte');
//print_r($accommodations);
?>
<div id="tour-link-accommodations" class="tour-section">
    <h2 class="tour-section-title"><?php esc_html_e($accommodations_title, 'tyto'); ?></h2>
    <div class="tour-accommodations">
        <?php foreach ($accommodations as $i => $accommodation) {
            if (!empty($accommodation['data'])) { ?>
                <div class="tour-acc-item">
                    <h3 class="tour-acc-head">
                        <a href="<?php echo get_permalink($accommodation['data']['post_id']) ?>">
                            <strong><?php echo $accommodation['data']['tytorawdata']->title ?></strong></a>
                    </h3>
                    <div class="tour-acc-info">
                        <?php if ($accommodation['data']['meals']) { ?>
                            <span class="meal"><strong>Verpflegung:</strong> <?php echo join(' / ', $accommodation['data']['meals']); ?></span>
                            <br>
                        <?php } ?>
                        <?php if ($accommodation['nights']) { ?>
                            <span class="nights"><strong>Aufenhalt:</strong>
                                                <?php printf(
                                                    _nx(
                                                        '1 Nacht',
                                                        '%1$s Nächte',
                                                        $accommodation['nights'],
                                                        'nights',
                                                        'tyto'
                                                    ),
                                                    $accommodation['nights']
                                                ); ?></span><br>
                        <?php } ?>
                    </div>
                    <div class="tour-acc-content show">
                        <div class="tour-acc-text">
                            <?php echo $accommodation['data']['tytorawdata']->description; ?>
                        </div>
                    </div>
                    <?php if (count($accommodation['data']['tytorawdata']->images)) { ?>
                        <div class="tour-acc-gallery-wrapper">
                            <div class="tour-acc-gallery" id="tour-acc-gallery-<?php echo $i ?>">
                                <?php foreach ($accommodation['data']['tytorawdata']->images as $img) {
                                    $gallery_thumbnails_option = array(
                                        "secure" => true,
                                        "width" => 900 / $gallery_images_number,
                                        "crop" => "fill",
                                        "gravity" => "center"
                                    );

                                    $gallery_original_option = array(
                                        "secure" => true,
                                        'width' => 1400
                                    );

                                    if ('http' === substr($img->image, 0, 4)) {
                                        $gallery_thumbnails_option['type'] = 'fetch';
                                        $gallery_original_option['type'] = 'fetch';
                                    }
                                    $gallery_img_thumbnail = \Cloudinary::cloudinary_url($img->image, $gallery_thumbnails_option);
                                    $gallery_img_original = \Cloudinary::cloudinary_url($img->image, $gallery_original_option); ?>
                                    <a href="<?php echo esc_url($gallery_img_original); ?>" class="slide-item"
                                       data-fancybox="tour-acc-gallery-<?php echo $i; ?>"
                                       data-options='{"backFocus" : false}'>
                                        <img src="<?php echo esc_url($gallery_img_thumbnail); ?>"
                                             data-original-src="<?php echo esc_url($gallery_img_original); ?>"
                                             class="z" alt="">
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        $.each($('.tour-accommodations .tour-acc-content'), function () {
            $(this).collapser({
                mode: 'lines',
                truncate: <?php echo intval($accommodation_excerpt_lines); ?>,
                ellipsis: '...',
                speed: 300,
                showClass: 'open',
                hideClass: 'collapsed',
                showText: '<?php echo get_theme_mod('tour_accommodations_show_more_text', 'Mehr erfahren >>')?>',
                hideText: '<?php echo get_theme_mod('tour_accommodations_hide_text', '')?>'
            });
        });
        $.each($('.tour-acc-gallery'), function () {
            tns({
                container: this,
                items: <?php echo $gallery_images_number ?>,
                slideBy: 'page',
                autoplay: false,
                mouseDrag: true,
                autoHeight: true,
                loop: false,
                responsive: {
                    240: {
                        items: 1
                    },
                    768: {
                        items: 2,
                        controls: true,
                        nav: false
                    },
                    992: {
                        items: <?php echo $gallery_images_number ?>,
                        controls: true
                    }
                }
            });
        });
    })
</script>
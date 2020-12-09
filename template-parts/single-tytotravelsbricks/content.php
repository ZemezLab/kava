<?php
// @codingStandardsIgnoreStart
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));

set_query_var('theme_mod_prefix', 'brick');

$style = get_query_var('style');
$additionalFieldsLabels = get_query_var('additional_fields_labels');

/* price */
$price = 0;
if ($record->additionalFields->preisbeispiel) {
//    preg_match('/\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})?/', $record->additionalFields->preisbeispiel, $match);
    $price = $record->additionalFields->preisbeispiel;
}
if ($price) {
    $price_prefix = '';
    if (isset($record->additionalFields->priceprefix) && !empty($record->additionalFields->priceprefix))
        $price_prefix = $record->additionalFields->priceprefix;
    if (empty($record->additionalFields->priceprefix))
        $price_prefix = get_theme_mod('brick_price_prefix', 'ab:');

    $price_suffix = '';
    if (isset($record->additionalFields->pricesuffix) && !empty($record->additionalFields->pricesuffix))
        $price_suffix = $record->additionalFields->pricesuffix;
    if (empty($record->additionalFields->pricesuffix))
        $price_suffix = get_theme_mod('brick_price_suffix', '/ pro Person');
}

/*destination*/
$destination = null;
if ($record->_destination) {
    $destination = $record->_destination;
} else {
    $tyto_countries = get_post_meta(get_the_ID(), 'tytocountries', true);
    $countries = [];
    if (!empty($tyto_countries)) {
        foreach ($tyto_countries as $tyto_country) {
            $countries[] = $tyto_country['official_name_de'];
        }
        $destination = implode(', ', $countries);
    }
}

$images_count = tyto_get_gallery_images_count($record);
if( $images_count ) {
    $slider_items_count = $images_count < 3 ? $images_count : 3;
    $gallery_img_array = tyto_get_gallery_images($record, ceil(2000/$slider_items_count), 1400);
}

/* show sidebar */
$show_sidebar = false;
if (get_theme_mod('tour_related_show_sidebar', true) && tyto_get_related_posts($record) !== false
    || !empty($record->lat) && !empty($record->lng)) $show_sidebar = true;

wp_register_script('dummy-handle-footer', '', [], '', true);
wp_enqueue_script('dummy-handle-footer');

?>
<?php /*MAIN CONTAINER*/ ?>
    <div id="has-sidebar-sticky">
        <?php /*LEFT CONTENT*/ ?>
        <div class="tour-left-content" <?php if (!$show_sidebar) echo 'style="width:100%; margin-right: 0;' ?>>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php /*GALLERY CAROUSEL*/
                if ('layout-2' == $style):
                    /*TINY SLIDER*/
                    wp_enqueue_style('tiny-slider');
                    wp_enqueue_script('tiny-slider-js');

                    wp_add_inline_script(
                        'tiny-slider-js',
                        "window.addEventListener( 'load', function(){
                                var slider = tns({
                                    container: '.tour-gallery-slide',
                                    loop: false,
                                    items: 1,
                                    lazyload: true,
                                    autoHeight: true,
                                    mouseDrag: true,
                                    nav: true,
                                    controls: false,
                                    arrowKeys: true,
                                    responsive: {
                                        768: {
                                            controls: true,
                                            nav: false
                                        }
                                    }
                                });
                            } );",
                        'after'
                    );

                    wp_enqueue_style( 'fancybox' );
                    wp_enqueue_script( 'jquery-fancybox' ); ?>
                    <div class="tour-gallery">
                        <div class="tour-gallery-slide">
                            <?php
                            foreach ($gallery_img_array as $key => $value):
                                $gallery_img_divided = explode('{{{}}}', $value);
                                $gallery_thumbnail_url = $gallery_img_divided[0];
                                $gallery_original_url = $gallery_img_divided[1];
                                ?>
                                <a href="<?php echo esc_url($gallery_original_url); ?>"
                                   data-fancybox="brick-gallery"
                                   data-options='{"backFocus" : false}'>
                                    <img src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                         data-src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                         class="tns-lazy tns-lazy-img" alt="">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php /*MAIN INFO SECTION*/ ?>
                    <div id="tour-link-info" class="tour-section">
                        <h2 class="tour-section-title"><?php echo empty($record->subtitle) ? esc_html('Informationen') : esc_html($record->subtitle); ?></h2>
                        <?php if (!is_null($record->description)) echo $record->description; ?>
                    </div>

                    <?php /* ADDITIONAL FIELDS */ ?>
                    <?php if ($record->additionalFields && !empty($additionalFieldsLabels)) {
                        foreach ($additionalFieldsLabels as $field_id => $field_label) {
                            if ($record->additionalFields->$field_id) {
                                preg_match('/<p><img.*?src="(.*?)"[^\>]+><\/p>/', $record->additionalFields->$field_id, $match);
                                $img = strip_tags($match[0], '<img>');
                                $descr = preg_replace('/<p><img.*?src="(.*?)"[^\>]+><\/p>/', '', $record->additionalFields->$field_id);
                                ?>
                                <div id="<?php echo $field_id ?>" class="tour-section tour-additional-field">
                                    <h2 class="tour-section-title"><?php echo $field_label?></h2>
                                    <div>
                                        <?php echo $img.$descr ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    } ?>

                    <?php /*GALLERY SECTION*/ ?>
                    <?php if ($style == 'layout-1' && $images_count) { ?>
                        <div id="tour-link-gallery" class="tour-section">
                            <h2 class="tour-section-title"><?php esc_html_e('Galerie', 'goto'); ?></h2>
                            <?php /*GALLERY*/
                            $slider_items_count = $images_count < 3 ? $images_count : 3;
                            /*LITY VIDEO LIGHTBOX*/
                            wp_enqueue_style('tiny-slider');
                            wp_enqueue_script('tiny-slider-js');
                            wp_add_inline_script(
                                'tiny-slider-js',
                                "document.addEventListener( 'DOMContentLoaded', function(){
							var slider = tns({
								container: '#tour-3-slider',
								loop: false,
								lazyload: true,
								items: " . $slider_items_count . ",
								gutter: 1,
								mouseDrag: true,
								nav: true,
								arrowKeys: true,
								autoHeight: true,
								controls: false,
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
										items: " . $slider_items_count . ",
										controls: true
									}
								}
							});
						} );",
                                'after'
                            );

                            wp_enqueue_style( 'fancybox' );
                            wp_enqueue_script( 'jquery-fancybox' ); ?>
                            <div class="tour-3-gallery">
                                <div id="tour-3-slider">
                                    <?php
                                    foreach ($gallery_img_array as $key => $value):
                                        $gallery_img_divided = explode('{{{}}}', $value);
                                        $gallery_thumbnail_url = $gallery_img_divided[0];
                                        $gallery_original_url = $gallery_img_divided[1];
                                        ?>
                                        <a href="<?php echo esc_url( $gallery_original_url ); ?>" class="tour-3-slide-item"
                                           data-fancybox="brick-gallery"
                                           data-options='{"backFocus" : false}'>
                                            <img src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                                 data-src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                                 class="tns-lazy tns-lazy-img" alt="">
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php /* RELATED SECTION*/ ?>
                </div>
                <?php
                if (get_theme_mod('brick_related_show_content', false)) {
                    set_query_var('related_position', '_content');
                    get_template_part('template-parts/content', 'related');
                } ?>
            </article>
        </div>

        <?php /*RIGHT SIDEBAR*/ ?>
        <?php if ($show_sidebar) { ?>
        <div class="tour-right-sidebar">
            <?php if ($record->lat && $record->lng) { ?>
            <div class="tour-book-form">
                <div style="width: 100%; height: 400px; margin-top: 30px" class="elementor-custom-embed"><iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $record->lat.','.$record->lng ?>&amp;t=m&amp;z=<?php echo get_theme_mod('brick_map_zoom', 12)?>&amp;output=embed&amp;iwloc=near" aria-label="<?php echo 1; ?>"></iframe>
                </div>
            </div>
            <?php } ?>
            <?php /* RELATED SECTION*/ ?>
            <?php
            set_query_var('related_position', '');
            if (get_theme_mod('tour_related_show_sidebar', true)) {
                get_template_part( 'template-parts/content', 'related' );
            } ?>
        </div>
        <?php } ?>
    </div>
    <div>
        <!--    --><?php //the_content(); ?>
    </div>
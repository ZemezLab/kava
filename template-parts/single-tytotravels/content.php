<?php
// @codingStandardsIgnoreStart
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));

set_query_var('theme_mod_prefix', 'tour');

/* LEFT CONTENT OPTIONS
***************************************************/
$tour_id = get_the_ID();
$tour_title = get_the_title();
$style = get_query_var('style');

/*price*/
$price = $record->price;
if ($price) {
    $price_prefix = '';
    if (isset($record->additionalFields->priceprefix) && !empty($record->additionalFields->priceprefix))
        $price_prefix = $record->additionalFields->priceprefix;
    if (empty($record->additionalFields->priceprefix))
        $price_prefix = get_theme_mod('tour_price_prefix', 'ab:');

    $price_suffix = '';
    if (isset($record->additionalFields->pricesuffix) && !empty($record->additionalFields->pricesuffix))
        $price_suffix = $record->additionalFields->pricesuffix;
    if (empty($record->additionalFields->pricesuffix))
        $price_suffix = get_theme_mod('tour_price_suffix', '/ pro Person');
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

/*short infomation*/
$days = array_sum(array_column($record->itinerary, 'days'));
if (isset($record->dates) && !empty($record->dates)) $date = date_create($record->dates[0]->start);

/*map*/
$waypoints = [];
if (function_exists('tyto_get_route_with_airports'))
    $waypoints = tyto_get_route_with_airports($record);

$images_count = tyto_get_gallery_images_count($record);
if( $images_count ) {
    $slider_items_count = $images_count < 3 ? $images_count : 3;
    $gallery_img_array = tyto_get_gallery_images($record, ceil(2000/$slider_items_count), 1400);
}

$travel_dates = get_query_var('travel_dates');

$show_attributes = get_theme_mod('tour_attributes', true);
$max_attributes = get_theme_mod('tour_max_attributes', 5);

$opened_boxes = get_theme_mod('single_tour_itinerary_opened_boxes', true);

$accommodations = get_query_var('itinerary_accommodations');

wp_register_script('dummy-handle-footer', '', [], '', true);
wp_enqueue_script('dummy-handle-footer');

?>
<?php /*MAIN CONTAINER*/ ?>
    <div id="has-sidebar-sticky">
        <?php /*LEFT CONTENT*/ ?>
        <div class="tour-left-content">
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
                                   data-fancybox="tour-gallery"
                                   data-options='{"backFocus" : false}'>
                                    <img src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                         data-src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                         class="tns-lazy tns-lazy-img" alt="">
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php /*SHORT INFORMATION*/ ?>
                <?php if ($show_attributes) {
                    $attributes_count = 0; ?>
                    <div class="tour-desc">
                        <?php if (!empty($days)):
                            $attributes_count++; ?>
                            <div class="tour-desc-item" title="Reisedauer">
                                <div class="tour-desc-item-inner">
                                    <span class="tour-desc-icon goto-icon-clock"></span>
                                    <div class="tour-desc-text">
                                        <?php
                                        if ($days < 2) {
                                            echo esc_html($days) . ' ' . esc_html__('Tag', 'goto');
                                        } else {
                                            echo esc_html($days) . ' ' . esc_html__('Tage', 'goto');
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($record->paxMin || $record->paxMax):
                            $attributes_count++; ?>
                            <div class="tour-desc-item" title="Personen">
                                <div class="tour-desc-item-inner">
                                    <span class="tour-desc-icon goto-icon-group"></span>
                                    <div class="tour-desc-text">
                                        Teilnehmer: <?php if ($record->paxMin) echo $record->paxMin;
                                        if ($record->paxMin && $record->paxMax) echo ' - ';
                                        if ($record->paxMax) echo $record->paxMax; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($record->additionalFields->reiseart)):
                            $attributes_count++; ?>
                            <div class="tour-desc-item" title="Reiseart">
                                <div class="tour-desc-item-inner">
                                    <span class="tour-desc-icon goto-icon-tag"></span>
                                    <div class="tour-desc-text">
                                        <?php echo esc_html($record->additionalFields->reiseart); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($record->additionalFields->reisesaison)):
                            $attributes_count++; ?>
                            <div class="tour-desc-item" title="Beste Reisezeit">
                                <div class="tour-desc-item-inner">
                                    <span class="tour-desc-icon goto-icon-calendar-2"></span>
                                    <div class="tour-desc-text">
                                        <?php echo esc_html($record->additionalFields->reisesaison); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($record->additionalFields->flugzeit)):
                            $attributes_count++; ?>
                            <div class="tour-desc-item" title="Flugzeit">
                                <div class="tour-desc-item-inner">
                                    <span class="tour-desc-icon goto-icon-paper"></span>
                                    <div class="tour-desc-text">
                                        <?php echo esc_html($record->additionalFields->flugzeit); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($attributes_count) {
                        if ($attributes_count > $max_attributes) $attributes_count = $max_attributes ?>
                        <style>
                            .single-tytotravels .tour-desc-item {
                                flex-basis: <?php echo 100/$attributes_count - 1;?>%;
                                width: <?php echo 100/$attributes_count - 1;?>%;
                            }

                            @media (max-width: 767px) {
                                .single-tytotravels .tour-desc-item {
                                    flex-basis: 49%;
                                    width: 49%;
                                }
                            }
                        </style>
                    <?php } ?>
                <?php } ?>

                <div class="entry-content">
                    <?php /*MAIN INFOR SECTION*/ ?>
                    <div id="tour-link-info" class="tour-section">
                        <h2 class="tour-section-title"><?php echo empty($record->subtitle) ? esc_html('Informationen') : esc_html($record->subtitle); ?></h2>
                        <?php if (!is_null($record->description)) echo $record->description;
                        if ($record->highlights || $record->servicesIncluded || $record->servicesExcluded || $record->servicesNote) { ?>
                            <table class="tour-table-info">
                                <tbody>
                                <?php if (!is_null($record->highlights) && !empty($record->highlights)) { ?>
                                    <tr>
                                        <td><h4>Highlights</h4></td>
                                        <td>
                                            <div class="highlights">
                                                <ul>
                                                    <?php foreach (explode("\n", $record->highlights) as $item): ?>
                                                        <?php if (isset($item) && $item !== '' && strlen($item) > 3): ?>
                                                            <li <?php if (substr($item, 0, 2) === '{{' && substr($item, -2) === '}}') echo 'class="text"'; ?>>
                                                                <span class="fa fas fa-star"></span>
                                                                <?php echo str_replace(['{{', '}}'], '', $item); ?>
                                                            </li>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($record->servicesIncluded)) { ?>
                                    <tr>
                                        <td><h4>Inklusivleistungen</h4></td>
                                        <td>
                                            <ul>
                                                <?php foreach (explode("\n", $record->servicesIncluded) as $item): ?>
                                                    <?php if (isset($item) && $item !== '' && strlen($item) > 3): ?>
                                                        <li <?php if (substr($item, 0, 2) === '{{' && substr($item, -2) === '}}') echo 'class="text"'; ?>>
                                                            <span class="editor-icon editor-icon-tick goto-icon-tick"></span>
                                                            <?php echo str_replace(['{{', '}}'], '', $item);; ?>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (!is_null($record->servicesExcluded) && !empty($record->servicesExcluded)) { ?>
                                    <tr>
                                        <td><h4>Exklusivleistungen</h4></td>
                                        <td>
                                            <ul>
                                                <?php
                                                foreach (explode("\n", $record->servicesExcluded) as $item): ?>
                                                    <?php if (isset($item) && $item !== '' && strlen($item) > 3): ?>
                                                        <li <?php if (substr($item, 0, 2) === '{{' && substr($item, -2) === '}}') echo 'class="text"'; ?>>
                                                            <span class="editor-icon ion-plus"></span>
                                                            <?php echo str_replace(['{{', '}}'], '', $item); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (!is_null($record->servicesNote) && !empty($record->servicesNote)) { ?>
                                    <tr>
                                        <td><h4>Hinweise</h4></td>
                                        <td>
                                            <div class="notes">
                                                <?php echo $record->servicesNote ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>

                    <?php /* ACCOMMODATIONS SECTION */ ?>
                    <?php if (count($accommodations)
                        && get_theme_mod('tour_show_accommodations', false) == true
                        && get_theme_mod('tour_show_accommodations_after_itinerary', false) == false
                    ) {
                        get_template_part('template-parts/single-tytotravels/section', 'accommodations');
                    } ?>

                    <?php /*ITINERARY SECTION*/ ?>
                    <?php if (count($record->itinerary)) { ?>
                        <div id="tour-link-itinerary" class="tour-section">
                            <h2 class="tour-section-title"><?php esc_html_e('Reiseverlauf', 'goto'); ?></h2>
                            <div class="tour-accordion">
                                <?php
                                $item_date = null;
                                $day = 1;
                                if (isset($record->dates) && !empty($record->dates)) $item_date = date_create($record->dates[0]->start);
                                $step = 0;
                                $pos = 0;
                                $last = count($record->itinerary) - 1;
                                foreach ($record->itinerary as $kk => $item) {
                                    $active_box = false;
                                    if ($opened_boxes == 'all'
                                        || ($opened_boxes == 'first' && $pos == 0)
                                        || ($opened_boxes == 'first_last' && ($pos == 0 || $pos == $last))) $active_box = true; ?>
                                    <div class="tour-acc-item timeline-item _pos<?php echo $step ?>-<?php echo $pos ?><?php
                                    if (isset($item->flights) && sizeof($item->flights) > 0 && $kk != 0 && $kk != $last) {
                                        echo ' _flight' . $step;
                                        $step++;
                                        $pos = 0;
                                        echo ' _pos' . $step . '-' . $pos;
                                    }
                                    if ($kk == $last)
                                        echo ' _pos' . $step . '-' . ($pos + 1);
                                    ?>">
                                        <h3 class="tour-acc-head timeline-item-title<?php if ($active_box) echo ' active' ?>">
                                            <strong>Tag <?php
                                                if ($item->days > 1) {
                                                    echo $day . ' - ' . ($day + $item->days - 1);
                                                } else {
                                                    echo $day;
                                                } ?>: <?php echo $item->brick->title; ?></strong></h3>
                                        <?php $day += $item->days; ?>
                                        <div class="tour-acc-content<?php if ($active_box) echo ' show'; ?>">
                                            <div class="tour-acc-text">
                                                <?php
                                                $imgs_lngth = sizeof($item->brick->images);
                                                if ($imgs_lngth > 0) {
                                                    if (strpos($item->brick->images[0]->image, 'unsplash')) {
                                                        $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=190&w=250';
                                                        $img_array = explode("?", $item->brick->images[0]->image);
                                                        $image_url = $img_array[0] . $unsplash_options;
                                                    } else {
                                                        $cloudinary_options = array(
                                                            "secure" => true,
                                                            "width" => 250,
                                                            "height" => 190,
                                                            "crop" => "thumb"
                                                        );
                                                        $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                                                    } ?>
                                                    <img class="tour-acc-img" src="<?php echo $image_url ?>"
                                                         alt="<?php echo $item->brick->title ?>">
                                                <?php } ?>
                                                <?php echo $item->brick->description; ?>
                                            </div>
                                            <?php
                                            if (get_theme_mod('tour_show_accommodations', false) == false) {
                                                $accommodation_data = [];
                                                $accommodation = tyto_get_itinerary_brick_accommodation($item);
                                                if ($accommodation['accommodation'] && $accommodation['tytoid']) {
                                                    $accommodation_data = tyto_get_accommodation_data($accommodation['tytoid'], $item);
                                                }
                                                if (!empty($accommodation_data)) { ?>
                                                    <div class="itinerary-accommodation">
                                                        <a class="itinerary-accommodation-title"
                                                           href="<?php echo get_the_permalink($accommodation_data['post_id']) ?>">
                                                            <strong><?php echo ((int)$item->brick->days > 1) ? 'Übernachtungen in' : 'Übernachtung:' ?>
                                                            </strong> <?php echo $accommodation_data['tytorawdata']->title; ?>
                                                        </a><br>
                                                        <?php if (count($accommodation_data['meals'])) {?>
                                                            <span class="meal"><strong>Verpflegung:</strong> <?php echo join(' / ', $accommodation_data['meals']); ?></span><br>
                                                        <?php } ?>
                                                        <div class="tour-acc-text">
                                                            <?php if ($accommodation_data['tytorawdata']->images[0]->image) {
                                                                $img_options = array(
                                                                    "secure" => true,
                                                                    "width" => 200,
                                                                    "height" => 160,
                                                                    "crop" => "thumb"
                                                                );
                                                                if ('http' === substr($accommodation_data['tytorawdata']->images[0]->image, 0, 4)) {
                                                                    $img_options['type'] = 'fetch';
                                                                }?>
                                                                <a href="<?php echo get_the_permalink($accommodation_data['post_id']) ?>">
                                                                    <img class="tour-acc-img"
                                                                         src="<?php echo \Cloudinary::cloudinary_url($accommodation_data['tytorawdata']->images[0]->image, $img_options) ?>"
                                                                         alt="<?php echo $item->brick->title ?>">
                                                                </a>
                                                            <?php } ?>
                                                            <?php echo $accommodation_data['tytorawdata']->description; ?></div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                    $pos++;
                                } ?>
                            </div>
                            <?php
                            wp_add_inline_script(
                                'kava-custom-script',
                                "jQuery( document.body ).on( 'click', '.tour-accordion .tour-acc-head', function( e ) {
											e.preventDefault();
											var parent    = jQuery( '.tour-accordion' ),
												t         = jQuery( this ),
												next      = t.next(),
												nextAlias = parent.find( '.tour-acc-content' ),
												head      = parent.find( '.tour-acc-head' );

											if ( next.hasClass( 'show' ) ) {
												next.slideUp( 300, function() {
													jQuery( this ).removeClass( 'show' );
												} );
												t.removeClass( 'active' );
											} else {
												next.slideDown( 300, function() {
													jQuery( this ).addClass( 'show' );
												} );
												t.addClass( 'active' );
											}
										});",
                                'after'
                            ); ?>
                        </div>
                    <?php } ?>

                    <?php /* ACCOMMODATIONS SECTION */ ?>
                    <?php if (count($accommodations)
                        && get_theme_mod('tour_show_accommodations', false) == true
                        && get_theme_mod('tour_show_accommodations_after_itinerary', false) == true
                    ) {
                        get_template_part('template-parts/single-tytotravels/section', 'accommodations');
                    } ?>

                    <?php /*MAP SECTION*/ ?>
                    <?php if (($record->lat && $record->lng || count($record->itinerary)) && get_theme_mod('tour_map_position', 'content') == 'content'): ?>
                        <div id="tour-link-map" class="tour-section">
                            <h2 class="tour-section-title"><?php esc_html_e('Karte', 'tyto'); ?></h2>
                            <?php get_template_part('template-parts/tyto/content/s-map'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($travel_dates) { ?>
                        <div id="tour-link-dates" class="tour-section">
                            <h2 class="tour-section-title"><?php esc_html_e('Termine und Preise', 'goto'); ?></h2>
                            <ul class="termine-header__list">
                                <li class="termine-header__item"></li>
                                <li class="termine-header__item">Start</li>
                                <li class="termine-header__item">Ende</li>
                                <li class="termine-header__item">Preis</li>
                                <li class="termine-header__item surcharge">EZ-Zuschlag</li>
                                <li class="termine-header__item">Hinweis</li>
                            </ul>
                            <?php
                            $show_surcharge = false;
                            foreach ($travel_dates as $item) {
                                if (isset($item->singleRoomSurcharge) && $item->singleRoomSurcharge > 0) $show_surcharge = true; ?>
                                <ul class="termine-body__list dates-row">
                                    <li class="termine-body__item termine-body__item--select">
                                            <span class="termine-body__value termine-body__value--select">
                                                <label>
                                                    <input type="radio" name="dates"><i class="far fa-circle"></i>
                                                    <span class="select-title">Auswählen</span>
                                                </label>
                                            </span>
                                    </li>
                                    <li class="termine-body__item"><span
                                            class="termine-body__caption">Start</span>
                                        <span
                                            class="termine-body__value start"><?php echo date_format(date_create($item->start), 'd.m.Y'); ?></span>
                                    </li>
                                    <li class="termine-body__item"><span
                                            class="termine-body__caption">Ende</span>
                                        <span
                                            class="termine-body__value end"><?php echo date_format(date_create($item->end), 'd.m.Y'); ?></span>
                                    </li>
                                    <li class="termine-body__item"><span
                                            class="termine-body__caption">Preis</span>
                                        <span
                                            class="termine-body__value">€ <span
                                                class="price"><?php echo number_format($item->price, 0, ',', '.'); ?></span>
                                                </span></li>
                                    <li class="termine-body__item surcharge"><span
                                            class="termine-body__caption">EZ-Zuschlag</span>
                                        <span
                                            class="termine-body__value">€ <span
                                                class="surcharge"><?php echo number_format($item->singleRoomSurcharge, 0, ',', '.'); ?></span>
                                                </span></li>
                                    <li class="termine-body__item"><span
                                            class="termine-body__caption">Hinweis</span>
                                        <div class="termine-body__value termine-body__value--last">
                                            <span class="note"><?php echo $item->note; ?></span>
                                        </div>
                                    </li>
                                    <li class="termine-body__item termine-body__item--select-mobile">
                                            <span class="termine-body__value termine-body__value--select">
                                                <label>
                                                    <input type="radio" name="dates"><i class="far fa-circle"></i>
                                                    <span class="select-title">Auswählen</span>
                                                </label>
                                            </span>
                                    </li>
                                </ul>
                                <?php
                            } ?>
                            <?php if (!$show_surcharge) { ?>
                                <style>#tour-link-dates .surcharge {
                                        display: none
                                    }</style>
                            <?php } ?>
                            <?php if ($record->datesNote) { ?>
                                <br><p style="font-size:1.3em;font-weight:bold">Termin- und Preishinweise</p>
                                <?php echo $record->datesNote; ?>
                            <?php } ?>
                        </div>
                        <?php
                        wp_add_inline_script(
                            'dummy-handle-footer',
                            "jQuery(document).ready(function($){
                            $('.dates-row').click(function() {
                              var row_ = $(this);
                              var radio = row_.find('[name=\"dates\"]');
                              $('.dates-row').removeClass('active');
                              $('.dates-row').find('input[type=\"radio\"]').prop('checked', false);
                              row_.addClass('active');
                              
                              var price_ = row_.find('span.price').html();
                              $('.tour-right-sidebar').find('.price-val').html(price_);
                              
                              var start = row_.find('span.start').html();
                              var end = row_.find('span.end').html();
                              var anfragen_btn_href = $('.tour-right-sidebar').find('.anfragen-button').attr('href');
                              urlObject = new URL(anfragen_btn_href);
                              urlObject.searchParams.set('dates', start + '-'+ end);
                              $('.tour-right-sidebar').find('.anfragen-button').attr('href', urlObject.href);
                              $('.tour-right-sidebar').find('.dates').html('<div>' + start + ' - '+ end + '</div>');
                            });
                            })",
                            'after'
                        ); ?>
                    <?php } ?>

                    <?php if (isset($record->additionalOptions) && $record->additionalOptions) { ?>
                        <div id="tour-link-additional-options" class="tour-section tour-additional-options">
                            <h2 class="tour-section-title"><?php echo get_theme_mod('additional_options_title', 'Optionen und Pakete') ?></h2>
                            <div>
                                <?php foreach ($record->additionalOptions as $i => $option) { ?>
                                    <label class="additional-option" data-i="<?php echo $i ?>"><input
                                            type="<?php echo get_theme_mod('additional_options_type', 'packets') == 'packets' ? 'radio' : 'checkbox' ?>"
                                            name="additional-option"><i
                                            class="far <?php echo get_theme_mod('additional_options_type', 'packets') == 'packets' ? 'fa-circle' : 'fa-square' ?>"></i>
                                        <strong class="option-name"><?php echo esc_html($option->label) ?></strong>
                                        <span class="option-price"><?php echo esc_html(number_format($option->price, 0, ',', '.')); ?> €</span></label>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        wp_add_inline_script(
                            'dummy-handle-footer',
                            "jQuery(document).ready(function($){
                            $('.additional-option').click(function() {
                              var selected_options = $('.tour-additional-options').find('input:checked');
                              var opt_html = ''; var opt_names = [];
                              $.each(selected_options, function() {
                                var option = $(this).closest('.additional-option');
                                opt_html +=  '<span data-i=\"'+option.data('i')+'\" class=\"option\">' + option.find('.option-name').html() + '&nbsp' + option.find('.option-price').html() + '&nbsp;<i class=\"ion-close\"></i><hr></span>';
                                opt_names.push(option.find('.option-name').html());
                              })
                              
                              $('.tour-right-sidebar').find('.additional').html(opt_html);
                              
                              var anfragen_btn_href = $('.tour-right-sidebar').find('.anfragen-button').attr('href');
                              var anfragen_url = new URL(anfragen_btn_href);
                              anfragen_url.searchParams.set('option', opt_names.join(','));
                              $('.tour-right-sidebar').find('.anfragen-button').attr('href', anfragen_url.href);      
                            });
                            $('body').on( 'click', '.tour-right-sidebar .option .ion-close', function( e ) {
                              var i = $(this).closest('.option').data('i'); 
                              $(this).closest('.option').remove();
                              $('.tour-additional-options').find('.additional-option[data-i=\"'+i+'\"] input').prop(\"checked\", false);
                            });
                            })",
                            'after'
                        );
                    } ?>
                    <?php /*GALLERY SECTION*/ ?>
                    <?php if ($style == 'layout-1') { ?>
                        <div id="tour-link-gallery" class="tour-section">
                            <h2 class="tour-section-title"><?php esc_html_e('Galerie', 'goto'); ?></h2>
                            <?php /*GALLERY*/
                            if ($images_count):
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
								items: " . $slider_items_count . ",
								gutter: 1,
								lazyload: true,
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
                                               data-fancybox="tour-gallery"
                                               data-options='{"backFocus" : false}'>
                                                <img src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                                     data-src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                                                     class="tns-lazy tns-lazy-img" alt="">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                    <?php /* RELATED SECTION*/ ?>
                </div>
                <?php
                if (get_theme_mod('tour_related_show_content', false)) {
                    set_query_var('related_position', '_content');
                    get_template_part('template-parts/content', 'related');
                } ?>
            </article>
        </div>

        <?php /*RIGHT SIDEBAR*/ ?>
        <div class="tour-right-sidebar">
            <div id="tour-sidebar-sticky">
                <div class="tour-sidebar js-booking-tour">
                    <div class="tour-price-box">
                        <?php if ($travel_dates || $record->type == 'INDEPENDENT' && $record->travelBegin && $record->travelEnd) { ?>
                            <div class="dates"><?php
                                if ($record->type == 'INDEPENDENT') {
                                    echo '<div>' . date_format(date_create($record->travelBegin), 'd.m.Y') . ' - ' . date_format(date_create($record->travelEnd), 'd.m.Y') . '</div>';
                                } ?></div>
                        <?php } ?>
                        <?php if ($record->price && get_theme_mod('tour_show_price', true)) {
                            $price = number_format($record->price, 0, ',', '.'); ?>
                            <div class="tour-price">
                                <?php echo $price_prefix . '&nbsp;' ?><span class="tour-regu-price"><span
                                        class="price-val"><?php echo $price ?></span> €</span><?php echo '&nbsp;' . $price_suffix ?>
                            </div>
                            <?php if (!empty($record->additionalFields->pricesubline)) { ?>
                            <div><?php echo $record->additionalFields->pricesubline ?></div>
                            <?php } ?>
                            <hr>
                        <?php } ?>
                        <span class="additional"></span>
                        <?php if ($record->type == 'INDEPENDENT') { ?>
                            <a class="anfragen-button individual" href="javascript:void(0);">Unverbindlich buchen</a>
                        <?php } else {
                            $btn_text = get_theme_mod('single_request_btn_text', 'Anfragen');
                            $url = get_theme_mod('single_request_btn_link', '/anfrageformular');
                            $url .= '?recordId=' . $record->id .
                                '&travel=' . esc_attr(urlencode(get_the_title()));
                            if ($destination)
                                $url .= '&destination=' . esc_attr(urlencode($destination));
                            if ($price)
                                $url .= '&price=' . esc_attr(urlencode($price)); ?>
                            <a class="anfragen-button" target="<?php echo get_theme_mod('single_request_btn_target', '_self') ?>"
                               href="<?php echo site_url($url) ?>"><?php echo $btn_text ?></a>
                        <?php } ?>
                        <?php if (\TyTo\Config::getValue('addpdf') == 'on') { ?>
                            <a id="tyto-pdf" class="tyto-download-button tyto-pdf"
                               href="<?php echo site_url('/download-pdf/' . get_the_ID()) ?>"
                               target="_blank">
                            <span class="icon">
                                <svg width="14" height="18" viewBox="0 0 22 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.25 20.875L20.0128 19.6378L17.75 21.9005V14.75H16V21.9005L13.7372 19.6378L12.5 20.875L16.875 25.25L21.25 20.875Z" fill="black"/>
                                <path d="M10.75 23.5H2V2.49999H9V7.74999C9.00138 8.2137 9.1862 8.65801 9.51409 8.9859C9.84198 9.31379 10.2863 9.49861 10.75 9.49999H16V12.125H17.75V7.74999C17.7531 7.635 17.7312 7.5207 17.6859 7.41496C17.6406 7.30923 17.5729 7.21456 17.4875 7.13749L11.3625 1.01249C11.2855 0.927044 11.1908 0.859324 11.0851 0.814005C10.9793 0.768686 10.865 0.746846 10.75 0.749992H2C1.5363 0.751377 1.09198 0.936196 0.764093 1.26409C0.436204 1.59197 0.251385 2.03629 0.25 2.49999V23.5C0.251385 23.9637 0.436204 24.408 0.764093 24.7359C1.09198 25.0638 1.5363 25.2486 2 25.25H10.75V23.5ZM10.75 2.84999L15.65 7.74999H10.75V2.84999Z" fill="#741333"/>
                                </svg>
                            </span>
                                <span>
                                <?php if (\TyTo\Config::getValue('download_label') != '') {
                                    echo \TyTo\Config::getValue('download_label');
                                } else {
                                    echo __('PDF Download', 'tyto');
                                } ?>
                            </span>

                            </a>
                        <?php } ?>
                        <?php if (get_theme_mod('tour_share_button', false)) { ?>
                            <a id="open-share-popup" href="javascript:;" class="button ghost themeborder">
                        <span class="icon">
                            <svg width="18" height="18" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.125 17.5C19.47 17.5033 18.8242 17.6536 18.235 17.9398C17.6459 18.226 17.1286 18.6409 16.7212 19.1537 L10.325 15.155C10.5581 14.4026 10.5581 13.5974 10.325 12.845L16.7212 8.84625C17.3704 9.65012 18.2832 10.1983 19.2979 10.3936C20.3125 10.5889 21.3636 10.4188 22.2648 9.91342C23.1661 9.40801 23.8593 8.59993 24.2218 7.63231C24.5843 6.6647 24.5926 5.60001 24.2453 4.62685C23.898 3.65369 23.2174 2.83487 22.3242 2.31545C21.4309 1.79602 20.3827 1.60951 19.3651 1.78896C18.3475 1.96841 17.4263 2.50224 16.7646 3.29588C16.1029 4.08951 15.7435 5.09173 15.75 6.125C15.7541 6.51628 15.813 6.90505 15.925 7.28L9.52873 11.2787C8.96404 10.5679 8.1922 10.0503 7.32019 9.79772C6.44817 9.54515 5.51918 9.5701 4.66199 9.86913C3.80479 10.1682 3.06186 10.7265 2.53615 11.4666C2.01045 12.2068 1.72803 13.0921 1.72803 14C1.72803 14.9079 2.01045 15.7932 2.53615 16.5334C3.06186 17.2735 3.80479 17.8318 4.66199 18.1309C5.51918 18.4299 6.44817 18.4548 7.32019 18.2023C8.1922 17.9497 8.96404 17.4321 9.52873 16.7212L15.925 20.72C15.813 21.0949 15.7541 21.4837 15.75 21.875C15.75 22.7403 16.0066 23.5862 16.4873 24.3056C16.968 25.0251 17.6513 25.5858 18.4507 25.917C19.2502 26.2481 20.1298 26.3347 20.9785 26.1659C21.8272 25.9971 22.6067 25.5804 23.2186 24.9686C23.8304 24.3567 24.2471 23.5772 24.4159 22.7285C24.5847 21.8799 24.4981 21.0002 24.167 20.2008C23.8358 19.4013 23.2751 18.7181 22.5556 18.2373C21.8361 17.7566 20.9903 17.5 20.125 17.5ZM20.125 3.5C20.6442 3.5 21.1517 3.65395 21.5833 3.94239C22.015 4.23083 22.3515 4.6408 22.5502 5.12045C22.7488 5.60011 22.8008 6.12791 22.6995 6.63711C22.5983 7.14631 22.3482 7.61404 21.9811 7.98115C21.614 8.34827 21.1463 8.59827 20.6371 8.69956C20.1279 8.80084 19.6001 8.74886 19.1204 8.55018C18.6408 8.3515 18.2308 8.01505 17.9424 7.58337C17.6539 7.15169 17.5 6.64417 17.5 6.125C17.5 5.4288 17.7765 4.76112 18.2688 4.26884C18.7611 3.77656 19.4288 3.5 20.125 3.5ZM6.12498 16.625C5.6058 16.625 5.09828 16.471 4.66661 16.1826C4.23493 15.8942 3.89847 15.4842 3.69979 15.0045C3.50111 14.5249 3.44913 13.9971 3.55042 13.4879C3.6517 12.9787 3.90171 12.511 4.26882 12.1438C4.63594 11.7767 5.10367 11.5267 5.61287 11.4254C6.12207 11.3242 6.64987 11.3761 7.12952 11.5748C7.60918 11.7735 8.01915 12.1099 8.30759 12.5416C8.59602 12.9733 8.74998 13.4808 8.74998 14C8.74998 14.6962 8.47342 15.3639 7.98113 15.8562C7.48885 16.3484 6.82117 16.625 6.12498 16.625ZM20.125 24.5C19.6058 24.5 19.0983 24.346 18.6666 24.0576C18.2349 23.7692 17.8985 23.3592 17.6998 22.8795C17.5011 22.3999 17.4491 21.8721 17.5504 21.3629C17.6517 20.8537 17.9017 20.386 18.2688 20.0188C18.6359 19.6517 19.1037 19.4017 19.6129 19.3004C20.1221 19.1992 20.6499 19.2511 21.1295 19.4498C21.6092 19.6485 22.0191 19.9849 22.3076 20.4166C22.596 20.8483 22.75 21.3558 22.75 21.875C22.75 22.5712 22.4734 23.2389 21.9811 23.7312C21.4888 24.2234 20.8212 24.5 20.125 24.5Z" fill="#741333"/>
                            </svg>
                        </span>
                                <span>
                            <?php echo get_theme_mod('tour_share_button_text', 'Reise teilen') ?>
                        </span>
                            </a>
                            <style> a.tyto-download-button {width: 48% !important;} </style>
                        <?php } ?>

                        <?php if ($record->files) {
                            foreach ($record->files as $file) {
                                $options = array(
                                    "secure" => true,
                                    'resource_type' => 'raw',
                                ); ?><a class="tyto-file" download
                                        href="https://cloud.typisch-touristik.de/files/download/<?php echo $file->fileId ?>">
                                <span class="icon">
                                    <svg width="18" height="18" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22.8813 14.8423L9.75625 1.71735C7.56875 -0.557652 3.98125 -0.557652 1.70625 1.62985C-0.56875 3.81735 -0.56875 7.49235 1.70625 9.67985L1.79375 9.76735L4.24375 12.3048L5.46875 11.0798L2.93125 8.54235C1.44375 7.05485 1.44375 4.51735 2.93125 3.02985C4.41875 1.54235 6.95625 1.45485 8.44375 2.94235L8.53125 3.02985L21.5688 16.0673C23.1438 17.5548 23.1437 20.0923 21.6562 21.5798C20.1688 23.1548 17.6312 23.1548 16.1437 21.6673L16.0562 21.5798L9.58125 15.1048C8.70625 14.2298 8.79375 12.8298 9.58125 12.0423C10.4563 11.2548 11.7688 11.2548 12.6438 12.0423L16.2312 15.6298L17.4562 14.4048L13.7813 10.7298C12.2063 9.24235 9.75625 9.32985 8.26875 10.9048C6.86875 12.3923 6.86875 14.7548 8.26875 16.3298L14.8312 22.8923C17.0187 25.1673 20.6063 25.1673 22.8813 22.9798C25.1562 20.7923 25.1562 17.1173 22.8813 14.8423C22.8813 14.8423 22.8813 14.9298 22.8813 14.8423Z" fill="#741333"/>
                                </svg>
                                </span>
                                <span><?php echo $file->title ?></a></span>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <?php /*MAP SECTION*/ ?>
            <?php if (($record->lat && $record->lng || count($record->itinerary)) && get_theme_mod('tour_map_position', 'content') == 'right_sidebar'): ?>
                <div class="tour-map">
                    <?php get_template_part('template-parts/tyto/content/s-map'); ?>
                </div>
            <?php endif; ?>
            <?php /* RELATED SECTION*/ ?>
            <?php
            if (get_theme_mod('tour_related_show_sidebar', false)) {
                set_query_var('related_position', '');
                get_template_part('template-parts/content', 'related');
            } ?>
        </div>
    </div>
    <div>
        <!--    --><?php //the_content(); ?>
    </div>
<?php if ($record->type == 'INDEPENDENT') { ?>
    <div id="individual-inquiry-popup" class="overlay">
        <div class="popup">
            <i class="fa fas fa-check-circle"></i>
            <p>
                Vielen Dank für Ihre Buchung.<br>
                Wir werden Sie schnellstmöglich zurückrufen,<br>
                um Ihre Bestellung abzuschließen.
            </p>
            <a class="ok">OK</a>
        </div>
    </div>
    <?php
    wp_add_inline_script('dummy-handle-footer',
        "jQuery(document).ready(function ($) {
        $('.anfragen-button.individual').click(function () {
            $.ajax({
                url: TytoAjaxVars.ajaxurl,
                dataType: 'json',
                method: 'post',
                data: {action: 'send_individual_inquiry', 'travel_id': " . get_the_ID() . "},
                success: function (data) {
                    if (data.success === true) {
                      $('#individual-inquiry-popup').addClass('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown)
                }
            });
        });
        
        $('#individual-inquiry-popup').find('a.ok').click(function () {
            $(this).parents('#individual-inquiry-popup').removeClass('show');
        });
    })");
    ?>
<?php } ?>


<?php
// @codingStandardsIgnoreStart
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));

set_query_var('theme_mod_prefix', 'accommodation');

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
        $price_prefix = get_theme_mod('accommodation_price_prefix', 'ab:');

    $price_suffix = '';
    if (isset($record->additionalFields->pricesuffix) && !empty($record->additionalFields->pricesuffix))
        $price_suffix = $record->additionalFields->pricesuffix;
    if (empty($record->additionalFields->pricesuffix))
        $price_suffix = get_theme_mod('accommodation_price_suffix', '/ pro Person');
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

                    <?php /* ADDITIONAL OPTIONS */ ?>
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
                                           data-fancybox="accommodation-gallery"
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
                if (get_theme_mod('accommodation_related_show_content', false)) {
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
                        <?php if ($price && get_theme_mod('accommodation_show_price', true)) { ?>
                            <div class="tour-price">
                            <?php echo $price_prefix.'&nbsp;' ?><span class="tour-regu-price"><?php echo strip_tags($price) ?> €</span><?php echo '&nbsp;'.$price_suffix ?>
                            </div>
                            <?php if (!empty($record->additionalFields->pricesubline)) { ?>
                            <div><?php echo $record->additionalFields->pricesubline ?></div>
                            <?php } ?>
                            <hr>
                        <?php } ?>
                        <span class="additional"></span>
                        <?php $btn_text = get_theme_mod('single_request_btn_text', 'Anfragen');
                        $url = get_theme_mod('single_request_btn_link', '/anfrageformular');
                        $url .= '?recordId=' . $record->id .
                            '&travel=' . esc_attr(urlencode(get_the_title()));
                        if ($destination)
                            $url .= '&destination=' . esc_attr(urlencode($destination));
                        if ($price)
                            $url .= '&price=' . esc_attr(urlencode($price)); ?>
                        <a class="anfragen-button" target="<?php echo get_theme_mod('single_request_btn_target', '_self') ?>"
                           href="<?php echo site_url($url) ?>"><?php echo $btn_text ?></a>
                        <?php if (\TyTo\Config::getValue('addpdf') == 'on') { ?>
                            <a id="tyto-pdf" class="tyto-download-button tyto-pdf disabled"
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
                        <?php if (get_theme_mod('accommodation_share_button', false)) { ?>
                            <a id="open-share-popup" href="javascript:;" class="button ghost themeborder">
                        <span class="icon">
                            <svg width="18" height="18" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.125 17.5C19.47 17.5033 18.8242 17.6536 18.235 17.9398C17.6459 18.226 17.1286 18.6409 16.7212 19.1537 L10.325 15.155C10.5581 14.4026 10.5581 13.5974 10.325 12.845L16.7212 8.84625C17.3704 9.65012 18.2832 10.1983 19.2979 10.3936C20.3125 10.5889 21.3636 10.4188 22.2648 9.91342C23.1661 9.40801 23.8593 8.59993 24.2218 7.63231C24.5843 6.6647 24.5926 5.60001 24.2453 4.62685C23.898 3.65369 23.2174 2.83487 22.3242 2.31545C21.4309 1.79602 20.3827 1.60951 19.3651 1.78896C18.3475 1.96841 17.4263 2.50224 16.7646 3.29588C16.1029 4.08951 15.7435 5.09173 15.75 6.125C15.7541 6.51628 15.813 6.90505 15.925 7.28L9.52873 11.2787C8.96404 10.5679 8.1922 10.0503 7.32019 9.79772C6.44817 9.54515 5.51918 9.5701 4.66199 9.86913C3.80479 10.1682 3.06186 10.7265 2.53615 11.4666C2.01045 12.2068 1.72803 13.0921 1.72803 14C1.72803 14.9079 2.01045 15.7932 2.53615 16.5334C3.06186 17.2735 3.80479 17.8318 4.66199 18.1309C5.51918 18.4299 6.44817 18.4548 7.32019 18.2023C8.1922 17.9497 8.96404 17.4321 9.52873 16.7212L15.925 20.72C15.813 21.0949 15.7541 21.4837 15.75 21.875C15.75 22.7403 16.0066 23.5862 16.4873 24.3056C16.968 25.0251 17.6513 25.5858 18.4507 25.917C19.2502 26.2481 20.1298 26.3347 20.9785 26.1659C21.8272 25.9971 22.6067 25.5804 23.2186 24.9686C23.8304 24.3567 24.2471 23.5772 24.4159 22.7285C24.5847 21.8799 24.4981 21.0002 24.167 20.2008C23.8358 19.4013 23.2751 18.7181 22.5556 18.2373C21.8361 17.7566 20.9903 17.5 20.125 17.5ZM20.125 3.5C20.6442 3.5 21.1517 3.65395 21.5833 3.94239C22.015 4.23083 22.3515 4.6408 22.5502 5.12045C22.7488 5.60011 22.8008 6.12791 22.6995 6.63711C22.5983 7.14631 22.3482 7.61404 21.9811 7.98115C21.614 8.34827 21.1463 8.59827 20.6371 8.69956C20.1279 8.80084 19.6001 8.74886 19.1204 8.55018C18.6408 8.3515 18.2308 8.01505 17.9424 7.58337C17.6539 7.15169 17.5 6.64417 17.5 6.125C17.5 5.4288 17.7765 4.76112 18.2688 4.26884C18.7611 3.77656 19.4288 3.5 20.125 3.5ZM6.12498 16.625C5.6058 16.625 5.09828 16.471 4.66661 16.1826C4.23493 15.8942 3.89847 15.4842 3.69979 15.0045C3.50111 14.5249 3.44913 13.9971 3.55042 13.4879C3.6517 12.9787 3.90171 12.511 4.26882 12.1438C4.63594 11.7767 5.10367 11.5267 5.61287 11.4254C6.12207 11.3242 6.64987 11.3761 7.12952 11.5748C7.60918 11.7735 8.01915 12.1099 8.30759 12.5416C8.59602 12.9733 8.74998 13.4808 8.74998 14C8.74998 14.6962 8.47342 15.3639 7.98113 15.8562C7.48885 16.3484 6.82117 16.625 6.12498 16.625ZM20.125 24.5C19.6058 24.5 19.0983 24.346 18.6666 24.0576C18.2349 23.7692 17.8985 23.3592 17.6998 22.8795C17.5011 22.3999 17.4491 21.8721 17.5504 21.3629C17.6517 20.8537 17.9017 20.386 18.2688 20.0188C18.6359 19.6517 19.1037 19.4017 19.6129 19.3004C20.1221 19.1992 20.6499 19.2511 21.1295 19.4498C21.6092 19.6485 22.0191 19.9849 22.3076 20.4166C22.596 20.8483 22.75 21.3558 22.75 21.875C22.75 22.5712 22.4734 23.2389 21.9811 23.7312C21.4888 24.2234 20.8212 24.5 20.125 24.5Z" fill="#741333"/>
                            </svg>
                        </span>
                                <span>
                            <?php echo get_theme_mod('accommodation_share_button_text', 'Unterkunft teilen') ?>
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
            <?php if ($record->lat && $record->lng) { ?>
            <div class="tour-book-form">
                <div id="map" style=""><iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $record->lat.','.$record->lng ?>&amp;t=m&amp;z=<?php echo get_theme_mod('accommodation_map_zoom', 12)?>&amp;output=embed&amp;iwloc=near" aria-label="<?php echo 1; ?>"></iframe>
                </div>
            </div>
            <?php } ?>
            <?php /* RELATED SECTION*/ ?>
            <?php
            set_query_var('related_position', '');
            if (get_theme_mod('accommodation_related_show_sidebar', true)) {
                get_template_part( 'template-parts/content', 'related' );
            } ?>
        </div>
    </div>
    <div>
        <!--    --><?php //the_content(); ?>
    </div>
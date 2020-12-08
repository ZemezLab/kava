<?php

require_once 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $file = lcfirst($file);
    $file = get_theme_file_path() . '/' . $file;

    if (file_exists($file)) {
        require $file;
        return true;
    }

    return false;
});

$theme = \Tourware\Theme::getInstance();
$theme->run();


// legacy stuff

/**
 * Child functions and definitions.
 */
add_filter( 'kava-theme/assets-depends/styles', 'kava_child_styles_depends' );

/**
 * Enqueue styles.
 */
function kava_child_styles_depends( $deps ) {

    $parent_handle = 'kava-parent-theme-style';

    wp_register_style(
        $parent_handle,
        get_template_directory_uri() . '/style.css',
        array(),
        kava_theme()->version()
    );

    $deps[] = $parent_handle;

    return $deps;
}

/**
 * Disable magic button for your clients
 *
 * Un-comment next line to disble magic button output for you clients.
 */
//add_action( 'jet-theme-core/register-config', 'kava_child_disable_magic_button' );

function kava_child_disable_magic_button( $config_manager ) {
    $config_manager->register_config( array(
        'library_button' => false,
    ) );
}

/**
 * Disable unnecessary theme modules
 *
 * Un-comment next line and return unnecessary modules from returning modules array.
 */
//add_filter( 'kava-theme/allowed-modules', 'kava_child_allowed_modules' );

function kava_child_allowed_modules( $modules ) {

    return array(
        'blog-layouts'    => array(),
        'crocoblock'      => array(),
        'woo'             => array(
            'woo-breadcrumbs' => array(),
            'woo-page-title'  => array(),
        ),
    );

}

/**
 * Registering a new structure
 *
 * To change structure and apropriate documnet type parameters go to
 * structures/archive.php and document-types/archive.php
 *
 * To print apropriate location add next code where you need it:
 * if ( function_exists( 'jet_theme_core' ) ) {
 *     jet_theme_core()->locations->do_location( 'kava_child_archive' );
 * }
 * Where 'kava_child_archive' - apropritate location name (from example).
 *
 * Un-comment next line to register new structure.
 */
//add_action( 'jet-theme-core/structures/register', 'kava_child_structures' );

function kava_child_structures( $structures_manager ) {

    require get_theme_file_path( 'structures/archive.php' );

    $structures_manager->register_structure( 'Kava_Child_Structure_Archive' );

}

function tyto_scripts()
{
    $styles_depends = apply_filters( 'kava-theme/assets-depends/styles', array(
        'font-awesome',
    ) );

    wp_enqueue_style(
        'custom-style',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        $styles_depends
    );

    wp_register_style(
        'login-register',
        get_stylesheet_directory_uri().'/assets/css/login-register.css',
    );

    wp_register_style(
        'single-common',
        get_stylesheet_directory_uri().'/assets/css/single-common.css',
        $styles_depends
    );

    wp_register_style(
        'single-tytotravels',
        get_stylesheet_directory_uri().'/assets/css/single-tytotravels.css',
        $styles_depends
    );
    wp_register_style(
        'single-tytoaccommodations',
        get_stylesheet_directory_uri().'/assets/css/single-tytoaccommodations.css',
        $styles_depends
    );
    wp_register_style(
        'single-tytotravelsbricks',
        get_stylesheet_directory_uri().'/assets/css/single-tytotravelsbricks.css',
        $styles_depends
    );


    $scripts_depends = apply_filters( 'kava-theme/assets-depends/script', array(
        'jquery'
    ) );

    wp_enqueue_script(
        'kava-custom-script',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        $scripts_depends,
        null,
        true
    );

    /* LIGHTBOX */
    wp_register_style(
        'lightbox',
        get_stylesheet_directory_uri() . '/assets/css/lightbox.css'
    );
    wp_register_script(
        'jquery-lightbox',
        get_stylesheet_directory_uri() . '/assets/js/lightbox.min.js',
        array('jquery'),
        null,
        true
    );

    /* FANCY BOX 3 */
    wp_register_style(
        'fancybox',
        get_stylesheet_directory_uri() . '/assets/css/fancybox.min.css'
    );
    wp_register_script(
        'jquery-fancybox',
        get_stylesheet_directory_uri() . '/assets/js/fancybox.min.js',
        array('jquery'),
        null,
        true
    );

    /* GOTO ICONS */
    wp_register_style(
        'gotoicons',
        get_stylesheet_directory_uri() . '/assets/css/goto-icon.css'
    );

    /* TOUR SINGLE JS */
    wp_register_script(
        'tour-single',
        get_stylesheet_directory_uri() . '/assets/js/tour-single.js',
        array(),
        null,
        true
    );
    wp_register_script(
        'sticky-sidebar',
        get_stylesheet_directory_uri() . '/assets/js/sticky-sidebar.js',
        array(),
        null,
        true
    );

    /*LITY: VIDEO LIGHTBOX*/
    wp_register_style(
        'lity',
        get_stylesheet_directory_uri() . '/assets/css/lity.css'
    );

    wp_register_script(
        'jquery-lity',
        get_stylesheet_directory_uri() . '/assets/js/lity.js',
        array(),
        null,
        true
    );

    /* JARALLAX : VIDEO PARALLAX */
    wp_register_script(
        'jarallax',
        get_stylesheet_directory_uri() . '/assets/js/jarallax.min.js',
        array( 'jquery' ),
        null,
        true
    );
    wp_register_script(
        'jarallax-video',
        get_stylesheet_directory_uri() . '/assets/js/jarallax-video.min.js',
        array( 'jquery' ),
        null,
        true
    );

    /*TINY SLIDER*/
    wp_register_script(
        'tiny-slider-js',
        get_template_directory_uri() . '/assets/js/tiny-slider.js',
        array(),
        null,
        true
    );

    wp_register_style(
        'tiny-slider',
        get_template_directory_uri() . '/assets/css/tiny-slider.css'
    );

    /* COLLAPSER */
    wp_register_script(
        'collapser-script',
        get_template_directory_uri() .'/assets/js/jquery.collapser.min.js',
        array('jquery'),
        false,
        true
    );
}

add_action('wp_enqueue_scripts', 'tyto_scripts');


function s_header_images_handling($w = 1920, $h = 505)
{
    global $wp_query;
    $single_data = $wp_query->query_vars['tytorawdata'];

    if (isset($single_data->images) && count($single_data->images) > 1) {
        $header_file = $single_data->images[1]->image;
    } else if (isset($single_data->images[0]->image)) {
        $header_file = $single_data->images[0]->image;
    } else {
        return 'https://via.placeholder.com/'.$w.'x'.$h;
    }

    if(strpos($header_file, 'unsplash')){
        $unsplash_img_array = explode('?', $header_file);
        $header_bg = $unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w='.$w;
    } else {
        $header_img_options = array(
            "secure" => true,
            "width" => $w,
            "height" => $h,
            "crop" => "thumb"
        );

        if ('http' === substr($single_data->images[0]->image, 0, 4) || 'http' === substr($single_data->images[1]->image, 0, 4)) {
            $header_img_options['type'] = 'fetch';
        }
        $header_bg = \Cloudinary::cloudinary_url($header_file, $header_img_options);
    }

    return $header_bg;
}

function tyto_get_gallery_images_count($record) {
    $count = 0;
    if (isset($record->images) && count($record->images)) {
        $count += count($record->images);
    }
    if (isset($record->itinerary) && count($record->itinerary)) {
        foreach ($record->itinerary as $item) {
            if (isset($item->brick->images) && count($item->brick->images)) {
                $count += count($item->brick->images);
            }
        }
    }
    return $count;
}

function tyto_get_gallery_images($record, $thumb_width = 500, $orig_width = 1920) {
    $gallery_img_array = [];
    if(count($record->images) > 0){
        foreach($record->images as $key=>$value){
            if(strpos($value->image, 'unsplash')){
                $unsplash_img_array = explode('?', $value->image);
                $gallery_img = $unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w='.$thumb_width.'{{{}}}'.$unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w='.$orig_width;
                $gallery_img_array[] = $gallery_img;
            } else {
                $gallery_thumbnails_option = array(
                    "secure" => true,
                    "width" => $thumb_width,
                    "crop" => "fill",
                    "gravity" => "center"
                );

                $gallery_original_option = array(
                    "secure" => true,
                    "width" => $orig_width
                );

                if ('http' === substr($value->image, 0, 4)) {
                    $gallery_thumbnails_option['type'] = 'fetch';
                    $gallery_original_option['type'] = 'fetch';
                }
                $gallery_img_thumbnail = \Cloudinary::cloudinary_url($value->image, $gallery_thumbnails_option);
                $gallery_img_original = \Cloudinary::cloudinary_url($value->image, $gallery_original_option);
                $gallery_img = $gallery_img_thumbnail.'{{{}}}'.$gallery_img_original;
                $gallery_img_array[] = $gallery_img;
            }
        }
    }
    if (isset($record->itinerary) && count($record->itinerary)) {
        foreach($record->itinerary as $value){
            if (isset($value->brick->images) && count($value->brick->images)) {
                foreach($value->brick->images as $data){
                    if(strpos($data->image, 'unsplash')){
                        $unsplash_img_array = explode('?', $data->image);
                        $gallery_img = $unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w='.$thumb_width.'{{{}}}'.$unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w='.$orig_width;
                        $gallery_img_array[] = $gallery_img;
                    } else {
                        $gallery_thumbnails_option = array(
                            "secure" => true,
                            "width" => $thumb_width,
                            "crop" => "fill",
                            "gravity" => "center"
                        );

                        $gallery_original_option = array(
                            "secure" => true,
                            "width" => $orig_width
                        );

                        if ('http' === substr($data->image, 0, 4)) {
                            $gallery_thumbnails_option['type'] = 'fetch';
                            $gallery_original_option['type'] = 'fetch';
                        }
                        $gallery_img_thumbnail = \Cloudinary::cloudinary_url($data->image, $gallery_thumbnails_option);
                        $gallery_img_original = \Cloudinary::cloudinary_url($data->image, $gallery_original_option);
                        $gallery_img = $gallery_img_thumbnail.'{{{}}}'.$gallery_img_original;
                        $gallery_img_array[] = $gallery_img;
                    }
                }
            }
        }
    }

    return $gallery_img_array;
}

function tyto_get_destination_breadcrumbs($record) {
    $breadcrumbs = [];
    $breadcrumbs_html = '';
    if (isset($record->_continent) || isset($record->_destination) || isset($record->_region)) {
        if (isset($record->_continent)) $breadcrumbs[] = [ 'link' => '', 'title' => $record->_continent];
        if (isset($record->_destination)) {
            $dest = get_page_by_title($record->_destination, OBJECT, 'tytodestinations');
            if (!is_null($dest)) $breadcrumbs[] = [ 'link' => get_the_permalink($dest->ID), 'title' => $record->_destination];
        }
        if (isset($record->_region)) {
            $reg = get_page_by_title($record->_region, OBJECT, 'tytoregion');
            if (!is_null($reg)) $breadcrumbs[] = [ 'link' => get_the_permalink($reg->ID), 'title' => $reg->_destination];
        }
    } else {
        $tyto_countries = get_post_meta(get_the_ID(), 'tytocountries', true);
        $countries = [];
        if (!empty($tyto_countries)) {
            foreach ($tyto_countries as $tyto_country) {
                $countries[] = $tyto_country['official_name_de'];
            }
            $breadcrumbs[] = ['link' => '', 'title' => implode(', ', $countries)];
        }
    }
    if (!empty($breadcrumbs)) {
        $breadcrumbs_html .= '<div id="theme-bread"><div class="breadcrumbs">';
        foreach ($breadcrumbs as $crumb) {
            $breadcrumbs_html .=
                sprintf(
                    '<span class="breadcrumbs-item">%s</span>',
                    $crumb['link'] ? '<a href="'.$crumb['link'].'"><span>'.$crumb['title'].'</span></a>' : '<span><span>'.$crumb['title'].'</span></span>'
                );
        }
        $breadcrumbs_html .= '</div></div>';
    }

    return $breadcrumbs_html;
}

function tyto_get_related_posts($record, $related_items_type = ['tytotravels', 'tytoaccommodations', 'tytotravelsbricks']) {
    if (!empty($record->related_items_ids)) {
        $related_items_ids = $record->related_items_ids;
        $args = array(
            'showposts' => -1,
            'post_type' => $related_items_type,
            'meta_query' => array(
                array(
                    'key' => 'tytoid',
                    'value' => $related_items_ids,
                    'compare' => 'IN'
                )
            ),
        );
        $rel_posts = new WP_Query($args);

        return $rel_posts;
    }
    return false;
}

/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * ### Options:
 *
 * - `ending` Will be used as Ending and appended to the trimmed string
 * - `exact` If false, $text will not be cut mid-word
 * - `html` If true, HTML tags would be handled correctly
 *
 * @param string  $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param array $options An array of html attributes and options.
 * @return string Trimmed string.
 * @access public
 * @link http://book.cakephp.org/view/1469/Text#truncate-1625
 */
function tyto_text_truncate($text, $length = 100, $options = array()) {
    $default = array(
        'ending' => '...', 'exact' => true, 'html' => false
    );
    $options = array_merge($default, $options);
    extract($options);

    if ($html) {
        if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach ($tags as $tag) {
            if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                    array_unshift($openTags, $tag[2]);
                } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                    $pos = array_search($closeTag[1], $openTags);
                    if ($pos !== false) {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if ($contentLength + $totalLength > $length) {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entitiesLength <= $left) {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        } else {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            } else {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if ($totalLength >= $length) {
                break;
            }
        }
    } else {
        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
    }
    if (!$exact) {
        $spacepos = mb_strrpos($truncate, ' ');
        if (isset($spacepos)) {
            if ($html) {
                $bits = mb_substr($truncate, $spacepos);
                preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                if (!empty($droppedTags)) {
                    foreach ($droppedTags as $closingTag) {
                        if (!in_array($closingTag[1], $openTags)) {
                            array_unshift($openTags, $closingTag[1]);
                        }
                    }
                }
            }
            $truncate = mb_substr($truncate, 0, $spacepos);
        }
    }
    $truncate .= $ending;

    if ($html) {
        foreach ($openTags as $tag) {
            $truncate .= '</'.$tag.'>';
        }
    }

    return $truncate;
}

function tyto_is_parallax_enabled() {
    return get_theme_mod('header_parallax', true);
}
function tyto_single_tour_header_layout() {
    return get_theme_mod('single_tour_header_layout', 'layout-1') == 'layout-2';
}
function tyto_single_accommodation_header_layout() {
    return get_theme_mod('single_accommodation_header_layout', 'layout-1') == 'layout-2';
}
function tyto_single_brick_header_layout() {
    return get_theme_mod('single_brick_header_layout', 'layout-1') == 'layout-2';
}

function wpse_custom_menu_order( $menu_ord ) {
    if ( !$menu_ord ) return true;

    return array(
        'index.php', // Dashboard

        'separator1', // First separator
        'edit.php', // Posts
        'edit.php?post_type=tytocontinents', // Continents
        'edit.php?post_type=tytodestinations', // Destinations
        'edit.php?post_type=tytoregions', // Regions
        'edit.php?post_type=tytotravels', // Travels
        'edit.php?post_type=tytoaccommodations', // Accommodations
        'edit.php?post_type=tytotravelsbricks', // Bricks
        'upload.php', // Media
        'link-manager.php', // Links
        'edit-comments.php', // Comments
        'edit.php?post_type=page', // Pages

        'separator2', // Second separator
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
        'separator-last', // Last separator
    );
}
add_filter( 'custom_menu_order', 'wpse_custom_menu_order', 10, 1 );
add_filter( 'menu_order', 'wpse_custom_menu_order', 10, 1 );

add_filter( 'tyto_before_update_item_travels', function ($travel) {
    // itinerary
    $response = \TyTo\Api::getInstance()->get('/api/itineraryitems?filter=[{"property":"travel","operator":"equals","value":"' . $travel['id'] . '"}]');
    $travel['itinerary'] = $response['records'];
    // additional options (options & packets)
    $additionalOptions = \TyTo\Api::getInstance()->get('/api/additionalbookableservice?filter=[{"property":"recordId","operator":"equals","value":"' . $travel['id'] . '"}]');
    $travel['additionalOptions'] = $additionalOptions['records'];
    // related items
    $relations = \TyTo\Api::getInstance()->get('/relations/getRelations/?collection=travels&recordId=' . $travel['id']);
    if ($relations['records']) {
        $related_items_ids = [];
        foreach ($relations['records'] as $r) {
            foreach ($r['items'] as $rel_item) {
                if ($rel_item['recordId'] != $travel['id']) {
                    $related_items_ids[] = $rel_item['recordId'];
                }
            }
        }
    }
    $travel['related_items_ids'] = $related_items_ids;

    return $travel;
});
add_filter( 'tyto_before_update_item_accommodations', function ($record) {
    $additionalOptions = \TyTo\Api::getInstance()->get('/api/additionalbookableservice?filter=[{"property":"recordId","operator":"equals","value":"' . $record['id'] . '"}]');
    $record['additionalOptions'] = $additionalOptions['records'];

    $relations = \TyTo\Api::getInstance()->get('/relations/getRelations/?collection=accommodations&recordId=' . $record['id']);
    if ($relations['records']) {
        $related_items_ids = [];
        foreach ($relations['records'] as $r) {
            foreach ($r['items'] as $rel_item) {
                if ($rel_item['recordId'] != $record['id']) {
                    $related_items_ids[] = $rel_item['recordId'];
                }
            }
        }
    }
    $record['related_items_ids'] = $related_items_ids;

    return $record;
});

add_filter( 'tyto_before_update_item_travelsbricks', function ($record) {
    $relations = \TyTo\Api::getInstance()->get('/relations/getRelations/?collection=travelsbricks&recordId=' . $record['id']);
    if ($relations['records']) {
        $related_items_ids = [];
        foreach ($relations['records'] as $r) {
            foreach ($r['items'] as $rel_item) {
                if ($rel_item['recordId'] != $record['id']) {
                    $related_items_ids[] = $rel_item['recordId'];
                }
            }
        }
    }
    $record['related_items_ids'] = $related_items_ids;

    return $record;
});

add_filter( 'body_class','tyto_body_classes' );
function tyto_body_classes( $classes ) {
    if (is_single() && (in_array(get_post_type(), ['tytotravels', 'tytoaccommodations', 'tytotravelsbricks']))) {
        if (get_post_type() == 'tytotravels') {
            $style = get_theme_mod( 'single_tour_header_layout', 'layout-1' );
            $classes[] = 'single-'.$style;
        }
        if (get_post_type() == 'tytoaccommodations') {
            $style = get_theme_mod( 'single_accommodation_header_layout', 'layout-1' );
            $classes[] = 'single-'.$style;
        }
        if (get_post_type() == 'tytotravelsbricks') {
            $style = get_theme_mod( 'single_brick_header_layout', 'layout-1' );
            $classes[] = 'single-'.$style;
        }
    }

    return $classes;
}

function tyto_get_itinerary_brick_accommodation($item) {
    $acc = $item->accommodations[0];
    $acc_id = isset($acc->accommodation->id) ? $acc->accommodation->id : $acc->accommodation;

    if (empty($acc)) {
        $acc = $item->brick->defaultAccommodation;
        $acc_id = $acc->id;
    }
    if (empty($acc)) {
        $acc = $item->brick->accommodations[0];
        $acc_id = $acc->id;
    }
    return ['accommodation' => $acc, 'tytoid' => $acc_id];
}

function tyto_get_accommodation_data($tytoid, $item) {
    $accommodation_data = [];
    $accommodations = get_posts(array(
        'meta_key' => 'tytoid',
        'meta_value' => $tytoid,
        'post_type' => 'tytoaccommodations',
        'post_status' => 'publish',
        'posts_per_page' => 1
    ));
    if (count($accommodations)) {
        $accommodation_wp_post = array_shift($accommodations);
        $accommodation_data['post_id'] = $accommodation_wp_post->ID;
        $accommodation_data['tytorawdata'] = json_decode(get_post_meta($accommodation_wp_post->ID, 'tytorawdata', true));

        $meals = [];
        $meals_types = [
            'breakfast' => 'Frühstück',
            'lunch' => 'Mittagessen',
            'lunchbox' => 'Lunchbox',
            'dinner' => 'Abendessen',
        ];
        foreach ($meals_types as $meal_type => $meal_name) if ($item->$meal_type) array_push($meals, $meal_name);
        if ($item->customMealType) array_push($meals, $item->customMealType);

        if (!count($meals)) {
            foreach ($meals_types as $meal_type => $meal_name) if ($item->brick->$meal_type) array_push($meals, $meal_name);
        }
        $accommodation_data['meals'] = $meals;
    }

    return $accommodation_data;
}

function tyto_get_all_itinerary_accommodations($record) {
    $accommodations = []; $prev = null; $nights = 0; $i = 0;
    usort($record->itinerary, function($a, $b) {
        return $a->position <=> $b->position;
    });

    foreach ($record->itinerary as $item) {
        $accommodation = tyto_get_itinerary_brick_accommodation($item);
        if (!empty($accommodation['accommodation']) && !empty($accommodation['tytoid'])) {
            $accommodation['data'] = tyto_get_accommodation_data($accommodation['tytoid'], $item);
            if (!empty($accommodation['data'])) {
                if ($prev == null && $i == 0) {
                    array_push($accommodations, $accommodation);
                    $i = 1;
                }
                if ($prev !== null && $prev['tytoid'] != $accommodation['tytoid']) {
                    if ($i > 0) $accommodations[$i-1]['nights'] = $nights;
                    array_push($accommodations, $accommodation);
                    $nights = 0;
                    $i++;
                }
                if (!empty($accommodation['accommodation'])) $nights += intval($accommodation['accommodation']->nights);
                $prev = $accommodation;
            }
        }
    }

    if ($nights !== 0) {
        $last = count($accommodations) - 1;
        $accommodations[$last]['nights'] = $nights;
    }

    return $accommodations;
}

function tyto_update_theme_mods($options) {
    $mods = get_theme_mods();

    foreach ( $options as $id => $option ) {
//        print_r($option);

        if ( 'control' != $option['type'] ) {
            continue;
        }

        if ( isset( $mods[ $id ] ) ) {
            continue;
        }

        $mods[ $id ] = tyto_get_default_theme_mod_value( $options, $id );
    }
    $theme = get_option( 'stylesheet' );
    update_option( "theme_mods_$theme", $mods );
}

function tyto_get_default_theme_mod_value($options, $id) {
    return isset( $options[ $id ]['default'] ) ? $options[ $id ]['default'] : null;
}

add_action( 'after_setup_theme', function(){
    register_nav_menus( [
        'user_menu' => 'User Menu',
    ] );
} );

add_action('wp_logout','tyto_redirect_after_logout');
function tyto_redirect_after_logout(){
    wp_redirect( site_url() );
    exit();
}

add_action('kava-theme/site/site-content-before', 'tyto_page_header_layout1', 999);
function tyto_page_header_layout1($tmpl)
{
    if ($tmpl == 'index') {
        if (is_home()) {
            $css = '';
            $post_id = get_option('page_for_posts');
            if (has_post_thumbnail($post_id))
                $css .= '.header-cover-image{background-image: url(' . esc_url(get_the_post_thumbnail_url($post_id, 'full')) . ')}';
            else if (!empty(get_theme_mod('header_bg_image')))
                $css .= '.header-cover-image{background-image: url(' . esc_url(get_theme_mod('header_bg_image')) . ')}';

            if (get_theme_mod('header_text_shadow', false)) {
                $css .= '.page-header .page-title, .page-header .breadcrumbs-item { text-shadow: 1px 1px 6px rgba(0,0,0,.94); }';
            }
            switch (get_theme_mod('header_horizontal_align', 'center')) {
                case 'flex-start':
                    $css .= '.page-header .page-title { text-align: left; }';
                    break;
                case 'center':
                    $css .= '.page-header .page-title { text-align: center; }';
                    break;
                case 'flex-end':
                    $css .= '.page-header .page-title { text-align: right; }';
                    break;
                default:
                    $css .= '.page-header .page-title { text-align: center; }';
                    break;
            }
            $css .= '.header-cover-image {background-color: '.get_theme_mod('header_bg_color').'}';
            /*PARALLAX*/
            $parallax_output = '';
            $parallax = get_theme_mod('header_parallax', true);
            $parallax_speed = get_theme_mod('header_parallax_speed', true);
            if (true == $parallax) $parallax_output .= 'id="page-header-parallax" data-speed="' . absint($parallax_speed) . '"';
            ?>
            <div class="header-cover-image page-header" <?php echo wp_kses_post($parallax_output); ?>>
                <div class="container">
                    <h1 class="page-title entry-title"><?php echo esc_html(get_the_title($post_id)); ?></h1>
                </div>
            </div>
            <style><?php echo $css; ?></style>

        <?php }
    }
}

/* CUSTOMIZER */
require get_template_directory() . '/inc/customizer.php';


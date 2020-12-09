<?php
$mapApiKey = \TyTo\Config::getValue('mapApiKey');
$plugin_dir_url = WP_PLUGIN_URL.'/midoffice-wordpress-plugin/';
wp_enqueue_script( 'lodash-adt',  $plugin_dir_url . 'assets/js/lodash.js', array('jquery'));
wp_enqueue_script( 'tyto-travels-map', $plugin_dir_url . 'assets/js/Map.js', array('jquery', 'lodash-adt', 'tyto'), ADT_VERSION, true);
wp_enqueue_script( 'tyto', $plugin_dir_url . 'assets/js/TyTo.js', array('jquery', 'lodash-adt'), ADT_VERSION, true);
wp_enqueue_script( 'google-maps', '//maps.googleapis.com/maps/api/js?key='.$mapApiKey, array('jquery'), ADT_VERSION );

$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
$json = array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'postId' => get_the_ID(),
    'model' => str_replace('tyto', '', get_post_type(get_the_ID())),
    'stylesheetUri' => get_stylesheet_directory_uri(),
    'primaryColor' => get_theme_mod('accent_color'), // TODO primary color
    'airportIconPath' => $plugin_dir_url . '/assets/img/airport.png',
    'cachedWaypoints' => get_post_meta(get_the_ID(), '_cached_waypoints', true),
    'showMapPreview' => get_theme_mod('tour_show_map_preview', true),
    'singleWaypointZoom' => get_theme_mod('tour_map_zoom', 12),
    'showDistances' => get_theme_mod('tour_itinerary_show_distances', false),
);

if ($record) {
    $json['recordId'] = $record->id;
    $json['kmlFile'] = $record->kmlFile;
}

$mapPreview = \TyTo\Config::getValue( 'mappreview' );

wp_localize_script('tyto', 'TyToConfig', $json);

$btn_text = get_theme_mod('tour_map_btn_text', 'Karte ansehen');
?>
    <div id="map">
        <span class="show-map more"><?php echo $btn_text ?></span>
    </div>
    <style>
        #map {position: relative; width: 100%; height: 300px; background: url("<?php echo $mapPreview ? $mapPreview : $plugin_dir_url.'assets/img/map.jpg' ?>"); background-size: cover}
    </style>
<?php

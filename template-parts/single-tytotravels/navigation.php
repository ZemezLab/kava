<?php /*TOUR NAVIGATION*/ ?>
<?php
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
$travel_dates = get_query_var('travel_dates');
$style = get_query_var('style');
$accommodations = get_query_var('itinerary_accommodations');
$accommodations_title = get_theme_mod('tour_accommodations_title', 'Unterkuenfte');
if ($style == 'layout-1') $gallery_images_count = tyto_get_gallery_images_count($record);
?>
<div class="fake-navbox">
    <div class="tour-navbox" id="tour-nav">
        <div class="container">
            <!--						<div class="tour-nav row">-->
            <a href="#tour-link-info" class="tour-nav-item"><?php esc_html_e('Informationen', 'goto'); ?></a>
            <?php if (count($accommodations) && get_theme_mod('tour_show_accommodations', false) == true) { ?>
                <a href="#tour-link-accommodations"
                   class="tour-nav-item"><?php esc_html_e($accommodations_title, 'tyto'); ?></a>
            <?php } ?>
            <?php if (count($record->itinerary)) { ?>
                <a href="#tour-link-itinerary"
                   class="tour-nav-item"><?php esc_html_e('Reiseverlauf', 'tyto'); ?></a>
            <?php } ?>
            <?php if (($record->lat && $record->lng || count($record->itinerary)) && get_theme_mod('tour_map_position', 'content') == 'content') { ?>
                <a href="#tour-link-map" class="tour-nav-item"><?php esc_html_e('Karte', 'tyto'); ?></a>
            <?php } ?>
            <?php if ($travel_dates) { ?>
                <a href="#tour-link-dates"
                   class="tour-nav-item"><?php esc_html_e('Termine und Preise', 'tyto'); ?></a>
            <?php } ?>
            <?php if (isset($record->additionalOptions) && $record->additionalOptions) { ?>
                <a href="#tour-link-additional-options"
                   class="tour-nav-item"><?php echo get_theme_mod('additional_options_title', 'Optionen und Pakete') ?></a>
            <?php } ?>
            <?php if ($style == 'layout-1' && $gallery_images_count) { ?>
                <a href="#tour-link-gallery" class="tour-nav-item"><?php esc_html_e( 'Galerie', 'tyto' ); ?></a>
            <?php } ?>
            <?php /*VIDEO LIGHTBOX*/ ?>
            <?php if ($style == 'layout-2' && !empty($video_url)) {
                $video_url = get_query_var('video_url'); ?>
                <a class="tour-lightbox-btn video-preview" href="<?php echo esc_url($video_url); ?>"
                   data-lity><?php esc_html_e('Video', 'tyto'); ?></a>
            <?php } ?>
        </div>
    </div>
</div>
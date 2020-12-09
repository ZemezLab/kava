<?php /*TOUR NAVIGATION*/ ?>
<?php
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
$style = get_query_var('style');
$additionalFieldsLabels = get_query_var('additional_fields_labels');
if ($style == 'layout-1') $gallery_images_count = tyto_get_gallery_images_count($record);
?>
<div class="fake-navbox">
    <div class="tour-navbox" id="tour-nav">
        <div class="container">
            <a href="#tour-link-info" class="tour-nav-item"><?php esc_html_e('Informationen', 'goto'); ?></a>
            <?php
            if ($record->additionalFields && !empty($additionalFieldsLabels)) {
                foreach ($additionalFieldsLabels as $field_id => $field_label) {
                    if ($record->additionalFields->$field_id) {?>
                        <a href="#<?php echo $field_id ?>"
                           class="tour-nav-item"><?php echo $field_label; ?></a>
                        <?php
                    }
                }
            } ?>
            <?php if ($record->additionalOptions) { ?>
                <a href="#tour-link-additional-options" class="tour-nav-item"><?php echo get_theme_mod('accommodation_additional_options_title', 'Optionen und Pakete') ?></a>
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
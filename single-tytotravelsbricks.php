<?php
/**
 * The template for displaying all single accommodations
 *
 * @package Kava
 */
global $post;

get_header();

set_query_var('option_type', 'brick');
set_query_var('post_type', 'tytotravelsbricks');

$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
set_query_var('tytorawdata', $record);

$tyto_additional_fields = get_option('tyto_additional_fields', []);
$additionalFieldsLabels = [];
if (!empty($tyto_additional_fields)) {
    usort($tyto_additional_fields, function($a, $b) {
        if (empty($b['description'])) return 1;
        return intval($a['description']) <=> intval($b['description']);
    });

    foreach ($tyto_additional_fields as $r) {
        if (!($r['name'] == 'servicesIncluded' || $r['name'] == 'servicesExcluded' || $r['name'] == 'priority' || $r['name'] == 'preisbeispiel'))
            $additionalFieldsLabels[$r['name']] = $r['fieldLabel'];
    }
}
set_query_var('additional_fields_labels', $additionalFieldsLabels);

$video_url = $record->videoLink;
if ( !empty( $video_url ) ) {
    wp_enqueue_style('lity');
    wp_enqueue_script('jquery-lity');
    set_query_var('video_url', $video_url);
}
wp_enqueue_script('tour-single');

do_action( 'kava-theme/site/site-content-before', 'single' );

$css = '';
$style = get_theme_mod( 'single_brick_header_layout', 'layout-1' );
set_query_var('style', $style);

/* Header */
get_template_part('template-parts/header/tyto-'.$style);
?>
    <div <?php kava_content_class() ?>>
        <div class="row">

            <?php do_action( 'kava-theme/site/primary-before', 'single' ); ?>

            <div id="primary" <?php kava_primary_content_class(); ?>>

                <?php do_action( 'kava-theme/site/main-before', 'single' ); ?>

                <main id="main" class="site-main"><?php
                    while ( have_posts() ) : the_post();
                        kava_theme()->do_location( 'single', 'template-parts/single-tytotravelsbricks/content' );
                    endwhile; // End of the loop.
                    ?></main><!-- #main -->

                <?php do_action( 'kava-theme/site/main-after', 'single' ); ?>

            </div><!-- #primary -->

            <?php do_action( 'kava-theme/site/primary-after', 'single' ); ?>

            <?php get_sidebar(); // Loads the sidebar.php template.  ?>
        </div>
    </div>

<?php do_action( 'kava-theme/site/site-content-after', 'single' );
wp_enqueue_style('gotoicons');
wp_enqueue_script( 'sticky-sidebar' );
wp_enqueue_style('single-common');
wp_enqueue_style('single-tytotravelsbricks');
wp_localize_script('tour-single', 'TourVars', [
    'parallax' => $parallax,
    'header_video' => get_theme_mod('brick_header_video', false),
    'header_video_autoplay' => get_theme_mod('brick_header_video_autoplay', false),
    'start' => isset($video_query['start']) ? $query['start'] : false,
    'end' => isset($video_query['end']) ? $query['end'] : false,
    'videoUrl' => isset($video_url) ? $video_url : false,
]);
get_footer();
?>
<?php
/**
 * The template for displaying all single travels
 *
 * @package Kava
 */
global $post;
if (post_password_required()) {
    get_template_part('template-parts/tyto/content/s-login');
    die;
}

get_header();

set_query_var('option_type', 'tour');
set_query_var('post_type', 'tytotravels');

$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
set_query_var('tytorawdata', $record);

$travel_dates = false;
if (count($record->dates)) {
    $now = new DateTime();
    foreach ($record->dates as $item) {
        $date = date_create($item->end);
        if ($now < $date && $item->price) {
            $travel_dates[] = $item;
        }
    }
}
set_query_var('travel_dates', $travel_dates);

$video_url = $record->videoLink;
if ( !empty( $video_url ) ) {
    wp_enqueue_style('lity');
    wp_enqueue_script('jquery-lity');
    set_query_var('video_url', $video_url);
}
wp_enqueue_script('tour-single');

$accommodations = tyto_get_all_itinerary_accommodations($record);
set_query_var('itinerary_accommodations', $accommodations);

do_action( 'kava-theme/site/site-content-before', 'single' );

$style = get_theme_mod( 'single_tour_header_layout', 'layout-1' );
set_query_var('style', $style);

/* Header */
get_template_part('template-parts/header/tyto-'.$style); ?>
    <div <?php kava_content_class() ?>>
        <div class="row">

            <?php do_action( 'kava-theme/site/primary-before', 'single' ); ?>

            <div id="primary" <?php kava_primary_content_class(); ?>>

                <?php do_action( 'kava-theme/site/main-before', 'single' ); ?>

                <main id="main" class="site-main"><?php
                    while ( have_posts() ) : the_post();
                        kava_theme()->do_location( 'single', 'template-parts/single-tytotravels/content' );
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
wp_enqueue_style('single-tytotravels');
wp_localize_script('tour-single', 'TourVars', [
    'parallax' => $parallax,
    'header_video' => get_theme_mod('tour_header_video', false),
    'header_video_autoplay' => get_theme_mod('tour_header_video_autoplay', false),
    'start' => isset($video_query['start']) ? $query['start'] : false,
    'end' => isset($video_query['end']) ? $query['end'] : false,
    'videoUrl' => isset($video_url) ? $video_url : false,
]);
get_footer();
?>
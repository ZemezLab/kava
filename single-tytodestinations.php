<?php
/**
 * The template for displaying all single destination
 *
 * @package Kava
 */
get_header();
do_action( 'kava-theme/site/site-content-before', 'single' );
$css = '';

$img_src = get_post_meta(get_the_ID(), 'header_image', true);
if (empty($img_src)) {
    $img_id     = get_post_thumbnail_id( get_the_ID() );
    $img_src    = ! empty( $img_id ) ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
}
 /*HEADER COVER IMAGE*/
    if ($img_src)
        $css     .= '.header-cover-image{background-image: url('. esc_url( $img_src ) .')}';
    else
        $css     .= '.header-cover-image{height: auto}';
    /*PARALLAX*/
    $parallax        = get_theme_mod( 'header_parallax', true );
    $parallax_speed  = get_theme_mod( 'header_parallax_speed', true );
    $parallax_output = '';
    if( true == $parallax ) $parallax_output = 'id="page-header-parallax" data-speed="' . absint( $parallax_speed ) . '"'; ?>
    <div class="header-cover-image page-header" <?php echo wp_kses_post( $parallax_output ); ?>>
        <div class="container">
            <h1 class="page-title entry-title"><?php echo esc_html($post->post_title); ?></h1>
        </div>
    </div>
    <style><?php echo $css ?></style>
    <div <?php kava_content_class() ?>>
        <div class="row">

            <!--            --><?php //do_action( 'kava-theme/site/primary-before', 'single' ); ?>

            <div id="primary" <?php kava_primary_content_class(); ?>>

                <!--                --><?php //do_action( 'kava-theme/site/main-before', 'single' ); ?>

                <main id="main" class="site-main"><?php
                    while ( have_posts() ) : the_post();
                        kava_theme()->do_location( 'single', 'template-parts/content-tytodestinations' );
                    endwhile; // End of the loop.
                    ?></main><!-- #main -->

                <?php do_action( 'kava-theme/site/main-after', 'single' ); ?>

            </div><!-- #primary -->

            <?php do_action( 'kava-theme/site/primary-after', 'single' ); ?>

            <?php get_sidebar(); // Loads the sidebar.php template.  ?>
        </div>
    </div>

<?php do_action( 'kava-theme/site/site-content-after', 'single' );

get_footer();

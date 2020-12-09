<?php
/* HEADER IMAGE*/
$record = get_query_var('tytorawdata');
$breadcrumbs_html = tyto_get_destination_breadcrumbs($record);
$option_type = get_query_var('option_type');
$post_type = get_query_var('post_type');
$img_src = s_header_images_handling(1900, 800);
$css     .= '.header-cover-image{background-image: url('. esc_url( $img_src ) .')}';
/*PARALLAX*/
$parallax_output = '';
$parallax        = get_theme_mod( 'header_parallax', true );
$parallax_speed  = get_theme_mod( 'header_parallax_speed', true );
$css     .= '.header-cover-image{background-image: url('. esc_url( $img_src ) .')}';

if (get_theme_mod('header_text_shadow', false)) {
    $css .= '.page-header .page-title, .page-header .breadcrumbs-item { text-shadow: 1px 1px 6px rgba(0,0,0,.94); }';
}
switch (get_theme_mod('header_horizontal_align', 'center')) {
    case 'flex-start': $css .= '.page-header .page-title { text-align: left; }'; break;
    case 'center': $css .= '.page-header .page-title { text-align: center; }'; break;
    case 'flex-end': $css .= '.page-header .page-title { text-align: right; }'; break;
    default: $css .= '.page-header .page-title { text-align: center; }'; break;
}

if (get_theme_mod($option_type.'_header_video', false) == true && !empty($video_url)) {
    $parts = parse_url($video_url);
    parse_str($parts['query'], $video_query);
    wp_enqueue_script('jarallax');
    wp_enqueue_script('jarallax-video');
} else {
    if( true == $parallax ){
        $parallax_output .= 'id="page-header-parallax" data-speed="' . absint($parallax_speed) . '"';
    }
} ?>
<?php /*HEADER COVER IMAGE*/ ?>
    <div class="header-cover-image page-header" <?php echo wp_kses_post($parallax_output); ?>>
        <div class="container">
            <h1 class="page-title entry-title"><?php echo esc_html(get_the_title()); ?></h1>
            <?php echo $breadcrumbs_html ?>
            <?php /*VIDEO LIGHTBOX*/ ?>
            <?php if (get_theme_mod($option_type.'_header_video', false) == false && !empty($video_url)) { ?>
                <a class="tour-lightbox-btn video-preview" href="<?php echo esc_url($video_url); ?>"
                   data-lity><?php esc_html_e('Video', 'goto'); ?></a>
            <?php } ?>
            <?php if (get_theme_mod($option_type.'_header_video', false) == true
                && !get_theme_mod($option_type.'_header_video_autoplay', false)
                && $video_url) { ?>
                <i class="fa fa-play-circle"></i>
            <?php } ?>
        </div>
    </div>
<?php get_template_part('template-parts/single-'.$post_type.'/navigation')?>
<style><?php echo $css ?></style>


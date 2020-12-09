<?php
$record = get_query_var('tytorawdata');
$breadcrumbs_html = tyto_get_destination_breadcrumbs($record);
$option_type = get_query_var('option_type');
$post_type = get_query_var('post_type');
$css = '';
if (get_theme_mod($option_type.'_header_images_darken', true) == false) {
    $css .= '#tour-3-slider .tour-3-slide-item:before{background-color: transparent}';
} else {
    $css .= '#tour-3-slider .tour-3-slide-item:before{background-color: rgba(36, 36, 41, 0.3);}';
}
/*GALLERY*/
$images_count = tyto_get_gallery_images_count($record);
if( $images_count ):
    $slider_items_count = $images_count < 3 ? $images_count : 3;
    $gallery_img_array = tyto_get_gallery_images($record, ceil(2000/$slider_items_count), 1400);
    wp_enqueue_style( 'tiny-slider' );
    wp_enqueue_script( 'tiny-slider-js' );
    wp_add_inline_script(
        'tiny-slider-js',
        "document.addEventListener( 'DOMContentLoaded', function(){
							var slider = tns({
								container: '#tour-3-slider',
								loop: false,
								lazyload: true,
								items: ".$slider_items_count.",
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
										items: ".$slider_items_count.",
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
            foreach( $gallery_img_array as $key => $value ):
                $gallery_img_divided = explode('{{{}}}', $value);
                $gallery_thumbnail_url = $gallery_img_divided[0];
                $gallery_original_url = $gallery_img_divided[1];
                ?>
                <a href="<?php echo esc_url( $gallery_original_url ); ?>"
                   class="tour-3-slide-item"
                   data-fancybox="header-gallery"
                   data-options='{"backFocus" : false}'>
                    <img src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                         data-src="<?php echo esc_url( $gallery_thumbnail_url ); ?>"
                         class="tns-lazy tns-lazy-img" alt="">
                </a>
            <?php endforeach; ?>
        </div>
        <div class="container">
            <?php /*VIDEO LIGHTBOX*/ ?>
            <?php if( !empty( $video_url ) ):  ?>
                <a class="tour-lightbox-btn video-preview" href="<?php echo esc_url( $video_url ); ?>" data-lity><?php esc_html_e( 'Video', 'tyto' ); ?></a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php /*TOUR TITLE*/ ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="tour-title-box">
                    <?php echo $breadcrumbs_html ?>
                    <h1 class="tour-title"><?php echo esc_html(get_the_title()); ?></h1>
                </div>
            </div>
        </div>
    </div>
<?php get_template_part('template-parts/single-'.$post_type.'/navigation')?>
<?php if ($css) echo '<style>'.$css.'</style>';

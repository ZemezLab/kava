<?php
$record = json_decode(get_post_meta(get_the_ID(), 'tytorawdata', true));
$images_array = [];
if(count($record->images) > 0){
    foreach($record->images as $key=>$value){
        if(strpos($value->image, 'unsplash')){
            $unsplash_img_array = explode('?', $value->image);
            $images_array[] = $unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w=1920';
        } else {
            $img_options = array(
                "secure" => true,
                "width" => 1920
            );

            if ('http' === substr($value->image, 0, 4)) {$img_options['type'] = 'fetch';}
            $images_array[] = \Cloudinary::cloudinary_url($value->image, $img_options);
        }
    }
}
foreach($record->itinerary as $value){
    foreach($value->brick->images as $data){
        if(strpos($data->image, 'unsplash')){
            $unsplash_img_array = explode('?', $data->image);
            $images_array[] = $unsplash_img_array[0].'?fm=jpg&crop=focalpoint&fit=crop&w=1920';
        } else {
            $img_options = array(
                "secure" => true,
                "width" => 1920
            );

            if ('http' === substr($data->image, 0, 4)) { $img_options['type'] = 'fetch'; }
            $images_array[] = \Cloudinary::cloudinary_url($data->image, $img_options);
        }
    }
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php wp_head(); ?>
</head>
<style>
    html, body {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        overflow: hidden;
    }

    #background-1, #background-2 {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-repeat: no-repeat;
        background-color: transparent;
        background-position: center center;
        color: #fff;
        text-shadow: 1px 1px 2px #000;
        font-size: 22px;
        font-weight: 400;
        padding: 2em 2em;
        transition: opacity 2s ease-in-out 0s;
        /*animation: pulse 24s linear infinite;*/
    }

    .post-login-form {
        transform: translate(-50%, -50%);
        position: absolute;
        top: 50%;
        left: 50%;
        background-color: rgba(0,0,0,0.6);
        padding: 2em;
        text-align: center;
        color: #fff;
        max-width: 98%;
        border-radius: 4px;
    }

    .post-login-form h2 {
        color: #fff;
    }
    .post-login-form .post-password-form label,
    .post-login-form .post-password-form input[type="submit"] {
        float: none;
        display: inline-block;
        vertical-align: bottom;
    }

    .post-login-form .post-password-form label {
        width: 100%;
        color: #fff;
    }

    .post-password-form input[type="password"] {
        min-width: 300px;
        margin-top: 10px;
        float: none;
    }

    .post-login-form .box-header {
        text-align: center;
    }

    input, button, submit { border:none; }

    /*@keyframes pulse {*/
    /*    0% {*/
    /*        transform: scale(1);*/
    /*    }*/

    /*    50% {*/
    /*        transform: scale(1.1);*/
    /*    }*/

    /*    100% {*/
    /*        transform: scale(1);*/
    /*    }*/
    /*}*/
    @media (max-width: 660px) {
        .post-password-form input[type="password"] {
            max-width: 100%;
            min-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        .post-login-form {
            width: 98%;
        }
    }
</style>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
<div id="background-1" class="background-bottom" style="opacity: 1; background-size: cover; background-image: url('<?php echo $images_array[0] ?>');"></div>
<div id="background-2" class="background-bottom" style="opacity: 0; background-size: cover; background-image: url('<?php echo $images_array[1] ?>');"></div>
<div class="content-wrapper  w-container center post-login-form">
    <div class="vc_row wpb_row vc_row-fluid page-padding-top-section vc_custom_1551351493218 vc_row-has-fill full-width">
        <div class="wpb_column vc_column_container vc_col-sm-12">
            <div class="wpb_wrapper">
                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                    <div class="wpb_column vc_column_container vc_col-sm-12 align-center page-padding-bottom-section">
                        <h2 class="box-header">Ihr individuelles Angebot</h2>
                        <?php
                        $label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
                        $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
	                    <p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
	                    <p><label for="' . $label . '">' . __( 'Password:' ) . '<br><input name="post_password" id="' . $label . '" type="password" size="20" /></label></p>
	                    <p><input class="tyto-button tyto-button-primary elementor-button" type="submit" name="Submit" value="' . esc_attr_x( 'Start', 'post password form' ) . '" /></p></form>';
                        echo $output; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    var images = <?php echo json_encode($images_array)?>;
    if (images.length) {
        var i = 0; j = 1;
        window.setInterval(function(){
            if (images[i+1]) i += 1; else i = 0;

            jQuery("#background-"+j).css({'opacity': 0});
            if (j == 2) j = 1; else j = 2;
            jQuery("#background-"+j).css({'background-image': 'url('+images[i]+')', 'opacity': 1});

        }, 6000);
    }
</script>
</html>

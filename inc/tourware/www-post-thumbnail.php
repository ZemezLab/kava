<?php

/*
MIT License (MIT)
Copyright (c) 2014 Giuseppe Mazzapica
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

add_filter( 'admin_post_thumbnail_html', 'WWWPostThumbnail_field' );

add_action( 'save_post', 'WWWPostThumbnail_save', 10, 2 );

add_filter( 'post_thumbnail_html', 'WWWPostThumbnail_markup', 10, PHP_INT_MAX );

function WWWPostThumbnail_field( $html ) {
    global $post;
    $value = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE ) ? : "";
    $nonce = wp_create_nonce( 'thumbnail_ext_url_' . $post->ID . get_current_blog_id() );
    $html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' . esc_attr( $nonce ) . '">';
    $html .= '<div><p>' . __('Or', 'www-post-thumb') . '</p>';
    $html .= '<p>' . __( 'Enter the url for external featured image (first image from Cloud by default)', 'www-post-thumb' ) . '</p>';
    $html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '"></p>';
    if ( ! empty($value) ) {
        $html .= '<p><img style="max-width:150px;height:auto;" src="' . esc_url($value) . '"></p>';
        $html .= '<p>' . __( 'Leave url blank to remove.', 'www-post-thumb' ) . '</p>';
    }
    $html .= '</div>';
    return $html;
}

function WWWPostThumbnail_save( $pid, $post ) {
    $cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
    if (
        ! current_user_can( $cap, $pid )
        || ! post_type_supports( $post->post_type, 'thumbnail' )
        || defined( 'DOING_AUTOSAVE' )
    ) {
        return;
    }
    $action = 'thumbnail_ext_url_' . $pid . get_current_blog_id();
    $nonce = filter_input( INPUT_POST,  'thumbnail_ext_url_nonce', FILTER_SANITIZE_STRING );
    $url = filter_input( INPUT_POST,  'thumbnail_ext_url', FILTER_VALIDATE_URL );
    if (empty( $nonce ) || ! wp_verify_nonce( $nonce, $action )) {
        return;
    }
    if ( ! empty( $url ) ) {
        update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
        update_post_meta( $pid, '_thumbnail_id', '999999' );
    } elseif ( get_post_meta( $pid, '_thumbnail_ext_url', TRUE ) ) {
        delete_post_meta( $pid, '_thumbnail_ext_url' );
        if ( get_post_meta( $pid, '_thumbnail_id', TRUE ) === '999999' ) {
            delete_post_meta( $pid, '_thumbnail_id' );
        }
    }
}

function WWWPostThumbnail_markup( $html, $post_id ) {
    $url =  get_post_meta( $post_id, '_thumbnail_ext_url', TRUE );
    if ( empty( $url ) ) {
        return $html;
    }
    $alt = get_post_field( 'post_title', $post_id ) . ' ' .  __( 'thumbnail', 'www-post-thumb' );
    $attr = array( 'alt' => $alt );
    $attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, NULL );
    $attr = array_map( 'esc_attr', $attr );
    $html = sprintf( '<img src="%s"', esc_url($url) );
    foreach ( $attr as $name => $value ) {
        $html .= " $name=" . '"' . $value . '"';
    }
    $html .= ' />';
    return $html;
}

function WWWPostThumbnail_get_external_image_src($attachment_id, $size) {
    $src = '';
    if ($attachment_id == 999999) {
        $post = get_post();
        $thumb_id = get_post_meta($post->ID, '_thumbnail_id', true);
        if ($thumb_id == '999999') {
            global $_wp_additional_image_sizes;
            if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
                $w = get_option("{$size}_size_w");
            } elseif (is_array($_wp_additional_image_sizes) && !empty($_wp_additional_image_sizes[$size])) {
                $w = $_wp_additional_image_sizes[$size]['width'];
            }
            if (empty($w)) $w = 600;
            $src = get_post_meta($post->ID, '_thumbnail_ext_url', true);
            if ($size !== 'full') $src = str_replace('/g_center', '/g_center/w_' . $w, $src);
        }
    }
    return $src;
}

add_filter('wp_get_attachment_image_src', 'WWWPostThumbnail_attachment_src', 999, 3);
function WWWPostThumbnail_attachment_src($image, $attachment_id, $size) {
    $src = WWWPostThumbnail_get_external_image_src($attachment_id, $size);
    if (!empty($src)) $image[0] = $src;

    return $image;
}

add_filter('elementor/image_size/get_attachment_image_html', 'WWWPostThumbnail_elementor_attachment_html', 999, 4);
function WWWPostThumbnail_elementor_attachment_html($html, $settings, $image_size_key, $image_key) {
    $image = $settings[ $image_key ];
    $attachment_id = $image['id'];
    $size = $settings[ $image_size_key . '_size' ];
    $src = WWWPostThumbnail_get_external_image_src($attachment_id, $size);

    if ( ! empty( $src ) ) {
        $image_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';
        $image_class_html = ! empty( $image_class ) ? ' class="' . $image_class . '"' : '';
        $html = sprintf( '<img src="%s" class="%s" />', esc_attr( $src ), $image_class_html );
    }

    return $html;
}

<?php
$theme_mod_prefix = get_query_var('theme_mod_prefix');
$position = get_query_var('related_position');
$rel_post_data = '';
$rel_post_data = json_decode(get_post_meta($post->ID, 'tytorawdata', true));
$item = [
    'title' => $post->post_title,
    'img' => '',
    'link' => get_the_permalink($post->ID)
];
if (isset($rel_post_data->images) && count($rel_post_data->images)) {
    $item['img'] = $rel_post_data->images[0]->image;
}
if (!empty($item)) {
    $show_excerpt = get_theme_mod($theme_mod_prefix . '_related_show_excerpt'.$position, true);
    if ($show_excerpt) {
        $excerpt_limit = get_theme_mod($theme_mod_prefix . '_related_excerpt_limit'.$position, 100);
        $tags_to_strip = ['img'];
        $description = $rel_post_data->description;
        foreach ($tags_to_strip as $tag)
            $description = preg_replace('/<\\/?' . $tag . '(.|\\s)*?>/', '', $description);
    }

    $img_src = '';
    if ($item['img']) {
        $img_options = array(
            "secure" => true,
            "width" => 500,
            "height" => 400,
            "crop" => "thumb"
        );

        if ('http' === substr($item['img'], 0, 4) || 'http' === substr($item['img'], 0, 4)) {
            $img_options['type'] = 'fetch';
        }
        $img_src = \Cloudinary::cloudinary_url($item['img'], $img_options);
    } ?>
    <div class="ht-grid-item">
        <div class="tour-related-item">
            <div class="tour-related-item-wr">
                <?php if ($img_src) { ?>
                    <a class="tour-related-item-head" <?php if ($item['link']) echo 'href="' . $item['link'] . '"' ?>>
                        <img src="<?php echo $img_src ?>"
                             alt="<?php echo esc_attr($item['title']) ?>">
                    </a>
                <?php } ?>
                <div class="tour-related-item-sum">
                    <h4 class="trs-title"><a
                            <?php if ($item['link']) echo 'href="' . $item['link'] . '"' ?>><?php echo esc_html($item['title']) ?></a>
                    </h4>
                    <?php if ($show_excerpt) { ?>
                    <div class="tlc-summary">
                        <?php echo tyto_text_truncate($description, $excerpt_limit, ['html' => true]); ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php }

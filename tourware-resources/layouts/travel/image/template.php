<?php use Elementor\Group_Control_Image_Size;
$repository = \Tourware\Repository\Travel::getInstance();
$item_data = $repository->findOneByPostId($post);

$size = $settings['image_size'];
global $_wp_additional_image_sizes;
if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
    $w = get_option("{$size}_size_w");
    $h = get_option("{$size}_size_h");
} elseif (is_array($_wp_additional_image_sizes) && !empty($_wp_additional_image_sizes[$size])) {
    $w = $_wp_additional_image_sizes[$size]['width'];
    $h = $_wp_additional_image_sizes[$size]['height'];
}
if (empty($w)) $w = 150;

$img_options = array(
    "secure" => true,
    "crop" => "thumb"
);
if ($size !== 'full') {
    $img_options['width'] = $w;
    if (!empty($h)) $img_options['height'] = $h;
}

if ($settings['type'] == 'contact') {
    $user = $item_data->getResponsibleUser();
    $img = $user->photo;
    $img_src = \Cloudinary::cloudinary_url($img, $img_options);
} elseif ($settings['type'] == 'featured') {
    if ($item_data->hasFeaturedImageUri())
        $img_src = $item_data->getFeaturedImageUri($img_options);
}
if ($img_src) {
    $settings['image']['url'] = $img_src;
    $this->add_render_attribute('wrapper', 'class', 'elementor-image'); ?>
    <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
        <?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'image'); ?>
    </div>
<?php }
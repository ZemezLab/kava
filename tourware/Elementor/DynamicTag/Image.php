<?php

namespace Tourware\Elementor\DynamicTag;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Data_Tag;

class Image extends Data_Tag {
    /**
     * Get Name
     *
     * Returns the Name of the tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return string
     */
    public function get_name() {
        return 'record-image';
    }

    /**
     * Get Title
     *
     * Returns the title of the Tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return string
     */
    public function get_title() {
        return __( 'Record Image', 'elementor-pro' );
    }

    /**
     * Get Group
     *
     * Returns the Group of the tag
     *
     * @since 2.0.0
     * @access public
     *
     * @return string
     */
    public function get_group() {
        return 'tourware';
    }

    /**
     * Get Categories
     *
     * Returns an array of tag categories
     *
     * @since 2.0.0
     * @access public
     *
     * @return array
     */
    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY,
//            \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY,
            \Elementor\Modules\DynamicTags\Module::MEDIA_CATEGORY
        ];
    }

    /**
     * Register Controls
     *
     * Registers the Dynamic tag controls
     *
     * @since 2.0.0
     * @access protected
     *
     * @return void
     */
    protected function _register_controls() {
        $this->add_control(
            'img_index',
            [
                'label' => __( 'Index', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
            ]
        );

        $this->add_control(
            'fallback',
            [
                'label' => __( 'Fallback', 'elementor-pro' ),
                'type' => Controls_Manager::MEDIA,
            ]
        );
    }

    public function get_value( array $options = [] ) {
        $index = $this->get_settings( 'img_index' );
        if ( $index === false || is_null($index) ) {
            return [];
        }

        if (get_post_type() === 'tytotravels') {
            $repository = \Tourware\Repository\Travel::getInstance();
        } else if (get_post_type() === 'tytoaccommodations') {
            $repository = \Tourware\Repository\Accommodation::getInstance();
        }

        if ( ! $repository ) {
            return [];
        }

        $record = $repository->findOneByPostId(get_the_ID());

        global $_wp_additional_image_sizes;
        $size = $this->get_settings('image_size');
        if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
            $w = get_option("{$size}_size_w");
            $h = get_option("{$size}_size_h");
        } elseif (is_array($_wp_additional_image_sizes) && !empty($_wp_additional_image_sizes[$size])) {
            $w = $_wp_additional_image_sizes[$size]['width'];
            $h = $_wp_additional_image_sizes[$size]['height'];
        }
        if (empty($w)) $w = 1920;

        $img_options = [
            'secure' => true,
            'crop' => 'thumb',
            'width' => $w
        ];
        if ($h) $image_uri['h'] = $h;
        $image_uri = $record->getImageUri($index, $img_options);

        if ( $image_uri ) {
            $image_data = [
                'id' => 'record_image-'.$index,
                'url' => $image_uri,
            ];
        } else {
            $image_data = $this->get_settings( 'fallback' );
        }

        return $image_data;
    }


}
add_filter('wp_get_attachment_image_src', function ( $image, $attachment_id, $size) {
    if (strpos($attachment_id, 'record_image') !== false) {
        $parts = explode('-', $attachment_id);
        $index = $parts[1];
        if (get_post_type() === 'tytotravels') {
            $repository = \Tourware\Repository\Travel::getInstance();
        } else if (get_post_type() === 'tytoaccommodations') {
            $repository = \Tourware\Repository\Accommodation::getInstance();
        }

        if (!$repository) {
            return [];
        }

        $record = $repository->findOneByPostId(get_the_ID());

        global $_wp_additional_image_sizes;
        if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
            $w = get_option("{$size}_size_w");
            $h = get_option("{$size}_size_h");
        } elseif (is_array($_wp_additional_image_sizes) && !empty($_wp_additional_image_sizes[$size])) {
            $w = $_wp_additional_image_sizes[$size]['width'];
            $h = $_wp_additional_image_sizes[$size]['height'];
        }
        if (empty($w)) $w = 1920;

        $img_options = [
            'secure' => true,
            'crop' => 'thumb',
            'width' => $w
        ];
        if ($h) $image_uri['h'] = $h;
        $src = $record->getImageUri($index, $img_options);
        if ($size !== 'full') $src = str_replace('/g_center', '/g_center/w_' . $w, $src);

        if (!empty($src)) $image[0] = $src;
        $image[0] = $src;
    }

    return $image;
}, 10, 3);

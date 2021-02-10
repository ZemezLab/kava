<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget\Image\AbstractImage;

class Image extends AbstractImage {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-image';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Image' );
    }

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytotravels';
    }

    /**
     * @return string
     */
    protected function getRecordTypeName()
    {
        return 'travel';
    }

    /**
     * @return string
     */
    protected function getWidgetName()
    {
        return 'image';
    }

    protected function _register_controls()
    {
        $this->start_controls_section('image_type', [
            'label' => esc_html__('Image Type')
        ]);

        $this->add_control('type', [
            'label' => __( 'Type', 'elementor' ),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'contact' => __( 'Contact', 'elementor' ),
                'featured' => __( 'Featured', 'elementor' ),
            ],
        ]);

        $this->end_controls_section();

        parent::_register_controls();
    }
}

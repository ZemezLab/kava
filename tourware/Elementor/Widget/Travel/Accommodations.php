<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget\Accordion\AbstractAccordion;

class Accommodations extends AbstractAccordion {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-accommodations';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Accommodations' );
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
        return 'accommodations';
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'accommodations_options',
            [
                'label' => __( 'Options', 'elementor-pro' )
            ]
        );

        $this->add_control(
            'meta_data',
            [
                'label' => __( 'Meta Data', 'elementor-pro' ),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'default' => [ 'date', 'comments' ],
                'multiple' => true,
                'options' => [
                    'nights' => __( 'Nights', 'tourware' ),
                    'meals' => __( 'Meals', 'tourware' ),
                    'room' => __( 'Room', 'tourware' ),

                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->addControlGroup(['id' => 'style_image', 'type' => 'image']);

        parent::_register_controls();
    }

}

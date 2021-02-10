<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Tourware\Elementor\Widget\Accordion\AbstractAccordion;

class AdditionalFields extends AbstractAccordion {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-additional-fields';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Additional Fields' );
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
        return 'additional-fields';
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'additional_fields_section',
            [
                'label' => __('Additional Fields', 'tourware'),
            ]
        );

        $additional_fields = wp_list_pluck(get_option('tyto_additional_fields'), 'fieldLabel', 'name');
        $repeater = new Repeater();
        $repeater->add_control(
            'field',
            [
                'label' => __( 'Field', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'placeholder' => __( 'List Item', 'elementor' ),
                'default' => __( 'List Item', 'elementor' ),
                'dynamic' => [
                    'active' => true,
                ],
                'options' => $additional_fields
            ]
        );

        $this->add_control(
            'additional_fields',
            [
                'label' => __( 'Items', 'elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ field }}}',
            ]
        );

        $this->end_controls_section();
        parent::_register_controls();

    }
}


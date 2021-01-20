<?php

namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget\Listing\AbstractListing;

class Listing extends AbstractListing
{

    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-listing';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Listing' );
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
     * @return array
     */
    protected function getCardLayoutOptions() {
//        return array_merge($this->defaultCardLayoutOptions, ['duration', 'persons']);
        return ['title', 'badge', 'price', 'excerpt', 'readmore', 'duration', 'persons', 'destination', 'categories'];
    }

    protected function optionsDuration() {
        $this->add_control(
            'heading_duration_options',
            [
                'label' => __( 'Duration', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_duration',
            [
                'label' => __( 'Show', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
            ]
        );

        $this->add_control( 'duration_prefix', array(
            'label'         =>  esc_html__( 'Prefix', 'tourware' ),
            'type'          =>  Controls_Manager::TEXT,
            'default'       =>  '',
            'condition' => ['show_duration' => 'yes']
        ));
    }

    protected function optionsPersons() {
        $this->add_control(
            'heading_persons_options',
            [
                'label' => __( 'Persons', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_persons',
            [
                'label' => __( 'Show', 'tourware' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
            ]
        );

        $this->add_control( 'persons_suffix', array(
            'label'         =>  esc_html__( 'Suffix', 'tourware' ),
            'type'          =>  Controls_Manager::TEXT,
            'default'       =>  '',
            'condition' => ['show_persons' => 'yes']
        ));
    }


}
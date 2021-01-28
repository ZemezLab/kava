<?php

namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Elementor\Repeater;
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
        return ['title', 'badge', 'price', 'excerpt', 'readmore', 'duration', 'persons', 'destination', 'categories', 'scores', 'flight'];
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

    protected function optionsScores()
    {
        $this->add_control(
            'heading_scores_options',
            [
                'label' => __( 'Scores', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_scores',
            [
                'label' => __( 'Show', 'tourware' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => '',
            ]
        );

        $tags = ($tyto_tags = get_option('tyto_tags', false)) ? wp_list_pluck($tyto_tags, 'name', 'name') : [];
        $repeater = new Repeater();
        $repeater->add_control(
            'score_tags',
            [
                'label' => __( 'Tags', 'elementor' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'placeholder' => __( 'Tags', 'elementor' ),
                'default' => __( '', 'elementor' ),
                'multiple' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'options' => $tags
            ]
        );

        $repeater->add_control(
            'score_icon',
            [
                'label' => __( 'Icon', 'elementor' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_control(
            'scores',
            [
                'label' => __( 'Items', 'elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ elementor.helpers.renderIcon( this, score_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ score_tags }}}',
                'condition' => ['show_scores' => 'yes']
            ]
        );
    }

    protected function optionsFlight()
    {
        $this->add_control(
            'heading_flight_options',
            [
                'label' => __( 'Flight', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_flight',
            [
                'label' => __( 'Show', 'tourware' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => '',
            ]
        );

        $tags = ($tyto_tags = get_option('tyto_tags', false)) ? wp_list_pluck($tyto_tags, 'name', 'name') : [];
        $this->add_control(
            'flight_tags',
            [
                'label' => __( 'Tags', 'elementor' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'placeholder' => __( 'Tags', 'elementor' ),
                'default' => __( '', 'elementor' ),
                'multiple' => true,
                'dynamic' => [
                    'active' => true,
                ],
                'options' => $tags,
                'condition' => ['show_flight' => 'yes']
            ]
        );
    }
}
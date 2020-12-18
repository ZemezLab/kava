<?php

namespace ElementorTyto\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Plugin;
use \Elementor\Core\Schemes as Schemes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Widget_Tyto_MainInformation extends Widget_Base
{
    public function __construct( $data = [], $args = null ) {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'tyto-main-information';
    }

    public function get_title()
    {
        return __('Main Information');
    }

    public function get_icon()
    {
        return 'eicon-table-of-contents';
    }

    public function get_categories()
    {
        return ['tyto'];
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();
        $record = json_decode(get_post_meta($post, 'tytorawdata', true));

        if ($record->description || $record->subtitle) { ?>
            <div class="travel-section travel-main-information">
                <h5 class="tour-section-title subtitle">
                    <?php echo (!$settings['show_subtitle'] || !$settings['subtitle_header']) ? $settings['title'] : esc_html($record->subtitle); ?></h5>
                <?php if ($settings['show_subtitle'] && !$settings['subtitle_header'] && isset($record->subtitle)) { ?>
                    <h6 class="subtitle"><?php echo $record->subtitle ?></h6>
                <?php } ?>
                <?php if ($settings['show_description'] && $record->description) { ?>
                    <div class="description"><?php echo $record->description ?></div>
                <?php } ?>
            </div>
        <?php }
    }



    protected function _register_controls()
    {
        $this->start_controls_section('options', array(
            'label' => esc_html__('Options'),
        ));

        $posts = wp_list_pluck(get_posts(['post_type' => ['tytotravels', 'tytoaccommodations'], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'tyto'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), ['tytotravels', 'tytoaccommodations']) ? get_the_ID() : ''
            ]

        );

        $this->add_control('title',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __('Title', 'tyto'),
                'default' => __('Information', 'tyto'),
                'condition' => [ 'subtitle_header!' => 'yes' ]
            ]
            );

        $this->add_control(
            'show_subtitle',
            [
                'label' => __( 'Subtitle', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => 'Subtitle Typography',
                'name' => 'subtitle_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_2,
                'selector' => '{{WRAPPER}} .subtitle',
                'condition' => ['subtitle_header!' => 'yes', 'show_subtitle' => 'yes']
            ]
        );

        $this->add_control(
            'subtitle_header',
            [
                'label' => __( 'Subtitle as Header', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'tyto' ),
                'label_off' => __( 'No', 'tyto' ),
                'default' => '',
                'return' => 'yes',
                'condition' => ['show_subtitle' => 'yes']
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => __( 'Description', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => 'Description Typography',
                'name' => 'description_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .description',
                'condition' => ['show_description' => 'yes']
            ]
        );

        $this->end_controls_section();
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new Widget_Tyto_MainInformation());
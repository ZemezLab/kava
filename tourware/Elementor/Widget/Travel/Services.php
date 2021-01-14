<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget;
use \Elementor\Core\Schemes as Schemes;

class Services extends Widget {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-services';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Services' );
    }

    protected function _register_controls()
    {
        $this->start_controls_section('options', array(
            'label' => esc_html__('Options'),
        ));

        $posts = wp_list_pluck(get_posts(['post_type' => ['tytotravels'], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'tourware'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), ['tytotravels']) ? get_the_ID() : ''
            ]
        );

        $this->add_control(
            'list_type',
            [
                'label' => __('List of', 'tourware'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'servicesIncluded'  => __('Included Services', 'tourware'),
                    'servicesExcluded'  => __('Excluded Services', 'tourware'),
                    'highlights'        => __('Highlights', 'tourware'),
                    'servicesNote'        => __('Services Notes', 'tourware'),
                ],
                'default' => 'servicesIncluded'
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => 'Content Typography',
                'name' => 'content_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .list-content',
            ]
        );

        $this->add_control( 'icon', array(
            'label'         =>  esc_html__( 'Icon', 'tourware' ),
            'type'          =>  Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-check',
                'library' => 'fa-solid',
            ],
            'separator' => 'before'
        ));

        $this->add_control( 'icon_color', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Icon Color', 'tourware' ),
            'default'   => 'green',
            'selectors' => array(
                '{{WRAPPER}} li .icon' => 'color: {{VALUE}};',
            ),
        ));

        $this->add_control(
            'icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon' => 'margin-right: {{SIZE}}{{UNIT}}',
                ],
                'default' => [
                    'size' => 10,
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();
        $record = json_decode(get_post_meta($post, 'tytorawdata', true));
        $list_type = $settings['list_type'];
        $list = $record->$list_type;
        if ($list) {
            include \Tourware\Path::getResourcesFolder() . 'layouts/travel/services/template.php';
        }
    }

}

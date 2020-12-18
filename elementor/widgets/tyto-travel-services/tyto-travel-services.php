<?php

namespace ElementorTyto\Widgets;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Plugin;
use \Elementor\Core\Schemes as Schemes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Widget_Tyto_TravelServices extends Widget_Base
{
    public function __construct( $data = [], $args = null ) {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'tyto-travel-services';
    }

    public function get_title()
    {
        return __('Travel Services');
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
        $list_type = $settings['list_type'];
        $list = $record->$list_type;
        if ($list) { ?>
            <div class="travel-section travel-services">
                <table class="travel-services-table">
                    <tbody>
                    <tr class="services-included">
                        <td class="list-title"><?php echo $settings['list_title'] ?></td>
                        <td class="list-content">
                            <ul>
                                <?php foreach(explode("\n", strip_tags($list)) as $item): ?>
                                    <?php if (isset($item) && $item !== '' && strlen($item) <= 200 && strlen($item) > 3): ?>
                                        <li>
                                            <span class="icon"><?php \Elementor\Icons_Manager::render_icon( $settings['icon'] ); ?></span>
                                            <?php echo $item; ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php }
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
                'label' => __('Post', 'tyto'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), ['tytotravels']) ? get_the_ID() : ''
            ]
        );

        $this->add_control(
            'list_type',
            [
                'label' => __('List of', 'tyto'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'servicesIncluded'  => __('Included Services', 'tyto'),
                    'servicesExcluded'  => __('Excluded Services', 'tyto'),
                    'highlights'        => __('Highlights', 'tyto'),
                    'servicesNote'        => __('Services Notes', 'tyto'),
                ],
                'default' => 'servicesIncluded'
            ]
        );

        $this->add_control('list_title',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __('Title', 'tyto'),
                'default' => __('Inklusivleistungen', 'tyto'),
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => 'Title Typography',
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .travel-services-table td.list-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'label' => 'Content Typography',
                'name' => 'content_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .travel-services-table td.list-content',
            ]
        );

        $this->add_control( 'icon_color', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Icon Color', 'tyto' ),
            'default'   => 'green',
            'selectors' => array(
                '{{WRAPPER}} .services-included li .icon' => 'color: {{VALUE}};',
            ),
        ));

        $this->add_control( 'icon', array(
            'label'         =>  esc_html__( 'Icon', 'tyto' ),
            'type'          =>  Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-check',
                'library' => 'solid',
            ],
        ));

        $this->end_controls_section();
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new Widget_Tyto_TravelServices());
<?php
namespace Tourware\Elementor\Widget\Accordion;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use Tourware\Elementor\Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class AbstractAccordion extends Widget {

    /**
     * @throws \Exception
     * @return string
     */
    public function get_name()
    {
        throw new \Exception('Needs to be implemented.');
    }

    /**
     * @throws \Exception
     * @return string
     */
    public function get_title()
    {
        throw new \Exception('Needs to be implemented.');
    }

    /**
     * @throws \Exception
     * @return string
     */
    protected function getPostTypeName()
    {
        throw new \Exception('Needs to be implemented.');
    }

    /**
     * @throws \Exception
     * @return string
     */
    protected function getRecordTypeName()
    {
        throw new \Exception('Needs to be implemented.');
    }

    /**
     * @throws \Exception
     * @return string
     */
    protected function getWidgetName()
    {
        throw new \Exception('Needs to be implemented.');
    }

    public function get_style_depends() {
        return ['ep-accordion'];
    }


    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_title',
            [
                'label' => __('Accordion', 'tourware'),
            ]
        );

        $posts = wp_list_pluck(get_posts(['post_type' => [$this->getPostTypeName()], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'tourware'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), [$this->getPostTypeName()]) ? get_the_ID() : '',
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'title_html_tag',
            [
                'label' => __('Title HTML Tag', 'tourware'),
                'type' => Controls_Manager::SELECT,
                'options' => element_pack_title_tags(),
                'default' => 'div',
            ]
        );

        $this->add_control(
            'accordion_icon',
            [
                'label' => __('Icon', 'tourware'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'chevron-down',
                        'angle-down',
                        'angle-double-down',
                        'caret-down',
                        'caret-square-down',
                    ],
                    'fa-regular' => [
                        'caret-square-down',
                    ],
                ],
                'skin' => 'inline',
                'label_block' => false,
            ]
        );

        $this->add_control(
            'accordion_active_icon',
            [
                'label' => __('Active Icon', 'tourware'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon_active',
                'default' => [
                    'value' => 'fas fa-minus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'chevron-up',
                        'angle-up',
                        'angle-double-up',
                        'caret-up',
                        'caret-square-up',
                    ],
                    'fa-regular' => [
                        'caret-square-up',
                    ],
                ],
                'skin' => 'inline',
                'label_block' => false,
                'condition' => [
                    'accordion_icon[value]!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content_additional',
            [
                'label' => __('Additional', 'tourware'),
            ]
        );

        $this->add_control(
            'collapsible',
            [
                'label' => __('Collapsible All Item', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'multiple',
            [
                'label' => __('Multiple Open', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_item',
            [
                'label' => __('Item', 'tourware'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_spacing',
            [
                'label' => __('Item Spacing', 'tourware'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item + .bdt-accordion-item' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_title',
            [
                'label' => __('Title', 'tourware'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_title_style');

        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => __('Normal', 'tourware'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-title' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'title_background',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'title_border',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item .bdt-accordion-title',
            ]
        );

        $this->add_responsive_control(
            'title_radius',
            [
                'label' => __('Border Radius', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item .bdt-accordion-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label' => __('Padding', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item .bdt-accordion-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => __('Hover', 'tourware'),
            ]
        );

        $this->add_control(
            'hover_title_color',
            [
                'label' => __('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item:hover .bdt-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'hover_title_background',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item:hover .bdt-accordion-title',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_active',
            [
                'label' => __('Active', 'tourware'),
            ]
        );

        $this->add_control(
            'active_title_color',
            [
                'label' => __('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'active_title_background',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'active_title_shadow',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'active_title_border',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title',
            ]
        );

        $this->add_responsive_control(
            'active_title_radius',
            [
                'label' => __('Border Radius', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_icon',
            [
                'label' => __('Icon', 'tourware'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'accordion_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => __('Alignment', 'tourware'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Start', 'tourware'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('End', 'tourware'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => is_rtl() ? 'left' : 'right',
                'toggle' => false,
                'label_block' => false,
            ]
        );

        $this->start_controls_tabs('tabs_icon_style');

        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label' => __('Normal', 'tourware'),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-title .bdt-accordion-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-title .bdt-accordion-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_background_color',
                'selector' => '{{WRAPPER}} .bdt-accordion-container .bdt-accordion .bdt-accordion-title .bdt-accordion-icon'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .bdt-accordion-container .bdt-accordion .bdt-accordion-title .bdt-accordion-icon',
            ]
        );

        $this->add_responsive_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion-container .bdt-accordion .bdt-accordion-title .bdt-accordion-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion-container .bdt-accordion .bdt-accordion-title .bdt-accordion-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_space',
            [
                'label' => __('Spacing', 'tourware'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-icon.bdt-flex-align-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-icon.bdt-flex-align-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('Icon Size', 'tourware'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-title .bdt-accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} .bdt-accordion-container .bdt-accordion .bdt-accordion-title .bdt-accordion-icon',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label' => __('Hover', 'tourware'),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item:hover .bdt-accordion-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item:hover .bdt-accordion-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_hover_background_color',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item:hover .bdt-accordion-icon'
            ]
        );

        $this->add_control(
            'icon_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'icon_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item:hover .bdt-accordion-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_active',
            [
                'label' => __('Active', 'tourware'),
            ]
        );

        $this->add_control(
            'icon_active_color',
            [
                'label' => esc_html__('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'icon_active_background_color',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-icon'
            ]
        );

        $this->add_control(
            'icon_active_border_color',
            [
                'label' => esc_html__('Border Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'icon_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-item.bdt-open .bdt-accordion-icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_toggle_style_content',
            [
                'label' => __('Content', 'tourware'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __('Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'content_background_color',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-content',
            ]
        );

        $this->add_responsive_control(
            'content_radius',
            [
                'label' => __('Border Radius', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => __('Padding', 'tourware'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label' => __('Spacing', 'tourware'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .bdt-accordion .bdt-accordion-content',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'tourware'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'tourware'),
                        'icon' => 'fas fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'tourware'),
                        'icon' => 'fas fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'tourware'),
                        'icon' => 'fas fa-align-right',
                    ],
                ],
                'selectors' => [
                    // '{{WRAPPER}} .bdt-accordion .bdt-accordion-title'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .bdt-accordion .bdt-accordion-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();
        $id       = $this->getWidgetName().'-accordion-' . $this->get_id();

        $this->add_render_attribute(
            [
                'accordion' => [
                    'id'            => $id,
                    'class'         => ['bdt-accordion', $this->getWidgetName().'-accordion'],
                    'bdt-accordion' => [
                        wp_json_encode([
                            "collapsible" => $settings["collapsible"] ? true : false,
                            "multiple"    => $settings["multiple"] ? true : false,
                            "transition"  => "ease-in-out",
                        ])
                    ]
                ]
            ]
        );

        $this->add_render_attribute(
            [
                'accordion_data' => [
                    'data-settings' => [
                        wp_json_encode([
                            "id"                => 'bdt-accordion-' . $this->get_id(),
                            'hashTopOffset'     => isset($settings['hash_top_offset']['size']) ? $settings['hash_top_offset']['size'] : false,
                            'hashScrollspyTime' => isset($settings['hash_scrollspy_time']['size']) ? $settings['hash_scrollspy_time']['size'] : false,
                        ]),
                    ],
                ],
            ]
        );


        $migrated = isset($settings['__fa4_migrated']['accordion_icon']);
        $is_new   = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

        $active_migrated = isset($settings['__fa4_migrated']['accordion_active_icon']);
        $active_is_new   = empty($settings['icon_active']) && Icons_Manager::is_migration_allowed();

        $post = $settings['post'] ? $settings['post'] : get_the_ID();

        include \Tourware\Path::getResourcesFolder() . 'layouts/'.$this->getRecordTypeName().'/'.$this->getWidgetName().'/template.php';
    }
}


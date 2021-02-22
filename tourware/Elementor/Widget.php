<?php

namespace Tourware\Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

abstract class Widget extends \Elementor\Widget_Base
{
    /**
     * This Widget constructor wraps our css class around each and every widget.
     *
     * @param array $data
     * @param null $args
     */
    public function __construct( $data = [], $args = null )
    {
        parent::__construct($data, $args);

        $this->_enqueue_styles();
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-post-list';
    }

    /**
     * @return string[]
     */
    public function get_categories()
    {
        return [ 'tyto' ];
    }

    public function _enqueue_styles()
    {

    }

    public function addControl(Control $control)
    {
        $this->add_control( $control->getId(), $control->getConfig() );
    }

    protected function addControlGroup($args) {
        if (!$args['id']) {
            throw new \Exception('Group ID is missing');
        }
        if (!$args['type']) {
            throw new \Exception('Group Type is missing');
        }
        $id = $args['id'].'_';
        $type = $args['type'];
        if (method_exists($this, 'addControlGroup'.ucfirst($type)))
            call_user_func([$this, 'addControlGroup'.ucfirst($type)], $id, $args);
        else
            throw new \Exception('Group Method is missing');
    }

    protected function startControlsGroupSection($section_id, $args) {
        $this->start_controls_section(
            $section_id,
            [
                'label' => $args['label'],
                'tab' => $args['tab'] ? $args['tab'] : Controls_Manager::TAB_STYLE,
                'condition' => $args['condition'] ? $args['condition'] : null,
                'conditions' => $args['conditions'] ? $args['conditions'] : null,
            ]
        );
    }

    protected function addControlGroupButton($id, $args)
    {
        $default_args = array(
            'label' => 'Button',
            'selector' => '.elementor-button',
        );
        $args = wp_parse_args( $args, $default_args );

        $this->startControlsGroupSection($id, $args);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => $id.'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} '.$args['selector'],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => $id.'text_shadow',
                'selector' => '{{WRAPPER}} '.$args['selector'],
            ]
        );

        $this->start_controls_tabs( $id.'tabs_button_style' );

        $this->start_controls_tab(
            $id.'tab_button_normal',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            $id.'button_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $id.'background_color',
            [
                'label' => __( 'Background Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_ACCENT,
                ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $id.'tab_button_hover',
            [
                'label' => __( 'Hover', 'elementor' ),
            ]
        );

        $this->add_control(
            $id.'hover_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'].':hover, {{WRAPPER}} '.$args['selector'].':focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} '.$args['selector'].':hover svg, {{WRAPPER}} '.$args['selector'].':focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $id.'button_background_hover_color',
            [
                'label' => __( 'Background Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'].':hover, {{WRAPPER}} '.$args['selector'].':focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $args['selector'].'button_hover_border_color',
            [
                'label' => __( 'Border Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'].':hover, {{WRAPPER}} '.$args['selector'].':focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $id.'hover_animation',
            [
                'label' => __( 'Hover Animation', 'elementor' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => $id.'border',
                'selector' => '{{WRAPPER}} '.$args['selector'],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            $id.'border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => $id.'button_box_shadow',
                'selector' => '{{WRAPPER}} '.$args['selector'],
            ]
        );

        $this->add_responsive_control(
            $id.'padding',
            [
                'label' => __( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            $id.'margin',
            [
                'label' => __( 'Margin', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function addControlGroupField($id, $args) {
        $default_args = array(
            'label' => 'Field',
            'selector' => '.tourware-field',
        );
        $args = wp_parse_args( $args, $default_args );

        $label_selectors = [
            '{{WRAPPER}} label',
        ];

        $input_selectors = [
            '{{WRAPPER}} input:not([type="button"]):not([type="submit"])',
            '{{WRAPPER}} textarea',
            '{{WRAPPER}} .elementor-field-textual',
            '{{WRAPPER}} '.$args['selector']
        ];

        $input_focus_selectors = [
            '{{WRAPPER}} input:focus:not([type="button"]):not([type="submit"])',
            '{{WRAPPER}} textarea:focus',
            '{{WRAPPER}} .elementor-field-textual:focus',
            '{{WRAPPER}} '.$args['selector'].':focus'
        ];

        $label_selector = implode( ',', $label_selectors );
        $input_selector = implode( ',', $input_selectors );
        $input_focus_selector = implode( ',', $input_focus_selectors );

        $this->startControlsGroupSection($id, $args);

        $this->add_control(
            $id.'form_label_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Label', 'elementor' ),
            ]
        );

        $this->add_control(
            $id.'form_label_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $label_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography', 'elementor' ),
                'name' => $id.'form_label_typography',
                'selector' => $label_selector,
            ]
        );

        $this->add_control(
            $id.'form_field_heading',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Field', 'elementor' ),
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography', 'elementor' ),
                'name' => $id.'form_field_typography',
                'selector' => $input_selector,
            ]
        );

        $this->start_controls_tabs( $id.'tabs_form_field_style' );

        $this->start_controls_tab(
            $id.'tab_form_field_normal',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            $id . 'form_field_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $input_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $id . 'form_field_background_color',
            [
                'label' => __( 'Background Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $input_selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => $id . 'form_field_box_shadow',
                'selector' => $input_selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => $id . 'form_field_border',
                'selector' => $input_selector,
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->add_control(
            $id . 'form_field_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $input_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $id.'tab_form_field_focus',
            [
                'label' => __( 'Focus', 'elementor' ),
            ]
        );

        $this->add_control(
            $id . 'form_field_focus_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $input_focus_selector => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $id . 'form_field_focus_background_color',
            [
                'label' => __( 'Background Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $input_focus_selector => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => $id . 'form_field_focus_box_shadow',
                'selector' => $input_focus_selector,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => $id . 'form_field_focus_border',
                'selector' => $input_focus_selector,
                'fields_options' => [
                    'color' => [
                        'dynamic' => [],
                    ],
                ],
            ]
        );

        $this->add_control(
            $id . 'form_field_focus_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    $input_focus_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            $id.'form_field_focus_transition_duration',
            [
                'label' => __( 'Transition Duration', 'elementor' ) . ' (ms)',
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    $input_selector => 'transition: {{SIZE}}ms',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            $id.'form_field_padding',
            [
                'label' => __( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    $input_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    $input_selector.' + .field-icon' => 'left: calc({{LEFT}}{{UNIT}} - 16px)'
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    protected function addControlGroupFieldIcon($id, $args)
    {
        $default_args = array(
            'label' => 'Field Icon',
            'selector' => '.field-icon',
        );
        $args = wp_parse_args($args, $default_args);

        $icon_focus_selectors = [
            '{{WRAPPER}} input:focus:not([type="button"]):not([type="submit"]) + '.$args['selector'],
            '{{WRAPPER}} textarea:focus + '.$args['selector'],
            '{{WRAPPER}} select:focus + '.$args['selector'],
            '{{WRAPPER}} .elementor-field-textual:focus + '.$args['selector'],
        ];
        $icon_focus_selector = implode( ',', $icon_focus_selectors );

        $this->startControlsGroupSection($id, $args);

        $this->start_controls_tabs( $id.'tabs_form_field_icon' );

        $this->start_controls_tab(
            $id.'tab_form_field_icon',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_control(
            $id . 'form_field_icon_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $id.'tab_form_field_focus_icon',
            [
                'label' => __( 'Focus', 'elementor' ),
            ]
        );

        $this->add_control(
            $id . 'form_field_focus_icon_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'dynamic' => [],
                'selectors' => [
                    $icon_focus_selector. ' + '.$args['selector'] => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function addControlGroupBox($id, $args) {
        $default_args = array(
            'label' => __( 'Box', 'elementor-pro' ),
            'selector' => '.ht-grid-item',
            'selector_content' => empty($args['selector_content']) ? $args['selector'] : $args['selector_content']
        );
        $args = wp_parse_args( $args, $default_args );

        $this->startControlsGroupSection($id, $args);

        $this->add_control(
            $id.'box_border_width',
            [
                'label' => __( 'Border Width', 'elementor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'border-style: solid; border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            $id.'box_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'border-radius: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            $id.'content_padding',
            [
                'label' => __( 'Content Padding', 'elementor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector_content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'default' => [
                    'size' => 15,
                ],
                'separator' => 'after',
            ]
        );

        $this->start_controls_tabs( $id.'bg_effects_tabs' );

        $this->start_controls_tab( $id.'classic_style_normal',
            [
                'label' => __( 'Normal', 'elementor-pro' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => $id.'box_shadow',
                'selector' => '{{WRAPPER}} '.$args['selector'],
            ]
        );

        $this->add_control(
            $id.'box_bg_color',
            [
                'label' => __( 'Background Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $id.'box_border_color',
            [
                'label' => __( 'Border Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( $id.'classic_style_hover',
            [
                'label' => __( 'Hover', 'elementor-pro' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => $id.'box_shadow_hover',
                'selector' => '{{WRAPPER}} '.$args['selector'].':hover',
            ]
        );

        $this->add_control(
            $id.'box_bg_color_hover',
            [
                'label' => __( 'Background Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'].':hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            $id.'box_border_color_hover',
            [
                'label' => __( 'Border Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'].':hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function addControlGroupImage($id, $args) {
        $default_args = array(
            'label' => __( 'Image', 'elementor-pro' ),
            'selector' => '.tour-image',
        );
        $args = wp_parse_args( $args, $default_args );

        $this->startControlsGroupSection($id, $args);

        $this->add_control(
            $id.'img_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'].' img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} '.$args['selector'].' .image-holder' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            $id.'image_spacing',
            [
                'label' => __( 'Spacing', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'default' => [
                    'size' => 15,
                ],
            ]
        );

        $this->start_controls_tabs( $id.'thumbnail_effects_tabs' );

        $this->start_controls_tab( $id.'normal',
            [
                'label' => __( 'Normal', 'elementor-pro' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => $id.'thumbnail_filters',
                'selector' => '{{WRAPPER}} '.$args['selector'].' img',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( $id.'hover',
            [
                'label' => __( 'Hover', 'elementor-pro' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => $id.'thumbnail_hover_filters',
                'selector' => '{{WRAPPER}} '.$args['selector'].':hover img',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function addControlGroupAttribute($id, $args) {
        $default_args = array(
            'label' => __( 'Attribute', 'elementor-pro' ),
            'selector' => '.tour-attribute',
        );
        $args = wp_parse_args( $args, $default_args );

        $this->startControlsGroupSection($id, $args);

        if (!empty($args['icon'])) {
            $this->add_control( $id.'icon', array(
                'label'         =>  esc_html__( 'Icon', 'elementor-pro' ),
                'type'          =>  Controls_Manager::ICONS,
                'default'       => $args['icon_default'] ? $args['icon_default'] : null
            ));
            $this->add_control(
                $id.'icon_spacing',
                [
                    'label' => __( 'Icon Spacing', 'elementor-pro' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} '.$args['selector'].' i' => 'margin-right: {{SIZE}}{{UNIT}}',
                    ],
                    'default' => [
                        'size' => 5,
                    ],
                ]
            );
        }

        $this->add_control($id.'text_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Color', 'elementor-pro' ),
                'selectors' => array(
                    '{{WRAPPER}} '.$args['selector'] => 'color: {{VALUE}};',
                    '{{WRAPPER}} '.$args['selector'].' *' => 'color: {{VALUE}};',
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => $id.'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} '.$args['selector'].', {{WRAPPER}} '.$args['selector'].' * ',
            ]
        );

        $this->add_control(
            $id.'attribute_spacing',
            [
                'label' => __( 'Spacing', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} '.$args['selector'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function addControlGroupArrows($id, $args) {
        $default_args = array(
            'label' => __( 'Arrows', 'elementor-pro' ),
            'selector' => '.tns-controls [data-controls]',
            'selector_next' => '.tns-controls [data-controls="next"]',
            'selector_prev' => '.tns-controls [data-controls="prev"]',
        );
        $args = wp_parse_args( $args, $default_args );

        $this->startControlsGroupSection($id, $args);

        $this->add_control(
            $id, array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows', 'elementor-pro' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'elementor-pro' ),
            'label_off'    => esc_html__( 'No', 'elementor-pro' ),
            'return_value' => 'yes',
        ) );

        $this->add_control(
            $id.'size', array(
            'type'       => Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Size', 'elementor-pro' ),
            'default'    => array(
                'size' => 40
            ),
            'range'      => array(
                'px' => array(
                    'min'  => 1,
                    'max'  => 200,
                    'step' => 1
                ),
            ),
            'size_units' => array( 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} '.$args['selector'] => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ),
        ) );

        $this->add_control(
            $id.'position', array(
            'type'       => Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Horizontal Pisition', 'elementor-pro' ),
            'default'    => array(
                'size' => - 50
            ),
            'range'      => array(
                'px' => array(
                    'min'  => - 200,
                    'max'  => 200,
                    'step' => 1
                ),
            ),
            'size_units' => array( 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} '.$args['selector_next'] => 'right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} '.$args['selector_prev'] => 'left: {{SIZE}}{{UNIT}};',
            ),
        ) );

        $this->add_control(
            $id.'color', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Color', 'elementor-pro' ),
            'default'   => '#fff',
            'selectors' => array(
                '{{WRAPPER}} '.$args['selector'] => 'color: {{VALUE}};'
            ),
        ) );

        $this->add_control(
            $id.'bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Background color', 'elementor-pro' ),
            'selectors' => array(
                '{{WRAPPER}} '.$args['selector'] => 'background-color: {{VALUE}};'
            ),
        ) );

        $this->add_control(
            $id.'tablet', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Tablet', 'elementor-pro' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'elementor-pro' ),
            'label_off'    => esc_html__( 'No', 'elementor-pro' ),
            'return_value' => 'yes',
        ) );

        $this->add_control(
            $id.'mobile', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Mobile', 'elementor-pro' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'elementor-pro' ),
            'label_off'    => esc_html__( 'No', 'elementor-pro' ),
            'return_value' => 'yes',
        ) );

        $this->end_controls_section();
    }

    protected function addControlGroupDots($id, $args) {
        $default_args = array(
            'label' => __( 'Dots', 'elementor-pro' ),
            'selector' => '.tns-nav',
            'selector_button' => '.tns-nav button',
            'selector_active' => '.tns-nav button.tns-nav-active'
        );
        $args = wp_parse_args( $args, $default_args );

        $this->startControlsGroupSection($id, $args);

        $this->add_control(
            $id, array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => $args['label'],
            'default'      => '',
            'label_on'     => esc_html__( 'Yes', 'elementor-pro' ),
            'label_off'    => esc_html__( 'No', 'elementor-pro' ),
            'return_value' => 'yes',
        ) );

        $this->add_responsive_control(
            $id.'align', array(
            'type'           => Controls_Manager::CHOOSE,
            'label'          => esc_html__( 'Alignment', 'elementor-pro' ),
            'options'        => array(
                'left'   => array(
                    'title' => esc_html__( 'Left', 'elementor-pro' ),
                    'icon'  => 'fa fa-align-left'
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'elementor-pro' ),
                    'icon'  => 'fa fa-align-center'
                ),
                'right'  => array(
                    'title' => esc_html__( 'Right', 'elementor-pro' ),
                    'icon'  => 'fa fa-align-right'
                ),
            ),
            'default'        => 'center',
            'tablet_default' => 'center',
            'mobile_default' => 'center',
            'selectors'      => array(
                '{{WRAPPER}} '.$args['selector'] => 'text-align: {{VALUE}};'
            ),
        ) );

        $this->add_control(
            $id.'bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Background color', 'elementor-pro' ),
            'selectors' => array(
                '{{WRAPPER}} '.$args['selector_button'] => 'background-color: {{VALUE}};',
            ),
        ) );

        $this->add_control(
            $id.'active_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Current background color', 'elementor-pro' ),
            'selectors' => array(
                '{{WRAPPER}} '.$args['selector_active'] => 'background-color: {{VALUE}};'
            ),
        ) );

        $this->add_control(
            $id.'tablet', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Tablet', 'elementor-pro' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'elementor-pro' ),
            'label_off'    => esc_html__( 'No', 'elementor-pro' ),
        ) );

        $this->add_control(
            $id.'mobile', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Mobile', 'elementor-pro' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'elementor-pro' ),
            'label_off'    => esc_html__( 'No', 'elementor-pro' ),
        ) );

        $this->end_controls_section();
    }

}
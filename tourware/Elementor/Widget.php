<?php

namespace Tourware\Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
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

    public function addControlGroupButton($args)
    {
        if (!$args['id']) {
            throw new \Exception('Button Group ID is missing');
        }

        $default_args = array(
            'label' => 'Button',
            'selector' => '.elementor-button',
        );
        $args = wp_parse_args( $args, $default_args );

        $id = $args['id'].'_';

        $this->start_controls_section(
            $id,
            [
                'label' => $args['label'],
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => $args['condition'] ? $args['condition'] : null,
                'conditions' => $args['conditions'] ? $args['conditions'] : null,
            ]
        );

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
            $id.'text_padding',
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

        $this->end_controls_section();
    }

    public function addControlGroupField($args) {
        if (!$args['id']) {
            throw new \Exception('Button Group ID is missing');
        }

        $default_args = array(
            'label' => 'Field',
            'selector' => '.elementor-button',
        );
        $args = wp_parse_args( $args, $default_args );

        $id = $args['id'].'_';

        $label_selectors = [
            '{{WRAPPER}} label',
        ];

        $input_selectors = [
            '{{WRAPPER}} input:not([type="button"]):not([type="submit"])',
            '{{WRAPPER}} textarea',
            '{{WRAPPER}} .elementor-field-textual',
        ];

        $input_focus_selectors = [
            '{{WRAPPER}} input:focus:not([type="button"]):not([type="submit"])',
            '{{WRAPPER}} textarea:focus',
            '{{WRAPPER}} .elementor-field-textual:focus',
        ];

        $label_selector = implode( ',', $label_selectors );
        $input_selector = implode( ',', $input_selectors );
        $input_focus_selector = implode( ',', $input_focus_selectors );

        $this->start_controls_section(
            $id.'section_form_fields',
            [
                'label' => __( 'Field', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

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
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

    }

    public function addControlGroupIcon($args)
    {
        if (!$args['id']) {
            throw new \Exception('Group ID is missing');
        }

        $default_args = array(
            'label' => 'Icon',
            'selector' => '.field-icon',
        );
        $args = wp_parse_args($args, $default_args);

        $id = $args['id'] . '_';

        $icon_focus_selectors = [
            '{{WRAPPER}} input:focus:not([type="button"]):not([type="submit"]) + '.$args['selector'],
            '{{WRAPPER}} textarea:focus + '.$args['selector'],
            '{{WRAPPER}} select:focus + '.$args['selector'],
            '{{WRAPPER}} .elementor-field-textual:focus + '.$args['selector'],
        ];
        $icon_focus_selector = implode( ',', $icon_focus_selectors );

        $this->start_controls_section(
            $id,
            [
                'label' => $args['label'],
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => $args['condition'] ? $args['condition'] : null,
                'conditions' => $args['conditions'] ? $args['conditions'] : null,
            ]
        );

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

}
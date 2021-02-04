<?php
namespace Elementor;

use \Elementor\Core\Schemes as Schemes;

class Tyto_Form_Builder_Field extends Widget_Base {
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        wp_register_script('tyto-field-date-js', \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/js/script.js');
    }

	public function get_name() {
		return 'tyto-form-builder-field';
	}

	public function get_title() {
		return __( 'Tyto Field' );
	}

	public function get_icon() {
		return 'fa fa-keyboard-o';
	}

	public function get_categories() {
		return [ 'tyto' ];
	}

	public function get_keywords() {
		return [ 'input', 'form', 'field' ];
	}

	public function get_script_depends() {
        $r = ['tyto','tyto-field-date-js'];
		return $r;
	}

	public function get_style_depends() {
		return [ 
			'tyto'
		];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'tyto_form_builder_field',
			[
				'label' => __( 'Field', 'tyto' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __( 'Form ID* (Required)', 'tyto' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Enter the same form id for all fields in a form, with latin character and no space. E.g order_form', 'tyto' ),
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'field_id',
			[
				'label' => __( 'Field ID* (Required)', 'tyto' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'Field ID have to be unique in a form, with latin character and no space. Please do not enter Field ID = product. E.g your_field_id', 'tyto' ),
				'render_type' => 'none',
			]
		);

        $this->add_control(
            'tyto_type',
            [
                'label' => __( 'Tyto type', 'tyto' ),
                'type' => Controls_Manager::SELECT,
                'description' => __( 'For autofill', 'tyto' ),
                'render_type' => 'none',
                'default' => 'travel',
                'options' => array(
                    'travel'     => esc_html__( 'Travel' ),
                    'accommodation' => esc_html__( 'Accommodation' ),
                    'destination' => esc_html__( 'Destination' ),
                    'price' => esc_html__( 'Price' ),
                    'dates' => esc_html__( 'Dates' ),
                    'start_date' => esc_html__( 'Start Date' ),
                    'end_date' => esc_html__( 'End Date' ),
                    'option' => esc_html__( 'Additional Option' ),
                    'picture' => esc_html__( 'Picture' ),
                ),
            ]
        );

        $this->add_control(
            'tyto_type_show_as',
            [
                'label' => __( 'Show as', 'tyto' ),
                'type' => Controls_Manager::SELECT,
                'render_type' => 'none',
                'default' => 'input',
                'options' => array(
                    'input'     => esc_html__( 'Input' ),
                    'text' => esc_html__( 'Text' ),
                ),
                'condition' => [
                    'tyto_type!' => 'picture',
                ],
            ]
        );

//		$this->add_control(
//			'shortcode',
//			[
//				'label' => __( 'Shortcode', 'elementor-pro' ),
//				'type' => Controls_Manager::RAW_HTML,
//				'classes' => 'forms-field-shortcode',
//				'raw' => '<input class="elementor-form-field-shortcode" readonly />',
//			]
//		);
        $this->add_control(
            'price_prefix',
            [
                'label' => __( 'Price Prefix', 'tyto' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'tyto_type' => 'price',
                    'tyto_type_show_as' => 'text'
                ],
            ]
        );
        $this->add_control(
            'price_suffix',
            [
                'label' => __( 'Price Suffix', 'tyto' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'tyto_type' => 'price',
                    'tyto_type_show_as' => 'text'
                ],
            ]
        );

		$this->add_control(
			'field_label',
			[
				'label' => __( 'Label', 'tyto' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ],
			]
		);

		$this->add_control(
			'field_label_show',
			[
				'label' => __( 'Show Label', 'tyto' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'return_value' => 'true',
				'default' => 'true',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ],
			]
		);

		$this->add_control(
			'field_placeholder',
			[
				'label' => __( 'Placeholder', 'tyto' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ],
			]
		);

		$this->add_control(
			'field_autocomplete',
			[
				'label' => __( 'Autocomplete', 'tyto' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'tyto' ),
				'label_off' => __( 'Off', 'tyto' ),
				'return_value' => 'true',
				'default' => 'true',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ],
			]
		);

		$this->add_control(
			'field_required',
			[
				'label' => __( 'Required', 'tyto' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default' => '',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ],
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => __( 'Required Mark', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-pro' ),
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'default' => '',
				'condition' => [
					'field_label!' => '',
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
				],
			]
		);

		$this->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'tyto' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'tyto' ),
			]
		);

		$this->add_control(
			'field_value',
			[
				'label' => __( 'Default Value', 'tyto' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ],
			]
		);

        $this->add_control(
            'use_native_date',
            [
                'label' => __( 'Native HTML5', 'tyto' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ],
                        ],
                        [
                            'name' => 'tyto_type_show_as',
                            'operator' => '!=',
                            'value' => 'text'
                        ]
                    ],
                ],
                'separator' => 'before',
            ]

        );

        $date_format = esc_attr( get_option( 'date_format' ) );

        $this->add_control(
            'date_format',
            [
                'label' => __( 'Date Format', 'pafe' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => false,
                'default' => $date_format,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'min_date',
            [
                'label' => __( 'Min. Date', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label_block' => false,
                'picker_options' => [
                    'enableTime' => false,
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ],
                        ],
                        [
                            'name' => 'tyto_type_show_as',
                            'operator' => '!=',
                            'value' => 'text'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'min_date_current',
            [
                'label' => __( 'Set Current Date for Min. Date', 'pafe' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => 'Yes',
                'label_off' => 'No',
                'return_value' => 'yes',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ]
                        ],
                        [
                            'name' => 'tyto_type_show_as',
                            'operator' => '!=',
                            'value' => 'text'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'max_date',
            [
                'name' => 'max_date',
                'label' => __( 'Max. Date', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label_block' => false,
                'picker_options' => [
                    'enableTime' => false,
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ]
                        ],
                        [
                            'name' => 'tyto_type_show_as',
                            'operator' => '!=',
                            'value' => 'text'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'max_date_current',
            [
                'label' => __( 'Set Current Date for Max. Date', 'pafe' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
                'label_on' => 'Yes',
                'label_off' => 'No',
                'return_value' => 'yes',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ],
                        ],
                        [
                            'name' => 'tyto_type_show_as',
                            'operator' => '!=',
                            'value' => 'text'
                        ]
                    ],
                ],
            ]
        );

        $this->add_control(
            'date_language',
            [
                'label' => __( 'Date Language', 'pafe' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'label_block' => false,
                'description' => __( 'This feature only works on the frontend.', 'pafe' ),
                'options' => [
                    'de' 	=>	 'German',
                    'en' 	=>	 'English',
                    'ru' 	=>	 'Russian',
                ],
                'default' => 'de',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'tyto_type',
                            'operator' => 'in',
                            'value' => [
                                'start_date',
                                'end_date',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'picture_size',
                'exclude' => [ 'custom' ],
                'default' => 'medium',
                'prefix_class' => 'elementor-picture--thumbnail-size-',
                'condition' => [ 'tyto_type' => 'picture' ]
            ]
        );

		$this->end_controls_section();


		$this->start_controls_section(
			'section_style_piotnet_form_label',
			[
				'label' => __( 'Label', 'tyto' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .elementor-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .elementor-labels-above .elementor-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group > label, {{WRAPPER}} .elementor-field-subgroup label' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => __( 'Mark Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-mark-required .elementor-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .elementor-field-group > label',
                'scheme' => Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_piotnet_form_field',
			[
				'label' => __( 'Field', 'tyto' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['tyto_type!' => 'picture']
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field' => 'color: {{VALUE}};',
					'{{WRAPPER}} .tyto-field-text' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
                'condition' => ['tyto_type!' => 'picture']
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selectors' => [
				        '{{WRAPPER}} .elementor-field-group .elementor-field',
				        '{{WRAPPER}} .elementor-field-subgroup label',
				        '{{WRAPPER}} .tyto-field-text'
                    ],
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
                'condition' => ['tyto_type!' => 'picture']
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field .elementor-field-textual' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_responsive_control(
			'input_max_width',
			[
				'label' => __( 'Input Max Width', 'tyto' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group .elementor-field:not(.elementor-select-wrapper)' => 'max-width: {{SIZE}}{{UNIT}}!important;',
					'{{WRAPPER}} .elementor-field-group .elementor-field .elementor-field-textual' => 'max-width: {{SIZE}}{{UNIT}}!important;',
				],
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_responsive_control(
			'input_padding',
			[
				'label' => __( 'Input Padding', 'tyto' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field .elementor-field-textual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_control(
			'input_placeholder_color',
			[
				'label' => __( 'Input Placeholder Color', 'tyto' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)::-webkit-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)::-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper):-ms-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper):-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field.elementor-field-textual::placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field.elementor-field-textual::-webkit-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field.elementor-field-textual::-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field.elementor-field-textual:-ms-input-placeholder' => 'color: {{VALUE}}; opacity: 1;',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field.elementor-field-textual:-moz-placeholder' => 'color: {{VALUE}}; opacity: 1;',
				],
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_control(
			'field_border_type',
			[
				'label' => _x( 'Border Type', 'Border Control', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'elementor' ),
					'solid' => _x( 'Solid', 'Border Control', 'elementor' ),
					'double' => _x( 'Double', 'Border Control', 'elementor' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'elementor' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'elementor' ),
					'groove' => _x( 'Groove', 'Border Control', 'elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field .elementor-field-textual' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .tyto-signature canvas' => 'border-style: {{VALUE}};',
				],
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_responsive_control(
			'field_border_width',
			[
				'label' => _x( 'Width', 'Border Control', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field .elementor-field-textual' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tyto-signature canvas' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'field_border_type!' => '',
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
				],
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => _x( 'Color', 'Border Control', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field .elementor-field-textual' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .tyto-signature canvas' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'field_border_type!' => '',
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
				],
			]
		);

		$this->add_responsive_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .elementor-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .tyto-signature canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'field_box_shadow',
				'label' => __( 'Box Shadow', 'plugin-domain' ),
				'selector' => '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper)',
                'condition' => [
                    'tyto_type!' => 'picture',
                    'tyto_type_show_as!' => 'text'
                ]
			]
		);

		$this->end_controls_section();
	}

	protected function form_fields_render_attributes( $i, $instance, $item ) {
		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'elementor-field-type-' . $item['tyto_type'],
						'elementor-field-group',
						'elementor-column',
						'elementor-field-group-' . $item['field_id'],
					],
				],
				'input' . $i => [
					'class' => [
						'elementor-field',
						'elementor-size-' . $item['input_size'],
						empty( $item['css_classes'] ) ? '' : esc_attr( $item['css_classes'] ),
					],
				],
				'label' . $i => [
					'for' => 'form-field-' . $item['field_id'],
					'class' => 'elementor-field-label',
				],
			]
		);

    	$this->add_render_attribute(
				[
					'input' . $i => [
//						'type' => $item['tyto_type'],
						'name' => "form_fields[{$item['field_id']}]",
						'id' => 'form-field-' . $item['field_id'],
					],
				]
			);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-col-' . $item['width'] );

		if ( ! empty( $item['width_tablet'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-md-' . $item['width_tablet'] );
		}

		if ( ! empty( $item['width_mobile'] ) ) {
			$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-sm-' . $item['width_mobile'] );
		}

		if ( ! empty( $item['field_placeholder'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'placeholder', $item['field_placeholder'] );
		}

		if ( ! empty( $item['field_autocomplete'] ) ) {
			$this->add_render_attribute( 'input' . $i, 'autocomplete', 'on' );
		} else {
			$this->add_render_attribute( 'input' . $i, 'autocomplete', 'off' );
		}

		$name = $this->get_field_name_shortcode("form_fields[{$item['field_id']}]");

		$value = $this->get_value_edit_post($name);

		if (empty($value)) {
			$value = $item['field_value'];
			$this->add_render_attribute( 'input' . $i, 'data-pafe-form-builder-default-value', $item['field_value'] );
		}

        $tyto_type = $item['tyto_type'];
        if ($tyto_type == 'start_date' || $tyto_type == 'end_date') {
            if ($_GET['dates']) {
                $dates = explode('-', $_GET['dates']);
                if ($tyto_type == 'start_date')
                    $value = date($item['date_format'], strtotime($dates[0]));
                else if ($tyto_type == 'end_date')
                    $value = date($item['date_format'], strtotime($dates[1]));
            }
        } else {
            $postid = $_GET['postId'];
            if ($postid) {
                $tytorawdata = json_decode(get_post_meta($postid, 'tytorawdata', true));
                if ('travel' == $tyto_type)
                    $value = get_the_title($postid);
                elseif ('destination' == $tyto_type) {
                    $t_countries = $tytorawdata->countries;
                    if (!empty($t_countries)) {
                        foreach ($t_countries as $t_country) {
                            $countries[] = $t_country->official_name_de;
                        }
                    }
                    if (empty($countries)) {
                        $countries[] = $tytorawdata->_destination;
                    }
                    if (!empty($countries)) $value = implode(', ', $countries);
                } elseif ('price' == $tyto_type) {
                    if ($_GET['dates'] && count($tytorawdata->dates)) {
                        $selected_dates = explode('-', $_GET['dates']);
                        foreach ($tytorawdata->dates as $t_date) {
                            if (date('d.m.Y', strtotime($t_date->start)) == $selected_dates[0]
                            && date('d.m.Y', strtotime($t_date->end)) == $selected_dates[1])
                                $value = $t_date->price;
                        }
                    } else {
                        $value = $tytorawdata->price;
                    }
                } elseif ('dates' == $tyto_type) {
                    if ($_GET['dates']) {
                        $value = $_GET['dates'];
                    } else {
                        if (count($tytorawdata->dates)) {
                            $value = date_create($tytorawdata->dates[0]->start)->format('d.m.Y').'-'.date_create($tytorawdata->dates[0]->end)->format('d.m.Y');
                        }
                    }
                }
            }
        }

		if ( ! empty( $value ) || $value == 0 ) {
			$this->add_render_attribute( 'input' . $i, 'value', $value );
			$this->add_render_attribute( 'input' . $i, 'data-pafe-form-builder-value', $value );
		}

		if ( ! empty( $item['field_required'] ) ) {
			$class = 'elementor-field-required';
			if ( ! empty( $item['mark_required'] ) ) {
				$class .= ' elementor-mark-required';
			}
			$this->add_render_attribute( 'field-group' . $i, 'class', $class );
            $this->add_render_attribute( 'input' . $i, 'required', 'required' );
            $this->add_render_attribute( 'input' . $i, 'aria-required', 'true' );
		}

	}

	public function get_field_name_shortcode($content) {
		$field_name = str_replace('[field id=', '', $content);
		$field_name = str_replace(']', '', $field_name);
		$field_name = str_replace('"', '', $field_name);
		$field_name = str_replace('form_fields[', '', $field_name);
		//fix alert ]
		return trim($field_name);
	}

	public function get_value_edit_post($name) {
		$value = '';
		if (!empty($_GET['edit'])) {
			$post_id = intval($_GET['edit']);
			if( is_user_logged_in() && get_post($post_id) != null ) {
				if (current_user_can( 'edit_others_posts' ) || get_current_user_id() == get_post($post_id)->post_author) {
					$sp_post_id = get_post_meta($post_id,'_submit_post_id',true);
					$form_id = get_post_meta($post_id,'_submit_button_id',true);

					if (!empty($_GET['smpid'])) {
						$sp_post_id = esc_sql($_GET['smpid']);
					}

					if (!empty($_GET['sm'])) {
						$form_id = esc_sql($_GET['sm']);
					}

					$elementor = Plugin::$instance;
					
					if ( version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ) {
						$meta = $elementor->documents->get( $sp_post_id )->get_elements_data();
					} else {
						$meta = $elementor->db->get_plain_editor( $sp_post_id );
					}

					$form = find_element_recursive( $meta, $form_id );

					if ( !empty($form)) {

						$widget = $elementor->elements_manager->create_element_instance( $form );
						$form['settings'] = $widget->get_active_settings();

						if(!empty($form['settings'])) {
							$sp_post_taxonomy = $form['settings']['submit_post_taxonomy'];
							$sp_title = $this->get_field_name_shortcode( $form['settings']['submit_post_title'] );
							$sp_content = $this->get_field_name_shortcode( $form['settings']['submit_post_content'] );
							$sp_terms = $form['settings']['submit_post_terms_list'];
							$sp_term = $this->get_field_name_shortcode( $form['settings']['submit_post_term'] );
							$sp_featured_image = $this->get_field_name_shortcode( $form['settings']['submit_post_featured_image'] );
							$sp_custom_fields = $form['settings']['submit_post_custom_fields_list'];

							if ($name == $sp_title) {
								$value = get_the_title($post_id);
							}

							if ($name == $sp_content) {
								$value = get_the_content(null,false,$post_id);
							}

							if ($name == $sp_term) {
								if (!empty($sp_post_taxonomy)) {
									$sp_post_taxonomy = explode('|', $sp_post_taxonomy);
									$sp_post_taxonomy = $sp_post_taxonomy[0];
									$terms = get_the_terms($post_id,$sp_post_taxonomy);
									if (!empty($terms) && ! is_wp_error( $terms )) {
										$value = $terms[0]->slug;
									}
								}
								
							}

							if (!empty($sp_terms)) {
								foreach ($sp_terms as $sp_terms_item) {
									$sp_post_taxonomy = explode('|', $sp_terms_item['submit_post_taxonomy']);
									$sp_post_taxonomy = $sp_post_taxonomy[0];
									$sp_term_slug = $sp_terms_item['submit_post_terms_slug'];
									$sp_term = get_field_name_shortcode( $sp_terms_item['submit_post_terms_field_id'] );

									if ($name == $sp_term) {
										$terms = get_the_terms($post_id,$sp_post_taxonomy);
										if (!empty($terms) && ! is_wp_error( $terms )) {
											foreach ($terms as $term) {
												$value .= $term->slug . ',';
											}
										}
									}
								}

								$value = rtrim($value, ',');
							}

							if ($name == $sp_featured_image) {
								$value = get_the_post_thumbnail_url($post_id,'full');
							}

							foreach ($sp_custom_fields as $sp_custom_field) {
								if ( !empty( $sp_custom_field['submit_post_custom_field'] ) ) {
									if ($name == $this->get_field_name_shortcode( $sp_custom_field['submit_post_custom_field_id'])) {

										$meta_type = $sp_custom_field['submit_post_custom_field_type'];

										if (function_exists('get_field') && $form['settings']['submit_post_custom_field_source'] == 'acf_field') {
											$value = get_field($sp_custom_field['submit_post_custom_field'],$post_id);

											if ($meta_type == 'image') {
												if (is_array($value)) {
													$value = $value['url'];
												}
											}

											if ($meta_type == 'gallery') {
												if (is_array($value)) {
													$images = '';
													foreach ($value as $item) {
														if (is_array($item)) {
															$images .= $item['url'] . ',';
														}
													}
													$value = rtrim($images, ',');
												}
											}

											if ($meta_type == 'select' || $meta_type == 'checkbox') {
												if (is_array($value)) {
													$value_string = '';
													foreach ($value as $item) {
														$value_string .= $item . ',';
													}
													$value = rtrim($value_string, ',');
												}
											}

											if ($meta_type == 'date') {
												$value = get_post_meta($post_id,$sp_custom_field['submit_post_custom_field'],true);
												$time = strtotime( $value );
												$value = date(get_option( 'date_format' ),$time);
											}

										} elseif ($form['settings']['submit_post_custom_field_source'] == 'toolset_field') {

											$meta_key = 'wpcf-' . $sp_custom_field['submit_post_custom_field'];

											$value = get_post_meta($post_id,$meta_key,false);

											if ($meta_type == 'gallery') {
												if (!empty($value)) {
													$images = '';
													foreach ($value as $item) {
														$images .= $item . ',';
													}
													$value = rtrim($images, ',');
												}
											} elseif ($meta_type == 'checkbox') {
												if (is_array($value)) {
													$value_string = '';
													foreach ($value as $item) {
														foreach ($item as $item_item) {
															$value_string .= $item_item[0] . ',';
														}
													}
													$value = rtrim($value_string, ',');
												}
											} elseif ($meta_type == 'date') {
												$value = date(get_option( 'date_format' ),$value[0]);
											} else {
												$value = $value[0];
											}

										} elseif ($form['settings']['submit_post_custom_field_source'] == 'jet_engine_field') {
											$value = get_post_meta($post_id,$sp_custom_field['submit_post_custom_field'],true);

											if ($meta_type == 'image') {
												if (!empty($value)) {
													$value = wp_get_attachment_url( $value );
												}
											}

											if ($meta_type == 'gallery') {
												if (!empty($value)) {
													$images = '';
													$images_id = explode(',', $value);
													foreach ($images_id as $item) {
														$images .= wp_get_attachment_url( $item ) . ',';
													}
													$value = rtrim($images, ',');
												}
											}

											if ($meta_type == 'select') {
												if (is_array($value)) {
													$value_string = '';
													foreach ($value as $item) {
														$value_string .= $item . ',';
													}
													$value = rtrim($value_string, ',');
												}
											}

											if ($meta_type == 'checkbox') {
												if (is_array($value)) {
													$value_string = '';
													foreach ($value as $key => $item) {
														if ($item) {
															$value_string .= $key . ',';
														}
													}
													$value = rtrim($value_string, ',');
												}
											}

											if ($meta_type == 'date') {
												$value = get_post_meta($post_id,$sp_custom_field['submit_post_custom_field'],true);
												$time = strtotime( $value );
												$value = date(get_option( 'date_format' ),$time);
											}

										} else {
											$value = get_post_meta($post_id,$sp_custom_field['submit_post_custom_field'],true);
										}
									}
								}
							}

						}
					}
				}
			}
		}

		return $value;

	}

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $item_index = 0;
        $item = $settings;

        $form_id = $settings['form_id'];
        $postid = $_GET['postId'];

        $item['input_size'] = '';
        $this->form_fields_render_attributes($item_index, '', $item); ?>
        <div class="elementor-form-fields-wrapper elementor-labels-above">
            <div <?php echo $this->get_render_attribute_string('field-group' . $item_index); ?>>
                <?php
                echo '<div data-pafe-form-builder-required></div>';

                if ($settings['field_label']) {
                    echo '<label ';
                    if (empty($settings['field_label_show'])) {
                        echo 'style="display:none" ';
                    }
                    echo $this->get_render_attribute_string('label' . $item_index);
                    echo '>' . $settings['field_label'] . '</label>';
                }

                // TODO refactor: add functions to make fields
                // TODO add field id, field name
                if ($settings['tyto_type'] == 'start_date' || $item['tyto_type'] == 'end_date') {
                    $this->add_render_attribute('input' . $item_index, 'data-pafe-form-builder-form-id', $form_id);
                    $this->add_render_attribute('input' . $item_index, 'class', 'elementor-field-textual elementor-date-field');

                    echo "<script src='" . \Tourware\Elementor\Loader::getElementorFolderUri() .  $this->get_name() . "/assets/js/flatpickr.min.js'></script>";

                    if (isset($item['use_native_date']) && 'yes' === $item['use_native_date']) {
                        $this->add_render_attribute('input' . $item_index, 'class', 'elementor-use-native');
                        $this->set_render_attribute('input' . $item_index, 'type', 'date');
                    }

                    if (!empty($item['min_date']) && empty($item['min_date_current'])) {
                        $this->add_render_attribute('input' . $item_index, 'min', esc_attr($item['min_date']));
                    }

                    if (!empty($item['min_date_current'])) {
                        $this->add_render_attribute('input' . $item_index, 'min', esc_attr(date('Y-m-d')));
                    }

                    if (!empty($item['max_date']) && empty($item['max_date_current'])) {
                        $this->add_render_attribute('input' . $item_index, 'max', esc_attr($item['max_date']));
                    }

                    if (!empty($item['max_date_current'])) {
                        $this->add_render_attribute('input' . $item_index, 'max', esc_attr(date('Y-m-d')));
                    }

                    if ($item['date_language'] != 'en') {
                        echo "<script src='" . \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/js/' . $item['date_language'] . ".js'></script>";
                    }

                    $this->add_render_attribute('input' . $item_index, 'data-pafe-form-builder-date-language', esc_attr($item['date_language']));
                    $this->add_render_attribute('input' . $item_index, 'data-date-format', esc_attr($item['date_format']));

                    echo '<input ' . $this->get_render_attribute_string('input' . $item_index) . '>';
                } else if ($settings['tyto_type'] == 'picture') {
                    global $_wp_additional_image_sizes;
                    $size = $settings['picture_size_size'];
                    if (in_array($size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
                        $w = get_option("{$size}_size_w");
                        $h =  get_option("{$size}_size_h");
                    } elseif (isset($_wp_additional_image_sizes[$size])) {
                        $w = $_wp_additional_image_sizes[$size]['width'];
                        $h = $_wp_additional_image_sizes[$size]['height'];
                    }
                    if (empty($w)) $w = 500;
                    if (empty($h)) $h = 500;

                    if (Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode()) {
                        echo '<img src="https://via.placeholder.com/'.$w.'x'.$h.'" width="100%" height="auto">';
                    } else {
                        if (!empty($postid)) {
                            $tytorawdata = json_decode(get_post_meta($postid, 'tytorawdata', true));
                            if (count($tytorawdata->images)) {
                                $img_options = array(
                                    "secure" => true,
                                    "width" => $w,
                                    "height" => $h,
                                    "crop" => "thumb"
                                );
                                if ('http' === substr($tytorawdata->images[0]->image, 0, 4)) {
                                    $img_options['type'] = 'fetch';
                                }
                                $img_url = \Cloudinary::cloudinary_url($tytorawdata->images[0]->image, $img_options);
                                echo '<img src="' . $img_url . '" width="100%" height="auto">';
                            }
                        }
                    }
                } else {
                    $this->add_render_attribute('input' . $item_index, 'data-pafe-form-builder-form-id', $form_id);
                   if ($settings['tyto_type_show_as'] == 'text') {
                       $this->add_render_attribute(
                           'input' . $item_index,
                           'style', 'display:none;');
                       $this->add_render_attribute(
                           'select-wrapper' . $item_index,
                           'style', 'display:none;');
                       $attributes = $this->get_render_attributes('input' . $item_index);
                       if (Plugin::$instance->editor->is_edit_mode() || Plugin::$instance->preview->is_preview_mode()) {
                           $value = '%value%';
                       } else {
                           $value = implode(' ', $attributes['value']);
                       }
                       echo '<div class="tyto-field-text">'.
                           ($item['tyto_type'] == 'price' ? $item['price_prefix'] : '').
                           $value.
                           ($item['tyto_type'] == 'price' ? $item['price_suffix'] : '').
                           '</div>';
                   }

                   if ($settings['tyto_type'] == 'dates') {
                       $attributes = $this->get_render_attributes('input' . $item_index);
                       $value = implode(' ', $attributes['value']);
                       $options = [];
                       if (!empty($postid)) {
                           $tytorawdata = json_decode(get_post_meta($postid, 'tytorawdata', true));
                           if (count($tytorawdata->dates)) {
                               foreach ($tytorawdata->dates as $t_date) {
                                   $opt_val = date_create($t_date->start)->format('d.m.Y').'-'.date_create($t_date->end)->format('d.m.Y');
                                   $options[$opt_val] = $opt_val;
                               }
                           }
                       }

                       $this->add_render_attribute(
                           [
                               'select-wrapper' . $item_index => [
                                   'class' => [
                                       'elementor-field',
                                       'elementor-select-wrapper',
                                       esc_attr( $item['css_classes'] ),
                                   ],
                               ],
                               'select' . $item_index => [
                                   'class' => [
                                       'elementor-field-textual',
                                       'elementor-size-' . $item['input_size'],
                                   ],
                               ],
                           ]
                       ); ?>
                       <div <?php echo $this->get_render_attribute_string( 'select-wrapper' . $item_index ); ?>>
                           <select <?php echo $this->get_render_attribute_string( 'select' . $item_index ); ?> data-options='<?php echo json_encode($options); ?>'>
                               <?php

                               foreach ( $options as $key => $option ) {
                                   $option_id = $key;
                                   $option_value = esc_attr( $key );
                                   $option_label = esc_html( $option );

                                   $this->add_render_attribute( $option_id, 'value', $option_value );

                                   if ( ! empty( $value ) && $option_value === $value ) {
                                       $this->add_render_attribute( $option_id, 'selected', 'selected' );
                                   }
                                   echo '<option ' . $this->get_render_attribute_string( $option_id ) . '>' . $option_label . '</option>';
                               }
                               ?>
                           </select>
                       </div>
                   <?php } else {
                       echo '<input size="1" ' . $this->get_render_attribute_string('input' . $item_index) . '>';
                   }

                } ?>
            </div>
        </div>
        <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type( new Tyto_Form_Builder_Field() );
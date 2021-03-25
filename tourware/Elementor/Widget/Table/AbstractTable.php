<?php
namespace Tourware\Elementor\Widget\Table;

use Tourware\Elementor\Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class AbstractTable extends Widget {

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
    protected function getWidgetName()
    {
        throw new \Exception('Needs to be implemented.');
    }

    protected function _register_controls()
    {
        $this->sectionTable();
        $this->sectionDataTableSetting();
        $this->sectionStyleTable();
        $this->sectionStyleHeader();
        $this->sectionStyleBody();
    }

    public function _enqueue_styles()
    {
        wp_enqueue_script('tourware-datatables', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/table/datatables.js', ['jquery']);
        wp_enqueue_script('tourware-table-script', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/table/script.js', ['jquery', 'tourware-datatables']);

        wp_localize_script('tourware-table-script', 'TytoAjaxVars',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );
    }

    public function renderTable($settings)
    {
        echo 'no data';
    }

    public function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('table-wrapper', 'class', ['tourware-table', 'tourware-data-table']);
        $this->add_render_attribute('table-wrapper', 'class', $settings['stripe_style'] ? 'tourware-stripe' : '');

        if ($settings['use_data_table'] == 'yes') {
            $this->add_render_attribute('table-wrapper', 'class', 'with-script');
            $this->add_render_attribute(
                [
                    'table-wrapper' => [
                        'data-settings' => [
                            wp_json_encode([
                                'paging'    => $settings['show_pagination'] == 'yes',
                                'info'      => $settings['show_info'] == 'yes',
                                'searching' => $settings['show_searching'] == 'yes',
                                'ordering'  => $settings['show_ordering'] == 'yes',
                                'order'     => [[$settings['order_by'], $settings['order']]],
                            ])
                        ]
                    ]
                ]
            );
        }

        echo '<div ' . $this->get_render_attribute_string('table-wrapper') . '>';
        $this->renderTable($settings);
        echo '</div>';
    }

    private function sectionTable()
    {
        $this->start_controls_section('section_content_table', [
            'label' => __('Table', 'tourware'),
        ]);

        $this->add_control('header_align',[
            'label'     => __('Header Alignment', 'tourware'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [
                    'title' => __('Left', 'tourware'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'tourware'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'  => [
                    'title' => __('Right', 'tourware'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table th' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_control('body_align', [
            'label'     => __('Body Alignment', 'tourware'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [
                    'title' => __('Left', 'tourware'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'tourware'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'  => [
                    'title' => __('Right', 'tourware'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'center',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table table' => 'text-align: {{VALUE}}; width: 100%;',
            ],
        ]);

        $this->add_control('use_data_table', [
            'label'   => esc_html__('Datatable', 'tourware'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('hide_header', [
            'label'         => esc_html__('Hide Header', 'tourware'),
            'type'          => Controls_Manager::SWITCHER,
            'return_value'  => 'none',
            'selectors'     => [
                '{{WRAPPER}} .tourware-data-table table th' => 'display: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    private function sectionDataTableSetting()
    {
        $this->start_controls_section('section_style_data_table', [
            'label'     => __('Data Table Settings', 'tourware'),
            'condition' => [
                'use_data_table' => 'yes',
            ],
        ]);

        $this->add_control('show_searching', [
            'label'   => esc_html__('Search', 'tourware'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_ordering', [
            'label'   => esc_html__('Ordering', 'tourware'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_pagination', [
            'label'   => esc_html__('Pagination', 'tourware'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_info', [
            'label'   => esc_html__('Info', 'tourware'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();
    }

    private function sectionStyleTable()
    {
        $this->start_controls_section('section_style_table', [
            'label' => __('Table', 'tourware'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control( 'stripe_style', [
            'label' => __('Stripe Style', 'tourware'),
            'type'  => Controls_Manager::SWITCHER,
        ]);

        $this->add_control('table_border_style', [
            'label'     => __('Border Style', 'tourware'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'solid',
            'options'   => [
                'none'   => __('None', 'tourware'),
                'solid'  => __('Solid', 'tourware'),
                'double' => __('Double', 'tourware'),
                'dotted' => __('Dotted', 'tourware'),
                'dashed' => __('Dashed', 'tourware'),
                'groove' => __('Groove', 'tourware'),
            ],
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table table' => 'border-style: {{VALUE}};',
            ],
        ]);

        $this->add_control('table_border_width', [
            'label'     => __('Border Width', 'tourware'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => [
                'size' => 1,
            ],
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 4,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table table' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('table_border_color', [
            'label'     => __('Border Color', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ccc',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table table' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('leading_column_show', [
            'label' => __('Leading Column Style', 'tourware'),
            'type'  => Controls_Manager::SWITCHER,
        ]);

        $this->end_controls_section();
    }

    private function sectionStyleHeader()
    {
        $this->start_controls_section('section_style_header', [
            'label' => __('Header', 'tourware'),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition' => [
                'hide_header' => ''
            ]
        ]);

        $this->add_control('header_background', [
            'label'     => __('Background', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e7ebef',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table th' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('header_color', [
            'label'     => __('Text Color', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#333',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table th' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('header_border_style', [
            'label'     => __('Border Style', 'tourware'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'solid',
            'options'   => [
                'none'   => __('None', 'tourware'),
                'solid'  => __('Solid', 'tourware'),
                'double' => __('Double', 'tourware'),
                'dotted' => __('Dotted', 'tourware'),
                'dashed' => __('Dashed', 'tourware'),
                'groove' => __('Groove', 'tourware'),
            ],
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table th' => 'border-style: {{VALUE}};',
            ],
        ]);

        $this->add_control('header_border_width', [
            'label'     => __('Border Width', 'tourware'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => [
                'size' => 1,
            ],
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 20,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table th' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('header_border_color', [
            'label'     => __('Border Color', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ccc',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table th' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('header_padding', [
            'label'      => __('Padding', 'tourware'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top'    => 1,
                'bottom' => 1,
                'left'   => 1,
                'right'  => 2,
                'unit'   => 'em'
            ],
            'selectors'  => [
                '{{WRAPPER}} .tourware-data-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);


        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'header_text_typography',
            'selector' => '{{WRAPPER}} .tourware-data-table th',
        ]);

        $this->end_controls_section();
    }

    private function sectionStyleBody(){

        $this->start_controls_section('section_style_body',[
            'label' => __('Body', 'tourware'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('cell_border_style', [
            'label'     => __('Border Style', 'tourware'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'solid',
            'options'   => [
                'none'   => __('None', 'tourware'),
                'solid'  => __('Solid', 'tourware'),
                'double' => __('Double', 'tourware'),
                'dotted' => __('Dotted', 'tourware'),
                'dashed' => __('Dashed', 'tourware'),
                'groove' => __('Groove', 'tourware'),
            ],
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table td' => 'border-style: {{VALUE}};',
            ],
        ]);

        $this->add_control('cell_border_width', [
            'label'     => __('Border Width', 'tourware'),
            'type'      => Controls_Manager::SLIDER,
            'default'   => [
                'size' => 1,
            ],
            'range'     => [
                'px' => [
                    'min' => 0,
                    'max' => 20,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table td' => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('cell_padding', [
            'label'      => __('Cell Padding', 'bdthemes-element-pack'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top'    => 0.5,
                'bottom' => 0.5,
                'left'   => 1,
                'right'  => 1,
                'unit'   => 'em'
            ],
            'selectors'  => [
                '{{WRAPPER}} .tourware-data-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'body_text_typography',
            'selector' => '{{WRAPPER}} .bdt-table td',
        ]);

        $this->start_controls_tabs('tabs_body_style');

        $this->start_controls_tab('tab_normal', [
            'label' => __('Normal', 'tourware'),
        ]);

        $this->add_control('normal_background', [
            'label'     => __('Background', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fff',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table td' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('normal_color', [
            'label'     => __('Text Color', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table td' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('normal_border_color', [
            'label'     => __('Border Color', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ccc',
            'selectors' => [
                '{{WRAPPER}} .tourware-data-table td' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('tab_hover', [
            'label' => __('Hover', 'tourware'),
        ]);

        $this->add_control('row_hover_background', [
            'label'     => __('Background', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.tourware-data-table table tr:hover td' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('row_hover_text_color', [
            'label'     => __('Text Color', 'tourware'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}}.tourware-data-table table tr:hover td' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

}
<?php
namespace Tourware\Elementor\Widget\Details;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Tourware\Elementor\Widget;
use Tourware\Path;

class AbstractDetails extends Widget
{
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

    protected function _register_controls() {
        $this->start_controls_section('options', array(
            'label' => esc_html__('Options'),
        ));

        $posts = wp_list_pluck(get_posts(['post_type' => [$this->getPostTypeName()], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'elementor'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), [$this->getPostTypeName()]) ? get_the_ID() : ''
            ]
        );

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'countries' => __('Countries', 'tourware'),
                    'additional_field' => __('Additional Field', 'tourware')
                ],
                'default' => 'countries'
            ]
        );

//        $this->add_control(
//            'field',
//            [
//                'label' => __('Field', 'elementor'),
//                'type' => Controls_Manager::SELECT,
//                'options' => wp_list_pluck(get_option('tyto_additional_fields'), 'fieldLabel', 'name'),
//                'condition' => [ 'type' => 'additional_field' ]
//            ]
//        );

        $repeater = new Repeater();

        $repeater->add_control(
            'field',
            [
                'label' => __( 'Field', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => true,
                'placeholder' => __( 'List Item', 'elementor' ),
                'default' => __( 'List Item', 'elementor' ),
                'dynamic' => [
                    'active' => true,
                ],
                'options' => wp_list_pluck(get_option('tyto_additional_fields'), 'fieldLabel', 'name'),
            ]
        );

        $repeater->add_control(
            'field_icon',
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
            'fields_list',
            [
                'label' => __( 'Items', 'elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ elementor.helpers.renderIcon( this, field_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ field }}}',
                'condition' => ['type' => 'additional_field']
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __( 'Layout', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'traditional',
                'options' => [
                    'traditional' => [
                        'title' => __( 'Default', 'elementor' ),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'inline' => [
                        'title' => __( 'Inline', 'elementor' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'render_type' => 'template',
                'classes' => 'elementor-control-start-end',
                'style_transfer' => true,
                'prefix_class' => 'elementor-icon-list--layout-',
            ]
        );

        $this->add_control(
            'icon_display',
            [
                'label' => __('Icon Display', 'tourware'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'tourware'),
                    'first' => __('Near the first item', 'tourware'),
                    'each' => __('Near each item', 'tourware')
                ],
                'default' => 'first',
                'condition' => ['type' => 'countries']
            ]
        );

        $this->add_control( 'icon', array(
            'label'         =>  esc_html__( 'Icon', 'elementor-pro' ),
            'type'          =>  Controls_Manager::ICONS,
            'default'       => [
                'value' => 'fas fa-check',
                'library' => 'fa-solid',
            ],
            'condition' => ['icon_display!' => 'none', 'type' => 'countries']
        ));

        $this->end_controls_section();


        $this->start_controls_section(
            'section_icon_list',
            [
                'label' => __( 'List', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => __( 'Space Between', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:last-child)' => 'padding-bottom: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items:not(.elementor-inline-items) .elementor-icon-list-item:not(:first-child)' => 'margin-top: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .elementor-icon-list-items.elementor-inline-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body.rtl {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'left: calc(-{{SIZE}}{{UNIT}}/2)',
                    'body:not(.rtl) {{WRAPPER}} .elementor-icon-list-items.elementor-inline-items .elementor-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_align',
            [
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => __( 'Icon', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => __( 'Hover', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-icon-list-icon svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_self_align',
            [
                'label' => __( 'Alignment', 'elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementor' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_text_style',
            [
                'label' => __( 'Text', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
            ]
        );

        $this->add_control(
            'text_color_hover',
            [
                'label' => __( 'Hover', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_indent',
            [
                'label' => __( 'Text Indent', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-text' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-list-item, {{WRAPPER}} .elementor-icon-list-item a',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post = $settings['post'] ? $settings['post'] : get_the_ID();

        if ('tytotravels' === $this->getPostTypeName()) {
            $repository = \Tourware\Repository\Travel::getInstance();
        }

        if ('tytoaccommodations' === $this->getPostTypeName()) {
            $repository = \Tourware\Repository\Accommodation::getInstance();
        }
        $item_data = $repository->findOneByPostId($post);

        $content = [];
        if ($settings['type'] == 'countries') {
            $t_countries = get_post_meta($post, 'tytocountries', true);
            if (!empty($t_countries)) {
                foreach ($t_countries as $t_country) {
                    $content[] = $t_country['official_name_de'];
                }
            }
            if (empty($countries)) {
//                $record->_destination
//                $content[] = 'Uganda';
//                $content[] = 'Namibia';
//                $content[] = 'Kenia';
            }
            //TODO use countries taxonomy
        } elseif ($settings['type'] == 'additional_field') {
//            print_r($settings);
            foreach ( $settings['fields_list'] as $index => $item ) {
                if ($af = $item_data->getAdditionalField($item['field'])) $content[] = [ 'icon' => $item['field_icon'], 'text' => $af];
            }

        }

        if (!empty($content)) {
            $this->add_render_attribute( 'icon_list', 'class', 'elementor-icon-list-items' );
            $this->add_render_attribute('list_item', 'class', 'elementor-icon-list-item');
            if ( 'inline' === $settings['view'] ) {
                $this->add_render_attribute( 'icon_list', 'class', 'elementor-inline-items' );
                $this->add_render_attribute('list_item', 'class', 'elementor-inline-item');
            }
            include Path::getResourcesFolder() . 'layouts/' . $this->getRecordTypeName() . '/details/template.php';
        }
    }
}
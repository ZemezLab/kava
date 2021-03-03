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

    /**
     * @throws \Exception
     * @return array
     */
    protected function getContent($post)
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

        $options = [
            'countries' => __('Countries', 'tourware'),
            'tags' => __('Tags', 'tourware'),
            'additional_field' => __('Additional Field', 'tourware'),
            'contact_person' => __('Contact Person', 'tourware')
        ];
        if ('tytotravels' === $this->getPostTypeName()) {
            $options['persons'] = __('Persons', 'tourware');
            $options['duration'] = __('Duration', 'tourware');
            $options['dates'] = __('Dates', 'tourware');
            $options['price'] = __('Price', 'tourware');
        }

        $this->add_control(
            'type',
            [
                'label' => __('Type', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => 'countries'
            ]
        );

        $additional_fields = wp_list_pluck(get_option('tyto_additional_fields'), 'fieldLabel', 'name');
        $this->addItemsListRepeater('additional_fields_list', $additional_fields, ['type' => 'additional_field']);

        $contact_fields = [
            'name' => __( 'Name', 'elementor' ),
            'phone' => __( 'Phone', 'elementor' ),
            'mobile' => __( 'Mobile', 'elementor' ),
            'email' => __( 'Email', 'elementor' ),
            'website' => __( 'Website', 'elementor' ),
        ];
        $this->addItemsListRepeater('contact_fields_list', $contact_fields, ['type' => 'contact_person']);

        $tags_taxomomy = get_terms(['taxonomy' => 'tytotags', 'hide_empty' => false]);
        $tags = wp_list_pluck( $tags_taxomomy, 'name', 'id' );
        $this->add_control(
            'tags',
            [
                'label' => __('Tags', 'tourware'),
                'type' => Controls_Manager::SELECT2,
                'options' => $tags,
                'multiple' => true,
                'condition' => ['type' => 'tags']
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
                'condition' => ['type' => ['countries', 'tags']]
            ]
        );

        $this->add_control( 'icon', array(
            'label'         =>  esc_html__( 'Icon', 'elementor-pro' ),
            'type'          =>  Controls_Manager::ICONS,
            'default'       => [
                'value' => 'fas fa-check',
                'library' => 'fa-solid',
            ],
            'condition' => ['icon_display!' => 'none', 'type' => ['countries', 'tags', 'persons', 'duration', 'dates', 'price']]
        ));

        $this->add_control(
            'prefix',
            [
                'label'         =>  esc_html__( 'Prefix', 'tourware' ),
                'type'          =>  Controls_Manager::TEXT,
                'condition' => ['type' => ['persons', 'duration', 'price']]
            ]
        );

        $this->add_control(
            'suffix',
            [
                'label'         =>  esc_html__( 'Suffix', 'tourware' ),
                'type'          =>  Controls_Manager::TEXT,
                'condition' => ['type' => ['persons', 'duration', 'price']]
            ]
        );

        $this->add_control(
            'dates_table_id',
            [
                'label'         =>  esc_html__( 'Dates Table ID', 'tourware' ),
                'type'          =>  Controls_Manager::TEXT,
                'condition' => ['type' => ['dates', 'price']]
            ]
        );

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

        $content = $this->getContent($post);

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

    private function addItemsListRepeater($id, $options, $condition) {
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
                'options' => $options
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
            $id,
            [
                'label' => __( 'Items', 'elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ elementor.helpers.renderIcon( this, field_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ field }}}',
                'condition' => $condition
            ]
        );
    }
}
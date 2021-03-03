<?php

namespace Tourware\Elementor\Widget\Listing;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Tourware\Elementor\Widget;
use Tourware\Elementor\Loader; // @todo: ugly
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Group_Control_Typography;
use \Elementor\Controls_Manager;

abstract class AbstractListing extends Widget
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
     * @return string
     */
    protected function getCardLayoutOptions() {
        throw new \Exception('Needs to be implemented.');
    }

    protected function render( $instance = [] )
    {
        if ('tytotravels' === $this->getPostTypeName()) {
            $repository = \Tourware\Repository\Travel::getInstance();
        }

        if ('tytoaccommodations' === $this->getPostTypeName()) {
            $repository = \Tourware\Repository\Accommodation::getInstance();
        }

        $settings = $this->get_settings();
        $args = $this->getQueryArgs();

        if ($settings['related'] == 'yes') {
            $post = $settings['post'] ? $settings['post'] : get_the_ID();
            $main_post_record = $repository->findOneByPostId($post);

            if (!empty($main_post_record->related_items_ids)) {
                array_push($args['meta_query'], array(
                    'key' => 'tytoid',
                    'value' => $main_post_record->related_items_ids,
                    'compare' => 'IN'
                ));
            }
        }
        unset($args['s']);

        $query = new \WP_Query( $args );
        $tiny_slider_id = uniqid( 'advanced-tyto-list-id-' );
        $this->renderCarousel( $tiny_slider_id, $settings['layout'], $settings['col'], $settings['col_tablet'], $settings['col_mobile'] );
        $tiny_slider_data = $this->carouselOptions( $settings['layout'], $settings['col'], $settings['col_tablet'], $settings['col_mobile'] );
        $classes          = 'tours-layout-' . $settings['layout'] . ' ht-grid ht-grid-' . $settings['col'] . ' ht-grid-tablet-' . $settings['col_tablet'] . ' ht-grid-mobile-' . $settings['col_mobile'];
        $layout_name      = 'carousel' == $settings['layout'] ? 'not-real-slider' : '';

        include \Tourware\Path::getResourcesFolder() . 'layouts/' . $this->getRecordTypeName() . '/listing/template.php';
    }

    public function _enqueue_styles() {
        wp_enqueue_script('throttle-debounce', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/listing/jquery-throttle-debounce.js', ['jquery']);
        wp_enqueue_script('tourware-widget-abstract-listing-js', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/listing/script.js', ['jquery', 'throttle-debounce']);

        wp_localize_script('tourware-widget-abstract-listing-js', 'TytoAjaxVars',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );

        wp_enqueue_script('lazysizes-script');
    }

    protected function _register_controls() {
        /* CONTENT */
        $this->sectionLayout();
        $this->sectionQuery();
        $this->sectionCardLayout();
        $this->sectionPagination();
        $this->sectionSearch();

        /* STYLE */
        $this->sectionAttributesStyle();
    }

    private function sectionLayout() {
        $this->start_controls_section( 't_layout', array(
            'label' => esc_html__( 'Layout' ),
        ) );

        $this->add_control( 'layout', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Skin' ),
            'default' => 'grid',
            'options' => array(
                'grid'     => esc_html__( 'Grid' ),
                'carousel' => esc_html__( 'Carousel' ),
            ),
        ) );

        $this->addControl(new \Tourware\Elementor\Control\LayoutSelector($this->getRecordTypeName().'/listing'));

        $this->add_responsive_control( 'col', array(
            'type'           => Controls_Manager::SELECT,
            'label'          => esc_html__( 'Columns', 'tyto' ),
            'default'        => 3,
            'tablet_default' => 2,
            'mobile_default' => 1,
            'options'        => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
            ),
        ) );

        $this->add_control( 'per_page', array(
            'type'    => Controls_Manager::NUMBER,
            'label'   => esc_html__( 'Posts Per Page' ),
            'default' => 6,
            'min'     => - 1,
            'max'     => 100,
        ) );

        $this->add_control( 'arrows', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes' ),
            'label_off'    => esc_html__( 'No' ),
            'return_value' => 'yes',
            'condition' => ['layout' => 'carousel']
        ) );

        $this->add_control( 'dots', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Dots' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes' ),
            'label_off'    => esc_html__( 'No' ),
            'return_value' => 'yes',
            'condition' => ['layout' => 'carousel']
        ) );

        $this->add_control( 'autoplay', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Autoplay' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes' ),
            'label_off'    => esc_html__( 'No' ),
            'return_value' => 'yes',
            'condition' => ['layout' => 'carousel']
        ) );

        $this->add_control( 'autoplay_timeout', array(
            'type'      => Controls_Manager::SLIDER,
            'label'     => esc_html__( 'Autoplay Speed' ),
            'default'   => array(
                'size' => 3000
            ),
            'range'     => array(
                'px' => array(
                    'min'  => 100,
                    'max'  => 5000,
                    'step' => 100
                ),
            ),
            'condition' => array(
                'autoplay' => 'yes'
            )
        ) );

        $this->end_controls_section();
    }

    private function sectionQuery() {
        $this->start_controls_section( 't_query', array(
            'label'     => esc_html__( 'Query', 'tyto' ),
            'condition' => array(
                't_layout.per_page!' => 0
            ),
        ) );

        $this->add_control( 'show_protected', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Show password protected' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes' ),
            'label_off'    => esc_html__( 'No' ),
            'return_value' => 'yes'
        ) );

        $this->add_control( 'related', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Related' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes' ),
            'label_off'    => esc_html__( 'No' ),
            'return_value' => 'yes'
        ) );

        $posts = wp_list_pluck(get_posts(['post_type' => ['tytotravels', 'tytoaccommodations', 'tytotravelsbricks'], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'tourware'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), ['tytotravels']) ? get_the_ID() : '',
                'condition' => ['related' => 'yes'],
                'separator' => 'after'
            ]
        );

        $tags_taxomomy = get_terms(['taxonomy' => 'tytotags', 'hide_empty' => false]);
        $tags = wp_list_pluck( $tags_taxomomy, 'name', 'slug' );
        $this->add_control( 'item_tags', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Item tags' ),
            'multiple' => true,
            'options'  => $tags,
        ) );

        $countries_taxomomy = get_terms(['taxonomy' => 'tytocountries', 'hide_empty' => false]);
        $countries = wp_list_pluck( $countries_taxomomy, 'name', 'slug' );
        $this->add_control( 'destinations', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Destinations' ),
            'multiple' => true,
            'options'  => $countries,
            'default'  => get_post_type( get_the_ID() ) == 'tytodestinations' ? array( get_the_ID() ) : []
        ) );

        $regions_args = array(
            'post_type'           => 'tytoregions',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => - 1,
        );

        $reg_q  = new \WP_Query( $regions_args );
        $output = wp_list_pluck( $reg_q->posts, 'post_title', 'ID' );
        $this->add_control( 'regions', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Regions' ),
            'multiple' => true,
            'options'  => $output,
            'default'  => get_post_type( get_the_ID() ) == 'tytoregions' ? array( get_the_ID() ) : []
        ) );

        $this->add_control( 'orderby', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Order By', 'tyto' ),
            'default' => 'date',
            'options' => array(
                'priority' => esc_html__( 'Priority', 'tyto' ),
                'date'     => esc_html__( 'Date', 'tyto' ),
                'title'    => esc_html__( 'Title', 'tyto' ),
                'ID'       => esc_html__( 'ID', 'tyto' ),
                'author'   => esc_html__( 'Author', 'tyto' ),
                'rand'     => esc_html__( 'Random', 'tyto' ),
                'modified' => esc_html__( 'Modified', 'tyto' ),
            ),
        ) );

        $this->add_control( 'order', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Order', 'tyto' ),
            'default' => 'DESC',
            'options' => array(
                'ASC'  => esc_html__( 'ASC', 'tyto' ),
                'DESC' => esc_html__( 'DESC', 'tyto' ),
            ),
        ) );

        $this->end_controls_section();
    }

    private function sectionCardLayout() {
        $this->start_controls_section( 'card_layout', array(
            'label' => esc_html__( 'Card Layout' ),
        ) );

        $this->add_control(
            'open_new_tab',
            [
                'label' => __( 'Open in new window', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'elementor-pro' ),
                'label_off' => __( 'No', 'elementor-pro' ),
                'default' => 'no',
                'render_type' => 'none',
            ]
        );

        $this->addCardLayoutOptions();

        $this->end_controls_section();
    }

    private function sectionPagination() {
        $this->start_controls_section('pagination_options', [
            'label' => esc_html__('Pagination', 'tyto'),
            'condition'    => array(
                'layout' => 'grid'
            )
        ]);
        $this->add_control( 'pagi', array(
            'type'         => Controls_Manager::SELECT,
            'label'        => esc_html__( 'Pagination', 'tyto' ),
            'default'      => 'none',
            'options'      => [
                'none' => esc_html__( 'None', 'tyto' ),
                'numbers' => esc_html__( 'Numbers', 'tyto' ),
                'load_more' => esc_html__( 'Load More', 'tyto' ),
                'infinity_scroll' => esc_html__( 'Infinity Scroll', 'tyto' ),
            ]
        ) );
        $this->end_controls_section();
    }

    private function sectionSearch() {
        $this->start_controls_section('search', [
            'label' => esc_html__('Search', 'tyto'),
            'condition' => ['related!' => 'yes']
        ]);

        $this->add_control(
            'advanced_search',
            [
                'label' => __('Use Advanced Tyto Search', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
            ]
        );

        $this->add_control('adv_list_id', [
            'type' => Controls_Manager::TEXT,
            'label' => esc_html__('Advanced List ID', 'tyto'),
            'description' => 'use this ID to bind the List to Advanced Tyto Search',
            'condition' => ['advanced_search' => 'yes']
        ]);

        $this->add_control('search_not_found', [
            'type' => Controls_Manager::TEXT,
            'label' => esc_html__('Not Found Text', 'tyto'),
            'default' => __('Not found', 'tyto'),
            'condition' => ['advanced_search' => 'yes']
        ]);

        $this->end_controls_section();
    }

    private function sectionAttributesStyle() {
        $this->start_controls_section( 'style_layout', array(
            'label'     => esc_html__( 'Layout', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ) );

        $this->add_control(
            'column_gap',
            [
                'label' => __( 'Columns Gap', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ht-grid-item' => 'padding-right: calc({{SIZE}}{{UNIT}}/2);padding-left: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .ht-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2);margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .tns-ovh' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
                ],
            ]
        );

        $this->add_control(
            'row_gap',
            [
                'label' => __( 'Rows Gap', 'elementor-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 35,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'frontend_available' => true,
                'selectors' => [
                    '{{WRAPPER}} .ht-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .tours-layout-carousel .ht-grid-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->addControlGroup(['id' => 'style_box', 'type' => 'box', 'selector' => '.tour-item', 'selector_content' => ' .tour-content']);

        /* IMAGE */
        $this->addControlGroup(['id' => 'style_image', 'type' => 'image']);

        /* BADGE */
        $this->start_controls_section( 'style_badge', array(
            'label'     => esc_html__( 'Badge', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_badge' => 'yes']
        ) );

        $this->add_control('badge_background',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Background color', 'elementor-pro' ),
                'selectors' => array(
                    '{{WRAPPER}} .badge' => 'background-color: {{VALUE}};'
                ),
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control('badge_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Text color', 'elementor-pro' ),
                'selectors' => array(
                    '{{WRAPPER}} .badge' => 'color: {{VALUE}};'
                ),
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'badge_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .badge',
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control(
            'badge_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* PRICE */
        $this->start_controls_section( 'style_price', array(
            'label'     => esc_html__( 'Price', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_price' => 'yes']
        ) );

        $this->add_control('price_background_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Background color', 'elementor-pro' ),
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .price' => 'background-color: {{VALUE}};'
                ),
            ]
        );

        $this->add_control('price_text_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Text color', 'elementor-pro' ),
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .price' => 'color: {{VALUE}};'
                ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .price',
                'condition' => ['show_price' => 'yes'],
            ]
        );

        $this->end_controls_section();

        /* TITLE */
        $this->addControlGroup([
            'id'=>'style_title',
            'type' => 'attribute',
            'label' => 'Title',
            'selector' => '.title',
            'condition' => ['show_title' => 'yes']
        ]);

        /* EXCERPT */
        $this->addControlGroup([
            'id'=>'style_excerpt',
            'type' => 'attribute',
            'label' => 'Excerpt',
            'selector' => '.excerpt',
            'condition' => ['show_excerpt' => 'yes']
        ]);

        /* READ MORE */
        $this->addControlGroup([
            'id'=>'style_readmore',
            'type' => 'button',
            'label' => 'Read More',
            'selector' => '.read-more',
            'condition' => ['show_read_more' => 'yes']
        ]);

        /* DESTINATION */
        $this->addControlGroup([
            'id'=>'style_destination',
            'type' => 'attribute',
            'label' => 'Destination',
            'selector' => '.destination',
            'icon' => true,
            'icon_default' => [
                'value' => 'far fa-map',
                'library' => 'fa-regular',
            ],
            'condition' => ['show_destination' => 'yes']
        ]);

        /* DURATION */
        $this->addControlGroup([
            'id'=>'style_duration',
            'type' => 'attribute',
            'label' => 'Duration',
            'selector' => '.duration',
            'icon' => true,
            'icon_default' => [
                'value' => 'far fa-clock-o',
                'library' => 'fa-regular',
            ],
            'condition' => ['show_duration' => 'yes']
        ]);

        /* PERSONS */
        $this->addControlGroup([
            'id'=>'style_persons',
            'type' => 'attribute',
            'label' => 'Persons',
            'selector' => '.persons',
            'icon' => true,
            'icon_default' => [
                'value' => 'far fa-user',
                'library' => 'fa-regular',
            ],
            'condition' => ['show_persons' => 'yes']
        ]);

        /* CATEGORIES */
        $this->addControlGroup([
            'id'=>'style_categories',
            'type' => 'attribute',
            'label' => 'Categories',
            'selector' => '.categories',
            'icon' => true,
            'icon_default' => [
                'value' => 'fas fa-tags',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_categories' => 'yes']
        ]);

        /* SCORES */
        $this->start_controls_section(
            'scores_style',
            [
                'label' => __( 'Scores', 'tourware' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_scores' => 'yes']
            ]
        );
        $this->add_responsive_control(
            'scores_space_between',
            [
                'label' => __( 'Space Between', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .score' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'scores_icon_color',
            [
                'label' => __( 'Icon Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .score i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .score svg' => 'fill: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'scores_icon_color_hover',
            [
                'label' => __( 'Icon Hover', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .score:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .score:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'scores_icon_size',
            [
                'label' => __( 'Icon Size', 'elementor' ),
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
                    '{{WRAPPER}} .score i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .score svg' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'scores_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .score .text' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
            ]
        );

        $this->add_control(
            'text_color_hover',
            [
                'label' => __( 'Text Hover', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .score:hover .text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'scores_text_indent',
            [
                'label' => __( 'Text Indent', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .score .text' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'scores_text_typography',
                'selector' => '{{WRAPPER}} .score .text',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_control(
            'scores_spacing',
            [
                'label' => __( 'Spacing', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .scores' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* FLIGHT */
        $this->start_controls_section(
            'flight_style',
            [
                'label' => __( 'Flight', 'tourware' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_flight' => 'yes']
            ]
        );
        $this->add_control(
            'flight_text_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .flight' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'flight_text_typography',
                'selector' => '{{WRAPPER}} .flight',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_control(
            'flight_spacing',
            [
                'label' => __( 'Spacing', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .flight' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* PAGINATION */
        $this->start_controls_section( 'pagination', array(
            'label'     => esc_html__( 'Pagination', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'conditions' => [
                'relation' => 'or',
                'terms' => [
                    [
                        'name' => 'pagi',
                        'operator' => 'in',
                        'value' => ['numbers', 'load_more']
                    ]
                ],
            ]
        ) );

        $this->add_control( 'pagination_align', array(
            'type'           => Controls_Manager::CHOOSE,
            'label'          => esc_html__( 'Alignment', 'tyto' ),
            'options'        => array(
                'left'   => array(
                    'title' => esc_html__( 'Left', 'tyto' ),
                    'icon'  => 'fa fa-align-left'
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'tyto' ),
                    'icon'  => 'fa fa-align-center'
                ),
                'right'  => array(
                    'title' => esc_html__( 'Right', 'tyto' ),
                    'icon'  => 'fa fa-align-right'
                ),
            ),
            'default'        => 'left',
            'selectors'      => array(
                '{{WRAPPER}} .advanced-tyto-list div.page-numbers' => 'text-align: {{VALUE}};',
                '{{WRAPPER}} .advanced-tyto-list ul.page-numbers' => 'text-align: {{VALUE}};'
            ),
        ));

        $this->add_control('pagination_button_text',
            [
                'type'      => Controls_Manager::TEXT,
                'label'     => esc_html__( 'Button text', 'tyto' ),
                'default'   => __('Mehr laden', 'tyto'),
                'condition' => [ 'pagi' => 'load_more' ]
            ]
        );
        $this->end_controls_section();

        /* PAGINATION LOAD MORE */
        $this->addControlGroup([
            'id' => 'load_more_button',
            'type' => 'button',
            'label' => 'Load More Button',
            'selector' => '.elementor-button.page-numbers',
            'condition' => ['pagi' => 'load_more']
        ]);

        /* ARROWS */
        $this->addControlGroup([
            'id' => 'arrows',
            'type' => 'arrows',
            'condition' => [
               'layout' => 'carousel',
               'arrows' => 'yes'
            ]
        ]);

        /* DOTS */
        $this->addControlGroup([
            'id' => 'dots',
            'type' => 'dots',
            'condition' => [
                'layout' => 'carousel',
                'dots' => 'yes'
            ]
        ]);
    }


    protected function addCardLayoutOptions() {
        $options = $this->getCardLayoutOptions();
        foreach ($options as $option) {
            if (method_exists($this, 'options'.ucfirst($option)))
                call_user_func([$this, 'options'.ucfirst($option)]);
        }
    }

    protected function optionsTitle() {
        $this->add_control(
            'heading_title_options',
            [
                'label' => __( 'Title', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_title',
            [
                'label' => __( 'Show', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __( 'HTML Tag', 'elementor-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
                'condition' => [ 'show_title' => 'yes'],
            ]
        );

        $this->add_control(
            'title_length',
            [
                'label' => __( 'Length', 'tourware' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 150
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 300,
                        'step' => 1
                    ),
                ),
                'size_units' => array( 'px' ),
                'condition' => ['show_title' => 'yes']
            ]
        );
    }

    protected function optionsBadge()
    {
        $tags_taxomomy = get_terms(['taxonomy' => 'tytotags', 'hide_empty' => false]);
        $tags = wp_list_pluck( $tags_taxomomy, 'name', 'name' );
        $this->add_control(
            'heading_badge_options',
            [
                'label' => __( 'Badge', 'tourware' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => __('Show', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-pro'),
                'label_off' => __('Hide', 'elementor-pro'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'badge_tag',
            [
                'label' => __('Tags', 'elementor-pro'),
                'type' => Controls_Manager::SELECT2,
                'options' => $tags,
                'multiple' => true,
                'condition' => ['show_badge' => 'yes'],
            ]
        );
    }

    protected function optionsPrice() {
        $this->add_control(
            'heading_price_options',
            [
                'label' => __( 'Price', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => __( 'Show', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'price_prefix',
            [
                'label' => __( 'Prefix', 'tourware' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'ab € ',
                'condition' => ['show_price' => 'yes']
            ]
        );
        $this->add_control(
            'price_suffix',
            [
                'label' => __( 'Suffix', 'tourware' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / pro Person',
                'condition' => ['show_price' => 'yes']
            ]
        );
    }

    protected function optionsExcerpt() {
        $this->add_control(
            'heading_excerpt_options',
            [
                'label' => __( 'Excerpt', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_excerpt',
            [
                'label' => __( 'Excerpt', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'excerpt_length',
            [
                'label' => __( 'Excerpt Length', 'tyto' ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 150
                ),
                'range'      => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1000,
                        'step' => 1
                    ),
                ),
                'size_units' => array( 'px' ),
                'condition' => ['show_excerpt' => 'yes']
            ]
        );
    }

    protected function optionsReadmore() {
        $this->add_control(
            'heading_readmore_options',
            [
                'label' => __( 'Read More', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_read_more',
            [
                'label' => __( 'Show', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __( 'Text', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Read More »', 'elementor-pro' ),
                'condition' => [ 'show_read_more' => 'yes' ],
            ]
        );
    }

    protected function optionsDestination() {
        $this->add_control(
            'heading_destination_options',
            [
                'label' => __( 'Destination', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_destination',
            [
                'label' => __( 'Show', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
            ]
        );
    }

    protected function optionsCategories() {
        $tags_taxomomy = get_terms(['taxonomy' => 'tytotags', 'hide_empty' => false]);
        $tags = wp_list_pluck( $tags_taxomomy, 'name', 'name' );
        $this->add_control(
            'heading_categories_options',
            [
                'label' => __( 'Categories', 'elementor-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_categories',
            [
                'label' => __( 'Show', 'elementor-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'categories_tags',
            [
                'label' => __( 'Tags for categories', 'tourware' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $tags,
                'condition' => ['show_categories' => 'yes'],
            ]
        );
    }

    public function renderCarousel( $tiny_slider_id, $layout, $col_desktop, $col_tablet, $col_mobile ) {
        $settings = $this->get_settings_for_display();

        if ( 'carousel' != $layout ) {
            return;
        }

        /*ENQUEUE SCRIPT AND STYLE*/
        wp_enqueue_style( 'tiny-slider' );
        wp_enqueue_script( 'tiny-slider-js' );


        /*GET TINY SLIDER OPTIONS*/
        $arrows        = 'yes' == $settings['arrows'] ? 1 : 0;
        $arrows_tablet = 'yes' == $settings['arrows'] && 'yes' == $settings['arrows_tablet'] ? 0 : 1;
        $arrows_mobile = 'yes' == $settings['arrows'] && 'yes' == $settings['arrows_mobile'] ? 0 : 1;
        $dots          = 'yes' == $settings['dots'] ? 1 : 0;
        $dots_tablet   = 'yes' == $settings['dots'] && 'yes' == $settings['dots_tablet'] ? 0 : 1;
        $dots_mobile   = 'yes' == $settings['dots'] && 'yes' == $settings['dots_mobile'] ? 0 : 1;

        $autoplay = $settings['autoplay'] == 'yes' ? 1 : 0;
        $autoplayTimeout = $settings['autoplay_timeout']['size'] ? $autoplayTimeout = $settings['autoplay_timeout']['size'] : 0;
        $loop = $settings['autoplay'] == 'yes' ? 1 : 0;
        $slide_by_mob = $settings['autoplay'] == 'yes' ? $col_mobile : 1;
        $slide_by_tab = $settings['autoplay'] == 'yes' ? $col_tablet : 1;
        $slide_by_desk = $settings['autoplay'] == 'yes' ? $col_desktop : 1;
        $speed = $settings['autoplay'] == 'yes' ? 1000 : 300;

        wp_add_inline_script(
            'tiny-slider-js',
            "document.addEventListener( 'DOMContentLoaded', function(){
                var _arr        = 1 == {$arrows} ? true : false,
					_arr_tablet = _arr && 1 == {$arrows_tablet} ? true : false,
					_arr_mobile = _arr && 1 == {$arrows_mobile} ? true : false,
					_dot        = 1 == {$dots} ? true : false,
					_dot_tablet = _dot && 1 == {$dots_tablet} ? true : false,
					_dot_mobile = _dot && 1 == {$dots_mobile} ? true : false;

                var slider = tns({
                    container: '#{$tiny_slider_id}',
                    controls: _arr_mobile,
                    nav: _dot_mobile,
                    items: {$col_mobile},
                    autoHeight: true,
                    autoplay: {$autoplay},
                    autoplayTimeout: {$autoplayTimeout},
                    autoplayButton: false,
                    autoplayButtonOutput: false,
                    mouseDrag: true,
                    lazyload: true,
                    loop: {$loop},
                    slideBy: {$slide_by_mob},
                    speed: {$speed},
                    responsive: {
                        768: {
                            items: {$col_tablet},
                            controls: _arr_tablet,
                            nav: _dot_tablet,
                            slideBy: {$slide_by_tab}
                        },
                        1024: {
                            items: {$col_desktop},
                            controls: _arr,
                            nav: _dot,
                            slideBy: {$slide_by_desk}
                        }
                    }
                });
            } );",
            'after'
        );
        ?>
    <?php }

    /*RENDER CAROUSEL FOR FRONT-END VIEW*/
    protected function carouselOptions( $layout, $col_desktop, $col_tablet, $col_mobile ) {
        $settings = $this->get_settings_for_display();

        if ( 'carousel' != $layout ) {
            return '';
        }

        $options = array(
            "items"      => intval( $col_mobile ),
            "controls"   => 'yes' == $settings['arrows'] && 'yes' != $settings['arrows_mobile'] ? true : false,
            "nav"        => 'yes' == $settings['dots'] && 'yes' != $settings['dots_mobile'] ? true : false,
            "lazyload"   => true,
            "autoHeight" => true,
            'slideBy' => $col_mobile,
            "responsive" => array(
                768  => array(
                    "items"    => intval( $col_tablet ),
                    "controls" => 'yes' == $settings['arrows'] && 'yes' != $settings['arrows_tablet'] ? true : false,
                    "nav"      => 'yes' == $settings['dots'] && 'yes' != $settings['dots_tablet'] ? true : false,
                    'slideBy' => $col_tablet
                ),
                1024 => array(
                    "items"    => intval( $col_desktop ),
                    "controls" => 'yes' == $settings['arrows'] ? true : false,
                    "nav"      => 'yes' == $settings['dots'] ? true : false,
                    'slideBy' => $col_desktop
                ),
            ),
            'autoplay' => $settings['autoplay'] == 'yes' ? 1 : 0,
            'autoplayTimeout' => $settings['autoplay_timeout']['size'] ? $autoplayTimeout = $settings['autoplay_timeout']['size'] : 0,
            'autoplayButton' => 0,
            'autoplayButtonOutput' => 0,
            'loop' => $settings['autoplay'] == 'yes' ? 1 : 0,
            'speed' => $settings['autoplay'] == 'yes' ? 1000 : 300,

        );

        $tiny_slider_data = "data-tiny-slider='" . json_encode( $options ) . "'";

        return $tiny_slider_data;
    }

    protected function renderPagination($query, $settings) {
        $args = $this->getQueryArgs();
        ob_start();
        Loader::renderListPagination($query, $settings);
        $r = ob_get_clean(); ?>
        <nav class="tyto-pagination" data-args="<?php echo esc_attr(json_encode($args)) ?>" data-post_id="<?php echo get_the_ID() ?>">
            <span class="screen-reader-text"><?php esc_html_e('Posts pagination', 'goto'); ?></span>
            <?php echo wp_kses_post($r); ?>
        </nav>
        <?php
    }

    protected function getQueryArgs() {
        $settings = $this->get_settings_for_display();

        $paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
        $paged = $paged ? intval( $paged ) : 1;

        $args       = array(
            'post_type'           => $this->getPostTypeName(),
            'ignore_sticky_posts' => 1,
            'post_status'         => 'publish',
            'posts_per_page'      => $settings['per_page'],
            'orderby'             => $settings['orderby'],
            'order'               => $settings['order'],
            'paged'               => $paged,
        );

        if ($settings['show_protected'] !== 'yes') $args['has_password'] = 0;

        if ($settings['orderby'] == 'priority') {
            $args['meta_key'] = 'tytopriority';
            $args['orderby'] = 'meta_value_num';
        }

        // check if there is default advanced search category
        // only on frontend
        $args['meta_query'] = ['relation' => 'AND'];

        if ($settings['related'] !== 'yes') {
            $search_tag = $search_str = '';
            if ($settings['adv_list_id'] && !empty($_GET['adv_list_id']) && $_GET['adv_list_id'] == $settings['adv_list_id']) {
                if (isset($_GET['category']) && !empty($_GET['category']))
                    $search_tag = $_GET['category'];
                else
                    $search_tag = get_query_var('advanced_search_category_'.$settings['adv_list_id']);

                if (isset($_GET['keywords']) )  $search_str = $_GET['keywords'];
                else $search_str = get_query_var('s');

                if ($search_str) $post_ids = Loader::getPostIDsByKeywords($search_str);
                $args['post__in'] = $post_ids;
            }
            if (!empty($search_tag) && !is_admin()) {
                $search_tags = explode(',', urldecode($search_tag));
                $search_tags_args = ['relation' => 'OR'];
                foreach ($search_tags as $s_tag) {
                    array_push( $search_tags_args, array(
                        'key'     => 'tytorawdata',
                        'value'   => '"name":"' . $s_tag . '"',
                        'compare' => 'LIKE'
                    ) );
                }
                if (!empty($search_tags_args)) $args['meta_query']['search_tag'] = $search_tags_args;
            }
        }

        if (!empty($settings['item_tags']) || !empty($settings['destinations']) || !empty($settings['regions'])) {

            if (!empty($settings['item_tags'])) {
                $args['tag'] = implode(',', $settings['item_tags']);
            }

            if (!empty($settings['destinations'])) {
                $args['tytocountries'] = implode(',', $settings['destinations']);
            }

            if (!empty($settings['regions'])) {
                $reg_args = array('relation' => 'OR',);
                foreach ($settings['regions'] as $reg_id) {
                    $reg_title = get_the_title($reg_id);
                    array_push($reg_args, array(
                        'key' => 'tytorawdata',
                        'value' => '"_region":"' . addcslashes($reg_title, '/') . '"',
                        'compare' => 'LIKE'
                    ));
                }

                array_push($args['meta_query'], $reg_args);
            }
        }
        return $args;
    }

}
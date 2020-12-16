<?php

namespace Tourware\Elementor\Widget\Listing;

use Tourware\Elementor\Widget;
use Tourware\Elementor\Loader; // @todo: ugly
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Group_Control_Typography;
use \Elementor\Controls_Manager;
use \Elementor\Plugin;
use \Elementor\Core\Schemes as Schemes;
use Tourware\Path;

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
        unset($args['s']);
        $query = new \WP_Query( $args );
        $tiny_slider_id = uniqid( 'advanced-tyto-list-id-' );
        $this->renderCarousel( $tiny_slider_id, $settings['layout'], $settings['col'], $settings['col_tablet'], $settings['col_mobile'] );
        $tiny_slider_data = $this->carouselOptions( $settings['layout'], $settings['col'], $settings['col_tablet'], $settings['col_mobile'] );
        $classes          = 'tours-layout-' . $settings['layout'] . ' ht-grid ht-grid-' . $settings['col'] . ' ht-grid-tablet-' . $settings['col_tablet'] . ' ht-grid-mobile-' . $settings['col_mobile'];
        $layout_name      = 'carousel' == $settings['layout'] ? 'not-real-slider' : '';

        include_once \Tourware\Path::getResourcesFolder() . '/layouts/' . $this->getRecordTypeName() . '/listing/template.php';
    }

    public function _enqueue_styles() {
        wp_enqueue_script('throttle-debounce', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/listing/jquery-throttle-debounce.js', ['jquery']);
        wp_enqueue_script('tourware-widget-abstract-listing-js', \Tourware\Path::getResourcesUri() . 'js/widget/abstract/listing/script.js', ['jquery', 'throttle-debounce']);

        wp_localize_script($this->get_name().'-js', 'TytoAjaxVars',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
            )
        );

        wp_enqueue_script('lazysizes-script');
    }

    protected function _register_controls() {
        /* CONTENT */
        $this->sectionLayout();
        $this->sectionQueryAndCardLayout();

        /* STYLE */
        $this->sectionAttributes();
        $this->sectionArrows();
        $this->sectionDots();

        /* LIST ID */
        $this->start_controls_section('search', [
            'label' => esc_html__('Search', 'tyto')
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

    private function sectionLayout() {
        $this->start_controls_section( 't_layout', array(
            'label' => esc_html__( 'Layout' ),
        ) );

        $this->add_control( 'layout', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Layout' ),
            'default' => 'grid',
            'options' => array(
                'grid'     => esc_html__( 'Grid' ),
                'carousel' => esc_html__( 'Carousel' ),
            ),
        ) );

        $this->addControl(new \Tourware\Elementor\Control\LayoutSelector('travel/listing'));

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

        $this->add_control( 'pagi', array(
            'type'         => Controls_Manager::SELECT,
            'label'        => esc_html__( 'Pagination', 'tyto' ),
            'default'      => 'none',
            'options'      => [
                'none' => esc_html__( 'None', 'tyto' ),
                'numbers' => esc_html__( 'Numbers', 'tyto' ),
                'load_more' => esc_html__( 'Load More', 'tyto' ),
                'infinity_scroll' => esc_html__( 'Infinity Scroll', 'tyto' ),
            ],
            'condition'    => array(
                'layout' => 'grid'
            )
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

    private function sectionQueryAndCardLayout() {
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

        $tags = [];
        if ($tyto_tags = get_option('tyto_tags', false)) $tags = wp_list_pluck($tyto_tags, 'name', 'id');

        $this->add_control( 'item_tags', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Item tags' ),
            'multiple' => true,
            'options'  => $tags,
        ) );

        $theme = wp_get_theme();
        if ($theme->parent() == 'Goto' )
            $dest_post_type = 'ht_dest';
        else
            $dest_post_type = 'tytodestinations';
        $destinations_args = array(
            'post_type'           => $dest_post_type,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => - 1,
        );
        $dest_q            = new \WP_Query( $destinations_args );
        $output            = wp_list_pluck( $dest_q->posts, 'post_title', 'ID' );
        $this->add_control( 'destinations', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Destinations' ),
            'multiple' => true,
            'options'  => $output,
            'default'  => get_post_type( get_the_ID() ) == $dest_post_type ? array( get_the_ID() ) : []
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

        $this->start_controls_section( 'card_layout', array(
            'label' => esc_html__( 'Card Layout' ),
        ) );

        $this->add_control(
            'title_length',
            [
                'label' => __( 'Title Length', 'tyto' ),
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
            ]
        );

        $this->add_control(
            'show_badge',
            [
                'label' => __( 'Badge', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
            ]
        );

        $tags = [];
        if ($tyto_tags = get_option('tyto_tags', false)) $tags = wp_list_pluck($tyto_tags, 'name', 'id');

        $this->add_control(
            'badge_tag',
            [
                'label' => __( 'Badge Tag', 'tyto' ),
                'type' => Controls_Manager::SELECT2,
                'options' => $tags,
                'multiple' => true,
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control(
            'show_price',
            [
                'label' => __( 'Price', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'price_prefix',
            [
                'label' => __( 'Price prefix', 'tyto' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'ab € ',
                'condition' => ['show_price' => 'yes']
            ]
        );
        $this->add_control(
            'price_suffix',
            [
                'label' => __( 'Price suffix', 'tyto' ),
                'type' => Controls_Manager::TEXT,
                'default' => ' / pro Person',
                'condition' => ['show_price' => 'yes']
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
                'separator' => 'before',
                'condition' => ['design!' => 'clean']
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

        $this->add_control(
            'show_duration',
            [
                'label' => __( 'Duration', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control( 'duration_prefix', array(
            'label'         =>  esc_html__( 'Duration prefix', 'tyto' ),
            'type'          =>  Controls_Manager::TEXT,
            'default'       =>  '',
            'condition' => ['show_duration' => 'yes']
        ));

        $this->add_control(
            'show_persons',
            [
                'label' => __( 'Persons', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control( 'persons_suffix', array(
            'label'         =>  esc_html__( 'Persons suffix', 'tyto' ),
            'type'          =>  Controls_Manager::TEXT,
            'default'       =>  '',
            'condition' => ['show_persons' => 'yes']
        ));

        $this->add_control(
            'show_destination',
            [
                'label' => __( 'Destination', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_categories',
            [
                'label' => __( 'Categories', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'tyto' ),
                'label_off' => __( 'Hide', 'tyto' ),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'categories_tags',
            [
                'label' => __( 'Tags for categories', 'tyto' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $tags,
                'condition' => ['show_categories' => 'yes'],
            ]
        );

        $this->end_controls_section();
    }

    protected function sectionArrows() {
        $this->start_controls_section( 'd_arrows', array(
            'label'     => esc_html__( 'Arrows', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => array(
                'd_layout.layout' => 'carousel',
                'd_layout.arrows' => 'yes',
            ),
        ) );

        $this->add_control( 'arrows', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows', 'tyto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'tyto' ),
            'label_off'    => esc_html__( 'No', 'tyto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ) );

        $this->add_control( 'arrows_size', array(
            'type'       => Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Size', 'tyto' ),
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
                '{{WRAPPER}} .tns-controls [data-controls]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ),
        ) );

        $this->add_control( 'arrows_position', array(
            'type'       => Controls_Manager::SLIDER,
            'label'      => esc_html__( 'Horizontal Pisition', 'tyto' ),
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
                '{{WRAPPER}} .tns-controls [data-controls="next"]' => 'right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .tns-controls [data-controls="prev"]' => 'left: {{SIZE}}{{UNIT}};',
            ),
        ) );

        $this->add_control( 'arrows_color', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Color', 'tyto' ),
            'default'   => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .tns-controls [data-controls]' => 'color: {{VALUE}};'
            ),
        ) );

        $this->add_control( 'arrows_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Background color', 'tyto' ),
            'default'   => '#aaa',
            'selectors' => array(
                '{{WRAPPER}} .tns-controls [data-controls]' => 'background-color: {{VALUE}};'
            ),
        ) );

        $this->add_control( 'arrows_tablet', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Tablet', 'tyto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'tyto' ),
            'label_off'    => esc_html__( 'No', 'tyto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel',
            )
        ) );

        $this->add_control( 'arrows_mobile', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Mobile', 'tyto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'tyto' ),
            'label_off'    => esc_html__( 'No', 'tyto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ) );

        $this->end_controls_section();
    }

    protected function sectionDots() {
        $this->start_controls_section( 'd_dots', array(
            'label'     => esc_html__( 'Dots', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => array(
                'd_layout.layout' => 'carousel',
                'd_layout.dots'   => 'yes',
            ),
        ) );

        $this->add_control( 'dots', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Dots', 'tyto' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes', 'tyto' ),
            'label_off'    => esc_html__( 'No', 'tyto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ) );

        $this->add_responsive_control( 'd_align', array(
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
            'default'        => 'center',
            'tablet_default' => 'center',
            'mobile_default' => 'center',
            'selectors'      => array(
                '{{WRAPPER}} .tns-nav' => 'text-align: {{VALUE}};'
            ),
        ) );

        $this->add_control( 'dots_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Background color', 'tyto' ),
            'default'   => 'rgba( 255, 255, 255, 0.3 )',
            'selectors' => array(
                '{{WRAPPER}} .tns-nav button' => 'background-color: {{VALUE}};'
            ),
        ) );

        $this->add_control( 'dots_active_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Current background color', 'tyto' ),
            'default'   => '#eeeeee',
            'selectors' => array(
                '{{WRAPPER}} .tns-nav button.tns-nav-active' => 'background-color: {{VALUE}};'
            ),
        ) );

        $this->add_control( 'dots_tablet', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Tablet', 'tyto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'tyto' ),
            'label_off'    => esc_html__( 'No', 'tyto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel',
            )
        ) );

        $this->add_control( 'dots_mobile', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Mobile', 'tyto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'tyto' ),
            'label_off'    => esc_html__( 'No', 'tyto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ) );

        $this->end_controls_section();
    }

    protected function sectionAttributes() {
        $primary_color = get_theme_mod('accent_color');
        $invert_text_color = get_theme_mod('invert_text_color');;

        $this->start_controls_section( 'card', array(
            'label'     => esc_html__( 'Card', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ) );

        $this->add_control('card_background',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Background', 'tyto' ),
                'default'   => '#fff',
                'selectors' => array(
                    '{{WRAPPER}} .tour-item' => 'background-color: {{VALUE}};'
                ),

            ]
        );
        $this->end_controls_section();

        $this->start_controls_section( 'excerpt_styles', array(
            'label'     => esc_html__( 'Excerpt', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [ 'show_excerpt' => 'yes' ]
        ) );

        $this->add_control( 'excerpt_align', array(
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
                '{{WRAPPER}} .advanced-tyto-list .tour-excerpt' => 'text-align: {{VALUE}};'
            ),
        ));

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'label'    => __( 'Typography', 'elementor' ),
                'scheme'   => Schemes\Typography::TYPOGRAPHY_3,
                'selector' => '{{WRAPPER}} .advanced-tyto-list .tour-excerpt',
            ]
        );

        $theme = wp_get_theme();
        if ($theme->parent() == 'Kava' )
            $default_color = get_theme_mod('primary_text_color');
        $this->add_control('text_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Text color', 'tyto' ),
                'default'   => isset($default_color) ? $default_color : '#555',
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .tour-excerpt' => 'color: {{VALUE}};',
                ),
            ]
        );
        $this->end_controls_section();

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

        $this->add_control( 'pagination_border_radius', array(
            'type'    => Controls_Manager::SLIDER,
            'label'   => esc_html__( 'Button border radius', 'tyto' ),
            'default' => array(
                'size' => 50,
            ),
            'range' => array(
                'px' => array(
                    'min'  => 0,
                    'max'  => 50,
                    'step' => 1,
                )
            ),
            'size_units' => array( 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} .advanced-tyto-list .page-numbers a'  => 'border-radius: {{SIZE}}{{UNIT}};',
            ),
            'condition' => [ 'pagi' => 'numbers' ]
        ));

        $this->add_control( 'pagination_load_more_border_radius', array(
            'type'    => Controls_Manager::SLIDER,
            'label'   => esc_html__( 'Button border radius', 'tyto' ),
            'default' => array(
                'size' => 0,
            ),
            'range' => array(
                'px' => array(
                    'min'  => 0,
                    'max'  => 100,
                    'step' => 1,
                )
            ),
            'size_units' => array( 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} .advanced-tyto-list .page-numbers.load-more a'  => 'border-radius: {{SIZE}}{{UNIT}};',
            ),
            'condition' => [ 'pagi' => 'load_more' ]
        ));

        $this->add_control('pagination_button_background_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Button background color', 'tyto' ),
                'default'   => isset($primary_color) ? $primary_color : '#fff',
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers.load-more a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers.load-more a:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a.current' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a.current:hover' => 'color: {{VALUE}};border-color: {{VALUE}}',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a:not(.current)' => 'border-color: {{VALUE}}; color: {{VALUE}}',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a:not(.current):hover' => 'background-color: {{VALUE}};',

                ),
            ]
        );
        $this->add_control('pagination_button_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Button text color, button hover color', 'tyto' ),
                'default'   => '#fff',
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers.load-more a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers.load-more a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a:not(.current)' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a:not(.current):hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .advanced-tyto-list .page-numbers:not(.load-more) a.current:hover' => 'background-color: {{VALUE}};',

                ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'attributes', array(
            'label'     => esc_html__( 'Attributes', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
        ) );

        $this->add_control( 'attributes_align', array(
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
                '{{WRAPPER}} .tour-attributes' => 'text-align: {{VALUE}};'
            ),
        ));

        $this->add_control(
            'attributes_font_size',
            [
                'label' => __( 'Font Size', 'tyto' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 13,
                ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 16,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tour-attributes .tour-attribute' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'attributes_font_weight',
            [
                'label' => __( 'Font Bold', 'tyto' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Bold', 'tyto' ),
                'label_off' => __( 'Normal', 'tyto' ),
                'default' => 'normal',
                'return_value' => 'bold',
                'selectors' => [
                    '{{WRAPPER}} .tour-attributes .tour-attribute' => 'font-weight: {{VALUE}};',
                ],
            ]
        );

        $this->add_control('attributes_text_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Text color', 'tyto' ),
                'default'   => '#999',
                'selectors' => array(
                    '{{WRAPPER}} .tour-attributes .tour-attribute' => 'color: {{VALUE}};'
                ),

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'style_badge', array(
            'label'     => esc_html__( 'Badge', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_badge' => 'yes']
        ) );

        $this->add_control('badge_background',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Badge background color', 'tyto' ),
                'default'   => '#e83f53',
                'selectors' => array(
                    '{{WRAPPER}} .tour-label' => 'background-color: {{VALUE}};'
                ),
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->add_control('badge_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Badge text color', 'tyto' ),
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .tour-label' => 'color: {{VALUE}};'
                ),
                'condition' => ['show_badge' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'style_price', array(
            'label'     => esc_html__( 'Price', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_price' => 'yes']
        ) );

        $this->add_control('price_voyage_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Price text color', 'tyto' ),
                'default'   => empty($invert_text_color) ? '#ffffff' : $invert_text_color,
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .tour-item .price' => 'color: {{VALUE}};'
                ),
            ]
        );

        $this->add_control('price_background_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Price background color', 'tyto' ),
                'default'   => empty($primary_color) ? '#FF4A52' : $primary_color,
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .tour-item .price' => 'background-color: {{VALUE}};'
                ),
            ]
        );

        $this->add_control('price_text_color',
            [
                'type'      => Controls_Manager::COLOR,
                'label'     => esc_html__( 'Price text color', 'tyto' ),
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .advanced-tyto-list .tour-item  .price' => 'color: {{VALUE}};'
                ),
                'condition' => ['design' => ['goto', 'grand-tour']],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'style_duration', array(
            'label'     => esc_html__( 'Duration', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_duration' => 'yes']
        ) );

        $this->add_control( 'icon_duration', array(
            'label'         =>  esc_html__( 'Icon duration', 'tyto' ),
            'type'          =>  Controls_Manager::ICONS,
            'default' => [
                'value' => 'fa fa-clock-o',
                'library' => 'regular',
            ],
            'condition' => ['show_duration' => 'yes']
        ));

        $this->end_controls_section();

        $this->start_controls_section( 'style_persons', array(
            'label'     => esc_html__( 'Persons', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_persons' => 'yes']
        ) );

        $this->add_control( 'icon_persons', array(
            'label'         =>  esc_html__( 'Persons Icon', 'tyto' ),
            'type'          =>  Controls_Manager::ICONS,
            'default' => [
                'value' => 'far fa-user',
                'library' => 'fa-regular',
            ],
            'condition' => ['show_persons' => 'yes']
        ));

        $this->end_controls_section();

        $this->start_controls_section( 'style_destination', array(
            'label'     => esc_html__( 'Destination', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_destination' => 'yes']
        ) );

        $this->add_control( 'icon_destination', array(
            'label'         =>  esc_html__( 'Icon destination', 'tyto' ),
            'type'          =>  Controls_Manager::ICONS,
            'default' => [
                'value' => 'far fa-map',
                'library' => 'fa-regular',
            ],
            'condition' => ['show_duration' => 'yes']
        ));

        $this->end_controls_section();

        $this->start_controls_section( 'style_categories', array(
            'label'     => esc_html__( 'Categories', 'tyto' ),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => ['show_categories' => 'yes']
        ) );

        $this->add_control( 'icon_categories', array(
            'label'         =>  esc_html__( 'Icon for categories', 'tyto' ),
            'type'          =>  Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-tags',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_categories' => 'yes']
        ));

        $this->end_controls_section();
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
        if (!empty($settings['item_tags']) || !empty($settings['destinations']) || !empty($settings['regions'])) {
            if (!empty($settings['item_tags'])) {
                $tags_args = array('relation' => 'OR',);
                foreach ($settings['item_tags'] as $tag) {
                    array_push($tags_args, array(
                        'key' => 'tytorawdata',
                        'value' => '"id":"' . $tag . '"',
                        'compare' => 'LIKE'
                    ));
                }
            }
            if (!empty($tags_args)) array_push($args['meta_query'], $tags_args);

            if (!empty($settings['destinations'])) {
                $dest_args = array('relation' => 'OR',);
                foreach ($settings['destinations'] as $dest_id) {
                    $dest_title = get_the_title($dest_id);
                    array_push($dest_args, array(
                        'key' => 'tytorawdata',
                        'value' => '"_destination":"' . addcslashes($dest_title, '/') . '"',
                        'compare' => 'LIKE'
                    ));
                    array_push($dest_args, array(
                        'key' => 'tytocountries',
                        'value' => addcslashes($dest_title, '/'),
                        'compare' => 'LIKE'
                    ));
                }
                array_push($args['meta_query'], $dest_args);
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
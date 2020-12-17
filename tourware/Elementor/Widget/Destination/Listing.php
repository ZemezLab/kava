<?php

namespace Tourware\Elementor\Widget\Destination;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget;
use Tourware\Path;

class Listing extends Widget
{
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-continents-with-destinations';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __('Destinations Listing');
    }

    /**
     * @return void
     */
    protected function _register_controls()
    {
        $this->start_controls_section('layout', array(
            'label' => esc_html__('Layout'),
        ));

        $this->addControl(new \Tourware\Elementor\Control\LayoutSelector('/destination/listing'));

        $this->add_control(
            'show_continents',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__('Show Continents'),
                'label_on' => esc_html__('Yes'),
                'label_off' => esc_html__('No'),
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('l_layout', array(
            'label' => esc_html__('Continents List Options'),
            'condition' => ['show_continents' => 'yes']
        ));

        $this->add_control(
            'destinations_title',
            [
                'label' => __('Title'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'title' => __('Enter some text'),
            ]
        );

        $continents = get_posts(['post_type' => 'tytocontinents', 'post_status' => 'publish', 'posts_per_page' => -1]);
        $this->add_control( 'excluded_continents', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Exclude' ),
            'multiple' => true,
            'options'  => wp_list_pluck($continents, 'post_title', 'ID'),
        ) );

        $this->add_control('continents_align', array(
            'type' => Controls_Manager::CHOOSE,
            'label' => esc_html__('Alignment', 'tyto'),
            'options' => array(
                'left' => array(
                    'title' => esc_html__('Left', 'tyto'),
                    'icon' => 'fa fa-align-left'
                ),
                'center' => array(
                    'title' => esc_html__('Center', 'tyto'),
                    'icon' => 'fa fa-align-center'
                ),
                'right' => array(
                    'title' => esc_html__('Right', 'tyto'),
                    'icon' => 'fa fa-align-right'
                ),
            ),
            'default' => 'left',
            'selectors' => array(
                '{{WRAPPER}} .continents-filter' => 'text-align: {{VALUE}};'
            ),
        ));

        $this->add_control('list_inline', array(
            'type' => Controls_Manager::SELECT,
            'label' => esc_html__('Display'),
            'default' => 'inherit',
            'options' => array(
                'inherit' => esc_html__('Column'),
                'inline-block' => esc_html__('Inline'),
            ),
        ));

        $this->add_control('list_inline_space', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Space'),
            'default' => array(
                'size' => 20
            ),
            'range' => array(
                'px' => array(
                    'min' => 1,
                    'max' => 100,
                    'step' => 1
                ),
            ),
            'size_units' => array('px'),
            'condition' => array(
                'l_layout.list_inline' => 'inline-block'
            ),
            'selectors' => array(
                '{{WRAPPER}} .continents-filter .wdd-item' => 'margin-right: {{SIZE}}{{UNIT}};'
            ),
        ));

        $this->add_control('list_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Color'),
            'default' => '',
            'selectors' => array(
                '{{WRAPPER}} .continents-filter .wdd-item a' => 'color: {{VALUE}};'
            ),
        ));

        $this->add_control('list_hover_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Hover color'),
            'default' => '',
            'selectors' => array(
                '{{WRAPPER}} .continents-filter .wdd-item a:hover' => 'color: {{VALUE}};'
            ),
        ));
        $this->end_controls_section();


        $this->start_controls_section('c_layout', array(
            'label' => esc_html__('Destinations Options'),
        ));

        $destinations = get_posts(['post_type' => 'tytodestinations', 'post_status' => 'publish', 'posts_per_page' => -1]);
        $this->add_control( 'excluded_destinations', array(
            'type'     => Controls_Manager::SELECT2,
            'label'    => esc_html__( 'Exclude' ),
            'multiple' => true,
            'options'  => wp_list_pluck($destinations, 'post_title', 'ID'),
        ) );

        $this->add_control( 'orderby', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Order By', 'tyto' ),
            'default' => 'title',
            'options' => array(
                'priority' => esc_html__( 'Priority', 'tyto' ),
                'title'    => esc_html__( 'Title', 'tyto' ),
                'rand'     => esc_html__( 'Random', 'tyto' ),
            ),
        ) );

        $this->add_control(
            'destinations_layout',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__('Layout'),
                'default' => 'carousel',
                'options' => array(
                    'grid' => esc_html__('Grid'),
                    'carousel' => esc_html__('Carousel'),
                    'masonry' => esc_html__('Masonry'),
                ),
            ]
        );

        $this->add_responsive_control('col', array(
            'type' => Controls_Manager::SELECT,
            'label' => esc_html__('Columns'),
            'default' => 3,
            'tablet_default' => 2,
            'mobile_default' => 1,
            'options' => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
                6 => 6,
            ),
        ));

        $this->add_responsive_control('rows', array(
            'type' => Controls_Manager::NUMBER,
            'label' => esc_html__('Rows'),
            'condition' => ['destinations_layout' => ['grid', 'masonry']]
        ));

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __( 'Border Radius', 'tyto' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .destinations-list .wdd-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control('c_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Title Color'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .destinations-list .wdd-item .wddc-name' => 'color: {{VALUE}};'
            ),
        ));

        $this->add_control('c_hover_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Hover title color'),
            'default' => ($accent_color = get_theme_mod('accent_color')) ? $accent_color : '#555',
            'selectors' => array(
                '{{WRAPPER}} .destinations-list .wdd-item:hover .wddc-name' => 'color: {{VALUE}};'
            ),
        ));

        $this->add_control('autoplay', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Autoplay'),
            'default' => '',
            'label_on' => esc_html__('Yes'),
            'label_off' => esc_html__('No'),
            'return_value' => 'yes',
        ));

        $this->add_control('speed', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Speed'),
            'default' => array(
                'size' => 500
            ),
            'range' => array(
                'px' => array(
                    'min' => 100,
                    'max' => 3000,
                    'step' => 100
                ),
            ),
            'condition' => array(
                'autoplay' => 'yes'
            )
        ));

        $this->add_control('arrows', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Arrows'),
            'default' => 'yes',
            'label_on' => esc_html__('Yes'),
            'label_off' => esc_html__('No'),
            'return_value' => 'yes',
        ));

        $this->add_control('dots', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Dots'),
            'default' => '',
            'label_on' => esc_html__('Yes'),
            'label_off' => esc_html__('No'),
            'return_value' => 'yes',
        ));
        $this->end_controls_section();

        $this->sectionArrows();
        $this->sectionDots();
    }

    protected function sectionArrows()
    {
        $this->start_controls_section('d_arrows', array(
            'label' => esc_html__('Arrows'),
            'condition' => array(
                'c_layout.arrows' => 'yes',
                'destinations_layout' => 'carousel'
            ),
        ));

        $this->add_control('arrows_size', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Size'),
            'default' => array(
                'size' => 40
            ),
            'range' => array(
                'px' => array(
                    'min' => 1,
                    'max' => 200,
                    'step' => 1
                ),
            ),
            'size_units' => array('px'),
            'selectors' => array(
                '{{WRAPPER}} .continents-destinations .slick-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ),
        ));

        $this->add_control('arrows_position', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Horizontal Position'),
            'default' => array(
                'size' => -40
            ),
            'range' => array(
                'px' => array(
                    'min' => -200,
                    'max' => 200,
                    'step' => 1
                ),
            ),
            'size_units' => array('px'),
            'selectors' => array(
                '{{WRAPPER}} .slick-next' => 'right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
            ),
        ));

        $this->add_control('arrows_color', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Color'),
            'default' => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .slick-arrow' => 'color: {{VALUE}};'
            ),
        ));

        $this->add_control('arrows_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Background color'),
            'default' => '#aaa',
            'selectors' => array(
                '{{WRAPPER}} .slick-arrow' => 'background-color: {{VALUE}};'
            ),
        ));

        $this->add_control('hide_arrows_tablet', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Hide on Tablet'),
            'default' => 'yes',
            'label_on' => esc_html__('Yes', 'goto'),
            'label_off' => esc_html__('No', 'goto'),
            'return_value' => 'yes',
        ));

        $this->add_control('hide_arrows_mobile', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Hide on Mobile'),
            'default' => 'yes',
            'label_on' => esc_html__('Yes', 'goto'),
            'label_off' => esc_html__('No', 'goto'),
            'return_value' => 'yes',
        ));

        $this->end_controls_section();
    }

    protected function sectionDots()
    {
        $this->start_controls_section('d_dots', array(
            'label' => esc_html__('Dots'),
            'condition' => array(
                'c_layout.dots' => 'yes',
            ),
        ));

        $this->add_control('dots_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Background color'),
            'default' => '#eee',
            'selectors' => array(
                '{{WRAPPER}} .slick-dots button' => 'background-color: {{VALUE}};'
            ),
        ));

        $this->add_control('dots_active_bg', array(
            'type' => Controls_Manager::COLOR,
            'label' => esc_html__('Current background color'),
            'default' => '#bbb',
            'selectors' => array(
                '{{WRAPPER}} .slick-dots .slick-active button' => 'background-color: {{VALUE}};'
            ),
        ));

        $this->add_control('dots_position', array(
            'type' => Controls_Manager::SLIDER,
            'label' => esc_html__('Position'),
            'default' => array(
                'size' => -5
            ),
            'range' => array(
                'px' => array(
                    'min' => -30,
                    'max' => 10,
                    'step' => 1
                ),
            ),
            'size_units' => array('px'),
            'selectors' => array(
                '{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
            ),
        ));

        $this->add_control('hide_dots_tablet', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Hide on Tablet'),
            'default' => 'yes',
            'label_on' => esc_html__('Yes'),
            'label_off' => esc_html__('No'),
            'return_value' => 'yes',
        ));

        $this->add_control('hide_dots_mobile', array(
            'type' => Controls_Manager::SWITCHER,
            'label' => esc_html__('Hide on Mobile'),
            'default' => 'yes',
            'label_on' => esc_html__('Yes'),
            'label_off' => esc_html__('No'),
            'return_value' => 'yes',
        ));

        $this->end_controls_section();
    }

    protected function render($instance = [])
    {
        $settings = $this->get_settings_for_display();

        /*ENQUEUE SCRIPT AND STYLE*/
        if ($settings['destinations_layout'] == 'carousel') {
            wp_enqueue_script('slick-script');
        } else {
            wp_enqueue_script('isotope-script');
            if ($settings['destinations_layout'] == 'masonry') wp_enqueue_script('masonry-script');
        }
        wp_enqueue_script('lazysizes-script');


        $posts_continents = get_posts(array(
            'post_type' => 'tytocontinents',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'exclude' => empty($settings['excluded_continents']) ? null : $settings['excluded_continents']
        ));

        $dest_count = 0;
        $continents_destinations = array();
        foreach ($posts_continents as $continent) {
            $args = array(
                'post_type' => 'tytodestinations',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => $settings['orderby'],
                'order' => 'ASC',
                'meta_query' => [
                    'relation' => 'AND',
                    'continent_clause' => [
                        'key' => 'continent',
                        'value' => $continent->post_title
                    ]
                ]
            );
            if ($settings['orderby'] == 'priority') {
                $args['meta_query']['priority_clause'] = ['key' => 'priority', 'type' => 'Numeric'];
                $args['orderby'] = 'priority_clause';
            }
            if (!empty($settings['excluded_destinations'])) {
                $args['exclude'] = $settings['excluded_destinations'];
            }
            $posts_countries = get_posts($args);
            $continents_destinations[$continent->ID] = $posts_countries;
            $dest_count = $dest_count + count($posts_countries);
        }


        $widget_id = uniqid('continents-w-destinations-id-');
        $slider_id = uniqid('continents-w-destinations-slider-id-');
        $filter_id = uniqid('continents-w-destinations-filter-id-');

        $main_class = '';
        if ($settings['show_continents'] != 'yes')
            $main_class = ' continents-disabled';
        else if ($settings['list_inline'] == 'inline-block')
            $main_class = ' continents-inline';

        $dest_content_class = '';
        if ($settings['destinations_layout'] == 'carousel') {
            $dest_content_class = 'tns-slider';
        } else {
            if ($settings['destinations_layout'] == 'grid') {
                $dest_content_class = 'ht-grid-'.$settings['col'].' ht-grid-tablet-'.$settings['col_tablet'].' ht-grid-mobile-'.$settings['col_mobile'];
            }
        }
        include Path::getResourcesFolder() . 'layouts/destination/listing/template.php';
    }

    public function _enqueue_styles()
    {
        wp_enqueue_style($this->get_name(), Path::getResourcesUri() . '/css/styles.css');
    }
}

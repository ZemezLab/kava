<?php

namespace ElementorTyto\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Widget_Continents_With_Destinations extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        $this->_enqueue_styles();
    }

    public function get_name()
    {
        return 'continents-with-destinations';
    }

    public function get_title()
    {
        return __('Continents With Destinations');
    }

    public function get_icon()
    {
        return 'eicon-map-pin';
    }

    public function get_categories()
    {
        return ['tyto'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section('layout', array(
            'label' => esc_html__('Layout'),
        ));

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
            'main_page_destinations_title',
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

        $theme = wp_get_theme();
        if ($theme->parent() == 'Goto')
            $dest_post_type = 'ht_dest';
        else
            $dest_post_type = 'tytodestinations';
        $destinations = get_posts(['post_type' => $dest_post_type, 'post_status' => 'publish', 'posts_per_page' => -1]);
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
            'default' => '#0d3d62',
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
        global $wpdb;
        $settings = $this->get_settings_for_display();

        /*ENQUEUE SCRIPT AND STYLE*/
        if ($settings['destinations_layout'] == 'carousel') {
            wp_enqueue_script('slick-script');
        } else {
            wp_enqueue_script('isotope-script');
            wp_enqueue_script('masonry-script');
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
        $theme = wp_get_theme();
        if ($theme->parent() == 'Goto')
            $dest_post_type = 'ht_dest';
        else
            $dest_post_type = 'tytodestinations';

        foreach ($posts_continents as $continent) {
            $args = array(
                'post_type' => $dest_post_type,
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
        ?>
        <div class="continents-w-destinations<?php echo $main_class ?>" id="<?php echo $widget_id ?>">
            <div class="continents-destinations-container" <?php if ($settings['list_inline'] == 'inline-block') echo 'style="display:block"' ?>>
                <?php if ($settings['show_continents']) { ?>
                    <div class="wd-dest-layout-list continents-filter"
                         id="<?php echo $filter_id ?>">
                        <div class="dest-content">
                            <?php if (!empty($settings['main_page_destinations_title'])) { ?>
                                <h2><?php echo $settings['main_page_destinations_title'] ?></h2>
                            <?php } ?>
                            <h4 class="wdd-item"><a href="#"
                                                    data-continent="">Alle
                                    (<?php echo $dest_count ?>)</a></h4>
                            <?php foreach ($posts_continents as $continent) { ?>
                                <h4 class="wdd-item"><a href="#"
                                                        data-continent="<?php echo $continent->ID ?>"><?php echo $continent->post_title ?>
                                        (<?php echo count($continents_destinations[$continent->ID]) ?>)</a></h4>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="wd-dest-layout-grid dest-layout-<?php echo $settings['destinations_layout']?> destinations-list">
                    <div class="dest-content <?php echo $dest_content_class ?>" id="<?php echo $slider_id ?>"
                         style="width: 100%">
                        <?php if ($settings['destinations_layout'] == 'masonry') { ?>
                        <div class="grid-sizer"></div>
                        <?php }
                        $i = 0;
                        foreach ($continents_destinations as $continent => $destinations) {
                            foreach ($destinations as $destination) {
                                $img_id = get_post_thumbnail_id($destination->ID);
                                $img_alt = get_the_title($destination->ID);
                                $img_src = !empty($img_id) ? get_the_post_thumbnail_url($destination->ID, 'medium_large') : get_post_meta($destination->ID, 'header_image', true);
                                if (empty($img_src)) $img_src = 'https://via.placeholder.com/300x400';
                                ?>
                                <div class="ht-grid-item continent-<?php echo $continent ?> <?php if ($settings['destinations_layout'] == 'masonry' && $i%($settings['col']+1) == 0) echo 'w2'?>">
                                    <div class="wdd-item">
                                        <a class="wdd-head"
                                           href="<?php echo get_the_permalink($destination->ID); ?>"></a>
                                        <img class="wdd-img lazyload"
                                             data-src="<?php echo esc_attr($img_src); ?>"
                                             alt="<?php echo esc_attr($img_alt); ?>">
                                        <div class="wdd-cont">
                                            <h3 class="wddc-name entry-title"
                                                itemprop="headline"><?php echo $destination->post_title; ?></h3>
                                            <?php if ($destination->post_excerpt) { ?>
                                            <div class="wddc-desc"
                                                 itemprop="text"><?php echo $destination->post_excerpt; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            $i++; }
                        } ?>
                        <?php ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($settings['destinations_layout'] == 'masonry') { ?>
        <style>
            #<?php echo $slider_id ?> .grid-sizer { width: <?php echo 100/$settings['col']?>%; }
            #<?php echo $slider_id ?> .ht-grid-item { width: <?php echo 100/$settings['col']?>%; }
            #<?php echo $slider_id ?> .ht-grid-item.w2 { width: <?php echo 100/$settings['col'] *2 ?>%; }
        </style>
        <?php } ?>
        <script>
            jQuery(document).ready(function($) {
                <?php if ($settings['destinations_layout'] !== 'carousel') { ?>
                var $grid = $('#<?php echo $slider_id ?>').isotope({
                    itemSelector: '.ht-grid-item',
                    <?php if ($settings['destinations_layout'] == 'masonry') { ?>
                    percentPosition: true,
                    masonry: {
                        columnWidth: '.grid-sizer'
                    }
                    <?php } ?>
                });
                <?php } ?>

                <?php if ($settings['destinations_layout'] !== 'carousel' && $settings['rows']) { ?>
                var item_height = $('#<?php echo $widget_id?>').find('.destinations-list').find('.ht-grid-item').first().outerHeight(true);
                var destinations_height = $('#<?php echo $slider_id?>').outerHeight(true);
                var destinations_container_height = item_height*<?php echo $settings['rows']?>;
                $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': destinations_container_height, 'overflow': 'hidden'});
                if (destinations_height > destinations_container_height) {
                    $('#<?php echo $widget_id?>>').append('<div class="show-more"><a href="#" data-rows="<?php echo $settings['rows']?>">Weitere anzeigen</a></div>')
                }
                $('#<?php echo $widget_id?>').find('.show-more').find('a').click(function(e) {
                    e.preventDefault();
                    var showed_rows = $(this).data('rows');
                    var settings_rows = <?php echo $settings['rows'] ?>;
                    destinations_container_height = item_height*(showed_rows+settings_rows);
                    if (destinations_height > destinations_container_height) {
                        $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': destinations_container_height});
                        $(this).data('rows', showed_rows+settings_rows);
                    } else {
                        $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': 'auto'});
                        $('#<?php echo $widget_id?>>').find('.show-more').hide();
                    }
                });
                <?php } ?>

                $('#<?php echo $filter_id ?> a').click(function (e) {
                    e.preventDefault();
                    $('#<?php echo $filter_id ?> a').removeClass('is-active');
                    $(this).addClass('is-active');
                    var continent = $(this).data('continent');

                    <?php if ($settings['destinations_layout'] == 'carousel') { ?>
                    $('#<?php echo $slider_id ?>').slick('slickUnfilter');
                    if (continent) $('#<?php echo $slider_id ?>').slick('slickFilter', '.ht-grid-item.continent-' + continent);
                    <?php } else { ?>
                    if (continent) {
                        $grid.isotope({ filter: '.ht-grid-item.continent-' + continent });
                        $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': 'auto'});
                        $('#<?php echo $widget_id?>').find('.show-more').hide();
                        $('#<?php echo $widget_id?>').find('.show-more').find('a').data('rows', <?php echo $settings['rows'] ?>);
                    } else {
                        $grid.isotope({ filter: '*' });
                        <?php if ($settings['rows']) { ?>
                        $('#<?php echo $widget_id?>>').find('.destinations-list').css({'height': item_height*<?php echo $settings['rows']?>, 'overflow': 'hidden'});
                        $('#<?php echo $widget_id?>').find('.show-more').show();
                        <?php } ?>
                    }
                    <?php } ?>
                });
            });
        </script>
        <?php if ($settings['destinations_layout'] == 'carousel') { ?>
        <style>#<?php echo $slider_id ?> { display: none }</style>
        <script>
            jQuery(document).ready(function ($) {
                $('#<?php echo $slider_id ?>').slick({
                    accessibility: false,
                    <?php if ($settings['autoplay']) { ?>
                    infinite: <?php echo $settings['autoplay'] ? 'true' : 'false' ?>,
                    speed: <?php echo $settings['speed']['size']?>,
                    autoplay: <?php echo $settings['autoplay'] ? 'true' : 'false' ?>,
                    <?php } ?>
                    slidesToShow: <?php echo $settings['col']?>,
                    slidesToScroll: <?php echo $settings['col']?>,
                    arrows: <?php echo $settings['arrows'] == true ? 'true' : 'false' ?>,
                    dots: <?php echo $settings['dots'] == true ? 'true' : 'false' ?>,
                    easing: 'linear',
                    responsive: [
                        {
                            breakpoint: 980,
                            settings: {
                                slidesToShow: <?php echo $settings['col_tablet']?>,
                                slidesToScroll: <?php echo $settings['col_tablet']?>,
                                arrows: <?php echo $settings['hide_arrows_tablet'] == true ? 'false' : 'true' ?>,
                                dots: <?php echo $settings['hide_dots_tablet'] == true ? 'false' : 'true' ?>,
                            },
                        },
                        {
                            breakpoint: 570,
                            settings: {
                                slidesToShow: <?php echo $settings['col_mobile']?>,
                                slidesToScroll: <?php echo $settings['col_mobile']?>,
                                arrows: <?php echo $settings['hide_arrows_mobile'] == true ? 'false' : 'true' ?>,
                                dots: <?php echo $settings['hide_dots_mobile'] == true ? 'false' : 'true' ?>
                            },
                        },
                    ],
                });
                $('#<?php echo $slider_id ?>').fadeIn();

                $('#<?php echo $filter_id ?> a').click(function (e) {
                    e.preventDefault();
                    $('#<?php echo $filter_id ?> a').removeClass('is-active');
                    $(this).addClass('is-active');
                    var continent = jQuery(this).data('continent');
                    $('#<?php echo $slider_id ?>').slick('slickUnfilter');
                    if (continent) $('#<?php echo $slider_id ?>').slick('slickFilter', '.ht-grid-item.continent-' + continent);

                });
            });
        </script>
        <?php
        }
    }

    public function _enqueue_styles()
    {
        wp_enqueue_style($this->get_name(), \Tourware\Elementor\Loader::getElementorWidgetsFolderUri() .  $this->get_name() . '/assets/css/styles.css');
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widget_Continents_With_Destinations());
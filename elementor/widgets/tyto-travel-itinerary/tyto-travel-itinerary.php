<?php

namespace ElementorTyto\Widgets;

use Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Plugin;
use \Elementor\Core\Schemes as Schemes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Widget_Tyto_TravelItinerary extends Widget_Base
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public function get_name()
    {
        return 'tyto-travel-itinerary';
    }

    public function get_title()
    {
        return __('Travel Itinerary');
    }

    public function get_icon()
    {
        return 'eicon-table-of-contents';
    }

    public function get_categories()
    {
        return ['tyto'];
    }

    public function render()
    {
        wp_register_script('dummy-handle-footer', '', [], '', true);
        wp_enqueue_script('dummy-handle-footer');
        $settings = $this->get_settings_for_display();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();
        $record = json_decode(get_post_meta($post, 'tytorawdata', true)); ?>
        <div class="travel-section travel-itinerary">
            <h5 class="tour-section-title"><?php esc_html_e($settings['title']); ?></h5>
            <div class="travel-itinerary-content">
                <?php
                $item_date = null;
                if ($settings['show_date'] == 'yes') {
                    if ('INDEPENDENT' === $record->type) { // start date for individual travel
                        if ($record->travelBegin) $item_date = date_create($record->travelBegin);
                    } else { // start date for group travel
                        if (count($record->dates) == 1) {
                            $item_date = date_create($record->dates[0]->start);
                        } else if (count($record->dates) > 1) {
                            foreach ($record->dates as $date) {
                                if (isset($date->tags)) {
                                    foreach ($date->tags as $date_tag) {
                                        if (strtolower($date_tag->name) == 'default') {
                                            $item_date = date_create($date->start);
                                        }
                                    }
                                }
                            }
                            if (is_null($item_date)) $item_date = date_create($record->dates[0]->start);
                        }
                    }
                }
                $day = 1; $step = 0; $pos = 0;
                $last = count($record->itinerary) - 1;
                foreach ($record->itinerary as $kk => $item) { ?>
                    <div class="travel-itinerary-brick timeline-item<?php if ($settings['brick_accordion'] == 'yes') echo ' brick-accordion' ?>">
                        <div class="travel-itinerary-brick-head timeline-item-title">
                            <div class="brick-day">Tag <?php
                                if ($item->days > 1) {
                                    echo $day . ' - ' . ($day + $item->days - 1);
                                } else {
                                    echo $day;
                                } ?><?php
                                if (!is_null($item_date)) echo ' - '. $item_date->format($settings['date_format']);
                                date_modify($item_date, '+'.$item->days.' day');
                                ?></div>
                            <div class="brick-title"<?php if ($settings['open_by'] == 'title') echo ' style="cursor:pointer;"' ?>><?php echo $item->brick->title; ?></div>
                        </div>
                        <?php $day += $item->days; ?>
                        <div class="travel-itinerary-brick-content">
                            <div class="travel-itinerary-brick-text" <?php
                            if ($settings['brick_accordion'] !== 'yes'
                                || $settings['opened_boxes'] == 'all'
                                || ($settings['opened_boxes'] == 'first' && $pos == 0)
                                || $settings['opened_boxes'] == 'first_last' && ($pos == 0 || $pos == $last)) {
                                echo 'data-start="show"';
                            } ?>>
                                <?php
                                $imgs_lngth = sizeof($item->brick->images);
                                if ($imgs_lngth > 0) {
                                    if (strpos($item->brick->images[0]->image, 'unsplash')) {
                                        $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=300&w=300';
                                        $img_array = explode("?", $item->brick->images[0]->image);
                                        $image_url = $img_array[0] . $unsplash_options;
                                    } else {
                                        $cloudinary_options = array(
                                            "secure" => true,
                                            "width" => 300,
                                            "height" => 300,
                                            "crop" => "thumb"
                                        );
                                        $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                                    } ?>
                                    <img class="travel-itinerary-brick-img" src="<?php echo $image_url ?>"
                                         alt="<?php echo $item->brick->title ?>">
                                <?php } ?>
                                <?php echo $item->brick->description; ?>

                            </div>
                            <?php
                            /*  Accommodation Start */
                            if (isset($item->brick->defaultAccommodation) && !empty($item->brick->defaultAccommodation)) {
                                $meals = [];
                                $meals_types = [
                                    'breakfast' => 'Frühstück',
                                    'lunch'     => 'Mittagessen',
                                    'lunchbox'  => 'Lunchbox',
                                    'dinner'    => 'Abendessen',
                                ];
                                foreach ($meals_types as $meal_type => $meal_name) if ($item->$meal_type) array_push($meals, $meal_name);
                                if ($item->customMealType) array_push($meals, $item->customMealType);

                                if (!count($meals)) {
                                    foreach ($meals_types as $meal_type => $meal_name) if ($item->brick->$meal_type) array_push($meals, $meal_name);
                                }
                                if (empty($meals)) array_push($meals, 'Selbstversorger');

                                if (!empty($item->brick->defaultAccommodation->images)) {
                                    if (strpos($item->brick->images[0]->image, 'unsplash')) {
                                        $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=150&w=250';
                                        $img_array = explode("?", $item->brick->images[0]->image);
                                        $image_url = $img_array[0] . $unsplash_options;
                                    } else {
                                        $cloudinary_options = array(
                                            "secure" => true,
                                            "width" => 250,
                                            "height" => 150,
                                            "crop" => "thumb"
                                        );
                                        if ('http' === substr($item->brick->defaultAccommodation->images[0]->image, 0, 4)) {
                                            $cloudinary_options['type'] = 'fetch';
                                        }
                                        $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                                    }
                                } else {
                                    $image_url = 'https://via.placeholder.com/250x150';
                                }
                                ?>
                                <div class="itinerary-accommodation">
                                    <strong><?php echo ((int)$item->brick->days > 1) ? 'Übernachtungen in' : 'Übernachtung:' ?></strong> <?php echo $item->brick->defaultAccommodation->title; ?>
                                    <div class="tour-acc-text">
                                        <img class="tour-acc-img"
                                             src="<?php echo $image_url ?>"
                                             alt="<?php echo $item->brick->title ?>">
                                        <div>
                                            <?php echo $item->brick->defaultAccommodation->description; ?>
                                            <p class="meal"><strong>Verpflegung:</strong> <?php echo join(' / ', $meals); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            /*  Accommodation End */ ?>
                        </div>
                    </div>
                    <?php
                    $pos++;
                } ?>
            </div>
            <?php
            if ($settings['brick_accordion'] == 'yes') {
                wp_enqueue_script('collapser-script'); ?>
                <script>
                    jQuery(document).ready(function ($) {
                        $.each($('.travel-itinerary-brick-text'), function () {
                            $(this).collapser({
                                mode: 'lines',
                                truncate: <?php echo intval($settings['description_rows']) ?>,
                                ellipsis: '...',
                                speed: 300,
                                controlBtn: <?php if ($settings['open_by'] == 'title') { ?> function () {
                                    return $(this).parents('.travel-itinerary-brick').find('.brick-title')
                                } <?php } else if ($settings['open_by'] == 'button') { ?>'show-more'<?php } ?>,
                                <?php if ($settings['open_by'] == 'button') { ?>
                                showText: '<i class="fa fa-chevron-circle-down"></i>',
                                hideText: '<i class="fa fa-chevron-circle-up"></i>',
                                <?php } else if ($settings['open_by'] == 'title') { ?>
                                showText: $(this).parents('.travel-itinerary-brick').find('.brick-title').first().html(),
                                hideText: $(this).parents('.travel-itinerary-brick').find('.brick-title').first().html(),
                                <?php } ?>
                                showClass: 'open',
                                hideClass: 'collapsed',
                            });
                        });
                        <?php if ($settings['open_by'] == 'title') { ?>
                        $('.travel-itinerary-brick').find('.brick-title').click(function () {
                            $(this).toggleClass('active');
                        });
                        <?php } ?>
                    })
                </script>
                <?php
            } ?>
        </div>
    <?php }


    protected function _register_controls()
    {
        $this->start_controls_section('options', array(
            'label' => esc_html__('Options'),
        ));

        $posts = wp_list_pluck(get_posts(['post_type' => ['tytotravels'], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'tyto'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), ['tytotravels']) ? get_the_ID() : ''
            ]
        );

        $this->add_control('title',
            [
                'type' => Controls_Manager::TEXT,
                'label' => __('Title', 'tyto'),
                'default' => __('Reiseverlauf', 'tyto'),
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $date_format = get_option( 'date_format', 'd.m.Y');
        $this->add_control(
            'date_format',
            [
                'label' => __('Date Format', 'tyto'),
                'type' => Controls_Manager::TEXT,
                'default' => $date_format,
                'condition' => ['show_date' => 'yes']
            ]
        );

        $this->add_control(
            'brick_accordion',
            [
                'label' => __('Brick Accordion', 'tyto'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'tyto'),
                'label_off' => __('No', 'tyto'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'open_by',
            [
                'label' => __('Open by click on', 'tyto'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'title' => 'Title',
                    'button' => 'Button'
                ],
                'default' => 'title',
                'condition' => ['brick_accordion' => 'yes']
            ]
        );

        $this->add_control(
            'opened_boxes',
            [
                'label' => __('Opened boxes', 'tyto'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'all' => __('All', 'tyto'),
                    'first' => __('First', 'tyto'),
                    'first_last' => __('First and Last', 'tyto'),
                    'none' => __('None', 'tyto'),
                ],
                'default' => 'all',
                'condition' => ['brick_accordion' => 'yes']
            ]
        );

        $this->add_control(
            'description_rows',
            [
                'label' => __('Description Rows', 'tyto'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
            ]
        );

        $this->end_controls_section();

        $primary_color = $title_color = '#000';
        /* Goto Theme Colors */
        if (class_exists('Goto_Kirki')) {
            $primary_color = \Goto_Kirki::get_option('goto', 'primary_color');
            $title_color = \Goto_Kirki::get_option('goto', 'typo_heading');
        }
        /* Kava Theme Colors */
        if (function_exists('kava_get_customizer_options')) {
            $primary_color = get_theme_mod('accent_color');
            $title_color = get_theme_mod('primary_text_color');;
        }

        /*  STYLE */
        $this->start_controls_section('brick', array(
            'label' => esc_html__('Brick', 'tyto'),
            'tab' => Controls_Manager::TAB_STYLE,
        ));

        $this->add_control('brick_background',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background', 'tyto'),
                'default' => '#e3e3e3',
                'selectors' => array(
                    '{{WRAPPER}} .travel-itinerary-brick' => 'background-color: {{VALUE}};'
                ),

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => 'Day Typography',
                'name' => 'day_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .travel-itinerary-brick .brick-day',
            ]
        );

        $this->add_control('title_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Title Color', 'tyto'),
                'default' => $title_color,
                'selectors' => array(
                    '{{WRAPPER}} .travel-itinerary-brick .brick-title' => 'color: {{VALUE}};'
                ),

            ]
        );
        $this->add_control('title_hover_color',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Title Hover Color', 'tyto'),
                'default' => $primary_color,
                'selectors' => array(
                    '{{WRAPPER}} .travel-itinerary-brick .brick-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .travel-itinerary-brick .brick-title.active' => 'color: {{VALUE}};',
                ),

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => 'Title Typography',
                'name' => 'title_typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .travel-itinerary-brick .brick-title',
            ]
        );

        $this->end_controls_section();
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new Widget_Tyto_TravelItinerary());
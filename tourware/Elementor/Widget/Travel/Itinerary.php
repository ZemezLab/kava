<?php
namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes as Schemes;

class Itinerary extends Widget {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-itinerary';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Itinerary' );
    }

    public function render()
    {
        wp_register_script('dummy-handle-footer', '', [], '', true);
        wp_enqueue_script('dummy-handle-footer');
        $settings = $this->get_settings_for_display();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();
        $repository = \Tourware\Repository\Travel::getInstance();
        $item_data = $repository->findOneByPostId($post);
        $dates = $item_data->getDates();
        $itinerary = $item_data->getItinerary();
        include \Tourware\Path::getResourcesFolder() . 'layouts/travel/itinerary/template.php';
    }


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

        /*  BRICK */
        $this->addControlGroup(['id' => 'style_box', 'type' => 'box', 'label' => 'Brick','selector' => '.travel-itinerary-brick']);

        /* DAY */
        $this->addControlGroup([
            'id'=>'style_day',
            'type' => 'attribute',
            'label' => 'Day',
            'selector' => '.brick-day',
        ]);

        /* TITLE */
        $this->addControlGroup([
            'id'=>'style_title',
            'type' => 'attribute',
            'label' => 'Title',
            'selector' => '.brick-title',
        ]);



    }

}

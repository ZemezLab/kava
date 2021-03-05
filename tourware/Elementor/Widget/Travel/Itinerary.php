<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget\Accordion\AbstractAccordion;

class Itinerary extends AbstractAccordion {
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

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytotravels';
    }

    /**
     * @return string
     */
    protected function getRecordTypeName()
    {
        return 'travel';
    }

    /**
     * @return string
     */
    protected function getWidgetName()
    {
        return 'itinerary';
    }

    protected function _register_controls()
    {
        parent::_register_controls();

        $this->start_controls_section(
            'itinerary_options',
            [
                'label' => __( 'Itinerary Options', 'tourware' )
            ]
        );
        $this->add_control(
            'show_accommodation',
            [
                'label' => __('Show Accommodation', 'tourware'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementor-pro' ),
                'label_off' => __( 'Hide', 'elementor-pro' ),
                'default' => 'yes'
            ]
        );
        $this->add_control(
            'accommodation_prefix',
            [
                'label' => __('Accommodation Prefix', 'tourware'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Unterkunft: ',
                'condition' => ['show_accommodation' => 'yes']
            ]
        );

        $this->end_controls_section();

        $this->addControlGroup([
            'id'=>'style_accommodation',
            'type' => 'attribute',
            'label' => 'Accommodation',
            'selector' => '.accommodation',
            'condition' => ['show_accommodation' => 'yes']
        ]);
    }

    /**
     * @return array
     */
    protected function getTemplateData() {
        $settings = $this->get_settings();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();
        $result = [];

        $repository = \Tourware\Repository\Travel::getInstance();
        $item_data = $repository->findOneByPostId($post);
        $itinerary = $item_data->getItinerary();
        $day = 1;
        foreach ($itinerary as $index => $item) {
            $day_str = 'Tag '.($item->days > 1 ? $day . ' - ' . ($day + $item->days - 1) : $day);
            $tab_title = $item->brick->title;
            if ($day_str) $tab_title = $day_str.': '.esc_html($tab_title);

            $tab_content = '';
            $imgs_lngth = sizeof($item->brick->images);
            if ($imgs_lngth > 0) {
                if (strpos($item->brick->images[0]->image, 'unsplash')) {
                    $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=300&w=700';
                    $img_array = explode("?", $item->brick->images[0]->image);
                    $image_url = $img_array[0] . $unsplash_options;
                } else {
                    $cloudinary_options = array(
                        "secure" => true,
                        "width" => 700,
                        "height" => 300,
                        "crop" => "thumb"
                    );
                    $image_url = \Cloudinary::cloudinary_url($item->brick->images[0]->image, $cloudinary_options);
                }
                $tab_content .= '<img class="itinerary-brick-img" src="'. $image_url .'" alt="'. $item->brick->title .'">';
            }
            $tab_content .= $item->brick->description;

            if ($settings['show_accommodation'] === 'yes') {
                if (!empty($item->accommodations)) {
                    foreach ($item->accommodations as $item_accommodation) {
                        if (!empty($item_accommodation->travel)) {
                            if (!empty($item_accommodation->accommodation->url)) {
                                $link = $item_accommodation->accommodation->url;
                                $parsed = parse_url($link);
                                if (empty($parsed['scheme'])) {
                                    $link = 'https://' . ltrim($link, '/');
                                }
                            } else {
                                $tytoid = $item_accommodation->accommodation->id;
                                $accommodations = get_posts(array(
                                    'meta_key' => 'tytoid',
                                    'meta_value' => $tytoid,
                                    'post_type' => 'tytoaccommodations',
                                    'post_status' => 'publish',
                                    'posts_per_page' => 1
                                ));

                                if (count($accommodations)) {
                                    $accommodation_wp_post = array_shift($accommodations);
                                    $link = get_permalink($accommodation_wp_post->ID);
                                }
                            }
                            if ($link) {
                                $tab_content .= '<div class="accommodation">'.$settings['accommodation_prefix'].'<a href="'.$link.'">'.$item_accommodation->accommodation->title. '</a></div>';
                            }
                        }
                    }
                }
            }


            $result['accordion_data'][] = [
                'tab_title' => $tab_title,
                'tab_content' => $tab_content
            ];

            $day += $item->days;
        }


        return $result;
    }
}

<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Tourware\Elementor\Widget\Accordion\AbstractAccordion;

class Accommodations extends AbstractAccordion {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-accommodations';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Accommodations' );
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
        return 'accommodations';
    }

    protected function _register_controls()
    {
        parent::_register_controls();
        $this->start_controls_section(
            'accommodations_options',
            [
                'label' => __( 'Accommodations Options', 'elementor-pro' )
            ]
        );

        $this->add_control(
            'heading_stay_options',
            [
                'label' => __( 'Stay', 'tourware' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'show_stay',
            [
                'label' => __('Show', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-pro'),
                'label_off' => __('Hide', 'elementor-pro'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'stay_title',
            [
                'label' => __( 'Title', 'tourware' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Ihr Aufenthalt: ',
                'condition' => ['show_stay' => 'yes']
            ]
        );
        $this->add_control(
            'stay_nights',
            [
                'label' => __( 'Show Nights', 'tourware' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_stay' => 'yes']
            ]
        );
        $this->add_control(
            'stay_meals',
            [
                'label' => __( 'Show Meals', 'tourware' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => ['show_stay' => 'yes']
            ]
        );

        $this->add_control(
            'heading_room_options',
            [
                'label' => __( 'Room', 'tourware' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'show_room',
            [
                'label' => __('Show', 'elementor-pro'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'elementor-pro'),
                'label_off' => __('Hide', 'elementor-pro'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'room_title',
            [
                'label' => __( 'Title', 'tourware' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Ihre Zimmerkategorie: ',
                'condition' => ['show_room' => 'yes']
            ]
        );

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
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __( 'Text', 'elementor-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Mehr lesen »', 'elementor-pro' ),
                'condition' => [ 'show_read_more' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        $this->addControlGroup(['id' => 'style_image', 'type' => 'image']);
    }

    protected function getTemplateData() {
        $settings = $this->get_settings();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();
        $result = [];

        $repository = \Tourware\Repository\Travel::getInstance();
        $item_data = $repository->findOneByPostId($post);
        $accommodations = $item_data->getAccommodations();
        foreach ($accommodations as $accommodation) {
//            print_r($accommodation);
            $tab_content = '';
            $imgs_lngth = sizeof($accommodation->accommodation->images);
            if ($imgs_lngth > 0) {
                if (strpos($accommodation->accommodation->images[0]->image, 'unsplash')) {
                    $unsplash_options = '?fm=jpg&crop=focalpoint&fit=crop&h=300&w=700';
                    $img_array = explode("?", $accommodation->accommodation->images[0]->image);
                    $image_url = $img_array[0] . $unsplash_options;
                } else {
                    $cloudinary_options = array(
                        "secure" => true,
                        "width" => 300,
                        "height" => 400,
                        "crop" => "thumb"
                    );
                    $image_url = \Cloudinary::cloudinary_url($accommodation->accommodation->images[0]->image, $cloudinary_options);
                }
                $tab_content .= '<div class="accommodation-left"><img class="" src="'. $image_url .'" alt="'. $accommodation->accommodation->title .'"></div>';
            }

            $tab_content .= '<div class="accommodation-right">';

            if ($settings['show_stay'] === 'yes') {
                $tab_content .= '<div class="stay">';
                if ($settings['stay_title'])
                    $tab_content .= '<div class="title">'.$settings['stay_title'].'</div>';
                if ($settings['stay_nights'] === 'yes' || $settings['stay_meals'] === 'yes')
                    $tab_content .= '<div class="text">';

                    $tab_content .=
                            _nx(
                                '1 Nacht',
                                $accommodation->nights.' Nächte',
                                $accommodation->nights,
                                'nights',
                                'tourware'
                            );

                    $tab_content .= '</div>';

                $tab_content .= '</div>';
            }

            if ($settings['show_room'] === 'yes' && !empty($accommodation->room)) {
                $tab_content .= '<div class="room">';
                if ($settings['room_title'])
                    $tab_content .= '<div class="title">'.$settings['room_title'].'</div>';
                $tab_content .= '<div class="text">'.$accommodation->room->title.'</div>';
                $tab_content .= '</div>';
            }

            $tab_content .= '<div class="excerpt">'.$accommodation->accommodation->description.'</div>';

            if (!empty($accommodation->accommodation->url)) {
                $link = $accommodation->accommodation->url;
            } else {
                $tytoid = $accommodation->accommodation->id;
                $wp_accommodations = get_posts(array(
                    'meta_key' => 'tytoid',
                    'meta_value' => $tytoid,
                    'post_type' => 'tytoaccommodations',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                ));
                if (count($wp_accommodations)) {
                    $accommodation_wp_post = array_shift($wp_accommodations);
                    $link = get_permalink($accommodation_wp_post->ID);
                }
            }
            if ($settings['show_read_more'] && $link) {
                $tab_content .= '<a href="'.$link.'" class="elementor-button read-more">'.$settings['read_more_text'].'</a>';
            }

            $tab_content .= '</div>';

            $result['accordion_data'][] = [
                'tab_title' => $accommodation->accommodation->title,
                'tab_content' => $tab_content
            ];
        }

        return $result;
    }

    protected function getTemplatePath () {
        return \Tourware\Path::getResourcesFolder() . 'layouts/accordion/template.php';
    }
}

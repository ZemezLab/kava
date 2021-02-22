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
            'meta_data',
            [
                'label' => __( 'Meta Data', 'elementor-pro' ),
                'label_block' => true,
                'type' => Controls_Manager::SELECT2,
                'default' => [ 'date', 'comments' ],
                'multiple' => true,
                'options' => [
                    'nights' => __( 'Nights', 'tourware' ),
                    'meals' => __( 'Meals', 'tourware' ),
                    'room' => __( 'Room', 'tourware' ),

                ],
                'separator' => 'before',
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
            if ($settings['meta_data']) {
                foreach ($settings['meta_data'] as $meta) {
                    if ($meta == 'room' && !empty($accommodation->room)) {
                        $tab_content .= '<div class="room">Ihre Zimmerkategorie: <br>'.$accommodation->room->title.'</div>';
                    }
                }
            }


            $tab_content .= '<div class="excerpt">'.$accommodation->accommodation->description.'</div>';

            $tab_content .= '</div>';

            $result['accordion_data'][] = [
                'tab_title' => $accommodation->accommodation->title . ' Nights: ' . $accommodation->nights,
                'tab_content' => $tab_content
            ];
        }

        return $result;
    }

    protected function getTemplatePath () {
        return \Tourware\Path::getResourcesFolder() . 'layouts/accordion/template.php';
    }
}

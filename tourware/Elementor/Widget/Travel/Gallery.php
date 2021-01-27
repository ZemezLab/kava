<?php

namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget;
use Elementor\Controls_Manager;

class Gallery extends Widget
{

    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-gallery';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Gallery', 'tourware' );
    }

    /**
     * @param array $instance
     */
    protected function render( $instance = [] )
    {
        $repository = \Tourware\Repository\Travel::getInstance();
        $record = $repository->findOneByPostId(get_the_ID());
        $config = $this->get_settings_for_display();

        echo '<div class="vue-widget-wrapper">
                <travel-gallery :images=\'' . json_encode($record->images, JSON_HEX_APOS|JSON_HEX_QUOT) . '\'></travel-gallery>
            </div>';
    }

}
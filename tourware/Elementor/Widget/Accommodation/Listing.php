<?php

namespace Tourware\Elementor\Widget\Accommodation;

class Listing extends \Tourware\Elementor\Widget\Abstract\Listing
{

    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-accommodation-listing';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Accommodation Listing' );
    }

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytoaccommodations';
    }
}
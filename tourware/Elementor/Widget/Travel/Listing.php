<?php

namespace Tourware\Elementor\Widget\Travel;

class Listing extends \Tourware\Elementor\Widget\Listing\AbstractListing
{

    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-listing';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Listing' );
    }

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytotravels';
    }

}
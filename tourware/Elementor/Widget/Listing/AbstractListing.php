<?php

namespace Tourware\Elementor\Widget\Listing;

class AbstractListing extends \Tourware\Elementor\Widget\Widget
{

    public function get_name()
    {
        return 'advanced-tyto-list';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Record Listing' );
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-post-list';
    }

    /**
     * @return string[]
     */
    public function get_categories()
    {
        return [ 'tyto' ];
    }

    protected function getPostTypeName()
    {
        return 'tytotravels';
    }

}
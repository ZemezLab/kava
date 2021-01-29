<?php
namespace Tourware\Elementor\Widget\Travel;

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
}

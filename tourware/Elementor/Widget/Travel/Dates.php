<?php
namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget\Accordion\AbstractAccordion;

class Dates extends AbstractAccordion {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-dates';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Dates' );
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
        return 'dates';
    }
}

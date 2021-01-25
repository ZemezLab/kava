<?php
namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget\Details\AbstractDetails;

class Details extends AbstractDetails {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-details';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Details' );
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

    protected function optionPersons() {

    }

}
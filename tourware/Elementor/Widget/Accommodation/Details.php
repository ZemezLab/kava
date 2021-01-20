<?php
namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget\Details\AbstractDetails;

class Details extends AbstractDetails {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-accommodation-details';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Accommodation Details' );
    }

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytoaccommodations';
    }

    /**
     * @return string
     */
    protected function getRecordTypeName()
    {
        return 'accommodation';
    }

}
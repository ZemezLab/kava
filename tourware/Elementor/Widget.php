<?php

namespace Tourware\Elementor;

use Tourware\Elementor\Control;

abstract class Widget extends \Elementor\Widget_Base
{
    /**
     * This Widget constructor wraps our css class around each and every widget.
     *
     * @param array $data
     * @param null $args
     */
    public function __construct( $data = [], $args = null )
    {
        parent::__construct($data, $args);

        $this->add_render_attribute(
            '_wrapper', 'class', [
                'tourware-widget'
            ]
        );

        $this->_enqueue_styles();
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

    public function addControl(Control $control)
    {
        $this->add_control( $control->getId(), $control->getConfig() );
    }

}
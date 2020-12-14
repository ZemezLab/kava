<?php

namespace Tourware\Elementor;

use \Elementor\Controls_Manager;

class Control
{
    protected $id = 'template';
    protected $type = Controls_Manager::SELECT;
    protected $label = 'Template';

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLabel()
    {
        return esc_html__($this->label);
    }

    public function getConfig()
    {
        return array();
    }

}

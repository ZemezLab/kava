<?php

namespace Tourware\Elementor\Control;

use Tourware\Elementor\Control;
use Tourware\Path;
use \Elementor\Controls_Manager;

class LayoutSelector extends Control
{

    protected $id = 'template';
    protected $type = Controls_Manager::SELECT;
    protected $label = 'Template';

    public function getConfig()
    {
        $layouts = [];

        foreach (glob(\Tourware\Path::getResourcesFolder() . 'layouts/travel/listing/layout*.php') as $layout) {
            $code = file_get_contents($layout);
            $name = basename($layout, '.php');

            if (preg_match_all('/\s\*\sName:\s(.*)/', $code, $matches)){
                $name = str_replace(' * Name: ', '', $matches[0]);
            }

            $layouts[$layout] = $name;
        }

        if (Path::getChildResourcesFolder() !== Path::getResourcesFolder()) {
            foreach (glob(\Tourware\Path::getChildResourcesFolder() . 'layouts/travel/listing/*.php') as $layout) {
                $code = file_get_contents($layout);
                $name = basename($layout, '.php');

                if (preg_match_all('/\s\*\sName:\s(.*)/', $code, $matches)){
                    $name = str_replace(' * Name: ', '', $matches[0]);
                }

                $layouts[$layout] = $name;
            }
        }

        return array(
            'type'    => $this->getType(),
            'label'   => $this->getLabel(),
            'options' => $layouts
        );
    }

}
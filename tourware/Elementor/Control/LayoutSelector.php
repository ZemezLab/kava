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

    protected $widgetPathPart;

    public function __construct($widgetPathPart)
    {
        if (!$widgetPathPart) {
            throw new \Exception('$widgetPathPart is missing');
        }

        $this->widgetPathPart = $widgetPathPart;
    }

    public function getConfig()
    {
        $layouts = [
            'none' => __('None', 'tourware')
        ];

        foreach (glob(\Tourware\Path::getResourcesFolder() . 'layouts/' . $this->widgetPathPart . '/layout*.php') as $layout) {
            $code = file_get_contents($layout);
            $basename = basename($layout, '.php');

            if (preg_match_all('/\s\*\sName:\s(.*)/', $code, $matches)){
                $name = str_replace(' * Name: ', '', $matches[0]);
            } else {
                $name = $basename;
            }

            $layouts['tourware##' . $this->widgetPathPart . '##' . $basename] = $name;
        }

        if (Path::getChildResourcesFolder() !== Path::getResourcesFolder()) {
            foreach (glob(\Tourware\Path::getChildResourcesFolder() . 'layouts/' . $this->widgetPathPart . '/*.php') as $layout) {
                $code = file_get_contents($layout);
                $name = basename($layout, '.php');

                if (preg_match_all('/\s\*\sName:\s(.*)/', $code, $matches)){
                    $name = str_replace(' * Name: ', '', $matches[0]);
                }

                $layouts['child##' . $this->widgetPathPart . '##' .$name ] = $name;
            }
        }

        return array(
            'type'    => $this->getType(),
            'label'   => $this->getLabel(),
            'default' => 'none',
            'options' => $layouts
        );
    }

}
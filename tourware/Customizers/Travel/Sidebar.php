<?php

namespace Tourware\Customizers\Travel;

use Tourware\Customizer;

class Sidebar extends Customizer
{

    public function getAdditionalOptions($options)
    {
       return [
            'single_sidebar' => [
                'title' => esc_html__('Single Sidebar', 'tyto'),
                'priority' => 76,
                'type' => 'panel',
            ],
            'single_request_button' => [
                'title' => esc_html__('Request Button', 'tyto'),
                'type' => 'section',
                'panel' => 'single_sidebar'
            ],
            'single_request_btn_text' => [
                'title'   => esc_html__( 'Button Text', 'tyto' ),
                'section' => 'single_request_button',
                'default' => 'Anfragen',
                'field'   => 'text',
                'type'    => 'control',
            ],
            'single_request_btn_link' => [
                'title'   => esc_html__( 'Button Link', 'tyto' ),
                'section' => 'single_request_button',
                'default' => '/anfrageformular',
                'field'   => 'url',
                'type'    => 'control',
            ],
            'single_request_btn_target' => [
                'title'   => esc_html__( 'Button Target', 'tyto' ),
                'section' => 'single_request_button',
                'default' => '_self',
                'field' => 'select',
                'choices'     => array(
                    '_self' => esc_html__('Self', 'tyto'),
                    '_blank'  => esc_html__('Blank', 'tyto'),
                ),
                'type' => 'control',
            ],
            'single_request_btn_style_type' => [
                'title'        => esc_html__( 'Style', 'tyto' ),
                'section' => 'header_menu',
                'default' => 'primary',
                'field' => 'select',
                'choices'     => array(
                    'primary' => esc_html__('Primary', 'tyto'),
                    'secondary'  => esc_html__('Secondary', 'tyto'),
                ),
                'type' => 'control',
            ],
        ];
    }

}
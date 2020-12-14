<?php

namespace Tourware\Customizer;

use Tourware\Customizer;

/**
 * Class Typography
 * @package Tourware\Customizer
 */
class Typography extends Customizer
{

    protected function getAdditionalOptions($options)
    {
        return [
            'body_font_family' => array(
                'title'   => esc_html__( 'Font Family', 'kava' ),
                'section' => 'body_typography',
                'default' => 'Cabin, sans-serif',
                'field'   => 'fonts',
                'type'    => 'control',
            ),

            'body_font_size' => array(
                'title'       => esc_html__( 'Font Size, px', 'kava' ),
                'section'     => 'body_typography',
                'default'     => '16',
                'field'       => 'number',
                'input_attrs' => array(
                    'min'  => 6,
                    'max'  => 50,
                    'step' => 1,
                ),
                'type' => 'control',
            ),

            'body_text_align' => array(
                'title'   => esc_html__( 'Text Align', 'kava' ),
                'section' => 'body_typography',
                'default' => 'center',
                'field'   => 'select',
                'choices' => kava_get_text_aligns(),
                'type'    => 'control',
            ),
        ];
    }

}
<?php

namespace Tourware\Customizer;

use Tourware\Customizer;

/**
 * Class Buttons
 * @package Tourware\Customizer
 */
class Buttons extends Customizer
{

    public function getAdditionalOptions($options)
    {
        return array(
            'buttons_styles' => array(
                'title' => esc_html__('Buttons', 'kava'),
                'priority' => 90,
                'type' => 'panel',
            ),

            'buttons_styles_primary' => array(
                'title' => esc_html__('Primary Buttons', 'kava'),
                'priority' => 70,
                'type' => 'section',
                'panel' => 'buttons_styles'
            ),

            'primary_btn_border_radius' => [
                'title'   => esc_html__( 'Button Border Radius', 'tyto' ),
                'description'   => esc_html__( '% or px', 'tyto' ),
                'section' => 'buttons_styles_primary',
                'default' => '0',
                'field'   => 'text',
                'type'    => 'control',
            ],

            'primary_btn_text_transform' => [
                'title'        => esc_html__( 'Button Text Transform', 'tyto' ),
                'section' => 'buttons_styles_primary',
                'default' => 'uppercase',
                'field' => 'select',
                'choices'     => array(
                    'uppercase' => esc_html__('Uppercase', 'tyto'),
                    'capitalize'  => esc_html__('Capitalize', 'tyto'),
                    'lowercase'  => esc_html__('Lowercase', 'tyto'),
                    'none'  => esc_html__('None', 'tyto'),
                ),
                'type' => 'control',
            ],

            'primary_btn_font_weight' => [
                'title'   => esc_html__( 'Button Font Weight', 'tyto' ),
                'section' => 'buttons_styles_primary',
                'default' => '700',
                'field'   => 'text',
                'type'    => 'control',
            ],

            'primary_btn_bg_color' => [
                'title'   => esc_html__( 'Button Background color', 'kava' ),
                'section' => 'buttons_styles_primary',
                'default' => get_theme_mod('link_color'),
                'field'   => 'hex_color',
                'type'    => 'control',
            ],

            'primary_btn_text_color' => [
                'title'   => esc_html__( 'Button Text color', 'kava' ),
                'section' => 'buttons_styles_primary',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],

            'primary_btn_hover_bg_color' => [
                'title'   => esc_html__( 'Button Hover Background color', 'kava' ),
                'section' => 'buttons_styles_primary',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],

            'primary_btn_hover_text_color' => [
                'title'   => esc_html__( 'Button Hover Text color', 'kava' ),
                'section' => 'buttons_styles_primary',
                'default' => get_theme_mod('link_color'),
                'field'   => 'hex_color',
                'type'    => 'control',
            ],


            'buttons_styles_secondary' => array(
                'title' => esc_html__('Secondary Buttons', 'kava'),
                'priority' => 70,
                'type' => 'section',
                'panel' => 'buttons_styles'
            ),

            'secondary_btn_border_radius' => [
                'title'   => esc_html__( 'Button Border Radius', 'tyto' ),
                'description'   => esc_html__( '% or px', 'tyto' ),
                'section' => 'buttons_styles_secondary',
                'default' => '0',
                'field'   => 'text',
                'type'    => 'control',
            ],

            'secondary_btn_text_transform' => [
                'title'        => esc_html__( 'Button Text Transform', 'tyto' ),
                'section' => 'buttons_styles_secondary',
                'default' => 'uppercase',
                'field' => 'select',
                'choices'     => array(
                    'uppercase' => esc_html__('Uppercase', 'tyto'),
                    'capitalize'  => esc_html__('Capitalize', 'tyto'),
                    'lowercase'  => esc_html__('Lowercase', 'tyto'),
                    'none'  => esc_html__('None', 'tyto'),
                ),
                'type' => 'control',
            ],

            'secondary_btn_font_weight' => [
                'title'   => esc_html__( 'Button Font Weight', 'tyto' ),
                'section' => 'buttons_styles_secondary',
                'default' => '700',
                'field'   => 'text',
                'type'    => 'control',
            ],

            'secondary_btn_bg_color' => [
                'title'   => esc_html__( 'Button Background color', 'kava' ),
                'section' => 'buttons_styles_secondary',
                'default' => 'transparent',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],

            'secondary_btn_text_color' => [
                'title'   => esc_html__( 'Button Text color', 'kava' ),
                'section' => 'buttons_styles_secondary',
                'default' => get_theme_mod('link_color'),
                'field'   => 'hex_color',
                'type'    => 'control',
            ],

            'secondary_btn_hover_bg_color' => [
                'title'   => esc_html__( 'Button Hover Background color', 'kava' ),
                'section' => 'buttons_styles_secondary',
                'default' => get_theme_mod('link_color'),
                'field'   => 'hex_color',
                'type'    => 'control',
            ],

            'secondary_btn_hover_text_color' => [
                'title'   => esc_html__( 'Button Hover Text color', 'kava' ),
                'section' => 'buttons_styles_secondary',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
        );
    }

}
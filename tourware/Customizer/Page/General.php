<?php

namespace Tourware\Customizer\Page;

use Tourware\Customizer;

/**
 * Class Header
 * @package Tourware\Customizer\Page
 */
class General extends Customizer
{

    public function __construct($wp_customize)
    {
        $wp_customize->add_setting('tourware[logo_white]', array(
            'capability' => 'edit_theme_options',
            'type'       => 'option',
        ));

        $wp_customize->add_control(
            new \WP_Customize_Image_Control(
                $wp_customize,
                'logo_white',
                array(
                    'label'      => __( 'Logo (White)', 'tourware' ),
                    'section'    => 'title_tagline',
                    'settings' => 'tourware[logo_white]',
                )
            )
        );
    }

}
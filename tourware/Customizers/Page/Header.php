<?php

namespace Tourware\Customizers\Page;

use Tourware\Customizer;

class Header extends Customizer
{

    protected function getAdditionalOptions($options)
    {
        return array(
            /* page header options */
            'header_height' => array(
                'title'       => esc_html__( 'Header height', 'tyto' ),
                'description'     => esc_html('px'),
                'section'     => 'header_styles',
                'default'     => '380',
                'field'       => 'number',
                'priority' => 1,
                'input_attrs' => array(
                    'min'  => 100,
                    'max'  => 1000,
                    'step' => 10,
                ),
                'type' => 'control',
            ),
            'header_margin' => array(
                'title'           => esc_html__( 'Header margin-bottom', 'kava' ),
                'description'     => esc_html('px'),
                'section'         => 'header_styles',
                'field'           => 'number',
                'default'         => '0',
                'type'            => 'control',
                'priority'        => 2
            ),
            'header_text_color' => array(
                'title'           => esc_html__( 'Header Text Color', 'kava' ),
                'section'         => 'header_styles',
                'field'   => 'hex_color',
                'default'         => '#3b3d42',
                'type'            => 'control',
                'priority'        => 3
            ),
            'header_breadcrumbs_color' => array(
                'title'           => esc_html__( 'Header Breadcrumbs Color', 'kava' ),
                'section'         => 'header_styles',
                'field'   => 'hex_color',
                'default'         => '#eee',
                'type'            => 'control',
                'priority'        => 3
            ),
            'header_text_shadow' => [
                'title'    => esc_html__( 'Header Text Shadow', 'tyto' ),
                'section'  => 'header_styles',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
                'priority'        => 3
            ],
            'header_vertical_align' => array(
                'title'    => esc_html__( 'Vertical align', 'tyto' ),
                'priority' => 3,
                'section'  => 'header_styles',
                'default'  => 'flex-start',
                'field'    => 'select',
                'choices'     => array(
                    'flex-start' => esc_attr__( 'Top', 'tyto' ),
                    'center' => esc_attr__( 'Middle', 'tyto' ),
                    'flex-end' => esc_attr__( 'Bottom', 'tyto' ),
                ),
                'type' => 'control',
            ),
            'header_padding_bottom' => [
                'title'       => esc_html__( 'Header Bottom Padding', 'tyto' ),
                'section'     => 'header_styles',
                'default'     => '20',
                'field'       => 'number',
                'priority' => 3,
                'type' => 'control',
                'active_callback' => 'tyto_header_is_bottom_aligned',
            ],
            'header_horizontal_align' => array(
                'title'    => esc_html__( 'Horizontal align', 'tyto' ),
                'priority' => 3,
                'section'  => 'header_styles',
                'default'  => 'flex-end',
                'field'    => 'select',
                'choices'     => array(
                    'flex-start' => esc_attr__( 'Left', 'tyto' ),
                    'center' => esc_attr__( 'Center', 'tyto' ),
                    'flex-end' => esc_attr__( 'Right', 'tyto' ),
                ),
                'type' => 'control',
            ),
            'header_parallax' => array(
                'title'    => esc_html__( 'Parallax', 'tyto' ),
                'priority' => 3,
                'section'  => 'header_styles',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ),
            'header_parallax_speed' => array(
                'title'       => esc_html__( 'Parallax Speed', 'tyto' ),
                'section'     => 'header_styles',
                'default'     => '3',
                'field'       => 'number',
                'priority' => 4,
                'input_attrs' => array(
                    'min'  => 1,
                    'max'  => 50,
                    'step' => 1,
                ),
                'type' => 'control',
                'active_callback' => 'tyto_is_parallax_enabled',
            ),
            /* Header Layout */
            'header_menu' => array(
                'title' => esc_html__('Main Header', 'kava'),
                'priority' => 71,
                'type' => 'section',
                'panel' => 'header_options'
            ),
            'header_menu_bg_color' => [
                'title'   => esc_html__( 'Background color', 'kava' ),
                'section' => 'header_menu',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
            'header_menu_bg_opacity' => [
                'title'   => esc_html__( 'Background opacity', 'kava' ),
                'description' => esc_html__( 'from 0 to 1', 'kava' ),
                'section' => 'header_menu',
                'default' => '1',
                'field'   => 'text',
                'type'    => 'control',
            ],
            'header_menu_text_color' => [
                'title'   => esc_html__( 'Text color', 'kava' ),
                'section' => 'header_menu',
                'default' => '#555555',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
            'header_menu_sticky_bg_color' => [
                'title'   => esc_html__( 'Sticky Background color', 'kava' ),
                'section' => 'header_menu',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
            'header_menu_sticky_bg_opacity' => [
                'title'   => esc_html__( 'Sticky Background opacity', 'kava' ),
                'description' => esc_html__( 'from 0 to 1', 'kava' ),
                'section' => 'header_menu',
                'default' => '1',
                'field'   => 'text',
                'type'    => 'control',
            ],
            'header_menu_sticky_text_color' => [
                'title'   => esc_html__( 'Sticky Text color', 'kava' ),
                'section' => 'header_menu',
                'default' => '#555555',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
            'header_menu_mobile_bg_color' => [
                'title'   => esc_html__( 'Mobile Background color', 'kava' ),
                'section' => 'header_menu',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
            'header_menu_mobile_text_color' => [
                'title'   => esc_html__( 'Mobile Text color', 'kava' ),
                'section' => 'header_menu',
                'default' => '#555555',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],
            'header_layout' => [
                'title'        => esc_html__( 'Layout', 'tyto' ),
                'section' => 'header_menu',
                'default' => 'layout-1',
                'field' => 'radio',
                'choices'     => array(
                    'layout-1' => 'Layout 1',
                    'layout-2' => 'Layout 2',
                ),
                'type' => 'control',
            ],
            'sticky_on' => [
                'title'    => esc_html__('Sticky Header', 'tyto'),
                'subtitle' => esc_html__('Header will be sticked when applicable.', 'tyto'),
                'section' => 'header_menu',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
            ],
            'hide_sidebar_icon' => [
                'title'    => esc_html__('Hidden Sidebar Icon', 'tyto'),
                'section' => 'header_menu',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
            ],
            'search_icon' => [
                'title'    => esc_html__('Search Icon', 'tyto'),
                'section' => 'header_menu',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
            ],
            'tyto_show_login_button' => [
                'title'    => esc_html__('Login/Account Button', 'tyto'),
                'section' => 'header_menu',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
            ],
            'tyto_show_registration_link' => [
                'title'    => esc_html__('Show Registration Link', 'tyto'),
                'description' => esc_html__('below Login Form', 'tyto'),
                'section' => 'header_menu',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_show_login_button'
            ],
            'tyto_registration_link' => [
                'title'    => esc_html__('Registration Link', 'tyto'),
                'section' => 'header_menu',
                'field' => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_show_registration_link'
            ],
            'header_btn' => [
                'title'    => esc_html__('Header Button', 'tyto'),
                'section' => 'header_menu',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
            ],
            'header_btn_text' => [
                'title'   => esc_html__( 'Header Button Text', 'tyto' ),
                'section' => 'header_menu',
                'default' => 'Request A Quote',
                'field'   => 'text',
                'type'    => 'control',
                'active_callback' => 'tyto_header_menu_header_btn'
            ],
            'header_btn_link_type' => [
                'title'   => esc_html__( 'Button Link Type', 'tyto' ),
                'section' => 'header_menu',
                'default' => 'page',
                'field' => 'select',
                'choices'     => array(
                    'page' => esc_html__('Page', 'tyto'),
                    'popup'  => esc_html__('Popup', 'tyto'),
                ),
                'type' => 'control',
                'active_callback' => 'tyto_header_menu_header_btn'
            ],
            'header_btn_link' => [
                'title'   => esc_html__( 'Header Button Link', 'tyto' ),
                'section' => 'header_menu',
                'default' => '',
                'field'   => 'text',
                'type'    => 'control',
                'active_callback' => 'tyto_header_menu_header_btn_page'
            ],
            'header_btn_target' => [
                'title'   => esc_html__( 'Button Target', 'tyto' ),
                'section' => 'header_menu',
                'default' => '_self',
                'field' => 'select',
                'choices'     => array(
                    '_self' => esc_html__('Self', 'tyto'),
                    '_blank'  => esc_html__('Blank', 'tyto'),
                ),
                'type' => 'control',
                'active_callback' => 'tyto_header_menu_header_btn_page'
            ],
            'header_btn_style_type' => [
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
            /* Header Top Panel */
            'top_panel_enable' => array(
                'title'   => esc_html__( 'Enable Top Panel', 'kava' ),
                'section' => 'header_top_panel',
                'default' => true,
                'field'   => 'checkbox',
                'type'    => 'control',
            ),
            'top_panel_bg' => array(
                'title'   => esc_html__( 'Background color', 'kava' ),
                'section' => 'header_top_panel',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
            ),
            'top_panel_bg_opacity' => [
                'title'   => esc_html__( 'Background opacity', 'kava' ),
                'description' => esc_html__( 'from 0 to 1', 'kava' ),
                'section' => 'header_top_panel',
                'default' => '1',
                'field'   => 'text',
                'type'    => 'control',
            ],
            'top_panel_text_color' => [
                'title'   => esc_html__( 'Text color', 'tyto' ),
                'section' => 'header_top_panel',
                'default' => '#000000',
                'field'   => 'hex_color',
                'type'    => 'control',
            ],'phone_label' => [
                'title'   => esc_html__('Phone Label', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'phone_number' => [
                'title'   => esc_html__('Phone Number', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'email_label' => [
                'title'   => esc_html__('Email Label', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'email_address' => [
                'title'   => esc_html__('Email Address', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'time_label' => [
                'title'   => esc_html__('Time Label', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'time_value' => [
                'title'   => esc_html__('Time', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'language_switch' => [
                'title'    => esc_html__('Language Switch', 'mintech'),
                'section' => 'header_top_panel',
                'default' => false,
                'field' => 'checkbox',
                'type' => 'control',
            ],

            'social_facebook_url' => [
                'title'   => esc_html__('Facebook URL', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'social_twitter_url' => [
                'title'   => esc_html__('Twitter URL', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'social_instagram_url' => [
                'title'   => esc_html__('Instagram URL', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'social_youtube_url' => [
                'title'   => esc_html__('Youtube URL', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],
            'social_tripadvisor_url' => [
                'title'   => esc_html__('Tripadvisor URL', 'tyto'),
                'section' => 'header_top_panel',
                'field' => 'text',
                'type' => 'control',
            ],

            /* Menu Typography */
            'menu_text_transform' => [
                'title'        => esc_html__( 'Text Transform', 'tyto' ),
                'section' => 'menu_typography',
                'default' => 'none',
                'field' => 'select',
                'choices'     => array(
                    'uppercase' => esc_html__('Uppercase', 'tyto'),
                    'capitalize'  => esc_html__('Capitalize', 'tyto'),
                    'lowercase'  => esc_html__('Lowercase', 'tyto'),
                    'none'  => esc_html__('None', 'tyto'),
                ),
                'type' => 'control',
            ]
        );
    }

}
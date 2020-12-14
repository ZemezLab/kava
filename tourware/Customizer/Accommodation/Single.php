<?php

namespace Tourware\Customizer\Accommodation;

use Tourware\Customizer;

/**
 * Class Single
 * @package Tourware\Customizer\Accommodation
 */
class Single extends Customizer
{

    public function getAdditionalOptions($options)
    {
        return array(
            'single_accommodation' => array(
                'title' => esc_html__('Single Accommodation', 'kava'),
                'priority' => 72,
                'type' => 'panel',
            ),
            /* WIDTHS */
            'single_accommodation_widths' => array(
                'title' => esc_html__('Widths', 'kava'),
                'priority' => 71,
                'type' => 'section',
                'panel' => 'single_accommodation'
            ),
            'accommodation_container_width' => [
                'title' => esc_html__('Container Width', 'tyto'),
                'description' => 'px',
                'priority' => 1,
                'section' => 'single_accommodation_widths',
                'default' => 1200,
                'field' => 'number',
                'type' => 'control',
            ],
            'accommodation_content_width' => [
                'title' => esc_html__('Main Content Width', 'tyto'),
                'description' => '%',
                'priority' => 1,
                'section' => 'single_accommodation_widths',
                'default' => 70,
                'field' => 'number',
                'type' => 'control',
            ],

            'accommodation_content_gap' => [
                'title' => esc_html__('Main Content Gap', 'tyto'),
                'description' => 'px',
                'section' => 'single_accommodation_widths',
                'default' => 30,
                'field' => 'number',
                'type' => 'control',
            ],

            /* HEADER */
            'single_accommodation_header' => array(
                'title' => esc_html__('Header', 'kava'),
                'type' => 'section',
                'panel' => 'single_accommodation'
            ),
            'single_accommodation_header_layout' => array(
                'title' => esc_html__('Layout', 'tyto'),
                'section' => 'single_accommodation_header',
                'default' => 'layout-1',
                'field' => 'select',
                'choices' => array(
                    'layout-1' => esc_attr__('Header 1', 'tyto'),
                    'layout-2' => esc_attr__('Header 2', 'tyto'),
                    'layout-3' => esc_attr__('Header 3', 'tyto'),
                ),
                'type' => 'control',
            ),

            'accommodation_header_video' => array(
                'title' => esc_html__('Show Video as Header Background', 'tyto'),
                'section' => 'single_accommodation_header',
                'default' => '',
                'field' => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_header_layout_1'
            ),
            'accommodation_header_video_autoplay' => array(
                'title' => esc_html__('Header Video Autoplay', 'tyto'),
                'section' => 'single_accommodation_header',
                'default' => '',
                'field' => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_header_video_autoplay'
            ),
            'accommodation_header_images_darken' => array(
                'title'    => esc_html__( 'Header images darken', 'tyto' ),
                'section'  => 'single_accommodation_header',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_header_layout_3',
            ),
            /* CONTENT */
            'single_accommodation_content' => array(
                'title' => esc_html__('Content Options', 'kava'),
                'type' => 'section',
                'panel' => 'single_accommodation'
            ),
            'single_accommodation_background_color' => array(
                'title'           => esc_html__( 'Background Color', 'kava' ),
                'section'         => 'single_accommodation_content',
                'field'           => 'hex_color',
                'default'         => '#fff',
                'type'            => 'control',
            ),
            'accommodation_share_button' => [
                'title'    => esc_html__( 'Show Share Button', 'tyto' ),
                'section'  => 'single_accommodation_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],
            'accommodation_share_button_text' => [
                'title'    => esc_html__( 'Share Button Text', 'tyto' ),
                'section'  => 'single_accommodation_content',
                'default'  => esc_html__('Unterkunft teilen', 'tyto'),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_show_share_button'
            ],
            'accommodation_map_zoom' => [
                'title' => esc_html__('Map Zoom', 'tyto'),
                'section' => 'single_accommodation_content',
                'default' => 15,
                'field' => 'number',
                'type' => 'control',
            ],
            'accommodation_show_price' => [
                'title'    => esc_html__( 'Show Price', 'tyto' ),
                'section'  => 'single_accommodation_content',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ],
            /* RELATED (Sidebar) */
            'single_accommodation_related_sidebar' => [
                'title' => esc_html__('Related (Sidebar)', 'tyto'),
                'type' => 'section',
                'panel' => 'single_accommodation'
            ],
            'accommodation_related_show_sidebar' => [
                'title'    => esc_html__( 'Show related items in the sidebar', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ],
            'accommodation_related_show_excerpt' => [
                'title'    => esc_html__( 'Show Excerpt', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_show_related_sidebar'
            ],
            'accommodation_related_excerpt_limit' => [
                'title'    => esc_html__( 'Excerpt Length', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => 100,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_related_show_excerpt_sidebar'
            ],
            'accommodation_group_related' => [
                'title'    => esc_html__( 'Group related by type', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_show_related_sidebar'
            ],
            'accommodation_related_title' => [
                'title'    => esc_html__( 'Related title', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => esc_html__( 'Passend dazu:', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_dont_group_related_sidebar'
            ],
            'accommodation_related_order_travels' => [
                'title'    => esc_html__( 'Travels position', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => 1,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_sidebar'
            ],
            'accommodation_related_order_accommodations' => [
                'title'    => esc_html__( 'Accommodations position', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => 2,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_sidebar'
            ],
            'accommodation_related_order_travelsbricks' => [
                'title'    => esc_html__( 'Bricks position', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => 3,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_sidebar'
            ],
            'accommodation_related_travels_title' => [
                'title'    => esc_html__( 'Related travels title', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => esc_html__( 'Reisen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_sidebar'
            ],
            'accommodation_related_accommodations_title' => [
                'title'    => esc_html__( 'Related accommodations title', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => esc_html__( 'Unterkunft', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_sidebar'
            ],
            'accommodation_related_travelsbricks_title' => [
                'title'    => esc_html__( 'Related bricks title', 'tyto' ),
                'section'  => 'single_accommodation_related_sidebar',
                'default'  => esc_html__( 'Informationen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_sidebar'
            ],
            /* RELATED (Content) */
            'single_accommodation_related_content' => [
                'title' => esc_html__('Related (Content)', 'tyto'),
                'type' => 'section',
                'panel' => 'single_accommodation'
            ],
            'accommodation_related_show_content' => [
                'title'    => esc_html__( 'Show related items in the content section', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],
            'accommodation_related_show_excerpt_content' => [
                'title'    => esc_html__( 'Show Excerpt', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_show_related_content'
            ],
            'accommodation_related_excerpt_limit_content' => [
                'title'    => esc_html__( 'Excerpt Length', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => 100,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_related_show_excerpt_content'
            ],
            'accommodation_group_related_content' => [
                'title'    => esc_html__( 'Group related by type', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_show_related_content'
            ],
            'accommodation_related_title_content' => [
                'title'    => esc_html__( 'Related title', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => esc_html__( 'Passend dazu:', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_dont_group_related_content'
            ],
            'accommodation_related_order_travels_content' => [
                'title'    => esc_html__( 'Travels position', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => 1,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_content'
            ],
            'accommodation_related_order_accommodations_content' => [
                'title'    => esc_html__( 'Accommodations position', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => 2,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_content'
            ],
            'accommodation_related_order_travelsbricks_content' => [
                'title'    => esc_html__( 'Bricks position', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => 3,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_content'
            ],
            'accommodation_related_travels_title_content' => [
                'title'    => esc_html__( 'Related travels title', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => esc_html__( 'Reisen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_content'
            ],
            'accommodation_related_accommodations_title_content' => [
                'title'    => esc_html__( 'Related accommodations title', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => esc_html__( 'Unterkunft', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_content'
            ],
            'accommodation_related_travelsbricks_title_content' => [
                'title'    => esc_html__( 'Related bricks title', 'tyto' ),
                'section'  => 'single_accommodation_related_content',
                'default'  => esc_html__( 'Informationen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_accommodation_group_related_content'
            ],
        );
    }

}
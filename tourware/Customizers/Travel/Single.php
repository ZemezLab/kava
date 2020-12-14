<?php

namespace Tourware\Customizers\Travel;

use Tourware\Customizer;

class Single extends Customizer
{

    public function getAdditionalOptions($options)
    {
        return array(
            'single_tour' => array(
                'title' => esc_html__('Single Travel', 'kava'),
                'priority' => 70,
                'type' => 'panel',
            ),

            'single_tour_widths' => array(
                'title' => esc_html__('Widths', 'kava'),
                'priority' => 70,
                'type' => 'section',
                'panel' => 'single_tour'
            ),

            'tour_container_width' => [
                'title' => esc_html__('Container Width', 'tyto'),
                'description' => 'px',
                'priority' => 1,
                'section' => 'single_tour_widths',
                'default' => 1200,
                'field' => 'number',
                'type' => 'control',
            ],

            'tour_content_width' => [
                'title' => esc_html__('Main Content Width', 'tyto'),
                'description' => '%',
                'priority' => 1,
                'section' => 'single_tour_widths',
                'default' => 70,
                'field' => 'number',
                'type' => 'control',
            ],

            'tour_content_gap' => [
                'title' => esc_html__('Main Content Gap', 'tyto'),
                'description' => 'px',
                'section' => 'single_tour_widths',
                'default' => 30,
                'field' => 'number',
                'type' => 'control',
            ],

            'single_tour_header' => array(
                'title' => esc_html__('Header', 'kava'),
                'type' => 'section',
                'panel' => 'single_tour'
            ),

            'single_tour_header_layout' => array(
                'title' => esc_html__('Layout', 'tyto'),
                'section' => 'single_tour_header',
                'default' => 'layout-1',
                'field' => 'select',
                'choices' => array(
                    'layout-1' => esc_attr__('Header 1', 'tyto'),
                    'layout-2' => esc_attr__('Header 2', 'tyto'),
                    'layout-3' => esc_attr__('Header 3', 'tyto'),
                ),
                'type' => 'control',
            ),

            'tour_header_video' => array(
                'title' => esc_html__('Show Video as Header Background', 'tyto'),
                'section' => 'single_tour_header',
                'default' => '',
                'field' => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_header_layout_1'
            ),

            'tour_header_video_autoplay' => array(
                'title' => esc_html__('Header Video Autoplay', 'tyto'),
                'section' => 'single_tour_header',
                'default' => '',
                'field' => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_header_video_autoplay'
            ),

            'tour_header_images_darken' => array(
                'title'    => esc_html__( 'Header images darken', 'tyto' ),
                'section'  => 'single_tour_header',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_header_layout_3',
            ),

            'single_tour_content' => array(
                'title' => esc_html__('Content Options', 'kava'),
                'type' => 'section',
                'panel' => 'single_tour'
            ),

            'single_tour_background_color' => array(
                'title'           => esc_html__( 'Background Color', 'kava' ),
                'section'         => 'single_tour_content',
                'field'           => 'hex_color',
                'default'         => '#fff',
                'type'            => 'control',
            ),

            'tour_attributes' => [
                'title'    => esc_html__( 'Show attributes', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_max_attributes' => [
                'title'    => esc_html__( 'Maximum attributes in a row', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => 4,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_attributes',
            ],

            'tour_travelcode' => [
                'title'    => esc_html__( 'Show Travelcode', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_share_button' => [
                'title'    => esc_html__( 'Show Share Button', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_share_button_text' => [
                'title'    => esc_html__( 'Share Button Text', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => esc_html__('Reise teilen', 'tyto'),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_share_button'
            ],

            'additional_options_title' => [
                'title'    => esc_html__( 'Options and Packets Title', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => esc_html__('Optionen und Pakete', 'tyto'),
                'field'    => 'text',
                'type' => 'control',
            ],

            'additional_options_type' => [
                'title'    => esc_html__( 'Options or Packets?', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => 'packets',
                'field'    => 'radio',
                'choices'  => [
                    'packets' => esc_html__('Packets', 'tyto'),
                    'options' => esc_html__('Options', 'tyto'),
                ],
                'type' => 'control',
            ],

            'tour_show_price' => [
                'title'    => esc_html__( 'Show Price', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_price_prefix' => [
                'title'    => esc_html__( 'Price Prefix', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => esc_html__('ab:', 'tyto'),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_price'
            ],

            'tour_price_suffix' => [
                'title'    => esc_html__( 'Price Suffix', 'tyto' ),
                'section'  => 'single_tour_content',
                'default'  => esc_html__('/ pro Person', 'tyto'),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_price'
            ],

            'single_tour_accommodations' => [
                'title' => esc_html__('Accommodations', 'tyto'),
                'type' => 'section',
                'panel' => 'single_tour'
            ],

            'tour_show_accommodations' => [
                'title'    => esc_html__( 'Show Accommodations Section', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_show_accommodations_after_itinerary' => [
                'title'    => esc_html__( 'Accommodations after Itinerary Section', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_accommodations'
            ],

            'tour_accommodations_title' => [
                'title'    => esc_html__( 'Section Title', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => esc_html__( 'Unterkünfte', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_accommodations'
            ],

            'tour_accommodation_excerpt_lines' => [
                'title'    => esc_html__( 'Exceprt Lines', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => 3,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_accommodations'
            ],

            'tour_accommodations_show_more_text' => [
                'title'    => esc_html__( 'Show More Text', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => esc_html__( 'Mehr erfahren >>', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_accommodations'
            ],

            'tour_accommodations_hide_text'  => [
                'title'    => esc_html__( 'Hide Text', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => esc_html__( '', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_accommodations'
            ],

            'tour_accommodation_gallery_images' => [
                'title'    => esc_html__( 'Gallery Images Number', 'tyto' ),
                'section'  => 'single_tour_accommodations',
                'default'  => 2,
                'field'    => 'select',
                'choices'  => [
                    '1' => esc_attr__( '1', 'tyto' ),
                    '2' => esc_attr__( '2', 'tyto' ),
                    '3' => esc_attr__( '3', 'tyto' ),
                ],
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_accommodations'
            ],


            'single_tour_map' => array(
                'title' => esc_html__('Map & Itinerary', 'tyto'),
                'type' => 'section',
                'panel' => 'single_tour'
            ),

            'single_tour_itinerary_opened_boxes' => [
                'title'    => esc_html__( 'Itinerary: opened boxes', 'tyto' ),
                'section'  => 'single_tour_map',
                'default'  => 'all',
                'field'    => 'select',
                'choices'  => [
                    'all' => esc_attr__( 'All', 'tyto' ),
                    'first' => esc_attr__( 'First', 'tyto' ),
                    'first_last' => esc_attr__( 'First and last', 'tyto' ),
                    'none' => esc_attr__( 'None', 'tyto' ),
                ],
                'type' => 'control',
            ],

            'tour_itinerary_show_distances' => [
                'title'    => esc_html__( 'Itinerary: show distances', 'tyto' ),
                'section'  => 'single_tour_map',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_map_position' => [
                'title'    => esc_html__( 'Map position', 'tyto' ),
                'section'  => 'single_tour_map',
                'default'  => 'content',
                'field'    => 'radio',
                'choices'  => [
                    'content' => esc_attr__( 'Content', 'tyto' ),
                    'right_sidebar' => esc_attr__( 'Right Sidebar', 'tyto' ),
                ],
                'type' => 'control',
            ],

            'tour_show_map_preview' => [
                'title'    => esc_html__( 'Show Map Preview', 'tyto' ),
                'section'  => 'single_tour_map',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_map_btn_text' => [
                'title'   => esc_html__( 'Map Button Text', 'tyto' ),
                'section' => 'single_tour_map',
                'default' => 'Karte ansehen',
                'field'   => 'text',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_border_radius' => [
                'title'   => esc_html__( 'Map Button Border Radius', 'tyto' ),
                'description'   => esc_html__( '% or px', 'tyto' ),
                'section' => 'single_tour_map',
                'default' => '50px',
                'field'   => 'text',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_text_transform' => [
                'title'        => esc_html__( 'Map Button Text Transform', 'tyto' ),
                'section' => 'single_tour_map',
                'default' => 'uppercase',
                'field' => 'select',
                'choices'     => array(
                    'uppercase' => esc_html__('Uppercase', 'tyto'),
                    'capitalize'  => esc_html__('Capitalize', 'tyto'),
                    'lowercase'  => esc_html__('Lowercase', 'tyto'),
                    'none'  => esc_html__('None', 'tyto'),
                ),
                'type' => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_font_weight' => [
                'title'   => esc_html__( 'Map Button Font Weight', 'tyto' ),
                'section' => 'single_tour_map',
                'default' => '700',
                'field'   => 'text',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_bg_color' => [
                'title'   => esc_html__( 'Map Button Background color', 'kava' ),
                'section' => 'single_tour_map',
                'default' => get_theme_mod('link_color'),
                'field'   => 'hex_color',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_text_color' => [
                'title'   => esc_html__( 'Map Button Text color', 'kava' ),
                'section' => 'single_tour_map',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_hover_bg_color' => [
                'title'   => esc_html__( 'Map Button Hover Background color', 'kava' ),
                'section' => 'single_tour_map',
                'default' => '#ffffff',
                'field'   => 'hex_color',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_btn_hover_text_color' => [
                'title'   => esc_html__( 'Map Button Hover Text color', 'kava' ),
                'section' => 'single_tour_map',
                'default' => get_theme_mod('link_color'),
                'field'   => 'hex_color',
                'type'    => 'control',
                'active_callback' => 'tyto_tour_show_map_preview'
            ],

            'tour_map_zoom' => [
                'title'    => esc_html__( 'Map Zoom', 'tyto' ),
                'description' => esc_html__( 'for maps with one point', 'tyto' ),
                'section'  => 'single_tour_map',
                'default'  => 12,
                'field'    => 'number',
                'type' => 'control',
            ],

            'single_tour_related_sidebar' => [
                'title' => esc_html__('Related (Sidebar)', 'tyto'),
                'type' => 'section',
                'panel' => 'single_tour'
            ],

            'tour_related_show_sidebar' => [
                'title'    => esc_html__( 'Show related items in the sidebar', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_related_show_excerpt' => [
                'title'    => esc_html__( 'Show Excerpt', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_related_sidebar'
            ],

            'tour_related_excerpt_limit' => [
                'title'    => esc_html__( 'Excerpt Length', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => 100,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_related_show_excerpt_sidebar'
            ],

            'tour_group_related' => [
                'title'    => esc_html__( 'Group related by type', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_related_sidebar'
            ],

            'tour_related_title' => [
                'title'    => esc_html__( 'Related title', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => esc_html__( 'Passend dazu:', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_dont_group_related_sidebar'
            ],

            'tour_related_order_travels' => [
                'title'    => esc_html__( 'Travels position', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => 1,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_sidebar'
            ],

            'tour_related_order_accommodations' => [
                'title'    => esc_html__( 'Accommodations position', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => 2,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_sidebar'
            ],

            'tour_related_order_travelsbricks' => [
                'title'    => esc_html__( 'Bricks position', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => 3,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_sidebar'
            ],

            'tour_related_travels_title' => [
                'title'    => esc_html__( 'Related travels title', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => esc_html__( 'Reisen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_sidebar'
            ],

            'tour_related_accommodations_title' => [
                'title'    => esc_html__( 'Related accommodations title', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => esc_html__( 'Unterkunft', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_sidebar'
            ],

            'tour_related_travelsbricks_title' => [
                'title'    => esc_html__( 'Related bricks title', 'tyto' ),
                'section'  => 'single_tour_related_sidebar',
                'default'  => esc_html__( 'Informationen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_sidebar'
            ],

            'single_tour_related_content' => [
                'title' => esc_html__('Related (Content)', 'tyto'),
                'type' => 'section',
                'panel' => 'single_tour'
            ],

            'tour_related_show_content' => [
                'title'    => esc_html__( 'Show related items in the content section', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
            ],

            'tour_related_show_excerpt_content' => [
                'title'    => esc_html__( 'Show Excerpt', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => true,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_related_content'
            ],

            'tour_related_excerpt_limit_content' => [
                'title'    => esc_html__( 'Excerpt Length', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => 100,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_related_show_excerpt_content'
            ],

            'tour_group_related_content' => [
                'title'    => esc_html__( 'Group related by type', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => false,
                'field'    => 'checkbox',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_show_related_content'
            ],

            'tour_related_title_content' => [
                'title'    => esc_html__( 'Related title', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => esc_html__( 'Passend dazu:', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_dont_group_related_content'
            ],

            'tour_related_order_travels_content' => [
                'title'    => esc_html__( 'Travels position', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => 1,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_content'
            ],

            'tour_related_order_accommodations_content' => [
                'title'    => esc_html__( 'Accommodations position', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => 2,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_content'
            ],

            'tour_related_order_travelsbricks_content' => [
                'title'    => esc_html__( 'Bricks position', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => 3,
                'field'    => 'number',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_content'
            ],

            'tour_related_travels_title_content' => [
                'title'    => esc_html__( 'Related travels title', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => esc_html__( 'Reisen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_content'
            ],

            'tour_related_accommodations_title_content' => [
                'title'    => esc_html__( 'Related accommodations title', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => esc_html__( 'Unterkunft', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_content'
            ],

            'tour_related_travelsbricks_title_content' => [
                'title'    => esc_html__( 'Related bricks title', 'tyto' ),
                'section'  => 'single_tour_related_content',
                'default'  => esc_html__( 'Informationen', 'tyto' ),
                'field'    => 'text',
                'type' => 'control',
                'active_callback' => 'tyto_single_tour_group_related_content'
            ],
        );
    }

}
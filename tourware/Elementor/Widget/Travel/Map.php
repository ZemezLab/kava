<?php
namespace Tourware\Elementor\Widget\Travel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Tourware\Elementor\Widget;
use Tourware\Path;

class Map extends Widget {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-map';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Map' );
    }

    public function get_style_depends()
    {
        return ['ep-advanced-gmap'];
    }

    public function get_script_depends() {
        return [ 'gmap-api', 'lodash-adt', 'tourware-travel-map' ];
    }

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytotravels';
    }

    /**
     * @return string
     */
    protected function getRecordTypeName()
    {
        return 'travel';
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content_gmap',
            [
                'label' => esc_html__( 'Google Map', 'bdthemes-element-pack' ),
            ]
        );

        $posts = wp_list_pluck(get_posts(['post_type' => ['tytotravels'], 'post_status' => 'publish', 'posts_per_page' => -1]), 'post_title', 'ID');
        $this->add_control(
            'post',
            [
                'label' => __('Post', 'tourware'),
                'type' => Controls_Manager::SELECT2,
                'options' => $posts,
                'default' => in_array(get_post_type(get_the_ID()), ['tytotravels']) ? get_the_ID() : '',
                'separator' => 'after'
            ]
        );

        $this->add_control(
            'avd_google_map_zoom_control',
            [
                'label'   => esc_html__( 'Zoom Control', 'bdthemes-element-pack' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'avd_google_map_default_zoom',
            [
                'label' => esc_html__( 'Default Zoom', 'bdthemes-element-pack' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 24,
                    ],
                ],
                'condition' => ['avd_google_map_zoom_control' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'avd_google_map_height',
            [
                'label' => esc_html__( 'Map Height', 'bdthemes-element-pack' ),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdt-advanced-gmap'  => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_gmap',
            [
                'label' => esc_html__( 'GMap Style', 'bdthemes-element-pack' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'route_color',
            [
                'label' => __('Route Color', 'tourware'),
                'type' => Controls_Manager::COLOR,
            ]
        );

        $this->add_control(
            'avd_google_map_style',
            [
                'label'   => esc_html__( 'Style Json Code', 'bdthemes-element-pack' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => '',
                'description'   => sprintf( __( 'Go to this link: %1s snazzymaps.com %2s and pick a style, copy the json code from first with [ to last with ] then come back and paste here', 'bdthemes-element-pack' ), '<a href="https://snazzymaps.com/" target="_blank">', '</a>' ),
            ]
        );

        $this->start_controls_tabs( 'tabs_style_css_filters' );

        $this->start_controls_tab(
            'tab_css_filter_normal',
            [
                'label' => __( 'Normal', 'bdthemes-element-pack' )
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters',
                'selector' => '{{WRAPPER}} .bdt-advanced-gmap',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_css_filter_hover',
            [
                'label' => __( 'Hover', 'bdthemes-element-pack' )
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'css_filters_hover',
                'selector' => '{{WRAPPER}} .bdt-advanced-gmap:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'map_border',
                'label'    => esc_html__('Border', 'bdthemes-element-pack'),
                'selector' => '{{WRAPPER}} .bdt-advanced-gmap',
                'separator'=> 'before'
            ]
        );

        $this->add_responsive_control(
            'map_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'bdthemes-element-pack'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .bdt-advanced-gmap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings           = $this->get_settings_for_display();
        $post = $settings['post'] ? $settings['post'] : get_the_ID();

        $id                 = 'tourware-travel-map';
        $mapApiKey = \TyTo\Config::getValue('mapApiKey');

        $map_settings       = [];
        $map_settings['el'] = '#'.$id;

        $repository = \Tourware\Repository\Travel::getInstance();
        $item_data = $repository->findOneByPostId($post);
        $waypoints = tyto_get_route_with_airports($item_data);

        $map_settings['zoomControl']       = ( $settings['avd_google_map_zoom_control'] ) ? true : false;

        $map_settings['streetViewControl'] = true;
        $map_settings['mapTypeControl']    = true;

        $tourware_map_settings = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'postId' => $post,
            'primaryColor' => $settings['route_color'],
            'cachedWaypoints' => get_post_meta($post, '_cached_waypoints', true),
            'showMapPreview' => false,
            'singleWaypointZoom' => $settings['avd_google_map_default_zoom']['size'],
            'showDistances' => false,
            'kmlFile' => $item_data->getKmlFile()
        );

        if (!empty($waypoints['routes'][0])) { ?>
        <?php if(empty($mapApiKey)) : ?>
            <div class="bdt-alert-warning" bdt-alert>
                <a class="bdt-alert-close" bdt-close></a>
                <?php $ep_setting_url = esc_url( admin_url('admin.php?page=tyto-midoffice-wordpress-plugin#tyto[mapApiKey]')); ?>
                <p><?php printf(__( 'Please set your google map api key in <a href="%s">tourware settings</a> to show your map correctly.', 'bdthemes-element-pack' ), $ep_setting_url); ?></p>
            </div>
        <?php endif;

        $this->add_render_attribute( 'advanced-gmap', 'id', $id );
        $this->add_render_attribute( 'advanced-gmap', 'class', 'bdt-advanced-gmap' );
        $this->add_render_attribute( 'advanced-gmap', 'class', 'tourware-travel-map' );


        $this->add_render_attribute( 'advanced-gmap', 'data-tourware_map_settings', wp_json_encode($tourware_map_settings) );

        if( '' != $settings['avd_google_map_style'] ) {
            $this->add_render_attribute( 'advanced-gmap', 'data-map_style', trim(preg_replace('/\s+/', ' ', $settings['avd_google_map_style'])) );
        }

        $this->add_render_attribute( 'advanced-gmap', 'data-map_settings', wp_json_encode($map_settings) );
        $this->add_render_attribute( 'advanced-gmap', 'data-map_geocode', ('yes' == $settings['gmap_geocode']) ? 'true' : 'false' );

        ?>

        <div <?php echo $this->get_render_attribute_string( 'advanced-gmap' ); ?>></div>

        <?php
        }
    }

}
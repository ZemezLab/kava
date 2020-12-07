<?php
namespace ElementorTyto\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Regions_List extends Widget_Base {

    public function get_name() {
        return 'regions-list';
    }

    public function get_title() {
        return __( 'Regions List' );
    }

    public function get_icon() {
        return 'eicon-post';
    }
    public function get_categories() {
        return [ 'tyto' ];
    }

    protected function _register_controls() {
        $this->sectionLayout();
        $this->sectionQuery();
        $this->sectionArrows();
        $this->sectionDots();
    }

    private function sectionLayout(){
        $this->start_controls_section( 't_layout', array(
            'label' => esc_html__( 'Layout', 'goto' ),
        ));

        $this->add_control( 'layout', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Layout', 'goto' ),
            'default' => 'grid',
            'options' => array(
                'grid'     => esc_html__( 'Grid', 'goto' ),
                'carousel' => esc_html__( 'Carousel', 'goto' ),
            ),
        ));

        $this->add_control( 'arrows', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Arrows', 'goto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ));

        $this->add_control( 'dots', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Dots', 'goto' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ));

        $this->add_responsive_control( 'col', array(
            'type'           => Controls_Manager::SELECT,
            'label'          => esc_html__( 'Columns', 'goto' ),
            'default'        => 3,
            'tablet_default' => 2,
            'mobile_default' => 1,
            'options'        => array(
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                5 => 5,
            ),
        ));

        $this->add_control( 'per_page', array(
            'type'    => Controls_Manager::NUMBER,
            'label'   => esc_html__( 'Posts Per Page', 'goto' ),
            'default' => 6,
            'min'     => -1,
            'max'     => 100,
        ));

        $this->add_control( 'pagi', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Pagination', 'goto' ),
            'default'      => '',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'grid'
            )
        ));

        $this->end_controls_section();
    }

    private function sectionQuery(){
        $this->start_controls_section( 't_query', array(
            'label'     => esc_html__( 'Query', 'goto' ),
            'condition' => array(
                't_layout.per_page!' => 0
            ),
        ));

        $this->add_control( 'item_types', array(
            'type'    => Controls_Manager::SELECT2,
            'label'   => esc_html__( 'Item types' ),
            'multiple'  => true,
            'options' => array(
                'tytocontinents'  => esc_html__( 'Continents' ),
                'ht_dest' => esc_html__( 'Destinations' ),
                'tytoregions' => esc_html__( 'Regions' ),
            ),
            'default' => get_post_type(get_the_ID()) == 'ht_dest' ? array('tytoregions') : []
        ));

        $destinations_args = array(
            'post_type'           => 'ht_dest',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => -1,
        );
        $dest_q   = new \WP_Query( $destinations_args );
        $output = wp_list_pluck( $dest_q->posts, 'post_title', 'ID' );
        $this->add_control( 'destinations', array(
            'type'    => Controls_Manager::SELECT2,
            'label'   => esc_html__( 'Destination' ),
            'multiple'  => true,
            'options' => $output,
            'default' => get_post_type(get_the_ID()) == 'ht_dest' ? array(get_the_ID()) : []
        ));

        $this->add_control( 'orderby', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Order By'),
            'default' => 'date',
            'options' => array(
                'date'     => esc_html__( 'Date' ),
                'title'    => esc_html__( 'Title'),
                'ID'       => esc_html__( 'ID'),
                'author'   => esc_html__( 'Author' ),
                'rand'     => esc_html__( 'Random' ),
                'modified' => esc_html__( 'Modified' ),
            ),
        ));

        $this->add_control( 'order', array(
            'type'    => Controls_Manager::SELECT,
            'label'   => esc_html__( 'Order' ),
            'default' => 'DESC',
            'options' => array(
                'ASC'  => esc_html__( 'ASC' ),
                'DESC' => esc_html__( 'DESC' ),
            ),
        ));

        $this->end_controls_section();
    }

    protected function sectionArrows(){
        $this->start_controls_section(  'd_arrows', array(
            'label'     => esc_html__( 'Arrows', 'goto' ),
            'condition' => array(
                'd_layout.layout' => 'carousel',
                'd_layout.arrows' => 'yes',
            ),
        ));

        $this->add_control( 'arrows_size', array(
            'type'    => Controls_Manager::SLIDER,
            'label'   => esc_html__( 'Size', 'goto' ),
            'default' => array(
                'size' => 50
            ),
            'range' => array(
                'px' => array(
                    'min'  => 1,
                    'max'  => 200,
                    'step' => 1
                ),
            ),
            'size_units' => array( 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} .tns-controls [data-controls]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ),
        ));

        $this->add_control( 'arrows_position', array(
            'type'    => Controls_Manager::SLIDER,
            'label'   => esc_html__( 'Horizontal Pisition', 'goto' ),
            'default' => array(
                'size' => -70
            ),
            'range' => array(
                'px' => array(
                    'min'  => -200,
                    'max'  => 200,
                    'step' => 1
                ),
            ),
            'size_units' => array( 'px' ),
            'selectors'  => array(
                '{{WRAPPER}} .tns-controls [data-controls="next"]' => 'right: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .tns-controls [data-controls="prev"]' => 'left: {{SIZE}}{{UNIT}};',
            ),
        ));

        $this->add_control( 'arrows_color', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Color', 'goto' ),
            'default'   => '#fff',
            'selectors' => array(
                '{{WRAPPER}} .tns-controls [data-controls]' => 'color: {{VALUE}};'
            ),
        ));

        $this->add_control( 'arrows_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Background color', 'goto' ),
            'default'   => '#aaa',
            'selectors' => array(
                '{{WRAPPER}} .tns-controls [data-controls]' => 'background-color: {{VALUE}};'
            ),
        ));

        $this->add_control( 'arrows_tablet', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Tablet', 'goto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel',
            )
        ));

        $this->add_control( 'arrows_mobile', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Mobile', 'goto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ));

        $this->end_controls_section();
    }

    protected function sectionDots(){
        $this->start_controls_section( 'd_dots', array(
            'label'     => esc_html__( 'Dots', 'goto' ),
            'condition' => array(
                'd_layout.layout' => 'carousel',
                'd_layout.dots'   => 'yes',
            ),
        ));

        $this->add_responsive_control( 'd_align', array(
            'type'    => Controls_Manager::CHOOSE,
            'label'   => esc_html__( 'Alignment', 'goto' ),
            'options' => array(
                'left' => array(
                    'title' => esc_html__( 'Left', 'goto' ),
                    'icon'  => 'fa fa-align-left'
                ),
                'center' => array(
                    'title' => esc_html__( 'Center', 'goto' ),
                    'icon'  => 'fa fa-align-center'
                ),
                'right' => array(
                    'title' => esc_html__( 'Right', 'goto' ),
                    'icon'  => 'fa fa-align-right'
                ),
            ),
            'default'        => 'center',
            'tablet_default' => 'center',
            'mobile_default' => 'center',
            'selectors'      => array(
                '{{WRAPPER}} .tns-nav' => 'text-align: {{VALUE}};'
            ),
        ));

        $this->add_control( 'dots_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Background color', 'goto' ),
            'default'   => 'rgba( 255, 255, 255, 0.3 )',
            'selectors' => array(
                '{{WRAPPER}} .tns-nav button' => 'background-color: {{VALUE}};'
            ),
        ));

        $this->add_control( 'dots_active_bg', array(
            'type'      => Controls_Manager::COLOR,
            'label'     => esc_html__( 'Current background color', 'goto' ),
            'default'   => '#eeeeee',
            'selectors' => array(
                '{{WRAPPER}} .tns-nav button.tns-nav-active' => 'background-color: {{VALUE}};'
            ),
        ));

        $this->add_control( 'dots_tablet', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Tablet', 'goto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel',
            )
        ));

        $this->add_control( 'dots_mobile', array(
            'type'         => Controls_Manager::SWITCHER,
            'label'        => esc_html__( 'Hide on Mobile', 'goto' ),
            'default'      => 'yes',
            'label_on'     => esc_html__( 'Yes', 'goto' ),
            'label_off'    => esc_html__( 'No', 'goto' ),
            'return_value' => 'yes',
            'condition'    => array(
                'layout' => 'carousel'
            )
        ));

        $this->end_controls_section();
    }


    protected function render( $instance = [] )
    {
        $settings = $this->get_settings_for_display();

        $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
        $paged = $paged ? intval($paged) : 1;

        $item_types = empty($settings['item_types']) ? array('tytocontinents', 'tytoregions', 'ht_dest') : $settings['item_types'];

        $args = array(
            'post_type' => $item_types,
            'post_status' => 'publish',
            'posts_per_page' => $settings['per_page'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'paged' => $paged,
        );

        $destinations = [];
        if (get_post_type(get_the_ID()) == 'ht_dest') {
            $destinations = [get_the_ID()];
        }
        if (!empty($settings['destinations'])) $destinations = $settings['destinations'];

        if (!empty($destinations)) {
            $args['meta_query'] = [ 'relation' => 'OR' ];
            foreach ($destinations as $destination) {
                array_push($args['meta_query'], array('key' => 'country', 'value' => get_the_title($destination)));
            }
        }

        $query = new \WP_Query($args);

        $tiny_slider_id = uniqid( 'advansed-tyto-list-id-' );
        $this->renderCarousel( $tiny_slider_id, $settings['layout'], $settings['col'], $settings['col_tablet'], $settings['col_mobile'] );
        $tiny_slider_data = $this->carouselOptions( $settings['layout'], $settings['col'], $settings['col_tablet'], $settings['col_mobile'] );
        $classes = 'wd-tours-layout-'. $settings['layout'] .' ht-grid ht-grid-'. $settings['col'] .' ht-grid-tablet-'. $settings['col_tablet'] .' ht-grid-mobile-'. $settings['col_mobile'];
        $layout_name = 'carousel' == $settings['layout'] ? 'not-real-slider' : '';
        ?>
        <div class="wd-tours">
            <div class="<?php echo $classes ?>">
                <div class="wd-tours-content <?php echo esc_attr( $layout_name ); ?>"  id="<?php echo esc_attr( $tiny_slider_id ); ?>" <?php echo wp_kses_post( $tiny_slider_data ); ?>>
                    <?php
                    while( $query->have_posts() ):
                        $query->the_post();

                        if( ! function_exists( 'FW' ) ) return;

                        $img_id     = get_post_thumbnail_id( get_the_ID() );
                        $img_alt    = get_the_title(get_the_ID());
                        $img_src    = ! empty( $img_id ) ? get_the_post_thumbnail_url( get_the_ID(), 'medium_large' ) : get_post_meta($destination->ID, 'header_image', true);
                        ?>
                        <div class="ht-grid-item" <?php goto_schema_markup( 'creative_work' ); ?>>
                            <div class="wd-tour-item">
                                <?php /*HEAD*/ ?>
                                <div class="wd-tour-head">
                                    <a href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
                                    </a>
                                </div>

                                <?php /*CONTENT*/ ?>
                                <div class="wd-tour-content" itemprop="text">
                                    <h3 class="wdtc-title entry-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <time class="sr-only" itemprop="datePublished" datetime="<?php echo get_the_time( 'c' ); ?>"><?php echo get_the_date(); ?></time>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;

                    if ( 'yes' == $settings['pagi'] && 'grid' == $settings['layout'] ) {
                        goto_paging( $query );
                    }

                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>

        <?php
    }

    protected function carouselOptions( $layout, $col_desktop, $col_tablet, $col_mobile ){
        $settings = $this->get_settings_for_display();

        if( 'carousel' != $layout ) return '';

        $options = array(
            "items"      => intval( $col_mobile ),
            "controls"   => 'yes' == $settings['arrows'] && 'yes' != $settings['arrows_mobile'] ? true : false,
            "nav"        => 'yes' == $settings['dots'] && 'yes' != $settings['dots_mobile'] ? true : false,
            "loop"       => false,
            "autoHeight" => true,
            "responsive" => array(
                768  => array(
                    "items"    => intval( $col_tablet ),
                    "controls" => 'yes' == $settings['arrows'] && 'yes' != $settings['arrows_tablet'] ? true : false,
                    "nav"      => 'yes' == $settings['dots'] && 'yes' != $settings['dots_tablet'] ? true : false,
                ),
                1024 => array(
                    "items"    => intval( $col_desktop ),
                    "controls" => 'yes' == $settings['arrows'] ? true : false,
                    "nav"      => 'yes' == $settings['dots'] ? true : false,
                ),
            ),
        );

        $tiny_slider_data = "data-tiny-slider='" . json_encode( $options ) . "'";

        return $tiny_slider_data;
    }
    /*RENDER CAROUSEL FOR FRONT-END VIEW*/
    protected function renderCarousel( $tiny_slider_id, $layout, $col_desktop, $col_tablet, $col_mobile ){
        $settings = $this->get_settings_for_display();

        if( 'carousel' != $layout ) return;

        /*ENQUEUE SCRIPT AND STYLE*/
        wp_enqueue_style( 'tiny-slider' );
        wp_enqueue_script( 'tiny-slider-js' );

        /*GET TINY SLIDER OPTIONS*/
        $arrows        = 'yes' == $settings['arrows'] ? 1 : 0;
        $arrows_tablet = 'yes' == $settings['arrows'] && 'yes' == $settings['arrows_tablet'] ? 0 : 1;
        $arrows_mobile = 'yes' == $settings['arrows'] && 'yes' == $settings['arrows_mobile'] ? 0 : 1;
        $dots          = 'yes' == $settings['dots'] ? 1 : 0;
        $dots_tablet   = 'yes' == $settings['dots'] && 'yes' == $settings['dots_tablet'] ? 0 : 1;
        $dots_mobile   = 'yes' == $settings['dots'] && 'yes' == $settings['dots_mobile'] ? 0 : 1;

        wp_add_inline_script(
            'tiny-slider-js',
            "document.addEventListener( 'DOMContentLoaded', function(){
                var _arr        = 1 == {$arrows} ? true : false,
					_arr_tablet = _arr && 1 == {$arrows_tablet} ? true : false,
					_arr_mobile = _arr && 1 == {$arrows_mobile} ? true : false,
					_dot        = 1 == {$dots} ? true : false,
					_dot_tablet = _dot && 1 == {$dots_tablet} ? true : false,
					_dot_mobile = _dot && 1 == {$dots_mobile} ? true : false;

                var slider = tns({
                    container: '#{$tiny_slider_id}',
                    controls: _arr_mobile,
                    nav: _dot_mobile,
                    items: {$col_mobile},
                    autoHeight: true,
                    mouseDrag: true,
                    loop: false,
                    responsive: {
                        768: {
                            items: {$col_tablet},
                            controls: _arr_tablet,
                            nav: _dot_tablet,
                        },
                        1024: {
                            items: {$col_desktop},
                            controls: _arr,
                            nav: _dot,
                        }
                    }
                });
            } );",
            'after'
        );
    }
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widget_Regions_List() );
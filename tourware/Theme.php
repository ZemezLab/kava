<?php

namespace Tourware;

use Tourware\Elementor;
use \Elementor\Plugin;

class Theme
{
    /**
     * A reference to an instance of this class.
     */
    private static $instance = null;

    /**
     * Returns the instance.
     */
    public static function getInstance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @return \Tourware\Theme
     */
    public function run() {
        $elementor = new Elementor();
        $elementor->init();



        add_action( 'elementor/dynamic_tags/register_tags', function( $dynamic_tags ) {
            \Elementor\Plugin::$instance->dynamic_tags->register_group( 'tourware', [
                'title' => 'tourware'
            ] );

            $dynamic_tags->register_tag( Elementor\DynamicTag\Option::class );
        } );

        add_action( 'init', function () {
            register_taxonomy_for_object_type( 'category', 'page' );
        } );

        add_action( 'wp_enqueue_scripts', function () {
            wp_enqueue_script('tourware-js', get_parent_theme_file_uri() . '/public/tourware.js', 'vue', null, true);
            wp_enqueue_style('tourware', get_parent_theme_file_uri() . '/public/tourware.css');
        } );

        add_action('elementor/frontend/before_register_scripts', function () {
            wp_register_script('lodash-adt', get_parent_theme_file_uri() . '/tourware-resources/js/widget/travel/lodash.js', ['jquery']);
            wp_register_script('tourware-travel-map', get_parent_theme_file_uri() . '/tourware-resources/js/widget/travel/Map.js', ['lodash-adt']);
        });

        add_action( 'elementor/widgets/widgets_registered', function() {
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Gallery() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Listing() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Services() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Itinerary() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Details() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Map() );

            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Accommodation\Listing() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Accommodation\Details() );

            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Destination\Listing() );

            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Search() );
        } );

        // Legacy
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Typography(), 'register'), 99 );
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Page\Header(), 'register'), 99 );
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Buttons(), 'register'), 99 );
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Travel\Single(), 'register'), 99 );
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Travel\Sidebar(), 'register'), 99 );
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Accommodation\Single(), 'register'), 99 );
        add_filter( 'kava-theme/customizer/options', array(new Customizer\Brick\Single(), 'register'), 99 );

        // Correct way
        add_action( 'customize_register', function ($wp_customize) {
            new Customizer\Page\General($wp_customize);
        } );

        include get_theme_file_path( 'inc/tourware/tcpdf/generate.php' );
        include get_theme_file_path( 'inc/tourware/form-action-hook.php' );
        include get_theme_file_path( 'inc/tourware/functions-pipedrive.php' );
        include get_theme_file_path( 'inc/tourware/pipedrive-sender.php' );

        return $this;
    }
}
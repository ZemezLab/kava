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

        add_action( 'wp_enqueue_scripts', function () {
            wp_enqueue_style('tourware', get_theme_file_uri() . '/tourware-resources/scss/tourware.css');
        } );

        add_action( 'elementor/widgets/widgets_registered', function() {
//            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Record\Listing() );
            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Travel\Listing() );
//            Plugin::instance()->widgets_manager->register_widget_type( new \Tourware\Elementor\Widget\Accommodation\Listing() );
        } );

        return $this;
    }
}
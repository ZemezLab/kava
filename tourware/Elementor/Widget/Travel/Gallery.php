<?php

namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget;
use Elementor\Controls_Manager;

class Gallery extends Widget
{

    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-gallery';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Gallery', 'tourware' );
    }

    /**
     * @param array $instance
     */
    protected function render( $instance = [] )
    {
        $repository = \Tourware\Repository\Travel::getInstance();
        $record = $repository->findOneByPostId(get_the_ID());
        $config = $this->get_settings_for_display();

        include \Tourware\Path::getResourcesFolder() . 'layouts/travel/headergallery/template.php';
    }

    public function _enqueue_styles()
    {
        $repository = \Tourware\Repository\Travel::getInstance();
        $record = $repository->findOneByPostId(get_the_ID());
        $imageCount = 3;

//        wp_enqueue_style( 'tiny-slider' );
//        wp_enqueue_script( 'tiny-slider-js' );

//        wp_add_inline_script(
//            'elementor-frontend',
//            "jQuery( function( $ ) {
//                // Add space for Elementor Menu Anchor link
//                if ( window.elementorFrontend ) {
//                    elementorFrontend.hooks.addAction( 'frontend/element_ready/tourware-travel-gallery.default', function() {
//							var slider = tns({
//								container: '.tourware-travel-gallery',
//								loop: false,
//								lazyload: true,
//								items: ".$imageCount.",
//								gutter: 1,
//								mouseDrag: true,
//								nav: true,
//								arrowKeys: true,
//								autoHeight: true,
//								controls: false,
//								responsive: {
//									240: {
//										items: 1
//									},
//									768: {
//										items: 2,
//										controls: true,
//										nav: false
//									},
//									992: {
//										items: ".$imageCount.",
//										controls: true
//									}
//								}
//							});
//                    } );
//                }
//            } );",
//            'after'
//        );
    }

}
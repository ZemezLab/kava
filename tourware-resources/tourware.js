import Vue from 'vue'
import { Cloudinary } from 'cloudinary-core'

const cloudinary = Cloudinary.new({ cloud_name: 'midoffice', secure: true })
const files = require.context('./vue', true, /\.vue$/i)

Vue.prototype.$cloudinary = cloudinary

files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

jQuery( function( $ ) {
    // editor mode
    if (elementorFrontend.hooks) {
        initializeVueWidgets();
    } else {
        jQuery(window).on('elementor/frontend/init', function() {
            initializeVueWidgets();
        });
    }
} );

function initializeVueWidgets() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function( $scope ) {
        var vueElement = $scope.find('.vue-widget-wrapper');

        if (vueElement.length > 0) {
            new Vue({
                el: vueElement[0]
            });
        }
    } );
}
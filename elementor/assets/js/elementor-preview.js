'use strict';

var onElementorLoaded = function onElementorLoaded(callback) {
    if (undefined === window.elementorFrontend || undefined === window.elementorFrontend.hooks) {
        setTimeout(function () {
            return onElementorLoaded(callback);
        });
        return;
    }
    callback();
};

var advancedTytoListCarousel = function tourCarousel() {
    var el = document.getElementsByClassName('tours-layout-carousel'),
        eleng = el.length,
        i = void 0;

    if (eleng < 1) return;

    for (i = 0; i < eleng; i++) {
        var c = el[i].getElementsByClassName('tours-content')[0];
        if (c.classList.contains('tns-slider')) continue;

        var opt = JSON.parse(c.getAttribute('data-tiny-slider'));
        opt.container = c;

        var slider = tns(opt);
    }
};

/* ON ELEMENTOR LOADED
 ***************************************************/
document.addEventListener( 'DOMContentLoaded', function(){
    onElementorLoaded(function () {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function () {
            advancedTytoListCarousel();
        })
    })
});

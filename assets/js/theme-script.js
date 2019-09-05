;var Kava_Theme_JS;

(function($) {
	'use strict';

	Kava_Theme_JS = {

		init: function() {
			this.page_preloader_init();
			this.toTopInit();
			this.responsiveMenuInit();
			this.magnificPopupInit();
			this.swiperInit();
		},

		page_preloader_init: function() {

			if ($('.page-preloader-cover')[0]) {
				$('.page-preloader-cover').delay(500).fadeTo(500, 0, function() {
					$(this).remove();
				});
			}
		},

		toTopInit: function() {
			if ( undefined === window.kavaConfig.toTop || ! window.kavaConfig.toTop ) {
				return;
			}

			this.toTop();
		},

		toTop: function( options ) {
			var defaults = {
					buttonID:    'toTop',
					min:         200,
					inDelay:     600,
					outDelay:    400,
					scrollSpeed: 600,
					easingType:  'linear'
				},
				settings = $.extend( defaults, options ),
				buttonSelector = '#' + settings.buttonID;

			$( 'body' ).append( '<div id="' + settings.buttonID + '" role="button"></div>' );

			$( buttonSelector ).hide().on( 'click.KavaThemeToTop', function() {
				$( 'html, body' ).animate( { scrollTop: 0 }, settings.scrollSpeed, settings.easingType );
				return false;
			} );

			$( window ).scroll( function() {
				var scrollTop = $( window ).scrollTop();

				if ( scrollTop > settings.min )
					$( buttonSelector ).fadeIn( settings.inDelay );
				else
					$( buttonSelector ).fadeOut( settings.outDelay );
			} );
		},

		responsiveMenuInit: function() {
			if (typeof kavaResponsiveMenu !== 'undefined') {
				kavaResponsiveMenu();
			}
		},

		magnificPopupInit: function() {

			if (typeof $.magnificPopup !== 'undefined') {

				//MagnificPopup init
				$('[data-popup="magnificPopup"]').magnificPopup({
					type: 'image'
				});

				$(".gallery > .gallery-item a").filter("[href$='.png'],[href$='.jpg']").magnificPopup({
					type: 'image',
					gallery: {
						enabled: true,
						navigateByImgClick: true,
					},
				});

			}
		},

		swiperInit: function() {
			if (typeof Swiper !== 'undefined') {

				//Swiper carousel init
				var mySwiper = new Swiper('.swiper-container', {
					// Optional parameters
					loop: true,
					spaceBetween: 10,
					autoHeight: true,

					// Navigation arrows
					navigation: {
						nextEl: '.swiper-button-next',
						prevEl: '.swiper-button-prev'
					}
				})

			}
		}
	};

	Kava_Theme_JS.init();

}(jQuery));
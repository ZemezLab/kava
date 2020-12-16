// Responsive menu
var kavaResponsiveMenu = function kavaResponsiveMenu() {
	var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	var defaults = {
		wrapper: '.main-navigation',
		menu: '.menu',
		threshold: 640, // Minimal menu width,
		mobileMenuClass: 'mobile-menu',
		mobileMenuOpenClass: 'mobile-menu-open',
		mobileMenuToggleButtonClass: 'mobile-menu-toggle-button',
		toggleButtonTemplate: '<i class="mobile-menu-close fa fa-bars" aria-hidden="true"></i><i class="mobile-menu-open fa fa-times" aria-hidden="true"></i>'
	};

	if ( typeof Object.assign !== 'function' ) {
		options = jQuery.extend( {}, defaults, options );
	} else {
		options = Object.assign( defaults, options );
	}

	var wrapper = options.wrapper.nodeType ?
		options.wrapper :
		document.querySelector(options.wrapper);

	var menu = options.menu.nodeType ?
		options.menu :
		document.querySelector(options.menu);

	var toggleButton,
		toggleButtonOpenBlock,
		toggleButtonCloseBlock,
		isMobileMenu,
		isMobileMenuOpen;

	// series
	var init = [
		addToggleButton,
		checkScreenWidth,
		addResizeHandler
	];

	if (wrapper && menu) {
		runSeries(init);
	}

	function addToggleButton() {
		toggleButton = document.createElement('button');

		toggleButton.innerHTML = options.toggleButtonTemplate.trim();
		toggleButton.className = options.mobileMenuToggleButtonClass;
		wrapper.insertBefore(toggleButton, wrapper.children[0]);

		toggleButtonOpenBlock = toggleButton.querySelector('.mobile-menu-open');
		toggleButtonCloseBlock = toggleButton.querySelector('.mobile-menu-close');

		toggleButton.addEventListener('click', mobileMenuToggle);
	}

	// menu switchers
	function switchToMobileMenu() {
		wrapper.classList.add(options.mobileMenuClass);
		toggleButton.style.display = "block";
		isMobileMenuOpen = false;
		hideMenu();
	}

	function switchToDesktopMenu() {
		wrapper.classList.remove(options.mobileMenuClass);
		toggleButton.style.display = "none";
		showMenu();
	}

	// mobile menu toggle
	function mobileMenuToggle() {
		if (isMobileMenuOpen) {
			hideMenu();
		} else {
			showMenu();
		}
		isMobileMenuOpen = !isMobileMenuOpen;
	}

	function hideMenu() {
		wrapper.classList.remove(options.mobileMenuOpenClass);
		menu.style.display = "none";
		toggleButtonOpenBlock.style.display = "none";
		toggleButtonCloseBlock.style.display = "block";
	}

	function showMenu() {
		wrapper.classList.add(options.mobileMenuOpenClass);
		menu.style.display = "block";
		toggleButtonOpenBlock.style.display = "block";
		toggleButtonCloseBlock.style.display = "none";
	}

	// resize helpers
	function checkScreenWidth() {
		var currentMobileMenuStatus = window.innerWidth < options.threshold ? true : false;

		if (isMobileMenu !== currentMobileMenuStatus) {
			isMobileMenu = currentMobileMenuStatus;
			isMobileMenu ? switchToMobileMenu() : switchToDesktopMenu();
		}
	}

	function addResizeHandler() {
		window.addEventListener('resize', resizeHandler);
	}

	function resizeHandler() {
		window.requestAnimationFrame(checkScreenWidth)
	}

	// general helpers
	function runSeries(functions) {
		functions.forEach( function( func ) {
			return func();
		} );
	}
};

var Kava_Theme_JS;

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
			var $pleloader = $( '.page-preloader-cover' );

			if ( $pleloader[0] ) {
				$pleloader.delay( 500 ).fadeTo( 500, 0, function() {
					$( this ).remove();
				} );
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

			$( window ).on( 'scroll', function() {
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

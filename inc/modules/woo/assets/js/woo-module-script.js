;var Kava_Woo_Module;

(function ($) {
	"use strict";

	Kava_Woo_Module = {

		init: function () {
			this.wooHeaderCart();
		},

		wooHeaderCart: function () {
			var headerCartButton = $('.header-cart__link');

			headerCartButton.on('click', function ( event ) {
				event.preventDefault();
				$('.header-cart__content').toggleClass('show');
			})
		}
	};

	Kava_Woo_Module.init();

}(jQuery));
/**
 * Tour single
 *
 * @package goto
 */

'use strict';

// Review must not be empty.
function reviewNotEmpty(){
	var reviewForm = document.getElementById( 'commentform' ),
		rating     = document.getElementById( 'rate' );

	if ( ! reviewForm || ! rating ) {
		return;
	}

	reviewForm.removeAttribute( 'novalidate' );
	reviewForm.addEventListener( 'submit', function( e ) {
		if ( ! rating.value ) {
			alert( rating.getAttribute( 'data-warning' ) );
			e.preventDefault();
		}
	} );
}

// Get title and url of Tour for Contact Form 7.
function getTourData() {
	var tourTitle      = document.querySelector( '.tour-title' ),
		tourUrl        = window.location.href,
		inputTourTitle = document.getElementById( 'ct7-tour-name' ),
		inputTourUrl   = document.getElementById( 'ct7-tour-url' );

	if ( ! inputTourUrl || ! inputTourTitle || ! tourTitle || ! tourUrl ) {
		return;
	}

	inputTourTitle.setAttribute( 'value', tourTitle.innerHTML );
	inputTourUrl.setAttribute( 'value', tourUrl );
}

// Sidebar sticky.
function sidebarSticky() {
	if ( ! document.getElementById( 'tour-sidebar-sticky' ) ) return;
	var adminBar         = document.getElementById( 'wpadminbar' ),
		adminBarHeight   = adminBar ? adminBar.offsetHeight : 0,
		nav              = document.getElementById( 'tour-nav' ),
		navHeight        = nav ? 35 : 0,
		mainHeader       = document.getElementById( 'site-header' ),
		mainHeaderHeight = mainHeader ? 60 : 0,
		space            = adminBarHeight + navHeight + mainHeaderHeight + 1,
		sidebar          = new StickySidebar( '#tour-sidebar-sticky', {
			topSpacing: space,
			containerSelector: '#has-sidebar-sticky',
			innerWrapperSelector: '.tour-sidebar',
			minWidth: 991
		});

	// Update for expand Itinerary button on child-theme, some customer.
	var expandButton = document.getElementById( 'tour-expand-all' );
	var additionalOptions = jQuery('.additional-option');
	var datesRow = jQuery('.dates-row');
	if ( expandButton ) {
		expandButton.onclick = function() {
			setTimeout(
				function() {
					sidebar.updateSticky();
				},
				500
			);
		}
	}
	if (additionalOptions) {
		additionalOptions.on('click', function() {
			setTimeout(
				function() {
					sidebar.updateSticky();
				},
				500
			);
		});
	}
	jQuery('body').on('click', '.additional i.ion-close', function() {
		setTimeout(
			function() {
				sidebar.updateSticky();
			},
			500
		);
	});
	if (datesRow) {
		datesRow.on('click', function() {
			setTimeout(
				function() {
					sidebar.updateSticky();
				},
				500
			);
		});
	}
}

// Scroll to section.
function scrollToSection() {
	jQuery( '#tour-nav' ).on( 'click', 'a', function ( e ) {
		e.preventDefault();

		var target         = jQuery( this ).attr( 'href' ),
			adminBar       = document.getElementById( 'wpadminbar' ),
			adminBarHeight = adminBar ? adminBar.offsetHeight : 0,
			nav            = document.getElementById( 'tour-nav' ),
			navHeight      = nav ? nav.offsetHeight : 0,
			mainHeader     = document.getElementById( 'tour-nav' ),
			mainHeaderHeight = mainHeader ? mainHeader.offsetHeight : 0,
			space          = adminBarHeight + navHeight + mainHeaderHeight - 1;

		jQuery( 'html, body' ).stop().animate( {
			scrollTop: jQuery( target ).offset().top - space
		}, 500 );

		return false;
	} );
}

// Scroll action.
function scrollAction() {
	if ( window.matchMedia( '( max-width: 991px )' ).matches ) {
		return;
	}

	var nav            = document.getElementById( 'tour-nav' ),
		parent         = nav ? nav.parentNode : false,
		navHeight      = nav ? nav.offsetHeight : 0,
		navOffsetTop   = parent ? parent.getBoundingClientRect().top : 0,
		mainHeader 	   = document.getElementById( 'site-header' ),
		mainHeaderHeight = mainHeader ? 60 : 0,
		adminBar       = document.getElementById( 'wpadminbar' ),
		adminBarHeight = adminBar ? adminBar.offsetHeight : 0,

		newSpace       = navHeight + adminBarHeight + mainHeaderHeight;

	if ( navOffsetTop - adminBarHeight - mainHeaderHeight >= 0 ) {
		nav.classList.remove( 'active' );
		nav.style.top = '';
	} else {
		nav.classList.add( 'active' );
		nav.style.top = adminBarHeight + mainHeaderHeight + "px";
	}

	var tourSection = document.getElementsByClassName( 'tour-section' );
	for ( var z = 0, k = tourSection.length; z < k; z++ ) {
		if ( tourSection[z].getBoundingClientRect().top - newSpace <= 0 ) {
			var tourActive = document.querySelector( '#tour-nav a.active' );

			if ( tourActive ) {
				document.querySelector( '#tour-nav a.active' ).classList.remove( 'active' );
			}
			document.getElementsByClassName( 'tour-nav-item' )[z].classList.add( 'active' );
		}
	}
}

function header_video() {
	jQuery(".header-cover-image").jarallax({
		speed: TourVars.parallax ? '0.5' : '1.0',
		videoSrc: TourVars.videoUrl+'&loop=1',
		videoLoop: true,
		videoPlayOnlyVisible: false,
		videoStartTime: TourVars.start ? TourVars.start : null,
		videoEndTime: TourVars.end ? TourVars.end : null,
	});
}

document.addEventListener( 'DOMContentLoaded', function () {
	scrollToSection();

	window.addEventListener( 'load', function(){
		setTimeout( scrollAction(), 500 );
		reviewNotEmpty();
		getTourData();
		sidebarSticky();
	} );

	window.addEventListener( 'scroll', scrollAction );
	window.addEventListener( 'resize', scrollAction );

	if (TourVars.header_video && TourVars.header_video_autoplay) {
		header_video();
	}
	if (TourVars.header_video && !TourVars.header_video_autoplay) {
		jQuery('.header-cover-image').on( 'click', function () {
			jQuery(this).find(".fa-play-circle").fadeOut();
			header_video();
		})
	}
} );

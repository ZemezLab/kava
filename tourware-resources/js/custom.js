// @codingStandardsIgnoreStart
'use strict';

/* READY
 ***************************************************/

document.addEventListener('DOMContentLoaded', function () {
    /* SCROLL
     ***************************************************/
    window.addEventListener('scroll', function () {

        /*PARALLAX PAGE HEADER*/
        var parallax = function () {
            if (!window.matchMedia("( min-width: 992px )").matches) return;

            var _p = document.getElementById('page-header-parallax');

            if (!_p) return;

            var _s = window.pageYOffset / _p.getAttribute('data-speed'),
                _o = '0% -' + _s + 'px';

            _p.style.backgroundPosition = _o;
        }();

    });
});

;(function ($) {
    "use strict";

    $(document).ready(function () {
        $(document).on('click', '.h-btn-search', function () {
            $('.cms-modal-search').addClass('open');
            setTimeout(function(){
                $('.cms-modal-search input[name="s"]').focus();
            },1000);
        });

        $(document).on('click', function (e) {
            if (e.target.className == 'cms-modal-close'){
                $(e.target).parents(".cms-modal-search").removeClass('open');
            }
            if (e.target.className == 'cms-modal cms-login-popup open')
                $('.cms-login-popup').removeClass('open').addClass('remove');
        });

        /* Mobile Menu */
        $("#main-menu-mobile .open-menu").on('click', function () {
            $(this).toggleClass('opened');
            $('.site-navigation').toggleClass('navigation-open');
        });
        $('.main-navigation li.menu-item-has-children').append('<span class="main-menu-toggle"><span class="material-icons">expand_more</span></span>');
        $('.main-menu-toggle').on('click', function () {
            $(this).parent().find('> .sub-menu').toggleClass('submenu-open');
            $(this).parent().find('> .sub-menu').slideToggle();
            if ($(this).parent().find('> .sub-menu').hasClass('submenu-open')) {
                $(this).html('<span class="material-icons">expand_less</span>');
            } else {
                $(this).html('<span class="material-icons">expand_more</span>');
            }
        });

        /* Login/Register */
        $('.btn-sign-in').click(function (e) {
            e.preventDefault();
            $('.cms-login-popup').removeClass('remove').toggleClass('open');
            $('.cms-register-popup').removeClass('open');
        });

        var scroll_top;
        var window_height;
        var window_width;
        var scroll_status = '';
        var lastScrollTop = 0;
        $(window).on('scroll', function () {
            scroll_top = $(window).scrollTop();
            window_height = $(window).height();
            window_width = $(window).width();
            if (scroll_top < lastScrollTop) {
                scroll_status = 'up';
            } else {
                scroll_status = 'down';
            }
            lastScrollTop = scroll_top;
            mintech_header_sticky();
        });

        $(window).on('load', function () {
            window_width = $(window).width();
            mintech_header_sticky();
        });

        function mintech_header_sticky() {
            scroll_top = $(window).scrollTop();
            var offsetTop = $('#site-header-wrap').outerHeight();
            var h_header = $('.fixed-height').outerHeight();
            var offsetTopAnimation = offsetTop + 10;
            if($('#site-header-wrap').hasClass('is-sticky')) {
                if (scroll_top > offsetTopAnimation) {
                    $('#site-header').addClass('h-fixed');
                } else {
                    $('#site-header').removeClass('h-fixed');
                }
            }
            if (window_width > 992) {
                $('.fixed-height').css({
                    'height': h_header
                });
            }
            $('.cms-navigation-menu1.ct-menu-fixed').each(function () {
                var adminbar_height = $('#wpadminbar').outerHeight();
                var secondary_menu_offset = $('.cms-navigation-offsettop').offset().top - adminbar_height;
                var h_secondary_menu = $(this).outerHeight();
                if (scroll_top > secondary_menu_offset) {
                    $(this).addClass('is-sticky');
                } else {
                    $(this).removeClass('is-sticky');
                }
                if (window_width > 992) {
                    $('.cms-navigation-wrap').css({
                        'height': h_secondary_menu
                    });
                }
            });
        }
    });
})(jQuery);

function setUrlRecordId(obj) {
    var anfragen_btn_href = jQuery(obj).attr('href');
    var urlObject = new URL(anfragen_btn_href);
    var postid = jQuery(obj).data('postid')
    urlObject.searchParams.set('postId', postid);
    jQuery(obj).attr('href', urlObject.href);
}

var ajaxRunning = false;
jQuery(document).ready(function() {
    // Bind the throttled handler to the scroll event.
    jQuery(window).scroll(jQuery.debounce(250,
        function () {
            var elem = jQuery('.infinity-scroll');
            if (elem.size()) {
                var docViewTop = jQuery(window).scrollTop();
                var docViewBottom = docViewTop + jQuery(window).height();
                var elemTop = elem.offset().top;
                var elemBottom = elemTop + elem.height() + 10;

                if (elemBottom <= docViewBottom && elemTop >= docViewTop && !ajaxRunning && elem.find('a.page-numbers:not(.hidden)').length) {
                    elem.closest('.advanced-tyto-list').find('.lds-ring').show();
                    var $page_num = elem.find('a.page-numbers:not(.hidden)');
                    add_tyto_items($page_num);
                }
            }
        })
    );
})

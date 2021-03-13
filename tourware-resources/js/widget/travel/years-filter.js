(function ($) {
    $(document).ready(function () {
        $('.years-filter a').click(function (e) {
            e.preventDefault();
            var dates_accordion = $(this).closest('.elementor-widget-container').find('.dates-accordion');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var year = $(this).data('year');
            if (year) {
                dates_accordion.find('.bdt-accordion-item:not([data-year="' + year + '"])').slideUp();
                dates_accordion.find('.bdt-accordion-item[data-year="' + year + '"]').slideDown();

            } else {
                dates_accordion.find('.bdt-accordion-item').fadeIn();
            }
        });
    });
})(jQuery);
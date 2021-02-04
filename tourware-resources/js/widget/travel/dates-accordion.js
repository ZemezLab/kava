(function ($) {
    $(document).ready(function () {
        var default_price = $(document).find('.details-price').html();
        var default_dates = $(document).find('.details-dates').html();
        $('.dates-accordion').find('.row').click(function () {
            var row = $(this);
            var selected_price = row.find('.price').data('value');
            var selected_dates = row.find('.dates').data('value');
            var inquiry_btn = $(document).find('#inquiry_btn');
            var urlObject = new URL(inquiry_btn.attr('href'));

            if (!row.closest('.bdt-accordion-item').hasClass('bdt-open')) {
                $(document).find('.details-price').html(selected_price);
                $(document).find('.details-dates').html(selected_dates);
                urlObject.searchParams.set('dates', selected_dates);
                inquiry_btn.attr('href', urlObject.href);
            } else {
                $(document).find('.details-price').html(default_price);
                $(document).find('.details-dates').html(default_dates);
                urlObject.searchParams.delete('dates');
            }
        })
    })
})(jQuery);
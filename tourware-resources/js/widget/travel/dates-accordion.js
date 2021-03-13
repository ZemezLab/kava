(function ($) {
    $(document).ready(function () {
        var default_price = $(document).find('.details-price').html();
        var default_dates = $(document).find('.details-dates').html();
        var inquiry_btn = $(document).find('#inquiry_btn');
        if (inquiry_btn.size()) {
            var urlObject = new URL(inquiry_btn.attr('href'));
        }
        var details_price = $(document).find('.details-price');
        var details_dates = $(document).find('.details-dates');

        $('.dates-accordion').find('.bdt-accordion-title').click(function () {
            var row = $(this);
            var selected_price = row.find('.price').data('value');
            var selected_dates = row.find('.dates').data('value');

            if (details_dates.html() === selected_dates) {
                details_price.removeClass('custom').html(default_price);
                details_dates.removeClass('custom').html(default_dates);
                if (urlObject) urlObject.searchParams.delete('dates');
            } else {
                details_price.addClass('custom').html(selected_price);
                details_dates.addClass('custom').html(selected_dates);
                if (urlObject)  urlObject.searchParams.set('dates', selected_dates);
                if (inquiry_btn)  inquiry_btn.attr('href', urlObject.href);
            }
        })
    })
})(jQuery);
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
            var details_price = $(document).find('.details-price');
            var details_dates = $(document).find('.details-dates');

            if (details_dates.html() === selected_dates) {
                details_price.removeClass('custom').html(default_price);
                details_dates.removeClass('custom').html(default_dates);
                urlObject.searchParams.delete('dates');
            } else {
                details_price.addClass('custom').html(selected_price);
                details_dates.addClass('custom').html(selected_dates);
                urlObject.searchParams.set('dates', selected_dates);
                inquiry_btn.attr('href', urlObject.href);
            }
        })
    })
})(jQuery);
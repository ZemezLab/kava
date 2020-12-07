(function ($) {
    var WidgetTytoFormBuilderHandlerDate = function ($scope, $) {
        var $elements = $scope.find('.elementor-date-field');

        if (!$elements.length) {
            return;
        }

        var addDatePicker = function addDatePicker($element) {
            if ($($element).hasClass('elementor-use-native')) {
                return;
            }
            var options = {
                minDate: $($element).attr('min') || null,
                maxDate: $($element).attr('max') || null,
                dateFormat: $($element).attr('data-date-format') || null,
                defaultDate: $($element).attr('data-pafe-form-builder-value') || null,
                allowInput: true,

                animate: false,
            };

            if ($($element).data('pafe-form-builder-date-range') != undefined) {
                options['mode'] = 'range';
            }

            if ($($element).data('pafe-form-builder-date-language') != 'english') {
                options['locale'] = $($element).attr('data-pafe-form-builder-date-language');
            }

            $element.flatpickr(options);
        };

        $.each($elements, function (i, $element) {
            addDatePicker($element);
        });

    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tyto-form-builder-field.default', WidgetTytoFormBuilderHandlerDate);
    });
})(jQuery);
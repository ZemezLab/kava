(function($, elementor) {

    'use strict';

    var TourwareTable = function($scope, $) {
        var $tourwareTableContainer = $scope.find('.tourware-data-table.with-script'),
            $tourwareTableSettings = $tourwareTableContainer.data('settings'),
            $tourwareTable = $tourwareTableContainer.find('> table');

        if ($tourwareTableContainer.length) {
            $($tourwareTable).DataTable($tourwareTableSettings);
        }
    }

    jQuery(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', TourwareTable);
    });

}(jQuery, window.elementorFrontend));
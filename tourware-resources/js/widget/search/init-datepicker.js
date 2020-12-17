jQuery(document).ready(function ($) {
    var $search_dates = $('#adv-search-time');
    $search_dates.daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: "DD.MM.YYYY",
            applyLabel: 'Übernehmen',
            cancelLabel: 'Abbrechen',
            fromLabel: 'Abreise',
            toLabel: 'Rückreise',
            daysOfWeek: ['SO', 'MO', 'DI', 'MI', 'DO', 'FR', 'SA'],
            monthNames: ['Jan.', 'Feb.', 'März', 'Apr.', 'Mai', 'Jun.', 'Jul.', 'Aug.', 'Sep.', 'Okt.', 'Nov.', 'Dez.'],
            firstDay: 1
        }
    });

    $search_dates.on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
    });

    $search_dates.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });
})
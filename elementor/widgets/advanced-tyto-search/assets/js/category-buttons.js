jQuery(document).ready(function ($) {
    $('.advanced-tyto-search').find('.tag-button').click(function (e) {
        $(this).toggleClass('active');
        var $container = $(this).parents('.advanced-tyto-search');
        var active_tags = $container.find('.tag-button.active').toArray().map(p => p.innerHTML).join(',');
        $container.find('#i-tags').val(active_tags).trigger('change');
    })
})
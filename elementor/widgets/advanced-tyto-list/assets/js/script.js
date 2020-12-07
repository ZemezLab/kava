jQuery(document).ready(function ($) {
    var pagination = document.querySelector('.advanced-tyto-list a.page-numbers');
    var $body = $('body');
    if (pagination) {
        $body.on('click search', '.advanced-tyto-list a.page-numbers', function (e) {
            e.preventDefault();
            var $page_num = $(this);
            add_tyto_items($page_num, e.type)
        });
    }
})

function add_tyto_items($page_num, trigger) {

    var $args = $page_num.closest('.tyto-pagination').data('args');
    var $container = $page_num.closest('.advanced-tyto-list');
    var id = $page_num.closest('.elementor-widget-advanced-tyto-list').data('id');
    var post_id = $page_num.closest('.tyto-pagination').data('post_id');
    if (trigger === 'search' || trigger === 'click' && $page_num.closest('ul.page-numbers').hasClass('numbers')) {
        jQuery('.autocompete-result').remove();
        $container.addClass('loading');
    }
    var data = {
        action: 'adv_list_pagination',
        args: $args,
        num: $page_num.data('num'),
        widget_id: id,
        post_ID: post_id
    };

    ajaxRunning = true;
    jQuery.ajax({
        url: TytoAjaxVars.ajaxurl,
        dataType: 'json',
        method: 'post',
        data: data,
        success: function (data) {
            $container.find('ul.page-numbers').replaceWith(data.pagination);
            var $tours_content = $container.find('.tours-content');
            $container.removeClass('loading');
            jQuery('.autocompete-result').remove();
            if (data.pagination_type === 'numbers' || data.request.paged === '1') {
                $tours_content.find('.ht-grid-item').fadeOut('fast');
                $container.find('.tours-content').html(data.posts);
                $tours_content.find('.ht-grid-item').hide().fadeIn(200);
                if (trigger === 'click') {
                    document.getElementById($tours_content.attr('id')).scrollIntoView({behavior: "smooth"});
                }
            } else if (data.pagination_type === 'load_more' || data.pagination_type === 'infinity_scroll' && data.request.paged !== '1') {
                jQuery(data.posts).hide().appendTo($container.find('.tours-content')).fadeIn(200);
            }
            if (data.pagination_type === 'infinity_scroll') {
                $container.find('.lds-ring').hide();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown)
        },
        complete: function () {
            ajaxRunning = false;
        }
    });
}


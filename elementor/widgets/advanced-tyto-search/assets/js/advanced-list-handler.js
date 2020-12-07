jQuery(function ($) {
    var AdvancedListHandler = function () {
        this.requests = [];
        $(document.body)
            .on('change', '.advanced-tyto-search:not([data-ajax_button="yes"]) #i-tags', {advancedListHandler: this}, this.reloadList)
            .on('keyup autocomplete', '.advanced-tyto-search:not([data-ajax_button="yes"]) #i-dest', {advancedListHandler: this}, $.debounce(500, this.reloadList))
            .on('click', '.advanced-tyto-search[data-ajax_button="yes"] button[type="submit"]', {advancedListHandler: this}, this.reloadList);
    };

    AdvancedListHandler.prototype.reloadList = function (e) {
        e.preventDefault();
        if (
            (e.keyCode >= 48 && e.keyCode <= 57) //numbers
            || (e.keyCode >= 65 && e.keyCode <= 90) //letters
            || e.keyCode === 13 //enter
            || e.keyCode === 8 //backspace
            || e.keyCode === 46 //delete
            || e.keyCode === 17 //insert
            || e.type === 'change' //select
            || e.type === 'autocomplete' //select autocomplete
            || e.type === 'click' // ajax button click
        ) {

            var $input = $(this);
            var search_str = $input.parents('.advanced-tyto-search').find('input[name="keywords"]').val();
            var search_category = $input.parents('.advanced-tyto-search').find('input[name="category"]').val();
            // if ($input.attr('id') === 'i-dest' && search_str.length > 0 && search_str.length < 2)
            //     return;

            var adv_list_id = $input.parents('.advanced-tyto-search').data('adv_list_id');
            if (adv_list_id) {
                var $adv_list = $('#'+adv_list_id);
                var $adv_list_data = $adv_list.find('.tyto-pagination').data('args');
                // categories
                if (search_category !== '') {
                    $adv_list_data.meta_query.search_tag = {};
                    var search_tags = search_category.split(',');
                    var tags = [];
                    $.each(search_tags, function() {
                        var tag = {};
                        tag.compare = 'LIKE';
                        tag.key = 'tytorawdata';
                        tag.value = "\"name\":\"" + this + "\""
                        tags.push(tag);
                    });
                    $adv_list_data.meta_query.search_tag = tags;
                } else {
                    $adv_list_data.meta_query.search_tag = {};
                }
                // keywords
                $adv_list_data.s = search_str;

                $adv_list.find('.tyto-pagination').data('args', $adv_list_data);
                $adv_list.find('.tyto-pagination a.page-numbers.hidden[data-num="1"]').trigger('search');
            }
        }
    };

    new AdvancedListHandler();
});

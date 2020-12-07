jQuery(function($) {
    var typingQuery;
    var input = document.querySelector('.advanced-tyto-search input[name=keywords]');
    var $body = $('body');
    if (input) {
        $body.on('input click', '.advanced-tyto-search input[name=keywords]', function (e) {
            typingQuery = $(this).val();
            var $input = $(this);
            var $container = $input.closest('.autocomplete-field');

            if (e.type === 'input') {
                var $form = $input.closest('form');
                $form.find('input[name="selected"]').val('');
            }

            var data = {
                action: 'search_autocomplete',
                search_str: typingQuery
            };
            $.ajax({
                url: TytoAjaxVars.ajaxurl,
                dataType: 'json',
                method: 'post',
                data: data,
                success: function (data) {
                    $container.find('.autocomplete-result').remove();
                    if (data.length) {
                        var resultStringHtml = '';
                        for (var i = 0; i < data.length; i++) {
                            resultStringHtml += '<span data-ID="'+ data[i].ID +'">' + data[i].post_title + '</span>';
                        }
                        $container.append('<div class="autocomplete-result">' + resultStringHtml + '</div>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown)
                }
            });
        });
    }


    $body.on('click', '.autocomplete-result span', function () {
        var $span = $(this);
        var $container = $span.closest('.autocomplete-field');
        var form = $span.closest('form');
        $container.find('input[name="keywords"]').val($(this).text()).trigger('autocomplete');
        form.find('input[name="selected"]').val($(this).data('id'));
        form.find('.error').empty();
        $container.find('.autocomplete-result').remove();
    });

    $body.click(function(e)
    {
        var container = $('.autocomplete-field');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.find('.autocomplete-result').remove();
        }
    });

    $('.advanced-tyto-search input[name="keywords"]').keydown(function(e) {
        var $input = $(this);
        var $container = $input.closest('.autocomplete-field');
        var form = $input.closest('form');

        if ($container.length) {
            var selected = null;
            if (e.keyCode === 38) { // up
                selected = $container.find(".selected");
                if (!selected.length) selected = $container.find(".autocomplete-result span:first-child");
                $container.find(".autocomplete-result span").removeClass("selected");
                if (selected.prev().length === 0) {
                    if (selected.siblings().length)
                        selected.siblings().last().addClass("selected");
                    else
                        selected.addClass("selected")
                } else {
                    selected.prev().addClass("selected");
                }
            }
            if (e.keyCode === 40) { // down
                selected = $container.find(".selected");
                if (!selected.length) selected = $container.find(".autocomplete-result span:last-child");
                $container.find(".autocomplete-result span").removeClass("selected");
                if (selected.next().length === 0) {
                    if (selected.siblings().length)
                        selected.siblings().first().addClass("selected");
                    else
                        selected.addClass("selected")
                } else {
                    selected.next().addClass("selected");
                }
            }

            if (e.keyCode === 13) { // enter
                if ($container.find('.autocomplete-result span').length > 0) {
                    e.preventDefault();
                    selected = $container.find(".selected");
                    if (!selected.length) selected = $container.find(".autocomplete-result span:first-child");
                    $container.find('input[name="keywords"]').val(selected.text());
                    form.find('input[name="selected"]').val(selected.data('id'));
                    $container.find('.autocomplete-result').remove();
                }
            }
        }
    });
})

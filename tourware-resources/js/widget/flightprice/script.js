jQuery(document).ready(function ($) {

    $('body').on('click', '.fp-modal-open', function () {
        var fpModalId = $(this).data('id');
        $('.fp-modal[data-id="' + fpModalId + '"]').addClass('active');
    });


    $('body').on('click', '.fp-modal-close', function () {
        $('.fp-modal').removeClass('active');
    });

});
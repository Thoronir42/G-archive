$(function () {
    $.nette.init();
});

$(document).ready(function () {
    initConfirmation();
    initSortable();
    initTags();
    initAjaxModals();
    initScrollspy();
});

function initConfirmation() {
    var options = {
        title: "Wowie, opravdu?",
        singleton: true,
        popout: true,
        placement: 'bottom',

        btnOkLabel: "Jasně",
        btnCancelLabel: "Ne-e"
    };
    $('[data-toggle=confirmation]').confirmation(options);
}

function initSortable() {
    var $sortables = $('.sortable');

    var options = {
        update: function () {
            $.get(handle_sort, {'sort': $sortables.sortable('toArray', {attribute: 'data-id'})});
        }
    };

    $sortables.sortable(options);
}

function initTags() {
    var options = {
        tags: true
    };
    $('.tags').select2(options)
}

function initAjaxModals() {
    $('a.ajax-modal').click(function (e) {

        var modal_id = $(this).data('modal-id');
        var $response = $(this).netteAjax(e);
        $response.success(function (data) {
            //console.log('Success:', data);
            //console.log('Open #' + modal_id);

            var $modal = $('#' + modal_id);
            $modal.modal('show');
        });
    })
}

function initScrollspy() {
    var $body = $('body');
    var spy_target_selector = '#navbar-spy';
    var $spy_target = $(spy_target_selector);
    $body.scrollspy({
        target: spy_target_selector,
        offset: 100
    });

    $spy_target.on('activate.bs.scrollspy', function () {
        $(this).find('li ul').hide();
        $(this).find('li.active ul').show();
    });
    $spy_target.trigger('activate.bs.scrollspy', $spy_target);



}

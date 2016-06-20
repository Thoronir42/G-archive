$(document).ready( function () {
    initConfirmation();

    initSortable();

    initStateAdder();

    $.nette.init();
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
            $.get(handle_sort, {'sort': $sortables.sortable('toArray', { attribute: 'data-id' })});
        }
    };

    $sortables.sortable(options);
}

function initStateAdder(){
    var $form = $("#form-state-adder");

    $form.submit(function(e){
        e.preventDefault();
        var $input = $(this).find("input[name=label]");
        var value = $input.val();

        var action = $form.attr('action').replace('420', value);
        //console.log(action);
        $.get(action);
        return;
    });
}
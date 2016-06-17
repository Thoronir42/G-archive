$(document).ready( function () {
    var options = {
        title: "Wowie, opravdu?",
        singleton: true,
        popout: true,
        placement: 'bottom',

        btnOkLabel: "Jasně",
        btnCancelLabel: "Ne-e"
    };
    $('[data-toggle=confirmation]').confirmation(options);
});

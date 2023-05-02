$(document).ready(function () {

    $(".answer").toggle();
    $(".toggle").addClass("hidden");

    $('h1').click(function () {
        $(this).parent().find(".toggle").toggleClass('hidden shown');
        $(this).parent().find(".answer").toggle();
    });

});
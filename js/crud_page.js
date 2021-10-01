$(document).ready(() => {
    // hide all update divs
    $('.update-row').hide();
    $('#row-add').hide();

    $(".data-row-toggle").click(function (e) {
        e.preventDefault();
        let target = $(this).attr('href');
        let _this = target.replace("-edit", "");
        $(_this).hide();
        $(target).show();
    });

    $(".update-row-toggle").click(function (e) {
        e.preventDefault();
        let target = $(this).attr('href');
        let _this = target.replace("row-", "row-edit-");
        $(_this).hide();
        $(target).show();
    });

    $('#btn-add').click( function () {
        $(this).hide("fast", function () {
            $('#row-add').show("slow");
        });
    })

    $("button[name='btnCancel']").click(function () {
        $('#row-add').hide("slow", function () {
            $('#btn-add').show("fast");
        });
    });
});
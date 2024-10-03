$('body > #filter > div .select .data > a').each(function () {
    $(this).on('click', function (param) {
        if ($(this).parent().parent().hasClass('on')) formSelectHide($(this).parent().parent());
        else formSelectShow($(this).parent().parent());
    });
});


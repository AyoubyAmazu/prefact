$('body > #filter > div .select .data > a').each(function () {
    $(this).on('click', function (param) {
        if ($(this).parent().parent().hasClass('on')) formSelectHide($(this).parent().parent());
        else formSelectShow($(this).parent().parent());
    });
});

$('body > #filter > div .select .data > .list .option a').each(function (item) {
    $(this).on("click", function () {
        console.log($(this).children().html());
        formSelectOption($(this).parent()   );
    });
 });
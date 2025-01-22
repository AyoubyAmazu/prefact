function sortColSelectFilter(div)
{
    formSelectFilter(div);
    
}

function sortColSelectOption(div)
{
    formSelectOption(div);
}
$(document).ready(function() {
    
    $("body > .cont > .data > .main > div > .select > .select.pas > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("body > .cont > .data > .main > div > .select > .select.pas > .data  > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });
    $("body > .cont > .data > .main > div > .select > .select.fac > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("body > .cont > .data > .main > div > .select > .select.fac > .data  > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });

    
    
});




    

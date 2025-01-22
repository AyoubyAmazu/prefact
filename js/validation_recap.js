function sortColSelectFilter(div)
{
    formSelectFilter(div);
    
}

function sortColSelectOption(div)
{
    formSelectOption(div);
    sortColAdapt();
   
}

function sortColAdapt()
{
    var code = $("body > .cont > .data > .main > div > .op > .side > .select.sortCol").attr("code"); if(code == undefined || code == null) code = "";
    var option = $("body > .cont > .data > .main > div > .op > .side > .select.sortCol > .data > .list > .option[code='" + code + "']");
    if(option == undefined || option == null || option == "" || option.length == 0) return;
    var parent = $(option).attr("parent"); if(parent == undefined || parent == null || parent == "") return;
    var txt = [ $(option).siblings(".option[code='" + parent + "']").children("a").text(), $(option).children("a").text() ].filter(e => e != "").join(" <i class='fa-solid fa-angle-right'></i> ");
    $(option).parents(".select").children(".data").children("a").children(".txt").children("div").html(txt);
}

$(document).ready(function() {
    sortColAdapt();
    $("body > .cont > .data > .main > div > .hd > .left > .select.year > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("body > .cont > .data > .main > div > .hd > .left > .select.year > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });
    $("body > .cont > .data > .main > div > .hd > .left > .select.regelement > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("body > .cont > .data > .main > div > .hd > .left > .select.regelement > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });
    
    
});





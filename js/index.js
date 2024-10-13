$(document).ready(function () {
  // $(".reset").on("click", resetFilter );
  $(".save").on("click", fetchData);
  $(".checkbox .data .list").children().each(function (option) {$(this).on("click", function () { formCheckboxUnique($(this))});});
  $("#cont > div > .op > .side > .select.sortCol > .data > .list > .input > .data > input").off("input").on("input", function(event) { sortColSelectFilter($(event.target).parents(".select")); });
  $("#cont > div > .op > .side > .select.sortCol > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });
});
/**
 * Selects an option from sortCol select
 * @param {HTMLElement} div 
 */
function sortColSelectOption(div)
{
    formSelectOption(div);
    sortColAdapt();
    var sortCol = $(div).attr("code"); if(sortCol == undefined || sortCol == null || sortCol == "") sortCol = "dossierCode";
    var obj = { index: { sortCol: sortCol } };
}
/**
 * displays the name and root of selected option 
 */
function sortColAdapt()
{
    var code = $("#cont > div > .op > .side > .select.sortCol").attr("code"); if(code == undefined || code == null) code = "";
    var option = $("#cont > div > .op > .side > .select.sortCol > .data > .list > .option[code='" + code + "']");
    if(option == undefined || option == null || option == "" || option.length == 0) return;
    var parent = $(option).attr("parent"); if(parent == undefined || parent == null || parent == "") return;
    var txt = [ $(option).siblings(".option[code='" + parent + "']").children("a").text(), $(option).children("a").text() ].filter(e => e != "").join(" <i class='fa-solid fa-angle-right'></i> ");
    $(option).parents(".select").children(".data").children("a").children(".main").children(".txt").html(txt);
}
/**
 * filters select options from the inner input of select
 * @param {HTMLElement} div 
 */
function sortColSelectFilter(div)
{
    formSelectFilter(div);
    $(div).children(".data").children(".list").children(".option").not(".off").each(function()
    {
        if($(this).css("display") == "none") return;
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        var parent = $(this).attr("parent"); if(parent == undefined || parent == null) parent = "";
        if(parent == "") $(div).children(".data").children(".list").children(".option[parent='" + code + "']").show();
        else $(div).children(".data").children(".list").children(".option[code='" + parent + "']").show();
    });
}
/**
 * Shows popup the button of each row in the index table
 * @param {HTMLElement} div 
 */
function openPopupMenu(div) {$(".list").addClass("off"); $(div).children(".list").removeClass("off");}
/**
 * fetchs data from php using ajax
 */
function fetchData() {
  $.ajax({
    type: "POST",
    url: "./index_search.php",
    data: {
      search: "",
      annee: $(".annee .data input").val(),
      soc: $(".soc").attr('code'),
      grp: $(".grp").attr('code'),
      txt: $(".txt .data input").val(),
      naf: $(".naf").attr('code'),
      segment: $(".segment").attr('code'),
      resp: $(".resp").attr('code'),
      rd: $(".rd").attr('code'),
      re: $(".re").attr('code'),
      rc: $(".rc").attr('code'),
      ra: $(".ra").attr('code'),
      res: $(".res").attr('code'),
      rs: $(".rs").attr('code'),
      rj: $(".rj").attr('code'),
      rfp: $(".rfp").attr('code'),
      tgr: $(".tgr").attr('code'),
      tgra: $(".tgra").attr('code'),
    },
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
      console.log(data);
      
      try {
        $("#cont > div > .list").append(data.data);
      } catch (e) {
        console.error("Invalid JSON response:", data);
        popError("Received an invalid response from the server.");
      }
      // declare on click functonality for each of rows buttons
      $("body > #cont > div > .list > .line > .col.op > .btn > a").off("click").on("click", function(event) {openPopupMenu($(event.target).parents(".col")); });
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus, errorThrown);
      popError("An error occurred while processing your request."); // Display a user-friendly error message
    },
  });
}

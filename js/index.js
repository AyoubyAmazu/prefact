$(document).ready(function () {
  // $(".reset").on("click", resetFilter );
  $(".save").on("click", fetchData);
  $(".checkbox .data .list").children().each(function (option) {$(this).on("click", function () { formCheckboxUnique($(this))});});
  $("#cont > div > .op > .side > .select.sortCol > .data > .list > .input > .data > input").off("input").on("input", function(event) { sortColSelectFilter($(event.target).parents(".select")); });
  $("#cont > div > .op > .side > .select.sortCol > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });
  $("#cont > div > .op > .side > .btn.displayParam > a").off("click").on("click", function() { displayParam(); });
});
/**
 * validats segment popup data
 */
function displaySegmeSave()
{
    var displaySegme = new Array();
    $("body > .popup.displaySegme > div > .checkbox > .data > .list > .option.on").each(function()
    {
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        displaySegme.push(code);
    });
    if(displaySegme.length == 0) { popError("Aucune colonnes séléctionnée"); return; }

    // var obj = { index: { displaySegme: displaySegme } };
}
/**
 * Adds functionaltiy to segment popup
 */
function displaySegmeAdapt()
{
    $("body > .popup.displaySegme > div > .checkbox > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { formCheckboxExec($(event.target).parents(".option")); });
    $("body > .popup.displaySegme > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displaySegme > div > .op > .btn.save > a").off("click").on("click", function() { displaySegmeSave(); });
}
/**
 * Fetch Segment Data
 */
function displaySegme()
{ 
    $.ajax({
        url: "index_segment.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displaySegmeAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}
/**
 * Save selected check boxes
 */
function displayParamSave()
{
    var displayParam = new Array();
    $("body > .popup.displayParam > div > .checkbox > .data > .list > .option.on").each(function()
    {
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        displayParam.push(code);
    });
    if(displayParam.length == 0) { popError("Aucune colonnes séléctionnée"); return; }
    else{popDown($("body > .popup.displayParam"));}

    // var obj = { index: { displayCol: displayParam } };
}
/**
 * Adds functionality(cancel, save...) to params popup
 */
function displayParamAdapt()
{
    $("body > .popup.displayParam > div > .checkbox > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { formCheckboxExec($(event.target).parents(".option")); });
    $("body > .popup.displayParam > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displayParam > div > .op > .btn.save > a").off("click").on("click", function() { displayParamSave(); });
}
/**
 * Fetchs The Params Popup data
 */
function displayParam()
{
    $.ajax({
        url: "index_param.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); console.error(error);return; }
            if(result["code"] == 200) { popUp(result["html"]); displayParamAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}
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
    sortTableRows(sortCol);
}
/**
 * Displays the name and root of selected option 
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
    var code = "";
    $(div).children(".data").children(".list").children(".option").not(".off").each(function()
    {
        if($(this).css("display") == "none") return;
        code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        var parent = $(this).attr("parent"); if(parent == undefined || parent == null) parent = "";
        if(parent == "") $(div).children(".data").children(".list").children(".option[parent='" + code + "']").show();
        else $(div).children(".data").children(".list").children(".option[code='" + parent + "']").show();
    });
}
/**
 * Sorts table lines
 * @param {String} code 
 */
function sortTableRows(code) { 
  let asc = $(".checkbox.sortDir > .data > .list > .option.on").attr("code")

  let lines = $("#cont > div > .list .line").map(function () {
    if ($(this).hasClass("st")) return null;
    let line = $(this);
    let label = $(this).next('.labels-section');
    
    return {line: line, label: label}
  }).get().filter((item) => {return item !== null});

  lines.sort((a, b) => {
    let cls = "";
    if (code.includes("dossierCode")) cls = ".col.dossier > .sub.code > a";
    if (code.includes("dossierNom")) cls = ".col.dossier > .sub.nom > a";
    if (code.includes("dossierGroupe")) cls = ".col.dossier > .sub.groupe > a";
    if (code.includes("tempsDuree")) cls = ".col.temps > .sub.duree > .value";
    if (code.includes("tempsCout")) cls = ".col.temps > .sub.cout > .value";
    if (code.includes("tempsDebours")) cls = ".col.temps > .sub.debours > .value";

    if(asc === "ASC") { return $(cls, a.line).text().localeCompare($(cls, b.line).text()); }
    else { return $(cls, b.line).text().localeCompare($(cls, a.line).text()); }
  });
  // console.log(lines);
  lines.forEach(item => {
    console.log(item.line, "======", item.label);
    
    item.line.appendTo($("#cont > div > .list"))
    item.label.appendTo($("#cont > div > .list"))
  })
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
      
      try {
        $("#cont > div > .list").append(data.data);
      } catch (e) {
        console.error("Invalid JSON response:", data);
        popError("Received an invalid response from the server.");
      }
      // declare on click functonality for each of rows buttons
      $("body > #cont > div > .list > .line > .col.op > .btn > a").off("click").on("click", function(event) {openPopupMenu($(event.target).parents(".col")); });

  $("#cont > div > .list > .line > .col.op > .list >.btn.displaySegme > a").off("click").on("click", function() { displaySegme();});
  $("#cont > div > .list > .line > .col.op > .list >.btn.displayCommentaire > a").off("click").on("click", function() { displayCommentaire(); });
  $("#cont > div > .list > .line > .col.op > .list >.btn.displayDéverrouiller > a").off("click").on("click", function() { displayDéverrouiller(); });
  $("#cont > div > .list > .line > .col.op > .list >.btn.displayInvalide > a").off("click").on("click", function() { displayInvalide(); });
  
},
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus, errorThrown);
      popError("An error occurred while processing your request."); // Display a user-friendly error message
    },
  });
}

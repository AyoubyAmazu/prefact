$(document).ready(function () {
    sortColAdapt();
    $("body > #title > div > .main > .op > .segment > a").off("click").on("click", function(){displaySegme()});
    $("body > #cont > div > .op > .side > .select.sortAnalyse > .data > a").off("click").on("click", function (event) { formSelectInit($(event.target).parents(".select"));});
    $("body > #cont > div > .op > .side > .select.sortAnalyse > .data > .list > .input.filter > .data > input").off("input").on("input", function (event) { sortColSelectFilter($(event.target).parents(".select")); });
    $("body > #cont > div > .op > .side > .select.sortAnalyse > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function (event) { sortColSelectOption($(event.target).parents(".option")); });
    $("body > #cont > div > .years > .yearsDiv > .btn.year").each(function () {$(this).off("click").on("click", function name(event) {$(event.target).parent(".btn").toggleClass("selected");displayField();});})
    
    
});
/**
 * Gets fields data from back-end
 */
function displayField()
{
    const params = new URLSearchParams(window.location.search);
    let adr = params.get("d");
    let selectedYears = [];
    $("body > #cont > div > .years > .yearsDiv > .btn.year.selected > a > .txt").each(function(){selectedYears.push($(this).contents()[0].textContent);});
    $.ajax({
        url: "recap_field.php"
        , type: "POST"
        ,dataType:"text"
        , data: 
        {
            adr:adr,
            years: selectedYears,
        }
        // , beforeSend: function() { loaderShow(); }
        // , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data);console.log(data);} catch(error) { popError(); return; }
            if(result["code"] == 200) {$("body > #cont > div > .fields").html(result.html);displayFieldAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
      });
}
/**
 * Add fields btns functionality 
 */
function displayFieldAdapt()
{
    $("body > #cont > div > .fields > .field > .all > .top > .vertica ").off("click").on("click", function (event) { openPopupMenu($(event.target).parents(".vertica")); });
    $("body > #cont > div > .fields > .field > .all > .top > .vertica > .list > .btn.commentaire > a").off("click").on("click", function() { displayCommentaire(); });
    $("body > #cont > div > .fields > .field > .all > .top > .vertica > .list > .btn.recap > a").off("click").on("click", function() { displayRecap(); });
    $("body > #cont > div > .fields > .field > .all > .top > .vertica > .list > .btn.rappel > a").off("click").on("click", function() { displayRappelList(); });
    $("body > #cont > div > .fields > .field > .all >  .tableY > .donneVirt > .value.virt > .labele.virt > .btn > a").off("click").on("click", function() { displayVirement(); });
    $('body > #cont > div > .fields > .field > .all > .table  > .donneTrav > .value > .labele').on('click',function(){  
    var list = $('body > #cont > div > .fields > .field > .all > .table > .donneTrav > .travaux-sublabels');
    if (list.css('display') === 'none') {list.css('display', 'initial');} else {list.css('display', 'none');} })
}
/**
 * Update Segmentation
 */
function updateSegment()
{ 
  $.ajax({
    type:"POST",
    url: "index_segment.php",
    data:
    {
      update_segment: "",
      adr: $("body > #cont > div > .list > .line > .col.op > .list.on").closest(".line ").children(".col.dossier").children(".sub.code").children("a").text(),
      segment:$(".popup.displaySegme > div > .checkbox.col > .data > .list > .option.on").attr("code")
    }
    , beforeSend: function() { popDown(".popup");loaderShow(); }
    , complete: function() { loaderHide(); }
    , success: function(data)
    {
      loaderHide();
        try { var result = JSON.parse(data); } catch(error) { popError(); return; }
        if(result["code"] == 200){popUp(result["html"]);fetch();$(".popup > div > .op > .btn.min.cancel > a").off("click").on("click", function() {popDown(".popup"); }); return; }
        popError(result["txt"], result["btn"]);
        
    }
});
}
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
    updateSegment();
    // var obj = { index: { displaySegme: displaySegme } };
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
            if(result["code"] == 200)
               { 
                popUp(result["html"]);
                displaySegmeAdapt();
                return; 
              }
            popError(result["txt"], result["btn"]);
        }
    });
}
/**
 * Adds functionaltiy to segment popup
 */
function displaySegmeAdapt()
{
    $("body > .popup.displaySegme > div > .checkbox > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { formCheckboxUnique($(event.target).parents(".option")); });
    $("body > .popup.displaySegme > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displaySegme > div > .op > .btn.save > a").off("click").on("click", function() {fetch();displaySegmeSave(); });
}
function sortColSelectFilter(div) {
    formSelectFilter(div);
    $(div).children(".data").children(".list").children(".option").not(".off").each(function () {
        if ($(this).css("display") == "none") return;
        var code = $(this).attr("code");
        if (code == undefined || code == null || code == "") return;
        var parent = $(this).attr("parent");
        if (parent == undefined || parent == null) parent = "";
    });
}

function sortColSelectOption(div) {
    formSelectOption(div);
    var sortCol = $(div).attr("code");
    if (sortCol == undefined || sortCol == null || sortCol == "") sortCol = "dossierCode";
    // Assuming you want to update the text in an element with class "selected-element"
    // $(sortSelected).text(sortCol);
    var obj = { index: { sortCol: sortCol } };
    // cookieSave(obj, true);
}


function sortColAdapt() {
    var code = $("body > #cont > div > .op > .side > .select.sortAnalyse").attr("code");
    if (code == undefined || code == null) code = "";
    var option = $("body > #cont > div > .op > .side > .select.sortAnalyse > .data > .list > .option[code='" + code + "']");
    if (option == undefined || option == null || option == "" || option.length == 0) return;
}

function openPopupMenu(div) {
    $(".list").addClass("off");
    if ($(div).children(".list").hasClass("on")) {$(div).children(".list").removeClass("on").addClass("off");return;}
    $(div).children(".list").removeClass("off").addClass("on");
    $(".popup-button").removeClass("active");
    $(div).find(".popup-button").addClass("active");
    formSelectInit(div);
}

function displayCommentaire()
{
    $.ajax({
        url: "index_ajouterComment.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayCommentaireAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}


function displayCommentaireSave()
{
    var displayCommentaire = new Array();
    $("body > .popup.displayCommentaire > div > .checkbox > .data > .list > .option.on").each(function()
    {
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        displayCommentaire.push(code);
    });
    if(displayCommentaire.length == 0) { popError(" "); return; }

    var obj = { index: { displayCommentaire: displayCommentaire } };
    cookieSave(obj, true);
}

function displayCommentaireAdapt()
{
    $("body > .popup.displayCommentaire > div > .checkbox > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { formCheckboxExec($(event.target).parents(".option")); });
    $("body > .popup.displayCommentaire > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displayCommentaire > div > .op > .btn.save > a").off("click").on("click", function() { displayCommentaireSave(); });
}

function displayRecap()
{
    $.ajax({
        url: "index_recap.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayRecapAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}

function displayRecapSave()
{
    var displayInvalide = new Array();
    $("body > .popup.displayRecap > div > .data > .list > .option.on").each(function()
    {
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        displayRecap.push(code);
    });
    if(displayRecap.length == 0) { popAlert("récap validé"); return ; }

    var obj = { index: { displayRecap: displayRecap } };
    cookieSave(obj, true);
}

function displayRecapAdapt()
{
    $("body > .popup.displayRecap > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents("div")); });
    $("body > .popup.displayRecap > div > .op > .btn.save > a").off("click").on("click", function() { displayRecapSave(); });
}

function displayRappel()
{
    $.ajax({
        url: "index_rappel.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayRappelAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}

function displayRappelSave()
{
    var displayRappel = new Array();
    $("body > .popup.displayRappel > div > .data > .list > .option.on").each(function()
    {
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        displayRappel.push(code);
    });
    if(displayRappel.length == 0) { displayRappelList(" "); return; }

    var obj = { index: { displayRppel: displayRappel } };
    cookieSave(obj, true);
}

function displayRappelAdapt()
{
    $("body > .popup.displayRappel > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents("div")); });
    $("body > .popup.displayRappel > div > .op > .btn.save > a").off("click").on("click", function() { displayRappelList(); });
}

function displayRappelListAdapt()
{
    
    $("body > .popup.displayRappelList > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents("div")); });
    $("body > .popup.displayRappelList > div > .op > .btn.save > a").off("click").on("click", function() { displayRappel(); });
}

function displayRappelList()
{
    $.ajax({
        url: "index_listRappel.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayRappelListAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}

function displayVirement()
{
    $.ajax({
        url: "index_Virement.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayVirementAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}

function displayVirmentSave()
{
    var displayVirement = new Array();
    $("body > .popup.displayVirement > div > .checkbox > .data > .list > .option.on").each(function()
    {
        var code = $(this).attr("code"); if(code == undefined || code == null || code == "") return;
        displayVirement.push(code);
    });
    if( displayVirement.length == 0) { popError(" "); return; }

    var obj = { index: { displayVirement: displayVirement } };
    cookieSave(obj, true);
}

function displayVirementAdapt()
{
    $("body > .popup.displayVirement > div > .op > .btn.min.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents("div")); });
    $("body > .popup.displayVirement > div > .op > .btn.min.save > a").off("click").on("click", function() { displayCommentaireSave(); });
}



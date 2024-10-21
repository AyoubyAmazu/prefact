$(document).ready(function () {
  // $(".reset").on("click", resetFilter );
  $(".save").on("click", fetchData);
  fetch();
  $(".checkbox .data .list").children().each(function (option) {$(this).on("click", function () { formCheckboxUnique($(this))});});
  $("#cont > div > .op > .side > .select.sortCol > .data > .list > .input > .data > input").off("input").on("input", function(event) { sortColSelectFilter($(event.target).parents(".select")); });
  $("#cont > div > .op > .side > .select.sortCol > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { sortColSelectOption($(event.target).parents(".option")); });
  $("#cont > div > .op > .side > .btn.displayParam > a").off("click").on("click", function() { displayParam(); });
});
/**
 * Ajax sends post data of Valide
 */
function displayInvalideSave()
{
  $.ajax({
    url: "index_invalide.php"
    , beforeSend: function() { loaderShow(); }
    , complete: function() { loaderHide(); 
    }
    ,data:
    {
      invalide: "",
      adr: $("body > #cont > div > .list > .line > .col.op > .list.on").closest(".line ").children(".col.dossier").children(".sub.code").children("a").text()
    }
    , success: function(data)
    {
        try { var result = JSON.parse(data); } catch(error) { popError(); return; }
        if(result["code"] == 200) { popUp(result["html"]); return; }
        popError(result["txt"], result["btn"]);
    }
});
}
/**
 * Handles invalide solde popup click listners
 */
function displayInvalideAdapt()
{
    $("body > .popup.displayInvalide > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displayInvalide > div > .op > .btn.save > a").off("click").on("click", function() { fetch();displayInvalideSave(); });
}
/**
 * Fetch the html of invalid solde popup
 */
function displayInvalide()
{
    $.ajax({
        url: "index_invalide.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayInvalideAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}
/**
 * Ajax sends post data of deverrouiller
 */
function displayDeverrouillerSave()
{
  $.ajax({
    url: "index_deverrouiller.php"
    , beforeSend: function() { loaderShow(); }
    , complete: function() { loaderHide(); 
    }
    ,data:
    {
      deverroui: "",
      adr: $("body > #cont > div > .list > .line > .col.op > .list.on").closest(".line ").children(".col.dossier").children(".sub.code").children("a").text()
    }
    , success: function(data)
    {
        try { var result = JSON.parse(data); } catch(error) { popError(); return; }
        if(result["code"] == 200) { popUp(result["html"]); displayInvalideAdapt(); return; }
        popError(result["txt"], result["btn"]);
    }
});
}
/**
 * deverrouiller popup buttons click listener
 */
function displayDeverrouillerAdapt()
{
    $("body > .popup.displayDéverrouiller > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displayDéverrouiller > div > .op > .btn.save > a").off("click").on("click", function() { displayDeverrouillerSave(); });
}
/**
 * Fetch the html of deverrouiller popup
 */
function displayDeverrouiller()
{
    $.ajax({    
        url: "index_deverrouiller.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popDown($('.popup'));popUp(result["html"]); fetch();displayDeverrouillerAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}
/**
 * sends comment data to backend to update comment
 */
function onCommentaireSave()
{
  $.ajax({
    url: "index_commentaire.php"
    , type: "POST"
    , data: 
    {
      adr: $("body > #cont > div > .list > .line > .col.op > .list.on").closest(".line ").children(".col.dossier").children(".sub.code").children("a").text(),
      comment: $(".popup.displayCommentaire > div > .textarea > .data > textarea").val(),
    }
    , beforeSend: function() { loaderShow(); }
    , complete: function() { loaderHide(); }
    , success: function(data)
    {
        try { var result = JSON.parse(data); } catch(error) { popError(); return; }
        if(result["code"] == 200) { popDown($('.popup'));popUp(result["html"]); fetch();displayCommentaireAdapt(); return; }
        popError(result["txt"], result["btn"]);
    }
  });
}
/**
 * add clicks functionality to the popup box
 */
function displayCommentaireAdapt()
{
    $("body > .popup.displayCommentaire > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displayCommentaire > div > .op > .btn.save > a").off("click").on("click", function() { onCommentaireSave(); });
}
/**
 * fetch commentair popup from php and displays it
 */
function displayCommentaire()
{ 
  let comment = $("body > #cont > div > .list > .line > .col.op > .list.on").closest('.line').next(".labels-section").children(".comment").text();
    $.ajax({
        url: "index_commentaire.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]);$(".popup > div > .textarea > .data > textarea").val(comment);displayCommentaireAdapt(); return; }
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
 * Adds functionaltiy to segment popup
 */
function displaySegmeAdapt()
{
    $("body > .popup.displaySegme > div > .checkbox > .data > .list > .option").not(".readonly").children("a").off("click").on("click", function(event) { formCheckboxUnique($(event.target).parents(".option")); });
    $("body > .popup.displaySegme > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")); });
    $("body > .popup.displaySegme > div > .op > .btn.save > a").off("click").on("click", function() {fetch();displaySegmeSave(); });
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
 * function filter columns 
 * @param {array} listparams 
 */
function cheakboxFilter(listparams)
{ 
  $("body > #cont > div >.list > .line > div > div  ").hide();
  $("body > #cont > div >.list >  .labels-section ").children().hide();

  listparams.forEach(element => {
    //.................................................dosier filter.................................................
    if(element == "dossierCode"){$("body > #cont > div >.list > .line > .col.dossier > .sub.code ").show();}
    if(element=="dossierNom"){$("body > #cont > div >.list > .line > .col.dossier > .sub.nom ").show();}
    if(element=="dossierGroupe"){$("body > #cont > div >.list > .line > .col.dossier > .sub.groupe").show();}
    //.................................................temps filter.................................................
    if(element=="tempsdurée"){$("body > #cont > div >.list > .line > .col.temps > .sub.duree").show();}
    if(element=="tempscout"){$("body > #cont > div >.list > .line > .col.temps > .sub.cout").show();}
    if(element=="tempsdebours"){$("body > #cont > div >.list > .line > .col.temps > .sub.debours").show();}
    //.................................................factures filter .................................................
    if(element == "facturesQuantité"){$("body > #cont > div >.list > .line > .col.factures > .sub.quantite ").show();}
    if(element=="facturesEmises"){$("body > #cont > div >.list > .line > .col.factures > .sub.emises ").show();}
    if(element=="facturesDébours"){$("body > #cont > div >.list > .line > .col.factures > .sub.debours").show();}
    //.................................................statu filter.................................................
    if(element == "statutSegmentation"){$("body > #cont > div >.list > .line > .col.Statut > .sub.segment ").show();}
    if(element=="statutValue"){$("body > #cont > div >.list > .line > .col.Statut > .sub.plusmoins ").show();}
    if(element=="statutCreance"){$("body > #cont > div >.list > .line > .col.Statut > .sub.solde").show();}
    //.................................................operation filter.................................................
    if(element == "operationsEnCours"){$("body > #cont > div >.list > .line > .col.Operation > .sub.op_encours ").show();}
    if(element=="operationsValide"){$("body > #cont > div >.list > .line > .col.Operation > .sub.op_rd ").show();}
    if(element=="operationsAdministraftif"){$("body > #cont > div >.list > .line > .col.Operation > .sub.op_admin").show();}
    //.................................................Provisions filter.................................................
    // if(element == "dossierCode"){$("body > #cont > div >.list > .line > .col.Provisions > .sub.op_encours ").show();}
    // if(element=="dossierNom"){$("body > #cont > div >.list > .line > .col.Provisions > .sub.op_rd ").show();}
    // if(element=="dossierGroupe"){$("body > #cont > div >.list > .line > .col.Provisions > .sub.op_admin").show();}
    //.................................................autres filter.................................................
    if(element == "lettresMission"){$("body > #cont > div >.list > .labels-section > .lettre   ").show();}
    if(element=="Montant"){$("body > #cont > div >.list > .labels-section > .montant  ").show();}
    if(element=="SoldeValidé"){$("body > #cont > div >.list > .labels-section > .solde  ").show();}
    if(element=="DossierVerrouillé"){$("body > #cont > div >.list > .labels-section > .ver ").show();}
    if(element=="Commentaire"){$("body > #cont > div >.list > .labels-section > .value ").show();}


  
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
    else
    {
      console.log(displayParam)
      cheakboxFilter(displayParam);
      
      popDown($("body > .popup.displayParam"));
    }

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
            try 
            { 
              var result = JSON.parse(data); 

            } 
            catch(error) 
            {
               popError();
                console.error(error);
                return;
            }
            if(result["code"] == 200) { popUp(result["html"]); displayParamAdapt(); return; }
            $(".op > .btn.min.save > a").on("click",paramsFilter());
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

    if(code.includes("FacturesQuantités"))cls = ".col.factures > .sub.quantite ";
    if(code.includes("FacturesEmises"))cls = ".col.factures > .sub.emises ";
    if(code.includes("FacturesDebours"))cls = ".col.factures > .sub.debours";
    //.................................................statu filter.................................................
    if(code.includes("StatutSegmentation"))cls = ".col.Statut > .sub.segment ";
    if(code.includes("StatutValue"))cls = ".col.Statut > .sub.plusmoins ";
    if(code.includes("StatutCreance"))cls = ".col.Statut > .sub.solde";
    //.................................................operation filter.................................................
    if(code.includes("OperationsEncours"))cls = ".col.Operations > .sub.op_encours ";
    if(code.includes("OperationsValid"))cls = ".col.Operations > .sub.op_rd > .value";
    if(code.includes("OperationsAdmin")) cls = ".col.Operations > .sub.op_admin > .value";
    //.................................................Provisions filter.................................................
    // if(element == "dossierCode"))cls = ".col.Provisions > .sub.op_encours ";
    // if(code.includes("dossierNom"))cls = ".col.Provisions > .sub.op_rd ";
    // if(code.includes("dossierGroupe"))cls = ".col.Provisions > .sub.op_admin";cod

    if(asc === "ASC") { return $(cls, a.line).text().localeCompare($(cls, b.line).text()); }
    else { return $(cls, b.line).text().localeCompare($(cls, a.line).text()); }
  });

  lines.forEach(item => {    
    item.line.appendTo($("#cont > div > .list"))
    item.label.appendTo($("#cont > div > .list"))
  })
 }
/**
 * Shows popup the button of each row in the index table
 * @param {HTMLElement} div 
 */
function openPopupMenu(div) { $(div).children(".list").toggleClass("on off");}
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
    dataType: "text",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
      console.log(data);
      
      try {
        let table = $("#cont > div > .list");
        let thead = $($("#cont > div > .list > .line.st"));
        table.html("");
        table.append(thead);
        table.append(data.data)
      } catch (e) {
        console.error("Invalid JSON response:", data);
        popError("Received an invalid response from the server.");
      }
      // declare on click functonality for each of rows buttons
    $("body > #cont > div > .list > .line > .col.op > .btn > a").off("click").on("click", function(event) {openPopupMenu($(event.target).parents(".col")); });
    $("body > #cont > div > .list.off > .line > .col.op > .btn > a").off("click").on("click", function(event) {popDown($(event.target).parents(".popup")); });
    $("#cont > div > .list > .line > .col.op > .list >.btn.displaySegme > a").off("click").on("click", function() { displaySegme();});
    $("#cont > div > .list > .line > .col.op > .list >.btn.displayCommentaire > a").off("click").on("click", function() { displayCommentaire(); });
    $("#cont > div > .list > .line > .col.op > .list >.btn.displayDéverrouiller > a").off("click").on("click", function() { displayDeverrouiller(); });
    $("#cont > div > .list > .line > .col.op > .list >.btn.displayInvalide > a").off("click").on("click", function() { displayInvalide(); });
  
},
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus, errorThrown);
      popError("An error occurred while processing your request."); // Display a user-friendly error message
    },
  });
}
/**
 * Munipulate search btn click
 */
function fetch() { 
  $(".save").click();
 }



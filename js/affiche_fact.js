/**
 * 
 */
let fact = new URLSearchParams(window.location.search).get("f");
let dossier = new URLSearchParams(window.location.search).get("d");
$(document).ready(function(){
  onDeleteFact(); // handels the click on supprimer cette facture
  initSelect();// setup the site select functionalities
  initCheckbox();
  $("#cont >  div > .content > fieldset > .heart > table > tbody > tr > td > .btn.min.operation-delete > a").on("click", function () {deletePres($(this).closest("tr"));});
  $("#cont >  div > .content > fieldset > .legend3 > .btn.min.categorie-remove > a").each(function(){
    $(this).on("click",function () {deleteCat($(this).closest("fieldset"));});
  });
  $("#cont >  div > .btn-last > .btn > a").on("click", function(){createCat()});
  $("#cont > div > .content > fieldset > .btn-out > .btn.min.categorie-add > a").on("click",function(){createDet($(this).closest("fieldset").attr("id"))});
  $("body > #cont >  div > .content > fieldset > .heart > .title > .operation-remove > .btn.operation > a").on("click", function(){deleteDet($(this).closest(".heart"))});
  $("#cont > div > .top > .first-line > div.archiver-fac > a").on("click", function () {archiverFact()});
  $("#cont > div > .top > .first-line > div.facture-fae > a").on("click", function () {Factfae()}); 
  $("#cont > div > .top > .first-line > div.envoyer-valid > a").on("click", function () {toValidate()});
  $("#cont > div > .top > .first-line > .checkbox > .data > .list > .option > a").on("click", function(){updateMission($(this))}); 
  $("#cont > div > .top > .first-line > div > div > .year > a").on("click", function(){updateDate($(this))});
});
/**
* Sends ajax request to the server to update date
*/
function updateDate(button)
{
  let input = document.querySelector("#date");
  input.showPicker();
  $(input).on("change", function(){
    $.ajax({
      type: "POST"
      ,url: `./affiche_fact.php?d=${dossier}&f=${fact}`
      ,data: { set_date: $(input).val()}
      , beforeSend: function() { loaderShow(); }
      , complete: function() { loaderHide(); }
      , success: function(data)
      {
          try { var result = JSON.parse(data); } catch(error) { popError(); return; }
          if(result["code"] == 200) {
            $(button).find(".txt").text(result["date"]);
            return;
          }
          else popError(result["txt"], result["btn"]);
      }
    });
  });
}
/**
* Sends ajax request to the server to update mission
*/
function updateMission(option)
{
  let code = $(option).closest(".option").attr("code");
  $.ajax({
    type: "POST"
    ,url: `./affiche_fact.php?d=${dossier}&f=${fact}`
    ,data: { set_mission: code}
    , beforeSend: function() { loaderShow(); }
    , complete: function() { loaderHide(); }
    , success: function(data)
    {
        try { var result = JSON.parse(data); } catch(error) { popError(); return; }
        if(result["code"] == 200) return;
        else popError(result["txt"], result["btn"]);
    }
  });
}
  /**
   * Sends ajax request to the server to handle fact delete
   */
function onDeleteFact() 
{
  $("#cont > div > .top > .first-line > div.supprimer-fac > a").on("click", function(){
    
    let code_dossier = new URLSearchParams(window.location.search).get("d");
    let fact = new URLSearchParams(window.location.search).get("f");
    console.log(code_dossier, fact);
    $.ajax({
      url: `affiche_fact.php?d=${code_dossier}&f=${fact}`,
      type: "POST",
      data: {"delete_fact": ""}
      , beforeSend: function() { loaderShow(); }
      , complete: function() { loaderHide(); }
      , success: function(data)
      {
          try { var result = JSON.parse(data); } catch(error) { popError(); return; }
          if(result["code"] == 200) { window.location.href = "resultat.php?d="+code_dossier; return; }
          popError(result["txt"], result["btn"]);
      }
    })
  });
}
/**
 * setup selects functionalities
 */
function initSelect()
{
  $("div.select > .data > a").on("click", function () {formSelectInit($(this).closest(".select"));});
  
  $("div.select > .data > .list > .input > .data > input").on("input", function () {formSelectFilter($(this).closest(".select"));});

  $( "div.select > .data > .list > .option > a").each(function() {
    $(this).on("click", function () {formSelectOption($(this).closest(".data"));});
  })
}
function initCheckbox()
{
  $("body > #cont >  div > .top > .first-line > .checkbox.bool > .data > .list > .option > a").each(function(){
    $(this).on("click", function () {formCheckboxUnique($(this).closest(".option"));});
  });
}
/**
 * sends ajax request with the id of prest row to be deleted
 */
function deletePres(row) {
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { delete_prest: row.attr("id") },
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.success == 200)row.remove();
        else popError("An error occurred while processing your request.");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus, errorThrown);
      popError("An error occurred while processing your request."); // Display a user-friendly error message
    },
  });
}
/**
 * sends ajax request with the id of detail field to be deleted 
 */
function deleteCat(field) {
    $.ajax({
      type: "POST",
      url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
      data: { delete_cat: field.attr("id") },
      dataType: "json",
      beforeSend: function () {
        loaderShow();
      },
      complete: function () {
        loaderHide();
      },
      success: function (data) {
          if(data.code == 200)field.remove();
          else popError("An error occurred while processing your request.");
      },
    });
  }
/**
* Send ajax request to server to create a new cat field 
*/
function createCat()
{
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { create_cat: "" },
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.code == 200) location.reload();
        else popError("An error occurred while processing your request.");
    }
  });
}
/**
* Send ajax request to server to create a new cat detail table 
*/
function createDet(id)
{
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { create_det: "", cat_id:id},
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.code == 200) location.reload();
        else popError("An error occurred while processing your request.");
    }
  });
}
/**
* Send ajax request to server to delete a cat detail table 
*/
function deleteDet(table)
{
  let id = $(table).find("table").attr("id");
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { delete_det: "", det_id:id},
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.code == 200) table.remove();
        else popError("An error occurred while processing your request.");
    }
  });
}
/**
 * Send ajax request to server to archive fact
 */
function archiverFact()
{
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { archiverFact: ""},
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.code == 200) window.location.href ="./resultat.php?d="+dossier;
        else popError("An error occurred while processing your request.");
    }
  });
}
/**
 * Send ajax request to server to set fae
 */
function Factfae()
{
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { fact_fae: ""},
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
      // window.location.href ="./resultat.php?d="+dossier;
        if(data.code == 200) ;
        else popError("An error occurred while processing your request.");
    }
  });
}
/*
* Send ajax server request to send fact to validation
*/
function toValidate()
{
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { validate: ""},
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.code == 200){
          window.location.href ="./fact_a_valider.php?d="+dossier;
        } else {
          popError("An error occurred while processing your request.");
        }
    }
  })
}
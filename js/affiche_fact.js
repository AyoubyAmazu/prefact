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
  $("#cont > div > .content > fieldset > .btn-out > .btn.min.categorie-add > a").on("click",function(){createCat()});
});
  /**
   * Sends ajax request to the server to handle fact delete
   */
function onDeleteFact() {
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
        if(data.code == 200)$("#cont > div > content").append(data.html);
        else popError("An error occurred while processing your request.");
    }
  });
}
/**
* Send ajax request to server to create a new cat detail table 
*/
function createDet()
{
  $.ajax({
    type: "POST",
    url: `./affiche_fact.php?d=${dossier}&f=${fact}`,
    data: { create_det: "" },
    dataType: "json",
    beforeSend: function () {
      loaderShow();
    },
    complete: function () {
      loaderHide();
    },
    success: function (data) {
        if(data.code == 200)$("#cont > div > .content > fieldset > .legend3 ").after(data.html);
        else popError("An error occurred while processing your request.");
    }
  });
}
// // the start of the new table script //

// $(document).on(
//   "click",
//   "body > ,
//   function () {
//     var clonedTitleSection = $(this)
//       .closest("fieldset")
//       .find(".heart > .title.hide");
//     var newTableStructure = $(this)
//       .closest("fieldset")
//       .find(".heart > table.show");

//     if (clonedTitleSection.length > 0 && newTableStructure.length > 0) {
//       var clonedTitleSectionCopy = clonedTitleSection.clone();
//       var newTableStructureCopy = newTableStructure.clone();
//       newTableStructureCopy.removeClass("show");
//       clonedTitleSectionCopy.removeClass("hide");

//       var destinationLocation = $(this).closest("fieldset").find(".heart");
//       destinationLocation.append(clonedTitleSectionCopy);
//       destinationLocation.append(newTableStructureCopy);
//     }
//   }
// );

// // the end of the new table script //

// // the start of removing a specific table //

// $(document).on(
//   "click",
//   "body > #cont >  div > .content > fieldset > .heart > .title > .operation-remove > .btn.operation > a",
//   function (event) {
//     event.preventDefault();
//     console.log("remove button got clicked");

//     var title = $(this).closest(".title");
//     var table = $(this).closest(".title").next("table");

//     title.remove();
//     table.remove();
//   }
// );
// // the end of removing a specific table //

// // the start of the textarea script //
// function autoResize(textarea) {
//   textarea.style.height = "18px";
//   textarea.style.height = textarea.scrollHeight + "px";
// }

// $(document).on(
//   "input",
//   "body > #cont >  div > .content > fieldset > .heart > table > tbody > tr > td > .textarea.textarea-container > .data > textarea",
//   function () {
//     autoResize(this);
//   }
// );
// // the end of the textarea script //

// // the start of fill in the comment //
// $(document).on(
//   "click",
//   "body > #cont >  div > .content > fieldset > .heart > table > tbody > tr > td > .btn.min.operation > a",
//   function () {
//     var $container = $(this).closest(
//       "body > #cont >  div > .content > fieldset > .heart > table > tbody > tr"
//     );
//     var content = $container.find(".titre").text();

//     var textarea = $(this)
//       .closest("table")
//       .find(
//         "tbody > tr > td > .textarea.textarea-container > .data > textarea"
//       );

//     if (textarea.val() === "") {
//       textarea.val(content);
//     } else {
//       textarea.val(textarea.val() + "\n" + "\n" + content);
//     }
//     autoResize(textarea[0]);

//     $("body").on("input propertychange", "textarea", function () {
//       autoResize(this);
//     });
//   }
// );
// // the end of fill in the comment //

// $(document).on(
//   "click",
//   "body #cont >  div .content fieldset .heart table tbody tr th .btn.min.action a",
//   function () {
//     var $table = $(this).closest("table");
//     var rows = $table.find("tbody > tr").get();
//     rows.sort(function (a, b) {
//       var keyA = new Date($(a).children("td").eq(1).text());
//       var keyB = new Date($(b).children("td").eq(1).text());

//       if (keyA < keyB) return 1;
//       if (keyA > keyB) return -1;
//       return 0;
//     });

//     $.each(rows, function (index, row) {
//       $table.find("tbody").append(row);
//     });
//   }
// );
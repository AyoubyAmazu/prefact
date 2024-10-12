$(document).ready(function () {
  // $(".reset").on("click", resetFilter );
  $(".save").on("click", fetchData);
  $(".checkbox .data .list").children().each(function (option) {$(this).on("click", function () { formCheckboxUnique($(this))});});
});
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

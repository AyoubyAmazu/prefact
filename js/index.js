$(document).ready(function () {
  $('#buttons > div >.parametres').on("click", function () 
  {
    popPrompt( "Colonnes a afficher","html", 'string', 'Parametres du tablue');
  });
  $(".reset").on("click", resetFilter );
  //data fetching
  $(".save").on("click", fetchData);
  // adding radio functionality
  $(".checkbox .data .list").children().each(function (option) {
    $(this).on("click", function (param) { 
      formCheckboxUnique($(this))
     });
  })
});
function resetFilter() {
  
}
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
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus, errorThrown);
      popError("An error occurred while processing your request."); // Display a user-friendly error message
    },
  });
}

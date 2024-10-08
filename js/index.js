$(document).ready(function () {
  $(".save").on("click", function () {
    $.ajax({
      type: "POST",
      url: "./index_search.php",
      data: {
        search: "",
        annee: $(".annee .data input").val(),
        soc: $(".soc").attr('code'),
        grp: $(".grp").attr('code'),
        txt: $(".txt .data a .main .txt input").val(),
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
          $("#affichData").html(affichData(data.data));
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
  });
});

function affichData(result) {
  console.log(result);

  let html = ""; // Initialize html as an empty string
  result.forEach((row) => {
    html += `
      <tr>
        <td><span>${row["adr"]}</span><br><span>${row["temps_dur"]}</span><br><span>${row["grp"]}</span></td>
        <td><span>${row["adr"]}</span><br><span>${row["temps_dur"]}</span><br><span>${row["grp"]}</span></td>
        <td><span>${row["adr"]}</span><br><span>${row["temps_dur"]}</span><br><span>${row["grp"]}</span></td>
        <td><span>${row["adr"]}</span><br><span>${row["temps_dur"]}</span><br><span>${row["grp"]}</span></td>
        <td><span>${row["adr"]}</span><br><span>${row["temps_dur"]}</span><br><span>${row["grp"]}</span></td>
        <td><span>${row["adr"]}</span><br><span>${row["temps_dur"]}</span><br><span>${row["grp"]}</span></td>
      </tr>`;
  });
  return html;
}

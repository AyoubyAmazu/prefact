$(document).ready(function () {
  $(".save").on("click", function () {
    $.ajax({
      type: "POST",
      url: "./index_search.php",
      data: {
        search: "",
        annee: $(".annee .data input").val(),
        soc: $(".soc .data a .main .txt").html(),
        grp: $(".grp .data a .main .txt").html(),
        txt: $(".txt .data a .main .txt input").val(),
        naf: $(".naf .data a .main .txt").html(),
        segment: $(".segment .data a .main .txt").html(),
        resp: $(".resp .data a .main .txt").html(),
        rd: $(".rd .data a .main .txt").html(),
        re: $(".re .data a .main .txt").html(),
        rc: $(".rc .data a .main .txt").html(),
        ra: $(".ra .data a .main .txt").html(),
        res: $(".res .data a .main .txt").html(),
        rs: $(".rs .data a .main .txt").html(),
        rj: $(".rj .data a .main .txt").html(),
        rfp: $(".rfp .data a .main .txt").html(),
        tgr: $(".tgr .data a .main .txt").html(),
        tgra: $(".tgra .data a .main .txt").html(),
      },
      beforeSend: function () {
        console.log($(".soc .data a .main .txt").html());
        loaderShow();
      },
      complete: function () {
        loaderHide();
      },
      success: function (data) {
        console.log(data.data);
        
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

$(document).ready(function () {
  $(".save").on("click", function () {
    $.ajax({
      type: "POST",
      url: "./index_search.php", // The URL of your PHP script
      data: { search: "test" }, // Data to send
      dataType: "json", // Expecting a JSON response
      beforeSend: function () {
        loaderShow(); // Optional: Show a loading indicator
      },
      complete: function () {
        loaderHide(); // Optional: Hide the loading indicator
      },
      success: function (data) {
        if (data && data.code === 200) {
          console.log(JSON.parse(data.data)); // Handle the success response
        } else {
          console.error("Unexpected response:", data);
          popError(data.err); // Optional: Handle unexpected response
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error: " + textStatus, errorThrown); // Handle error
        // popError(); // Optional: Handle AJAX error
      },
    });
  });
});

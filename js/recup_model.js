let dossier = new URLSearchParams(window.location.search).get("d");
$(document).ready(function(){
	$("#cont > div > div.all > div.left > div.btn.retour > a").on("click", function(){window.history.back()});
	rigesterOnClicks();
});

function getPage(isNext, item) {
	if($(item).parent().hasClass("readonly"))return;
	let page = Number($("#cont > div > div.content > div.text > div.pagination > div.txt.page").text());
	let data = {get_page: page};
	if(isNext == false) data["back"] = "true";
	$.ajax({
      type: "POST"
      ,url: `./recup_model.php?d=${dossier}`
      ,data: data
      , beforeSend: function() {  }
      , complete: function() {  }
      , success: function(data)
      {
          try { var result = JSON.parse(data); } catch(error) { popError(); return; }
          if(result["code"] == 200) {
          	$("#cont > div > div.content").html(result.html);
          	rigesterOnClicks();
          	return;
          }
          else popError(result["txt"], result["btn"]);
      }
    });
}

function rigesterOnClicks(){

	$("#cont > div > div.content > div.text > div.pagination > div.btn.min.next > a").on("click", function(){getPage(true, $(this))});
	$("#cont > div > div.content > div.text > div.pagination > div.btn.min.prev > a").on("click", function(){getPage(false, $(this))});
}
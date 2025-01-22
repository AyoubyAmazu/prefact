let dossier = new URLSearchParams(window.location.search).get("d");
$(document).ready(function () {


    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .btn > a ").off("click").on("click", function (event) {openPopupMenu($(event.target).parents(".verticalB"));});
    $("body > #cont > div > .centre > table > tbody > tr > td >  .checkbox  > .verr > .data > .list > .option > a").off("click").on("click", function (event) {CheckboxUnique($(event.target).parents(".option"));});
    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list > .btn.commentaire > a").on("click", function () {displayCommentPopup($(this).closest(".list"));});
    $(window).on("click", function (event) {if (!$(event.target).closest(".verticalB").length) {$("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list ").addClass("off");}});
    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list > .close").click(function(){anuller_fact($(this));});
    search();
    $("#cont > div > div.centre > table > tbody > tr.row > td> div.date > div.dp > div.data > div.cal > a").on("click", function(){
        updateFactDate($(this).closest(".data"));
    });
});
/*
* Send ajax request to the server to update fact date 
*/
function updateFactDate(button)
{
    let fact = $(button).closest(".row").attr("id");
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
            $(button).find("input").val(result["date"]);
            return;
          }
          else popError(result["txt"], result["btn"]);
      }
    });
  });
}
/*
* Gets Comment popup html 
*/
function displayCommentPopup(list)
{
    let comment = $(list).find(".comment").val()
    $.ajax({
        url: "validation_comment.php"
        ,type:"POST"
        ,data:{comment: comment}
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); }
             catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayRappelListAdapt(comment, list.attr("factId")); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}
function displayRappelListAdapt(comment, idFact) {

    $("body > .popup.displayRappelList > div > .op > .btn.cancel > a").off("click").on("click", function (event) {
        popDown($(event.target).parents("div"));
    });
    $("body > .popup.displayRappelList > div > .op > .btn.save > a").on("click", function () {
        let mComment = $("body > .popup.displayRappelList > div > .contenue > .comment > .data > input").val();
        if(comment == mComment) {
            popDown($(event.target).parents("div"));
            return;
        }
        $.ajax({
            url: "validation_comment.php"
            ,type:"POST"
            ,data:{saveComment: mComment, idFact: idFact}
            , beforeSend: function() { loaderShow(); }
            , complete: function() { loaderHide(); }
            , success: function(data){
                try { var result = JSON.parse(data); }
                catch(error) { popError(); return; }
                if(result["code"] == 200) { popDown($(event.target).parents("div"));; return; }
                popError(result["txt"], result["btn"]);
            }
        });
    });
}
function openPopupMenu(div) {
    $(".list").addClass("off");
    $(div).children(".list").removeClass("off");
}


function CheckboxUnique(div) {
    formCheckboxExec(div);
}

function anuller_fact(div){
    id = div.parents().attr("factId");
    $.ajax({
        url: "fact_a_valider.php"
        ,type:"POST"
        ,data:{facture_id: id}
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data){
            try { var result = JSON.parse(data); }
            catch(error) { popError(); return; }
            if(result["code"] == 200) { popDown($(event.target).parents("div")); location.reload(); // Refresh the page
                return; }
            popError(result["txt"], result["btn"]);
        }
    });

}
function search() {
    $("body > #cont > div > .centre > table > tbody > .comm > td > div > div > .data > input").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody .row").filter(function() {
            // $(this).toggle($(this).find(".nom_dossier").text().toLowerCase().indexOf(value) > -1);
            var nomDossier = $(this).find(".nom_dossier").text().toLowerCase();
            var codeDossier = $(this).find(".code_dossier").text().toLowerCase();
            $(this).toggle(nomDossier.indexOf(value) > -1 || codeDossier.indexOf(value) > -1);
        });
    });
};

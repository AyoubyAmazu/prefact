$(document).ready(function () {


    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .btn > a ").off("click").on("click", function (event) {openPopupMenu($(event.target).parents(".verticalB"));});
    $("body > #cont > div > .centre > table > tbody > tr > td >  .checkbox  > .verr > .data > .list > .option > a").off("click").on("click", function (event) {CheckboxUnique($(event.target).parents(".option"));});
    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list > .btn.commentaire > a").on("click", function () {displayCommentPopup($(this).closest(".list"));});
    $(window).on("click", function (event) {if (!$(event.target).closest(".verticalB").length) {$("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list ").addClass("off");}});
});
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
        console.log(comment == mComment)
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



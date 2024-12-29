



function openPopupMenu(div) {
    
    $(".list").addClass("off");
    $(div).children(".list").removeClass("off");
    
    
}


function CheckboxUnique(div)
{
    formCheckboxExec(div);
   
    
}
function displayRappelListAdapt()
{
    
    $("body > .popup.displayRappelList > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents("div")); });
    $("body > .popup.displayRappelList > div > .op > .btn.save > a").off("click").on("click", function() { displayRappel(); });
}




function displayRappelList()
{
    $.ajax({
        url: "index_AddComment.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); displayRappelListAdapt(); return; }
            popError(result["txt"], result["btn"]);
        }
    });
}


$(document).ready(function () {
   

    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .btn > a ").off("click").on("click", function(event) {openPopupMenu($(event.target).parents(".verticalB")); });
    $("body > #cont > div > .centre > table > tbody > tr > td >  .checkbox  > .verr > .data > .list > .option > a").off("click").on("click", function(event) { CheckboxUnique($(event.target).parents(".option")); });
    $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list > .btn.commentaire > a").off("click").on("click", function(event) { displayRappelList(); });
    $(window).on("click", function(event) { if (!$(event.target).closest(".verticalB").length) { $("body > #cont > div > .centre > table > tbody > tr > td > .verticalB > .list ").addClass("off"); }});

});
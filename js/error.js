function det()
{
    $("#cont > div > .det").toggleClass("off");
    if($("#cont > div > .det").hasClass("off")) $("#cont > div > .det > .btn.toggle > a").attr("title", "Afficher plus d'information").children(".ico").children("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    else $("#cont > div > .det > .btn.toggle > a").attr("title", "Masquer les informations").children(".ico").children("i").addClass("fa-angle-up").removeClass("fa-angle-down");
}

$(document).ready(function()
{
    $("#cont > div > .det > .btn.toggle > a").off("click").on("click", function() { det() });
});
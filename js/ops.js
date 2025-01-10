const APPurl = window.location.origin + "/";

function loaderShow() { $(":focus").blur(); $("body").css("overflow", "hidden").css("border-right", "10px solid var(--dark2)"); $(".popup").css("overflow", "hidden").css("border-right", "10px solid var(--dark2)"); $("#loader").show(); }
function loaderHide() { $("#loader").hide(); $(".popup").last().css("border-right", "none").css("overflow-y", "scroll"); if($(".popup").length == 0) $("body").css("border-right", "none").css("overflow-y", "scroll");  }
function popUp(html) { $(":focus").blur(); $("body").css("overflow", "hidden").css("border-right", "10px solid var(--dark2)"); $(".popup").css("overflow", "hidden").css("border-right", "10px solid var(--dark2)"); $("body").append(html); }
function popDown(div) { $(div).remove(); $(".popup").last().css("border-right", "none").css("overflow-y", "scroll"); if($(".popup").length == 0) $("body").css("border-right", "none").css("overflow-y", "scroll");  }

function popAlert(txt, btn)
{
    var html = "<div class='popup' id='alert'><div>";
        if(txt != undefined && txt != null && txt != "") html += "<div class='txt'>" + txt + "</div>";
        if(btn !== false) html += "<div class='op'>" + formBtn({ key: "cancel", txt: ((btn === true)? "Ré-essayer" : "Fermer"), href: ((btn == undefined || btn == null)? "" : btn) }) + "</div>";
    html += "</div></div>";
    popUp(html);
    if(btn !== false && (btn == undefined || btn == null || btn == "") && btn !== true) $(".popup#alert > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")) });
}

function popError(txt, btn, det)
{
    var html = "<div class='popup' id='error'><div>";
        html += "<div class='txt'>" + ((txt == undefined || txt == null || txt == "")? "Une erreur est survenue" : txt) + "</div>";
        if(btn !== false) html += "<div class='op'>" + formBtn({ key: "cancel", txt: ((btn === true)? "Ré-essayer" : "Fermer"), href: ((btn == undefined || btn == null)? "" : btn) }) + "</div>";
        if(det != undefined && det != null && det != "") html += "<div class='det off'>" + formBtn({ key: "toggle", ico: "angle-down", title: "Afficher plus d'information" }) + "<div class='txt'>" + det + "</div></div>";
    html += "</div></div>";
    popUp(html);
    if(btn !== false && (btn == undefined || btn == null || btn == "") && btn !== true) $(".popup#error > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")) });
    if(det != undefined && det != null && det != "") $(".popup#error > div > .det > .btn.toggle > a").off("click").on("click", function(event) { popErrorDet($(event.target).parents(".popup")) });
}

function popErrorDet(div)
{
    $(div).children("div").children(".det").toggleClass("off");
    if($(div).children("div").children(".det").hasClass("off")) $(div).children("div").children(".det").children(".btn.toggle").children("a").attr("title", "Afficher plus d'information").children(".ico").children("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    else $(div).children("div").children(".det").children(".btn.toggle").children("a").attr("title", "Masquer les informations").children(".ico").children("i").addClass("fa-angle-up").removeClass("fa-angle-down");
}

function popPrompt(txt, btn, data, label)
{
    var html = "<div class='popup' id='prompt'" + ((data == undefined || data == null || data == "")? "" : (" data='" + data + "'")) + "><div>";
        html += "<div class='label'>" + ((label == undefined || label == null || label == "")? "Attention" : label) + "</div>";
        if(txt != undefined && txt != null && txt != "") html += "<div class='txt'>" + txt + "</div>";
        html += "<div class='op'>";
            html += formBtn({ key: "cancel", txt: "Non" });
            if(btn !== false && btn != undefined && btn != null && btn != "") html += formBtn({ key: "save", txt: "Oui", href: ((btn === true)? true : "") });
        html += "</div>";
    html += "</div></div>";
    popUp(html);
    $(".popup#prompt > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")) });
    if(btn !== false && btn != undefined && btn != null && btn != "" && btn !== true) $(".popup#prompt > div > .op > .btn.save > a").off("click").on("click", function(event) { window[btn]($(event.target).parents(".popup")) });
}

function popHelp()
{
    $.ajax({
        url: APPurl + "help.php"
        , beforeSend: function() { loaderShow(); }
        , complete: function() { loaderHide(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); return; }
            if(result["code"] == 200) { popUp(result["html"]); $(".popup#help > div > .op > .btn.cancel > a").off("click").on("click", function(event) { popDown($(event.target).parents(".popup")) }); return; }
            popError(result["txt"], result["btn"], result["det"]);
        }
    });
}

function filterDel()
{
    $("#filter > div > .annee > .data > input").val(new Date().getFullYear());
    var obj = { filter: { year: -1, start: -1, end: -1, resp: -1, soc: "", grp: "", naf: "", segment: "", keyword: "" } };
    $("#filter > div > .select").each(function()
    {
        // console.log($(this));

        var k = $(this).attr("resp"); if(k == undefined || k == null || k == "") return;
        obj["filter"][k] = "";
    });

    cookieSave(obj, true);
}

function titleToggle(div)
{
    var code = $(div).attr("code"); if(code == undefined || code == null || code == "") return;
    $("#title > div > .main > .det[code='" + code + "']").toggleClass("off");
    if($("#title > div > .main > .det[code='" + code + "']").hasClass("off")) $(div).removeClass("on").children("a").children(".ico").children("i").addClass("fa-angle-down").removeClass("fa-angle-up");
    else $(div).addClass("on").children("a").children(".ico").children("i").removeClass("fa-angle-down").addClass("fa-angle-up");
    var obj = { title: {} }; obj["title"][code] = !$("#title > div > .main > .det[code='" + code + "']").hasClass("off");
    cookieSave(obj, false);
}

function cookieSave(obj, url)
{
    if(obj == undefined || obj == null || obj == "") return;
    if(url == undefined || url == null || url == "") url = false;

    $.ajax({
        url: APPurl + "cookie.php", method: "post", data: { obj: JSON.stringify(obj) }
        , beforeSend: function() { if(url !== false) loaderShow(); }
        , success: function(data)
        {
            try { var result = JSON.parse(data); } catch(error) { popError(); if(url !== false) loaderHide(); return; }
            if(result["code"] == 200) { if(url === true) location.reload(); else if(url !== false) window.open(url, "_self"); return; }
            popError(result["txt"], result["btn"], result["det"]); if(url !== false) loaderHide();
        }
    });
}

$(document).ready(function()
{
    $("#header > div > .op > .btn.help > a").off("click").on("click", function() { popHelp(); });
    $("#filter > div > .input > .data > .btn.prev > a").off("click").on("click", function(event) { formInputPrev($(event.target).parents(".input")); });
    $("#filter > div > .input > .data > .btn.next > a").off("click").on("click", function(event) { formInputNext($(event.target).parents(".input")); });
    $("#filter > div > .select > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("#filter > div > .select > .data > .list > .input > .data > input").off("input").on("input", function(event) { formSelectFilter($(event.target).parents(".select")); });
    $("#filter > div > .select > .data > .list > .option > a").off("click").on("click", function(event) { formSelectOption($(event.target).parents(".option")); });
    $("#filter > div > .op > .btn.reset > a").off("click").on("click", function() { filterDel(); });
    $("#cont > div >.op > .side > .sortCol > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("#title > div > .main > .adr > .select.grp > .data > a").off("click").on("click", function(event) { formSelectInit($(event.target).parents(".select")); });
    $("#title > div > .main > .adr > .select.grp > .data > input").off("input").on("input", function(event) { formSelectFilter($(event.target).parents(".select")); });
    $("#title > div > .main > .op > .btn.toggle > a").off("click").on("click", function(event) { titleToggle($(event.target).parents(".btn")); });
    $(".btn.min.refresh").on("click", function () {location.reload()})
});

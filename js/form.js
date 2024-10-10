function formBtn(opts)
{
    if(opts["key"] == undefined || opts["key"] == null) opts["key"] = ""; opts["key"] = opts["key"].toString().replace(/\'/g, "&apos;");
    if(opts["align"] == undefined || opts["align"] == null) opts["align"] = ""; if($.inArray(opts["align"], [ "l", "r" ]) == -1) opts["align"] = "";
    if(opts["type"] == undefined || opts["type"] == null) opts["type"] = "solid"; if($.inArray(opts["type"], [ "solid", "regular" ]) == -1) opts["type"] = "solid";
    if(opts["ico"] == undefined || opts["ico"] == null) opts["ico"] = ""; opts["ico"] = opts["ico"].toString().replace(/\'/g, "&apos;");
    if(opts["txt"] == undefined || opts["txt"] == null) opts["txt"] = ""; opts["txt"] = opts["txt"].toString().replace(/\'/g, "&apos;");
    if(opts["title"] == undefined || opts["title"] == null) opts["title"] = ""; if(opts["title"] == "") opts["title"] = opts["txt"]; opts["title"] = opts["title"].toString().replace(/\'/g, "&apos;");
    if(opts["href"] == undefined || opts["href"] == null) opts["href"] = ""; if(opts["href"] !== true) opts["href"] = opts["href"].toString().replace(/\'/g, "&apos;");
    if(opts["target"] == undefined || opts["target"] == null) opts["target"] = ""; if($.inArray(opts["target"], [ "_self", "_blank" ]) == -1) opts["target"] = "";
    if(opts["readonly"] == undefined || opts["readonly"] == null) opts["readonly"] = false;
    if(opts["off"] == undefined || opts["off"] == null) opts["off"] = false;
    if(opts["extra"] == undefined || opts["extra"] == null) opts["extra"] = new Array();
    if(opts["attr"] == undefined || opts["attr"] == null) opts["attr"] = new Array();

    var div = new Array();
    div.push("btn");
    if(opts["ico"] == "" || opts["txt"] == "") div.push("min");
    if(opts["align"] != "") div.push(opts["align"]);
    if(opts["key"] != "") div.push(opts["key"]);
    if(opts["extra"].length != 0) div.push(...opts["extra"]);
    if(opts["readonly"]) div.push("readonly");
    if(opts["off"]) div.push("off");

    var attr = new Array();
    attr.push("class='" + div.join(" ") + "'");
    if(opts["attr"].length != 0) attr.push(...opts["attr"]);

    var data = new Array();
    if(opts["title"] != "") data.push("title='" + opts["title"] + "'");
    if(opts["href"] != "") data.push("href='" + ((opts["href"] === true)? "" : opts["href"]) + "'");
    if(opts["target"] != "") data.push("target='" + opts["target"] + "'");

    var html = "<div " + attr.join(" ") + ">";
        html += "<a" + ((data.length == 0)? "" : (" " + data.join(" "))) + ">";
            if(opts["ico"] != "") html += "<div class='ico'><i class='fa-" + opts["type"] + " fa-" + opts["ico"] + "'></i></div>";
            if(opts["txt"] != "") html += "<div class='txt'>" + opts["txt"] + "</div>";
        html += "</a>";
    html += "</div>";

    return html;
}
// added by aymen
function formInputPrev(div)
{
    var step = $(div).children(".data").children("input").attr("step"); if(step == undefined || step == null || step == "") step = 1; step = Number(step); if(isNaN(step)) step = 1;
    var min = $(div).children(".data").children("input").attr("min"); if(min == undefined || min == null) min = ""; if(min != "") { min = Number(min); if(isNaN(min)) min = ""; }
    var max = $(div).children(".data").children("input").attr("max"); if(max == undefined || max == null) max = ""; if(max != "") { max = Number(max); if(isNaN(max)) max = ""; }
    var val = $.trim($(div).children(".data").children("input").val()); if(val == "") { $(div).children(".data").children("input").val(((min == "")? (-1 * step) : "")); return; }
    val = Number(val); if(isNaN(val)) { $(div).children(".data").children("input").val(""); return; }
    if(min != "" && val - step < min) { $(div).children(".data").children("input").val(""); return; }
    if(max != "" && val - step > max) { $(div).children(".data").children("input").val(max); return; }
    $(div).children(".data").children("input").val(val - step);
}

function formInputNext(div)
{
    var step = $(div).children(".data").children("input").attr("step"); if(step == undefined || step == null || step == "") step = 1; step = Number(step); if(isNaN(step)) step = 1;
    var min = $(div).children(".data").children("input").attr("min"); if(min == undefined || min == null) min = ""; if(min != "") { min = Number(min); if(isNaN(min)) min = ""; }
    var max = $(div).children(".data").children("input").attr("max"); if(max == undefined || max == null) max = ""; if(max != "") { max = Number(max); if(isNaN(max)) max = ""; }
    var val = $.trim($(div).children(".data").children("input").val()); if(val == "") { $(div).children(".data").children("input").val(((min == "")? "0" : min)); return; }
    val = Number(val); if(isNaN(val)) { $(div).children(".data").children("input").val(""); return; }
    if(min != "" && val + step < min) { $(div).children(".data").children("input").val(((min == "")? "0" : min)); return; }
    if(max != "" && val + step > max) { $(div).children(".data").children("input").val(max); return; }
    $(div).children(".data").children("input").val(val + step);
}

function formInputPwd(div)
{
    if($(div).children(".data").children("input").attr("type") == "password") $(div).children(".data").children("input").attr("type", "text").siblings(".btn.toggle").children("a").attr("title", "Masquer").children(".ico").children("i").addClass("fa-eye-slash").removeClass("fa-eye");
    else $(div).children(".data").children("input").attr("type", "password").siblings(".btn.toggle").children("a").attr("title", "Afficher").children(".ico").children("i").addClass("fa-eye").removeClass("fa-eye-slash");
}

function formTextareaInput(div, min)
{
    $(div).children(".data").children("textarea").css("height", ((min == undefined || min == null || min == "" || isNaN(Number(min)))? 18 : Number(min)) + "px");
    $(div).children(".data").children("textarea").css("height", ($(div).children(".data").children("textarea").prop("scrollHeight") - 10) + "px");
}
/**
 * shows the list of a select
 * @param {HTMLElement} div
 */
function formSelectShow(div) { $(((div == undefined || div == null || div == "")? ".select" : div)).addClass("on").children(".data").children("a").children(".ico").children("i").addClass("fa-angle-up").removeClass("fa-angle-down"); }

/**
 * hides the list of a select
 * @param {HTMLElement} div
 */
function formSelectHide(div) { $(((div == undefined || div == null || div == "")? ".select" : div)).removeClass("on").children(".data").children("a").children(".ico").children("i").addClass("fa-angle-down").removeClass("fa-angle-up"); }
/**
 * inialise the init status of a select
 * @param {HTMLElement} div
 */
function formSelectInit(div)
{
    if($(div).hasClass("on")) { formSelectHide(div); return; }
    formSelectHide();
    formSelectShow(div);
    $(div).children(".data").children(".list").children(".option").not(".off").show();
    $(div).children(".data").children(".list").children(".input").children(".data").children("input").val("").focus();
    $(div).children(".data").children(".list").scrollTop(0);
}

function formSelectFilter(div)
{
    var keyword = $.trim($(div).children(".data").children(".list").children(".input").children(".data").children("input").val()).normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    if(keyword == "") { $(div).children(".data").children(".list").children(".option").not(".off").show(); return; }
    $(div).children(".data").children(".list").children(".option").not(".off").each(function()
    {
        $(this).hide();
        if($.trim($(this).children("a").children(".txt").text()).normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().includes(keyword)) $(this).show();
        if($.trim($(this).children("a").children(".code").text()).normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase().startsWith(keyword)) $(this).show();
    });
}

function formSelectOption(div)
{
    var code = $(div).attr("code"); if(code == undefined || code == null) code = ""; $(div).parents(".select").attr("code", code);
    var txt = $(div).children("a").html(); if(txt == undefined || txt == null) txt = ""; $(div).parents(".select").children(".data").children("a").children(".main").html(txt);
    var title = $(div).children("a").attr("title"); if(title == undefined || title == null) title = ""; $(div).parents(".select").children(".data").children("a").attr("title", title);
    formSelectHide($(div).parents(".select"));
}

$(window).click(function(event) { if($(event.target).parents(".select").length == 0) formSelectHide(); });
/*
function formCheckboxExec(div)
{
    $(div).toggleClass("on");
    if($(div).hasClass("on")) $(div).children("a").children(".ico").children("i").addClass("fa-circle-dot").removeClass("fa-circle");
    else $(div).children("a").children(".ico").children("i").addClass("fa-circle").removeClass("fa-circle-dot");
}
*/
/**
 * works on radion buttons selects one at a time
 * @param {HTMLElement} div 
 */
function formCheckboxUnique(div)
{
    $(div).addClass("on").siblings(".option.on").removeClass("on");
    $(div).parent().children(".option").each(function()
    {
        if($(this).hasClass("on")) $(this).children("a").children(".ico").children("i").addClass("fa-circle-dot").removeClass("fa-circle");
        else $(this).children("a").children(".ico").children("i").addClass("fa-circle").removeClass("fa-circle-dot");
    });
}



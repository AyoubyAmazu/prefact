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
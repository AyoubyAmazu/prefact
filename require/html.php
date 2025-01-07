<?php
/**
 * Build html page
 * @param array $opts
 * @return string
 */
function html($opts = array())
{
    $opts = htmlOpts($opts);

    $html = "<!doctype html>";
    $html .= "<html lang='en'>";
    $html .= "<head>" . htmlHead($opts) . "</head>";
    $html .= "<body>";
    $html .= "<div id='header'>" . htmlHeader($opts) . "</div>";
     if ($opts["adr"] === "") $html .= "<div id='filter'>" . htmlFilter($opts) . "</div>";
     elseif ($opts["adr"] !== false) $html .= "<div id='title'>" . htmlTitle($opts) . "</div>";
    $html .= "<div id='cont'><div>" . $opts["cont"] . "</div></div>";
    $html .= "<div id='footer'>" . htmlFooter() . "</div>";
    $html .= "<div id='loader'>" . htmlLoader() . "</div>";
    $html .= "</body>";
    $html .= "</html>";

    return $html;
}
/**
 * Falidates and inialize parameters
 * @param array $opts
 * @return array
 */
function htmlOpts($opts = array())
{
    if (!isset($opts["cont"])) $opts["cont"] = "";
    if (!isset($opts["script"])) $opts["script"] = "";
    if (!isset($opts["title"])) $opts["title"] = "";
    if (!isset($opts["adr"])) $opts["adr"] = "";
    if (!isset($opts["css"])) $opts["css"] = true;
    if (!isset($opts["js"])) $opts["js"] = true;
    if (!isset($opts["filter"])) $opts["filter"] = array();
    if (!isset($opts["user"])) $opts["user"] = array();
    if (!isset($opts["user"]["id"])) $opts["user"]["id"] = "";
    if (!isset($opts["user"]["soc"])) $opts["user"]["soc"] = "";
    if (!isset($opts["user"]["socList"])) $opts["user"]["socList"] = array();
    if(!isset($opts["grp"])) $opts["grp"] = true;

    return $opts;
}

/**
 * Creates the head tag for html
 * @param array $opts
 * @return string
 */
function htmlHead($opts = array())
{
    $opts = htmlOpts($opts);

    $html = "<meta charset='UTF-8'>";
    $html .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
    $html .= "<meta name='description' content='" . str_replace("'", "&apos;", APPdesc) . "'>";
    $html .= "<meta name='keywords' content='" . str_replace("'", "&apos;", APPwords) . "'>";
    $html .= "<meta name='author' content='" . str_replace("'", "&apos;", APPauthor) . "'>";
    $html .= "<meta name='title' content='" . str_replace("'", "&apos;", implode(" - ", array_filter(array($opts["title"], APPname)))) . "'>";
    $html .= "<title>" . implode(" - ", array_filter(array($opts["title"], APPname))) . "</title>";
    $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/fa.css?v=" . APPversion . "'>";
    $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/fa_solid.css?v=" . APPversion . "'>";
    $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/fa_regular.css?v=" . APPversion . "'>";
    $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/style.css?v=" . APPversion . "'>";
    $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/form.css?v=" . APPversion . "'>";
    if ($opts["script"] != "" && $opts["css"]) $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/" . $opts["script"] . ".css?v=" . APPversion . "'>";
    $html .= "<script type='application/javascript' src='" . APPurl . "js/jquery.js?v=" . APPversion . "'></script>";
    $html .= "<script type='application/javascript' src='" . APPurl . "js/ops.js?v=" . APPversion . "'></script>";
    $html .= "<script type='application/javascript' src='" . APPurl . "js/form.js?v=" . APPversion . "'></script>";
    $html .= "<script type='application/javascript' src='" . APPurl . "js/str.js?v=" . APPversion . "'></script>";
    if ($opts["script"] != "" && $opts["js"]) $html .= "<script type='application/javascript' src='" . APPurl . "js/" . $opts["script"] . ".js?v=" . APPversion . "'></script>";

    return $html;
}
/**
 * Creates html of the header
 * @param array $opts
 * @return string html
 */
function htmlHeader($opts = array())
{
    $opts = htmlOpts($opts);

    $navOp = array();
    array_push($navOp, array("type" => "pre", "txt" => formBtn(array("key" => "cancel", "ico" => "xmark", "title" => "Annuler"))));
    array_push($navOp, array("type" => "post", "txt" => formBtn(array("key" => "save", "ico" => "magnifying-glass", "title" => "Rechercher"))));

    $html = "<div>";
    $html .= "<a href='" . APPurl . "'>";
    $html .= "<img src='" . APPurl . "media/fs50.png?v=" . APPversion . "'>";
    $html .= "<div>";
    $html .= "<b>" . APPname . "</b>";
    $html .= "<span>by " . APPauthor . "</span>";
    $html .= "</div>";
    $html .= "</a>";
    $html .= "<div class='op'>";
    if ($opts["adr"] != "") $html .= formInput(array("key" => "nav", "align" => "c", "placeholder" => "Rechercher un dossier...", "op" => $navOp));
    $html .= formBtn(array("key" => "help", "ico" => "question", "title" => "Aide"));
    if ($opts["user"]["id"] != "") $html .= formBtn(array("key" => "out", "ico" => "power-off", "txt" => $opts["user"]["id"], "title" => "Se déconnecter", "href" => AUTHout));
    $html .= "</div>";
    $html .= "</div>";

    return $html;
}
/**
 * Creates html of filters
 * @param array $opts array of options that contains the filter array
 * @return string html
 */
function htmlFilter($opts = array())
{
    $dataResp = json_decode(DATAresp, true);
    $opts = htmlOpts($opts);
        
    $anneeOp = array();
    array_push($anneeOp, array("type" => "pre", "txt" => formBtn(array("key" => "prev", "ico" => "angle-left", "title" => "Année précedente"))));
    array_push($anneeOp, array("type" => "post", "txt" => formBtn(array("key" => "next", "ico" => "angle-right", "title" => "Année suivante"))));

    $html = "<div>";
        $html .= formInput(array("key" => "annee", "type" => "number", "align" => "c", "label" => "Année", "value" => $opts["filter"]['annee'], "op" => $anneeOp));
        $html .= formSelect(array("key" => "soc", "label" => "Cabinet", "selected" => $opts["filter"]["soc"]["selected"], "list" => $opts["filter"]["soc"]["list"], "readonly" => (count($opts["filter"]["soc"]["list"]) <= 1)));
        $html .= formSelect(array("key" => "grp", "label" => "Groupe", "selected" => $opts["filter"]["grp"]["selected"], "list" => $opts["filter"]["grp"]["list"], "readonly" => (count($opts["filter"]["grp"]["list"]) <= 1)));
        $html .= formInput(opts: array('key' => 'txt', 'label' => 'Code/Name', "value" => $opts["filter"]["txt"]));
        $html .= formSelect(array("key" => "naf", "label" => "Code NAF", "selected" => $opts["filter"]["naf"]["selected"], "list" => $opts["filter"]["naf"]['list'], "code" => true, "readonly" => (count($opts["filter"]["naf"]['list']) <= 1)));
        $html .= formSelect(array("key" => "segment", "label" => "Segmentation", "selected" => $opts["filter"]["segment"]['selected'], "list" => $opts["filter"]["segment"]['list'], "filter" => false, "readonly" => (count($opts["filter"]["segment"]['list']) <= 1)));
        $html .= formSelect(array("key" => "resp", "label" => "Resp", "title" => "Responsable", "selected" => $opts["filter"]["resp"]["selected"], "list" => $opts["filter"]["resp"]["list"], "code" => true, "readonly" => (count($opts["filter"]["resp"]["list"]) <= 1)));
        foreach($dataResp as $v) $html .= formSelect(array("key" => $v["code"], "label" => $v["abr"], "title" => $v["txt"], "selected" => $opts["filter"][$v["code"]]["selected"], "list" => $opts["filter"][$v["code"]]["list"], "code" => true, "readonly" => (count($opts["filter"][$v["code"]]["list"]) <= 1), "extra" => array("resp")));
        $html .= "<div class='op'>";
            $html .= formBtn(array('key' => 'save', 'ico' => 'magnifying-glass', "title" => "Executer le filtre"));
            $html .= formBtn(array('key' => 'reset', 'ico' => 'rotate', "title" => "Réinitialiser le filtre"));
        $html .= "</div>";
    $html .= "</div>";
    
    return $html;
}
/**
 * initiate data of filters that will be used in @param htmlFilter
 * @param array $opts array of options that contains the filter array
 * @return string html
 */
function htmlFilterData($opts = array())
{
    $dataResp = json_decode(DATAresp, true);
    $dataSegment = json_decode(DATAsegment, true);
    $opts = htmlOpts($opts);
    $cookie = cookieInit();

    $filter = array();

    $filter["annee"] = intval($cookie["filter"]["annee"]);
    if ($filter["annee"] < 1) $filter["annee"] = intval(date("Y"));

    $filter["soc"] = array();
    $filter["soc"]["list"] = array();
    $filter["soc"]["selected"] = array();
    $socNull = array("code" => "", "txt" => "", "placeholder" => "Tous", "title" => "Tous les cabiets");
    $sql = "SELECT DISTINCT `soc`, `soc_txt` FROM `adr`";
    $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
    foreach ($result as $v) if (count($opts["user"]["socList"]) == 0 || in_array($v["soc"], $opts["user"]["socList"])) array_push($filter["soc"]["list"], array("code" => $v["soc"], "txt" => $v["soc_txt"]));
    usort($filter["soc"]["list"], function ($a, $b) {
        return (($a["txt"] < $b["txt"]) ? -1 : (($a["txt"] > $b["txt"]) ? 1 : 0));
    });
    if (count($filter["soc"]["list"]) == 0) err(array_merge($opts, array("txt" => "Accès refusé", "det" => "Aucun cabinet trouvé")));
    elseif (count($filter["soc"]["list"]) == 1) $filter["soc"]["selected"] = $filter["soc"]["list"][0];
    else {
        array_unshift($filter["soc"]["list"], $socNull);
        $k = array_search((($cookie["filter"]["soc"] == -1) ? $opts["user"]["soc"] : $cookie["filter"]["soc"]), array_column($filter["soc"]["list"], "code"));
        $filter["soc"]["selected"] = (($k === false) ? $socNull : $filter["soc"]["list"][$k]);
    }

    $filter["grp"] = array();
    $filter["grp"]["list"] = array();
    $filter["grp"]["selected"] = array();
    $grpNull = array("code" => "", "txt" => "", "placeholder" => "Tous", "title" => "Tous les groupes");
    $grpNone = array("code" => "-", "txt" => "", "placeholder" => "Aucun", "title" => "Aucun groupe");
    $sql = "SELECT DISTINCT `grp`, `grp_txt` FROM `adr`";
    $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
    foreach ($result as $v) array_push($filter["grp"]["list"], array("code" => $v["grp"], "txt" => $v["grp_txt"]));
    usort($filter["grp"]["list"], function ($a, $b) {
        return (($a["txt"] < $b["txt"]) ? -1 : (($a["txt"] > $b["txt"]) ? 1 : 0));
    });
    if (count($filter["grp"]["list"]) == 0) $filter["grp"]["selected"] = $grpNull;
    else {
        $k = array_search("", array_column($filter["grp"]["list"], "code"));
        if ($k !== false) {
            unset($filter["grp"]["list"][$k]);
            array_unshift($filter["grp"]["list"], $grpNone);
        }
        if (count($filter["grp"]["list"]) == 1) $filter["grp"]["selected"] = $filter["grp"]["list"][0];
        else {
            array_unshift($filter["grp"]["list"], $grpNull);
            $k = array_search($cookie["filter"]["grp"], array_column($filter["grp"]["list"], "code"));
            $filter["grp"]["selected"] = (($k === false) ? $grpNull : $filter["grp"]["list"][$k]);
        }
    }

    $filter["resp"] = array();
    $filter["resp"]["list"] = array();
    $filter["resp"]["selected"] = array();
    foreach($dataResp as $v)
    {
        $filter[$v["code"]] = array();
        $filter[$v["code"]]["list"] = array();
        $filter[$v["code"]]["selected"] = array();
        $respNull = array("code" => "", "txt" => "", "placeholder" => "Tous", "title" => $v["all"]);
        $respNone = array("code" => "-", "txt" => "", "placeholder" => "Aucun", "title" => "Aucun " . strtolower($v["txt"]));
        $sql = "SELECT DISTINCT `" . $v["code"] . "`, `" . $v["code"] . "_txt` FROM `adr`";
        $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
        foreach ($result as $w)
        {
            array_push($filter[$v["code"]]["list"], array("code" => $w[$v["code"]], "txt" => $w[$v["code"] . "_txt"], "title" => $v["txt"] . " (" . $v["abr"] . ") : " . $w[$v["code"] . "_txt"] . " (" . $w[$v["code"]] . ")"));
            if(!in_array($w[$v["code"]], array_column($filter["resp"]["list"], "code"))) array_push($filter["resp"]["list"], array("code" => $w[$v["code"]], "txt" => $w[$v["code"] . "_txt"], "title" => "Responsable : " . $w[$v["code"] . "_txt"] . " (" . $w[$v["code"]] . ")"));
        }
        usort($filter[$v["code"]]["list"], function ($a, $b) {
            return (($a["txt"] < $b["txt"]) ? -1 : (($a["txt"] > $b["txt"]) ? 1 : 0));
        });
        if (count($filter[$v["code"]]["list"]) == 0) $filter[$v["code"]]["selected"] = $respNull;
        else {
            $k = array_search("", array_column($filter[$v["code"]]["list"], "code"));
            if ($k !== false) {
                unset($filter[$v["code"]]["list"][$k]);
                array_unshift($filter[$v["code"]]["list"], $respNone);
            }
            if (count($filter[$v["code"]]["list"]) == 1) $filter[$v["code"]]["selected"] = $filter[$v["code"]]["list"][0];
            else {
                array_unshift($filter[$v["code"]]["list"], $respNull);
                $k = array_search($cookie["filter"][$v["code"]], array_column($filter[$v["code"]]["list"], "code"));
                $filter[$v["code"]]["selected"] = (($k === false) ? $respNull : $filter[$v["code"]]["list"][$k]);
            }
        }
    }
    usort($filter["resp"]["list"], function ($a, $b) {
        return (($a["txt"] < $b["txt"]) ? -1 : (($a["txt"] > $b["txt"]) ? 1 : 0));
    });
    if (count($filter["resp"]["list"]) == 0) $filter["resp"]["selected"] = $respNull;
    else {
        $k = array_search("", array_column($filter["resp"]["list"], "code"));
        if ($k !== false) {
            unset($filter["resp"]["list"][$k]);
            array_unshift($filter["resp"]["list"], $respNone);
        }
        if (count($filter["resp"]["list"]) == 1) $filter["resp"]["selected"] = $filter["resp"]["list"][0];
        else {
            array_unshift($filter["resp"]["list"], $respNull);
            $k = array_search($cookie["filter"]["resp"], array_column($filter["resp"]["list"], "code"));
            $filter["resp"]["selected"] = (($k === false) ? $respNull : $filter["resp"]["list"][$k]);
        }
    }
    
    $filter["naf"] = array();
    $filter["naf"]["list"] = array();
    $filter["naf"]["selected"] = array();
    $nafNull = array("code" => "", "txt" => "", "placeholder" => "Tous", "title" => "Tous les codes NAF");
    $nafNone = array("code" => "-", "txt" => "", "placeholder" => "Aucun", "title" => "Aucun code NAF");
    $sql = "SELECT DISTINCT `naf`, `naf_txt` FROM `adr`";
    $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
    foreach ($result as $v) array_push($filter["naf"]["list"], array("code" => $v["naf"], "txt" => $v["naf_txt"], "title" => "Code NAF : " . $v["naf"] . " - " . $v["naf_txt"]));
    usort($filter["naf"]["list"], function ($a, $b) {
        return (($a["txt"] < $b["txt"]) ? -1 : (($a["txt"] > $b["txt"]) ? 1 : 0));
    });
    if (count($filter["naf"]["list"]) == 0) $filter["naf"]["selected"] = $nafNull;
    else {
        $k = array_search("", array_column($filter["naf"]["list"], "code"));
        if ($k !== false) {
            unset($filter["naf"]["list"][$k]);
            array_unshift($filter["naf"]["list"], $nafNone);
        }
        if (count($filter["naf"]["list"]) == 1) $filter["naf"]["selected"] = $filter["naf"]["list"][0];
        else {
            array_unshift($filter["naf"]["list"], $nafNull);
            $k = array_search($cookie["filter"]["naf"], array_column($filter["naf"]["list"], "code"));
            $filter["naf"]["selected"] = (($k === false) ? $nafNull : $filter["naf"]["list"][$k]);
        }
    }

    $filter["segment"] = array();
    $filter["segment"]["list"] = array();
    $filter["segment"]["selected"] = array();
    $segmentNull = array("code" => "", "txt" => "", "placeholder" => "Toutes", "title" => "Toutes les segmentations");
    $segmentNone = array("code" => "-", "txt" => "", "placeholder" => "Aucune", "title" => "Aucune segmentation");
    foreach($dataSegment as $v) array_push($filter["segment"]["list"], array("code" => $v["code"], "txt" => $v["abr"], "title" => "Segmentation : " . $v["abr"] . " - " . $v["txt"], "attr" => array("color='" . $v["color"] . "'")));
    if (count($filter["segment"]["list"]) == 0) $filter["segment"]["selected"] = $segmentNull;
    else {
        $k = array_search("", array_column($filter["segment"]["list"], "code"));
        if ($k !== false) {
            unset($filter["segment"]["list"][$k]);
            array_unshift($filter["segment"]["list"], $segmentNone);
        }
        if (count($filter["segment"]["list"]) == 1) $filter["segment"]["selected"] = $filter["segment"]["list"][0];
        else {
            array_unshift($filter["segment"]["list"], $segmentNull);
            $k = array_search($cookie["filter"]["segment"], array_column($filter["segment"]["list"], "code"));
            $filter["segment"]["selected"] = (($k === false) ? $segmentNull : $filter["segment"]["list"][$k]);
        }
    }

    $filter["txt"] = $cookie["filter"]["txt"];

    // echo '<script>console.log(' . json_encode($filter['codenaf']) . ');</script>';

    return $filter;
}

function htmlTitle($opts = array())
    {
        $dtMonth = json_decode(DTmonth, true);
        $dataResp = json_decode(DATAresp, true);
        $dataSegment = json_decode(DATAsegment, true);
        $opts = htmlOpts($opts);
        $cookie = cookieInit();

        if($opts["adr"] === "" || $opts["adr"] === false) return "";
        
        $sql = "SELECT a.*, 
       (SELECT `solde` FROM `synthese` s WHERE s.`adr` = a.code) AS synthese_data
       FROM `adr` AS a 
       WHERE a.`code` = ".$opts['adr'];
        $adr = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
        if(count($adr) == 0) err(array_merge($opts, array("txt" => "Dossier non trouvé", "det" => "ID: " . $opts["adr"], "btn" => APPurl)));

        $grpCode = $adr[0]["grp"];
        $grpTxt = $adr[0]["grp_txt"];
        $grpList = array();
        if($grpCode == "") $grpTxt = "";
        elseif($opts["grp"])
        {
            $sql = "SELECT `id`, `code`, `txt` FROM `adr` WHERE `grp` LIKE '" . $grpCode . "' AND `id` = " . $adr[0]["id"];
            $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
            foreach($result as $v) array_push($grpList, array("code" => $v["code"], "txt" => $v["txt"], "href" => APPscript . "?d=" . $v["id"]));
            usort($grpList, function ($a, $b) { return (($a["txt"] < $b["txt"]) ? -1 : (($a["txt"] > $b["txt"]) ? 1 : 0)); });
        }
        if($grpTxt == "") $grpTxt = $grpCode;
        $grpSelected = array("txt" => $grpTxt, "title" => "Groupe : " . $grpTxt);

        $segmentIco = ((in_array($adr[0]["segment"], array_keys($dataSegment)))? "" : "pencil");
        $segmentTxt = ((in_array($adr[0]["segment"], array_keys($dataSegment)))? $dataSegment[$adr[0]["segment"]]["abr"] : "Segmentation");
        $segmentTitle = implode(" : ", array_filter(array("Modifier la segmentation", ((in_array($adr[0]["segment"], array_keys($dataSegment)))? ($dataSegment[$adr[0]["segment"]]["abr"] . " - " . $dataSegment[$adr[0]["segment"]]["txt"]) : ""))));
        $segmentColor = ((in_array($adr[0]["segment"], array_keys($dataSegment)))? $dataSegment[$adr[0]["segment"]]["color"] : "");
        
        $solde = (($adr[0]["synthese_data"] == "")? "-" : (number_format(floatval($adr[0]["synthese_data"]), 2, ",", " ") . " €"));
        // $clotureTxt = (($adr[0]["cloture"] == "")? "-" : $dtMonth[intval(substr($adr[0]["cloture"], 4, 2))]);
        // $clotureTitle = (($adr[0]["cloture"] == "")? "-" : dtDate($adr[0]["cloture"]));

        $desc = "123";
                
        $sql = "SELECT * FROM `mission` WHERE `adr` = '" . $opts["adr"] . "' ORDER BY `date` DESC";
        $mission = dbSelect($sql, array_merge($opts, array("db" => "prefact")));

        $html = "<div>";
            $html .= "<div class='op l'>" . formBtn(array("key" => "refresh", "ico" => "rotate", "title" => "Rafraichir les données manuelement")) . "</div>";
            $html .= "<div class='main'>";
                $html .= "<div class='adr'>";
                    $html .= "<div class='txt' title='Nom du dossier : " . $adr[0]["txt"] . "'>" . $adr[0]["txt"] . "</div>";
                    $html .= "<div class='code' title='Code du dossier : " . $adr[0]["code"] . "'>" . $adr[0]["code"] . "</div>";
                    if(count($grpList) != 0) $html .= "<div class='sep'>-</div>" . formSelect(array("key" => "grp", "selected" => $grpSelected, "list" => $grpList, "code" => true));
                    elseif($grpTxt != "") $html .= "<div class='sep'>-</div><div class='grp'>" . $grpTxt . "</div>";
                $html .= "</div>";
                $html .= "<div class='op'>";
                    if($desc != "") $html .= formBtn(array("key" => "desc", "ico" => "angle-" . (($cookie["title"]["desc"])? "up" : "down"), "txt" => "Description", "attr" => array("code='desc'"), "extra" => array_filter(array("toggle", (($cookie["title"]["desc"])? "on" : "")))));
                    $html .= formBtn(array("key" => "resp", "ico" => "angle-" . (($cookie["title"]["resp"])? "up" : "down"), "txt" => "Responsables", "attr" => array("code='resp'"), "extra" => array_filter(array("toggle", (($cookie["title"]["resp"])? "on" : "")))));
                    if(count($mission) != 0) $html .= formBtn(array("key" => "mission", "ico" => "angle-" . (($cookie["title"]["mission"])? "up" : "down"), "txt" => "Lettres de mission", "attr" => array("code='mission'"), "extra" => array_filter(array("toggle", (($cookie["title"]["mission"])? "on" : "")))));
                    $html .= formBtn(array("key" => "solde", "txt" => "<span>Solde :</span><b>" . $solde . "</b>", "title" => "Afficher le détail du solde : " . $solde, "href" => "", "target" => "_blank"));
                    // $html .= formDisplay(array("key" => "cloture", "txt" => "<span>Clôture :</span><b>" . $clotureTxt . "</b>", "title" => "Prochaine date de cloture : " . $clotureTitle));
                    $html .= formBtn(array("key" => "segment", "ico" => $segmentIco, "txt" => $segmentTxt, "title" => $segmentTitle, "extra" => array_filter(array($segmentColor))));
                    $html .= formBtn(array("key" => "crm", "ico" => "up-right-from-square", "txt" => "CRM", "title" => "Ouvrir sur le CRM", "href" => "", "target" => "_blank"));
                $html .= "</div>";
                if($desc != "") $html .= "<div class='det" . (($cookie["title"]["desc"])? "" : " off") . "' code='desc'>" . formDisplay(array("txt" => $desc, "title" => "Description du dossier dans le CRM")) . "</div>";
                $html .= "<div class='det" . (($cookie["title"]["resp"])? "" : " off") . "' code='resp'>";
                    foreach($dataResp as $v)
                    {
                        $code = (($adr[0][$v["code"]] == "")? "" : $adr[0][$v["code"]]);
                        $txt = (($adr[0][$v["code"] . "_txt"] == "")? "" : $adr[0][$v["code"] . "_txt"]);
                        if($txt == "") $txt = $code;
                        $op = array();
                        array_push($op, array("type" => "post", "txt" => "<div class='code'>" . $code . "</div>"));
                        // array_push($op, array("type" => "post", "txt" => "<div class='tel'>" . $adr[0][$v["code"] . "_tel"] . "</div>"));
                        // array_push($op, array("type" => "post", "txt" => formBtn(array("key" => "teams", "ico" => "comments", "title" => "Contacter par Teams", "href" => teams(array($adr[0][$v["code"] . "_email"])), "readonly" => ($adr[0][$v["code"] . "_email"] == "")))));
                        // array_push($op, array("type" => "post", "txt" => formBtn(array("key" => "mail", "ico" => "envelope", "title" => "Contacter par Email", "href" => (($adr[0][$v["code"] . "_email"] == "")? "" : ("mailto:" . $adr[0][$v["code"] . "_email"])), "readonly" => ($adr[0][$v["code"] . "_email"] == "")))));
                        $title = $v["txt"] . " (" . $v["abr"] . ")" . (($txt == "")? "" : (" : " . $txt . (($code == "" || $code == $txt)? "" : (" (" . $code . ")"))));
                        $html .= formDisplay(array("key" => $v["code"], "label" => $v["abr"], "txt" => $txt, "title" => $title, "op" => $op));
                    }
                $html .= "</div>";
                if(count($mission) != 0)
                {
                    $off = false;
                    $offY = "";
                    $html .= "<div class='det" . (($cookie["title"]["mission"])? "" : " off") . "' code='mission'>";
                        foreach($mission as $v)
                        {
                            $y = intval(substr($v["date"], 0, 4));
                            if($offY == "") $offY = $y; elseif($offY != $y) $off = true;
                            $txt = "<span>" . dtDate($v["date"]) . " :</span><b>" . number_format(floatval($v["montant"]), 2, ",", " ") . " €</b>";
                            $title = "Lettre de mission :\n- Date : " . dtDate($v["date"]) . "\n- Montant : " . number_format(floatval($v["montant"]), 2, ",", " ") . " €";
                            $html .= formDisplay(array("txt" => $txt, "title" => $title, "attr" => array("y='" . $y . "'"), "off" => $off));
                        }
                        if($off) $html .= formBtn(array("key" => "plus", "ico" => "ellipsis", "title" => "Afficher plus"));
                    $html .= "</div>";
                }
            $html .= "</div>";
            $html .= "<div class='op r'>" . formBtn(array("key" => "cancel", "ico" => "xmark", "title" => "Revenir à la liste des dossiers")) . "</div>";
        $html .= "</div>";

        return $html;
    }
/**
 * Creates html of footer
 * @return string html
 */
function htmlFooter()
{
    return "<div><i class='fa-regular fa-copyright'></i> " . APPauthor . ", " . date("Y") . " - Tous droits reservés</div>";
}
/**
 * Creates html of a loader
 * @return string html
 */
function htmlLoader()
{
    return "<div><div><div></div><div></div><div></div><div></div><div></div></div><div>Chargement</div></div>";
}

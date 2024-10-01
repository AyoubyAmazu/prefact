<?php

    function html($opts = array())
    {
        $opts = htmlOpts($opts);

        $html = "<!doctype html>";
        $html .= "<html lang='en'>";
            $html .= "<head>" . htmlHead($opts) . "</head>";
            $html .= "<body>";
                $html .= "<div id='header'>" . htmlHeader($opts) . "</div>";
                if($opts["adr"] === "") $html .= "<div id='filter'>" . htmlFilter($opts) . "</div>";
                elseif($opts["adr"] !== false) $html .= "<div id='title'>" . htmlTitle($opts) . "</div>";
                $html .= "<div id='cont'><div>" . $opts["cont"] . "</div></div>";
                $html .= "<div id='footer'>" . htmlFooter() . "</div>";
                $html .= "<div id='loader'>" . htmlLoader() . "</div>";
            $html .= "</body>";
        $html .= "</html>";
        
        return $html;
    }

    function htmlOpts($opts = array())
    {
        if(!isset($opts["cont"])) $opts["cont"] = "";
        if(!isset($opts["script"])) $opts["script"] = "";
        if(!isset($opts["title"])) $opts["title"] = "";
        if(!isset($opts["adr"])) $opts["adr"] = "";
        if(!isset($opts["css"])) $opts["css"] = true;
        if(!isset($opts["js"])) $opts["js"] = true;
        if(!isset($opts["filter"])) $opts["filter"] = array();
        if(!isset($opts["user"])) $opts["user"] = array();
        if(!isset($opts["user"]["id"])) $opts["user"]["id"] = "";
        if(!isset($opts["user"]["soc"])) $opts["user"]["soc"] = "";
        if(!isset($opts["user"]["socList"])) $opts["user"]["socList"] = array();

        return $opts;
    }

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
        if($opts["script"] != "" && $opts["css"]) $html .= "<link rel='stylesheet' type='text/css' href='" . APPurl . "css/" . $opts["script"] . ".css?v=" . APPversion . "'>";
        $html .= "<script type='application/javascript' src='" . APPurl . "js/jquery.js?v=" . APPversion . "'></script>";
        $html .= "<script type='application/javascript' src='" . APPurl . "js/ops.js?v=" . APPversion . "'></script>";
        $html .= "<script type='application/javascript' src='" . APPurl . "js/form.js?v=" . APPversion . "'></script>";
        $html .= "<script type='application/javascript' src='" . APPurl . "js/str.js?v=" . APPversion . "'></script>";
        if($opts["script"] != "" && $opts["js"]) $html .= "<script type='application/javascript' src='" . APPurl . "js/" . $opts["script"] . ".js?v=" . APPversion . "'></script>";

        return $html;
    }
    
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
                if($opts["adr"] != "") $html .= formInput(array("key" => "nav", "align" => "c", "placeholder" => "Rechercher un dossier...", "op" => $navOp));
                $html .= formBtn(array("key" => "help", "ico" => "question", "title" => "Aide"));
                if($opts["user"]["id"] != "") $html .= formBtn(array("key" => "out", "ico" => "power-off", "txt" => $opts["user"]["id"], "title" => "Se déconnecter", "href" => AUTHout));
            $html .= "</div>";
        $html .= "</div>";            

        return $html;
    }

    function htmlFilter($opts = array())
    {
        $opts = htmlOpts($opts);
        
        $anneeOp = array();
        array_push($anneeOp, array("type" => "pre", "txt" => formBtn(array("key" => "prev", "ico" => "angle-left", "title" => "Année précedente"))));
        array_push($anneeOp, array("type" => "poste", "txt" => formBtn(array("key" => "next", "ico" => "angle-right", "title" => "Année suivante"))));

        $html = "<div>";
            $html .= formInput(array("key" => "annee", "type" => "number", "align" => "c", "label" => "Année", "value" => $opts["filter"]['annee'], "op" => $anneeOp));
            $html .= formSelect(array("key" => "soc", "label" => "Cabinet", "selected" => $opts["filter"]["soc"]["selected"], "list" => $opts["filter"]["soc"]["list"], "readonly" => (count($opts["filter"]["soc"]["list"]) <= 1)));
        $html .= "</div>";            

        return $html;
    }

    function htmlFilterData($opts = array())
    {
        $opts = htmlOpts($opts);
        $cookie = cookieInit();
        
        $filter = array();

        $filter["annee"] = intval($cookie["filter"]["annee"]);
        if($filter["annee"] < 1) $filter["annee"] = intval(date("Y"));

        $filter["soc"] = array();
        $filter["soc"]["list"] = array();
        $filter["soc"]["selected"] = array();
        $socNull = array("code" => "", "txt" => "", "placeholder" => "Tous", "title" => "Tous les cabiets");
        $sql = "SELECT DISTINCT `soc`, `soc_txt` FROM `adr`";
        $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
        foreach($result as $v) if(count($opts["user"]["socList"]) == 0 || in_array($v["soc"], $opts["user"]["socList"])) array_push($filter["soc"]["list"], array("code" => $v["soc"], "txt" => $v["soc_txt"]));
        usort($filter["soc"]["list"], function($a, $b) { return (($a["txt"] < $b["txt"])? -1 : (($a["txt"] > $b["txt"])? 1 : 0)); });
        if(count($filter["soc"]["list"]) == 0) err(array_merge($opts, array("txt" => "Accès refusé", "det" => "Aucun cabinet trouvé")));
        elseif(count($filter["soc"]["list"]) == 1) $filter["soc"]["selected"] = $filter["soc"]["list"][0];
        else
        {
            array_unshift($filter["soc"]["list"], $socNull);
            $k = array_search((($cookie["filter"]["soc"] == -1)? $opts["user"]["soc"] : $cookie["filter"]["soc"]), array_column($filter["soc"]["list"], "code"));
            $filter["soc"]["selected"] = (($k === false)? $socNull : $filter["soc"]["list"][$k]);
        }
        
        $filter["grp"] = array();
        $filter["grp"]["list"] = array();
        $filter["grp"]["selected"] = array();
        $grpNull = array("code" => "", "txt" => "", "placeholder" => "Tous", "title" => "Tous les groupes");
        $grpNone = array("code" => "-", "txt" => "", "placeholder" => "Aucun", "title" => "Aucun groupe");
        $sql = "SELECT DISTINCT `grp`, `grp_txt` FROM `adr`";
        $result = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
        foreach($result as $v) array_push($filter["grp"]["list"], array("code" => $v["grp"], "txt" => $v["grp_txt"]));
        usort($filter["grp"]["list"], function($a, $b) { return (($a["txt"] < $b["txt"])? -1 : (($a["txt"] > $b["txt"])? 1 : 0)); });
        if(count($filter["grp"]["list"]) == 0) $filter["grp"]["selected"] = $grpNull;
        else
        {
            $k = array_search("", array_column($filter["grp"]["list"], "code"));
            if($k !== false) { unset($filter["grp"]["list"][$k]); array_unshift($filter["grp"]["list"], $grpNone); }
            if(count($filter["grp"]["list"]) == 1) $filter["grp"]["selected"] = $filter["grp"]["list"][0];
            else
            {
                array_unshift($filter["grp"]["list"], $grpNull);
                $k = array_search($cookie["filter"]["grp"], array_column($filter["grp"]["list"], "code"));
                $filter["grp"]["selected"] = (($k === false)? $grpNull : $filter["grp"]["list"][$k]);
            }
        }

        $keys = array_keys(json_decode(DATAresp, true));
        $filtersSelect = 'SELECT '.implode(' , ', $keys).' FROM `adr`;';
        $filters = dbSelect($filtersSelect, array_merge($opts, array("db" => "prefact")));

        return $filter;
    }
    
    function htmlTitle($opts = array())
    {
        $opts = htmlOpts($opts);

        $html = "<div>";
            // TO-DO
        $html .= "</div>";            

        return $html;
    }

    function htmlFooter()
    {
        return "<div><i class='fa-regular fa-copyright'></i> " . APPauthor . ", " . date("Y") . " - Tous droits reservés</div>";
    }

    function htmlLoader()
    {
        return "<div><div><div></div><div></div><div></div><div></div><div></div></div><div>Chargement</div></div>";
    }

?>
<?php

    require_once("config.php");

    $opts = array();
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));
    $opts["user"] = auth($opts);
    $opts['user']['socList'] = array();
    $cookie = cookieInit();
    $getD = ((isset($_GET["d"]))? cryptDel($_GET["d"]) : false);
    if($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));

    $anaList = array();
   array_push($anaList, array("code" => "analyseannuelle", "txt" => "Analyse annuelle", "title" => "analyse annuelle",));
   array_push($anaList, array("code" => "analysemensuelle", "txt" => "Analyse mensuelle", "title" => "analyse mensuelle","href"=>"syntheseFac.php"));
   array_push($anaList, array("code" => "analysemission", "txt" => "Analyse par mission", "title" => "Analyse par mission"));
   array_push($anaList, array("code" => "analysecollaborateur", "txt" => "Analyse par collaborateur", "title" => "Analyse par collaborateur" , "href"=>"synthese_collab.php"));
   $sortNull = array("code" => "Trier", "txt" => "analyse annuelle ", "title" => "analyse annuelle");

    $k = array_search($cookie["index"]["sortCol"], array_column($anaList, "code"));
    if($k === false) $sortSelected = $sortNull;
    else
    {
       $k2 = array_search($anaList[$k]["code"], array_column($anaList, "code"));
       $sortSelected = array("code" => $anaList[$k2]["code"]);
    }

    $filter = "<div class='op'>";
    $filter .= "<div class='side'>";
    $filter .= formBtn(array("key" => "refresh", "ico" => "fa-solid fa-arrows-rotate","title"=>"refresh"));
    $filter .= formBtn(array("key" => "facturation", "ico" => "fa-solid fa-file-invoice", "txt" => "Facturation", "href"=>"resultat.php"));
    $filter .= formBtn(array("key" => "tarifs", "ico" => "fa-solid fa-user", "txt" => "Tarifs social" , "href"=>"tarifs_social.php"));
    $filter .= formBtn(array("key" => "arret", "ico" => "fa-solid fa-square", "txt" => "Arrêt des travaux"));
    $filter .= "</div>";
    $filter .= "<div class='side'>";
    $filter .= formSelect(array("key" => "sortAnalyse", "label" => "Affichage :", "title"=>"trier par : analyse" , "selected"=> $sortSelected , "list" => $anaList ));
    $filter .= "<div class='slider'>";
    $filter .= formBtn(array("key"=>"angleLeft", "ico"=>"fa-solid fa-angle-left", "title"=>"scroll left"));
    $filter .= "<div class='sliderYears' id='yearsfilterainer'>";
    $filter .= "<div class='sliderTotal'>";
    $filter .= formBtn(array("key"=>"total", "txt"=>"TOTAL<div>-0.00</div>", "title"=>"le total" ));
    $filter .= "</div>";
    $filter .= "</div>";
    $filter .= "</div>";
    $cont = "";

    $html = html(array_merge($opts, array("cont" => $filter, "script" => "recap", "adr" => $getD)));

    die($html);

?>
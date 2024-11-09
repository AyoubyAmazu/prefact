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
   array_push($anaList, array("code" => "analysecollaborateur", "txt" => "Analyse par collaborateur", "title" => "Analyse par collaborateur" , "href"=>"synthese_collab.php"));
   $sortNull = array("code" => "Trier", "txt" => "analyse annuelle ", "title" => "analyse annuelle");

    $k = array_search($cookie["index"]["sortCol"], array_column($anaList, "code"));
    if($k === false) $sortSelected = $sortNull;
    else
    {
       $k2 = array_search($anaList[$k]["code"], array_column($anaList, "code"));
       $sortSelected = array("code" => $anaList[$k2]["code"]);
    }

    $cont = "<div class='op'>";
    $cont .= "<div class='side'>";
    $cont .= formBtn(array("key" => "facturation", "ico" => "fa-solid fa-file-invoice", "txt" => "Facturation", "href"=>"resultat.php"));
    $cont .= formBtn(array("key" => "tarifs", "ico" => "fa-solid fa-user", "txt" => "Tarifs social" , "href"=>"tarifs_social.php"));
    $cont .= formBtn(array("key" => "arret", "ico" => "fa-solid fa-square", "txt" => "Arrêt des travaux"));
    $cont .= "</div>";
    $cont .= "<div class='side'>";
    $cont .= formSelect(array("key" => "sortAnalyse", "label" => "Affichage", "title"=>"trier par : analyse" , "selected"=> $sortSelected , "list" => $anaList ));
    $cont .= "</div>";
    $cont .= "</div>";
    $cont .= "<div class='main'>";
    $cont .="<div class='pre-angle'>";
    $cont .= formBtn(array("key"=>"angleLeft", "ico"=>"fa-solid fa-angle-left", "title"=>"scroll right"));
    $cont .="</div>";

    $cont .= "<div class='fields'>";
    $cont .= "</div>";
    
    $cont .="<div class='post-angle'>";
    $cont .= formBtn(array("key"=>"angleRight", "ico"=>"fa-solid fa-angle-right", "title"=>"scroll right"));
    $cont .="</div>";
    $cont .= "</div>";
    $cont .= "<div class='years'>";

    $select = "select distinct AnneeChoix from z_fact.rech_fact where Adr_Id ='" . $getD."'";
    $years = array_column(dbSelect($select, array_merge($opts, array("db" => "prefact"))), "AnneeChoix");
    rsort($years);
    
    
    $selected = 3;
    $cont .="<div class='yearsDiv'>";
    foreach($years as $year)
    {
        $selected--;
        $cont .= formBtn(array("key"=>$year, "txt"=>$year . "<div>+0.00</div>", "title"=>"l'Année", "class"=>"sliderButton", "extra"=>array("year", $selected >= 0?"selected":"")));
    }
    $cont .="</div>";
    $cont .= "</div>";

    $html = html(array_merge($opts, array("cont" => $cont, "script" => "recap", "adr" => $getD)));

    die($html);

?>
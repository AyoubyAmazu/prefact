<?php

    require_once("config.php");

    $opts = array("ajax"=>true);
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
    $opts["user"] = auth($opts);
    $opts['user']['socList'] = array();

    $opts["filter"] = htmlFilterData($opts);

    $where = array();
    array_push($where, "`id` IN (SELECT `adr` FROM `synthese` WHERE `annee` = " . $opts["filter"]["annee"] . ")");

    if($opts["filter"]["soc"]["selected"] == "") array_push($where, "`soc` IN ('" . implode("', '", array_column($opts["filter"]["soc"]["list"], "code")) . "')");
    else array_push($where, "`soc` = '" . $opts["filter"]["soc"]["selected"]["code"] . "'");


    $sql = "SELECT *";
    $sql .= ", (SELECT `temps_dur` FROM `synthese` x WHERE x.`adr` = z.`id` AND `annee` = " . $opts["filter"]["annee"] . " LIMIT 1) AS 'temps_dur'";
    $sql .= " FROM `adr` z" . ((count($where) == 0)? "" : (" WHERE " . implode(" AND ", $where)));
    $list = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
    $cont ="";
    $cont.="<div id=buttons>";
    $cont.='<div>';
    $cont.= formSelect(array(
        "key" => "codeFilter",
        "label" => "trier par :",
        "title" => "Choose an option",
        "selected" => array(
            "code" => "1",
            "txt" => "Option 1",
            "placeholder" => "Select...",
        ),
        "list" => array(
            array("code" => "1", "txt" => "Option 1"),
            array("code" => "2", "txt" => "Option 2"),
            array("code" => "3", "txt" => "Option 3"),
        )));
    $cont.= formCheckbox(array(
        "key" => "myCheckbox",
        "align" => "c",
        "title" => "Choose your options",
        "selected" => array(
            "code" => "option1",
            "txt" => "Option 1",
            "placeholder" => "Choose an option",
            "title" => "Selected Option 1"
        ),
        "code" => "",
        "filter" => true,
        "other" => false,
        "required" => true,
        "readonly" => false,
        "off" => false,
        "list" => array(
            array(
                "code" => "asend",
                "txt" => "Asendant",
                "title" => "Asendant",
                "value" => true,
            ),
            array(
                "code" => "desend",
                "txt" => "Desendant",
                "title" => "Desendant",
                "value" => false,
            ),
        )
    ));
    $cont.= formBtn(array("key"=>"parametres","align"=>'l',"ico"=>"wrench",'type'=>'solid','txt'=>"Paramétres"));
    $cont.= "</div>";
    $cont.="<div>";
    $cont.=formBtn(array("key"=>"statistiques","aling"=>"c","ico"=>"chart-pie","type"=>"solid","txt"=>"statistiques"));
    $cont.=formBtn(array("key"=>"fileExcel","aling"=>"c","ico"=>"file-excel","type"=>"solid"));
    $cont.="</div>";
    $cont.="</div>";
// table
    $cont .= "<div  class='list'>";
        $cont .= "<div  class='line st'>";
            $cont .= "<div  class='col'>Dossier</div>";
            $cont .= "<div class='col'>Temps</div>";
            $cont .= "<div class='col'>Factures</div>";
            $cont .= "<div class='col'>Statut</div>";
            $cont .= "<div class='col'>Opérations</div>";
            $cont .= "<div class='col'>Provisions</div>";
            $cont .= "<div class='col'>  </div>";
        $cont .= "</div>";

// Data
$cont .= "</div>";
    
    $html = html(array_merge($opts, array("cont" => $cont, "script" => "index")));
    die($html);

<?php

    require_once("config.php");

    $opts = array("ajax"=>true);
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
    $opts["user"] = auth($opts);
    $opts['user']['socList'] = array();
    $cookie = cookieInit();


    $opts["filter"] = htmlFilterData($opts);

    $where = array();
    array_push($where, "`id` IN (SELECT `adr` FROM `synthese` WHERE `annee` = " . $opts["filter"]["annee"] . ")");

    if($opts["filter"]["soc"]["selected"] == "") array_push($where, "`soc` IN ('" . implode("', '", array_column($opts["filter"]["soc"]["list"], "code")) . "')");
    else array_push($where, "`soc` = '" . $opts["filter"]["soc"]["selected"]["code"] . "'");

    $sortList = array();
    array_push($sortList, array("code" => "dossier", "txt" => "Dossier", "readonly" => true, "off" => (!in_array("dossierCode", $cookie["index"]["displayCol"]) && !in_array("dossierNom", $cookie["index"]["displayCol"]) && !in_array("dossierGroupe", $cookie["index"]["displayCol"]))));
    array_push($sortList, array("code" => "dossierCode", "txt" => "Code", "title" => "Trier par : Code du dossier", "attr" => array("parent='dossier'"), "parent" => "dossier", "off" => !in_array("dossierCode", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "dossierNom", "txt" => "Nom", "title" => "Nom du dossier", "attr" => array("parent='dossier'"), "parent" => "dossier", "off" => !in_array("dossierNom", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "dossierGroupe", "txt" => "Groupe", "title" => "Groupe du dossier", "attr" => array("parent='dossier'"), "parent" => "dossier", "off" => !in_array("dossierGroupe", $cookie["index"]["displayCol"])));
     
    array_push($sortList, array("code" => "temps", "txt" => "Temps", "readonly" => true));
    array_push($sortList, array("code" => "tempsDuree", "txt" => "Durée", "title" => "Durée de temps des prestations", "attr" => array("parent='temps'"), "parent" => "temps"));
    array_push($sortList, array("code" => "tempsCout", "txt" => "Coût de revient", "title" => "Coût de revient des prestations", "attr" => array("parent='temps'"), "parent" => "temps"));
    array_push($sortList, array("code" => "tempsDebours", "txt" => "Débours", "title" => "Débours", "attr" => array("parent='temps'"), "parent" => "temps"));
 
    array_push($sortList, array("code" => "factures", "txt" => "Factures", "readonly" => true));
    array_push($sortList, array("code" => "FacturesQuantités", "txt" => "Quanitité", "title" => "Quantité", "attr" => array("parent='factures'"), "parent" => "factures"));
    array_push($sortList, array("code" => "FacturesEmises", "txt" => "Emises", "title" => "Emises", "attr" => array("parent='factures'"), "parent" => "factures"));
    array_push($sortList, array("code" => "FacturesDebours", "txt" => "Débours", "title" => "Débours", "attr" => array("parent='factures'"), "parent" => "factures"));
    
    array_push($sortList, array("code" => "statut", "txt" => "Statut", "readonly" => true));
    array_push($sortList, array("code" => "StatutSegmentation", "txt" => "Segmentation", "title" => "Segmentation", "attr" => array("parent='statut'"), "parent" => "statut"));
    array_push($sortList, array("code" => "StatutValue", "txt" => "+/-value", "title" => "+/-value", "attr" => array("parent='statut'"), "parent" => "statut"));
    array_push($sortList, array("code" => "StatutCreance", "txt" => "Solde de créances", "title" => "Solde de créances", "attr" => array("parent='statut'"), "parent" => "statut"));
 
    array_push($sortList, array("code" => "operations", "txt" => "Operations", "readonly" => true));
    array_push($sortList, array("code" => "OperationsEncours", "txt" => "en cours", "title" => "en cours", "attr" => array("parent='operations'"), "parent" => "operations"));
    array_push($sortList, array("code" => "OperationsValid", "txt" => "Validation du RD", "title" => "Validation du RD", "attr" => array("parent='operations'"), "parent" => "operations"));
    array_push($sortList, array("code" => "OperationsAdmin", "txt" => "Traitement administratif", "title" => "Traitement administratif", "attr" => array("parent='operations'"), "parent" => "operations"));
 
    array_push($sortList, array("code" => "provisions", "txt" => "Provisions", "readonly" => true));
    array_push($sortList, array("code" => "ProvisionsEncours", "txt" => "en cours", "title" => "en cours", "attr" => array("parent='provisions'"), "parent" => "provisions"));
    array_push($sortList, array("code" => "ProvisionsValid", "txt" => "Validation du RD", "title" => "Validation du RD", "attr" => array("parent='provisions'"), "parent" => "provisions"));
    array_push($sortList, array("code" => "ProvisionssAdmin", "txt" => "Traitement administratif", "title" => "Traitement administratif", "attr" => array("parent='provisions'"), "parent" => "provisions"));
 
    
    
    // TODO : CHECK sortCol IN displayCol (COOKIE)
 
    $sortNull = array("code" => "dossierCode", "txt" => "Code", "title" => "Trier par : Code du dossier", "attr" => array("parent='dossier'"), "parent" => "dossier");
 
    $k = array_search($cookie["index"]["sortCol"], array_column($sortList, "code"));
    if($k === false) $sortSelected = $sortNull;
    else
    {
       $k2 = array_search($sortList[$k]["parent"], array_column($sortList, "code"));
       $sortSelected = array("code" => $sortList[$k]["code"], "txt" => (($k2 === false)? "" : ($sortList[$k2]["txt"] . " > ")) . $sortList[$k]["txt"], "title" => $sortList[$k]["title"]);
    }

    $dirList = array();
    array_push($dirList, array("code" => "ASC", "txt" => "Ascendant", "value" => ($cookie["index"]["sortDir"] == "ASC")));
    array_push($dirList, array("code" => "DESC", "txt" => "Descendant", "value" => ($cookie["index"]["sortDir"] == "DESC")));

    $sql = "SELECT *";
    $sql .= ", (SELECT `temps_dur` FROM `synthese` x WHERE x.`adr` = z.`id` AND `annee` = " . $opts["filter"]["annee"] . " LIMIT 1) AS 'temps_dur'";
    $sql .= " FROM `adr` z" . ((count($where) == 0)? "" : (" WHERE " . implode(" AND ", $where)));
    $list = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
    $cont ="";

    $cont = "<div class='op'>";
        $cont .= "<div class='side'>";
            $cont .= formSelect(array("key" => "sortCol", "label" => "Trier par", "selected" => $sortSelected, "list" => $sortList));
            $cont .= formCheckbox(array("key" => "sortDir", "list" => $dirList));
            $cont .= formBtn(array("key" => "displayParam", "ico" => "wrench", "txt" => "Paramètres", "id"=>"popupLink", "list"=> $sortList ));
        $cont .= "</div>";
        $cont .= "<div class='side'>";
            $cont .= formBtn(array("key" => "stats", "ico" => "chart-pie", "txt" => "Statistiques"));
            $cont .= formBtn(array("key" => "xlsx", "ico" => "file-excel","title"=>"excel"));
        $cont .= "</div>";
    $cont .= "</div>";
    
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

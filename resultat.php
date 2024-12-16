<?php

require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));
$cookie = cookieInit();



$youlist = array();
array_push($youlist, array("code" => "year", "txt" => "2021/05/08", "title" => "2021/05/08"));
array_push($youlist, array("code" => "year", "txt" => "2023/08/15", "title" => "2021/05/08"));
array_push($youlist, array("code" => "year", "txt" => "2023/08/01", "title" => "2023/08/01"));

$sortNull = array("code" => "nouvelle_facture", "txt" => "Nouvelle facture");

$k = array_search($cookie["index"]["sortCol"], array_column($youlist, "code"));
if ($k === false) {
    $sortSelected = $sortNull;
} else {
    $k2 = array_search($youlist[$k]["code"], array_column($youlist, "code"));
    $sortSelected = array("code" => $youlist[$k]["code"]);
}




$sql = "SELECT TEMPS_DATE, COL_CODE, PREST_CODE, EXO_CODE, TEMPS_MEMO, TEMPS_M_QTE, TEMPS_DUREE, TEMPS_M_PV";
$sql .= " FROM temps";
$sql .= " WHERE EXO_CODE = 2022 AND ADR_ID = 2856";
$sql .= " ORDER BY TEMPS_DATE DESC";
$sql .= " LIMIT 50";
$list = dbSelect($sql, array("db" => "dia"));




$html = "<div class='all'>";
$html .= "<div class='right-div'>";
$html .= formBtn(array("key" => "affiche-exep", "txt" => "Afficher l'exceptionnel"));
$html .= formBtn(array("key" => "presentation", "txt" => "Prestations facturèes"));
$html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier", "href" => "synthese.php"));
$html .= "</div>";
$html .= "<div class='left-div'>";
$html .= formLabel(array(
    "key" => "Sèlection d'une facture non terminèe : ",
));
$html .= formSelect(array("key" => "sortAnalyse", "selected" => $sortSelected, "list" => $youlist));
$html .= formBtn(array("key" => "affiche_pre_facture", "ico" => "eye", "txt" => "Afficher la pré-facture", "href" => "affiche_fact.php"));
$html .= "</div>";
$html .= "</div>";






// Travaux compta 
$html .= formTable(array("legend" => "Travaux Comptable et fiscaux", "id"=>"trav-com","list" => $list), con: function ($opts): string {
    if ((($opts["prest"] >= 200) && ($opts["prest"] < 400)) || (strpos($opts["prest"], 'T2') !== false && strpos($opts["prest"], 'T2', 0) == 0)) {
        return $opts['rw'];
    }
    return "";
});

// Travaux social

$html .= formTable(array("legend" => "Travaux Sociaux", "id"=>"trav-soc", "list" => $list), con: function ($opts): string {
    if ((($opts["prest"] >= 400) && ($opts["prest"] < 500))) {
        return $opts['rw'];
    }
    return "";
});

// Travaux Conseil 

$html .= formTable(array("legend" => "Travaux Conseil", "id"=>"trav-cons","list" => $list), con: function ($opts): string {
    if ((($opts["prest"] >= 500) && ($opts["prest"] < 600)) || (($opts["prest"] >= 700) && ($opts["prest"] < 900))) {
        return $opts['rw'];
    }
    return "";
});

// Travaux Juridiques 

$html .= formTable(array("legend" => "Travaux Juridiques","id"=>"trav-juri", "list" => $list), con: function ($opts): string {
    if ((($opts["prest"] >= 600) && ($opts["prest"] < 700))) {
        return $opts['rw'];
    }
    return "";
});

// Travaux Abonnements 

$html .= formTable(array("legend" => "Abonnements","id"=>"trav-abon", "list" => $list), con: function ($opts): string {
    if ((($opts["prest"] >= 900) && ($opts["prest"] < 999))) {
        return $opts['rw'];
    }
    return "";
});


/**
 * Form Factures tables html
 * @param mixed $opts [string $legend] lagend of the fieldset, [array $list] list of factures
 * @param callable $con condition of the displaied facts
 * @return string
 */
function formTable($opts = array(), $con): string
{
    $html = "<fieldset class='field'>";
    $html .= "<legend>" . $opts['legend'] . "</legend>";
    $html .= "<table id ='".$opts['id']."' class='customers'>";
    $html .= "<thead>";
    $html .= "<tr>";
    $html .= "<th class='first-1'>" . formBtn(array("key" => "first-check", "ico" => "check-double"));
    $html .= "</th>";
    $html .= "<th class='second-2 date'>Date</th>";
    $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('".$opts["id"]."')\">Collab</p></th>";
    $html .= "<th class='second-2 prest-header'><p onclick=\"sortPrest('".$opts["id"]."')\">Prest</p></th>";
    $html .= "<th class='no-line exercice'><div class='all-year'><p class='exerciceLabel'>Exercice</p></div></th>";
    $html .= "<th>Titre</th>";
    $html .= "<th class='last-3'>Qte</th>";
    $html .= "<th class='last-3'>Duree</th>";
    $html .= "<th class='last-3'>PV</th>";
    $html .= "</tr>";
    $html .= "</thead>";
    foreach ($opts["list"] as $row) {
        $prest = (strpos($row['PREST_CODE'], '@', 0) == 0) ? str_replace("@", "", $row['PREST_CODE']) : $row['PREST_CODE'];

        $tblRow = "<tr class='rw'>";
        $formattedDate = date("m/d/Y", strtotime($row['TEMPS_DATE']));
        // added unvalide icon on porpuse to give the chape of box to the button
        $tblRow .= "<td>" . formBtn(array("key" => "first-check", "ico" => "fa-circle")) . "</td>"; 
        $tblRow .= "<td class='centered-td date'>" . $formattedDate . "</td>";
        $tblRow .= "<td class='centered-td code-row'><a href='#'>" . $row['COL_CODE'] . "</a></td>";
        $tblRow .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" . formInput(array("key" => "prest_code", "align" => "c", "value" => $row['PREST_CODE'])) . "</span></p></td>";
        $tblRow .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . formInput(array("key" => "exo_code", "align" => "c", "value" => $row['EXO_CODE'])) . "</span></p></td>";
        $tblRow .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
        $tblRow .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
        $tblRow .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
        $tblRow .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";

        $tblRow .= "</tr>";
        $html .= call_user_func($con, array("prest" => $prest, "rw" => $tblRow));
    }
    $html .= "</table>";
    $html .= "<div class='add-table-btn'>";
    $html .= formBtn(array("key" => "Ajouter-facture", "ico" => "plus", "txt" => "Ajouter â la facture", "href" => "affiche_fact.php"));
    $html .= formBtn(array("key" => "ne-pas-facturer", "ico" => "ban", "txt" => "Ne pas facturer"));
    $html .= "</div>";
    $html .= "</fieldset>";
    return $html;
}



$cont = html(array_merge($opts, array("cont" => $html, "script" => "resultat")));
die($cont);

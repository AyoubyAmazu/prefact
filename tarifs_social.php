<?php

require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$getD = ((isset($_GET["d"])) ? cryptDel($_GET["d"]) : false);
if ($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
//    $filter = htmlFilter($opts);


$pasList = array();
array_push($pasList, array("code" => "oui", "txt" => "oui", "title" => "oui"));
array_push($pasList, array("code" => "non", "txt" => "non", "title" => "non"));

$sortNull = array("code" => "oui", "txt" => "oui", "title" => "oui", "attr" => array("code"));

$facList = array();
array_push($facList, array("code" => "mens", "txt" => "mensuelle", "title" => "mensuelle"));
array_push($facList, array("code" => "trim", "txt" => "trimestrielle ", "title" => "trimestrielle "));
array_push($facList, array("code" => "seme", "txt" => "semestrielle ", "title" => "semestrielle "));
array_push($facList, array("code" => "annu", "txt" => "annuelle", "title" => "annuelle"));


$sortNull = array("code" => "trimestrielle", "txt" => "trimestrielle", "title" => "trimestrielle", "attr" => array("code"));

$html = "<div class='hd'>";
$html .= formBtn(array("key" => "retour", "ico" => "fa-solid fa-angles-left", "txt" => "Retour au tableau récap", "title" => "retour au tableau", "href" => "synthese.php"));
$html .= formBtn(array("key" => "retour", "ico" => "fa-solid fa-angle-left", "txt" => "Retour au facturation", "title" => "retour au facturation", "href" => "affiche_fact.php"));

$html .= "</div>";


$html .= "<div class='menu'>";
$html .= "<table>";
$html .= "<tr class='first'>";
$html .= "<th class='first'>Code Dossier </th>";
$html .= "<th>Nom Dossier</th>";
$html .= "<th>Bulletins</th>";
$html .= "<th>Solde Tout Compte CDI</th>";
$html .= "<th>Solde Tout Compte CDD</th>";
$html .= "<th>DSN maladie</th>";
$html .= "<th>DSN Arrêt Travail + Déclaration</th>";
$html .= "<th>DPAE</th>";
$html .= "<th>Gestion Dossier Prévoy</th>";
$html .= "<th>Gestion Mi-temps Thérap</th>";
$html .= "<th>Contrat CDI / CDD</th>";
$html .= "<th>Avenant Au CDI</th>";
$html .= "<th>Rupture Convention</th>";
$html .= "</tr>";

$html .= "<tr class='second'>";
$html .= "<th class='left'>TLS845500 </th>";
$html .= "<th>TRAIZE</th>";
$html .= "<th>";

$html .= "<div class='bull'>";
$html .= formInput(array("key" => "bull", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='soldeCDI'>";
$html .= formInput(array("key" => "soldeCDI", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='soldeCDD'>";
$html .= formInput(array("key" => "soldeCDD", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='dsnMaladie'>";
$html .= formInput(array("key" => "dsnMaladie", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='dsnArret'>";
$html .= formInput(array("key" => "dsnArret", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='DPAE'>";
$html .= formInput(array("key" => "DPAE", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='gestionDossier'>";
$html .= formInput(array("key" => "gestionDossier", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='gestionMi-temp'>";
$html .= formInput(array("key" => "gestionMi", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='contrat'>";
$html .= formInput(array("key" => "contrat", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='avenant'>";
$html .= formInput(array("key" => "avenant", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";
$html .= "<th>";
$html .= "<div class='rupture'>";
$html .= formInput(array("key" => "rupture", "value" => "25,01"));
$html .= "</div>";
$html .= "</th>";

$html .= "</tr>";


$html .= "</table>";
$html .= "</div>";
$html .= "<div class='select'>";
$html .= formInput(array("key" => "comment", "value" => "commentaire", "label" => "Commentaire :"));
$html .= formSelect(array("key" => "pas", "list" => $pasList, "label" => "PAS :"));
$html .= formSelect(array("key" => "fac", "list" => $facList, "label" => "Facturation :"));
$html .= "</div>";








echo html(array("user" => $user, "cont" => $html, "script" => "tarifs_social", "adr"=>$getD));

die();

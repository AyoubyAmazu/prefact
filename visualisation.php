<?php

require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$getD = ((isset($_GET["d"])) ? cryptDel($_GET["d"]) : false);
if ($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
// $filter = htmlFilter($opts);









$html = "<div class='div1'>";

$html .= formBtn(array("key" => "retour", "txt" => "Retour vers la pré-facturation", "href" => "affiche_fact.php"));
$html .= formBtn(array("key" => "envoyer", "txt" => "Envoyer à la validation"));
$html .= formBtn(array("key" => "enregister", "txt" => "Enregister cette facture sans envoyer"));
$html .= formBtn(array("key" => "impression", "txt" => "Impression PDF"));
$html .= formBtn(array("key" => "impr", "txt" => "Impr. Dètail Facture PDF"));
$html .= formBtn(array("key" => "archive", "txt" => "Archiver la facture"));
$html .= formBtn(array("key" => "fusionner", "txt" => "Fusionner des facture"));
$html .= formBtn(array("key" => "facture", "txt" => "Facture FAE"));
$html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthése du dossier", "href" => "synthese.php"));


$html .= "</div>";


$html .= "<div class='div2'>";
$html .= "<div class='label_test'>";
$html .= formLabel(array("key" => "- TLS261099 - CLIENTS TEST ", "title" => " - TLS261099 - CLIENTS TEST "));
$html .= "</div>";


$html .= "<div class='div_area1'>";
$html .= formBtn(array("key" => "Date", "txt" => "Modifier l'adresse de facturation "));
$html .= "<div class='area1'>" . formTextarea(array("key" => "formTextareaInput")) . "</div>";
$html .= "</div>";

$html .= "<div class='div_area2'>";
$html .= formBtn(array("key" => "btn_area", "txt" => "Modalités de paiement"));
$html .= "<div class='area2 '>" . formTextarea(array("key" => "formTextareaInput")) . "</div>";
$html .= "</div>";


$html .= "<div class='div3'>";
$html .= "<div class='div3_label'>";
$html .= formLabel(array("key" => "Mois de facturation  : 06/23 - 12/23", "title" => " Mois de facturation "));
$html .= "</div>";
$html .= "<a>Niveau de validation = 1 : SAISIE COLLAB</a>";
$html .= "</div>";



$html .= "<div class='div4'>";
$html .= "<div class='div4_label'>";
$html .= formLabel(array("key" => "Travaux Comptable et Fiscaux", "title" => "Travaux Comptable et Fiscaux"));
$html .= "</div>";
$html .= formLabel(array("key" => "Travaux Juridiques", "title" => " Travaux Juridiques  "));
$html .= "</div>";

$html .= "<div class='div5'>";
$html .= "<div class='total'>" . formLabel(array("key" => "1000,00", "title" => "total")) . "</div>";
$html .= "<div class='total_ht'>" . formLabel(array("key" => " 500,00", "title" => "total")) . "</div>";
$html .= "<div class='total_ht'>" . formLabel(array("key" => "= 1500,00", "title" => "total")) . "</div>";
$html .= "<div class='total_ht'>" . formLabel(array("key" => "Mensuel = 750,00", "title" => "total")) . "</div>";
$html .= "</div>";
$html .= "</div>";












echo html(array("user" => $user, "cont" => $html, "script" => "visualisation", "adr" => $getD));
die();

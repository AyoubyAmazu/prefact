<?php

require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$filter = htmlFilter($opts);

$sortYear = array();
array_push($sortYear, array("code" => "temps", "txt" => "2020"));
array_push($sortYear, array("code" => "temps", "txt" => "2021"));
array_push($sortYear, array("code" => "temps", "txt" => "2022"));
array_push($sortYear, array("code" => "temps", "txt" => "2023"));

$sortNull = array("code" => "dossierCode", "txt" => "Code", "title" => "Trier par : Code du dossier", "attr" => array("parent='dossier'"), "parent" => "dossier");


$sortRegl = array();
array_push($sortRegl, array("code" => "regle", "txt" => "Virement"));
array_push($sortRegl, array("code" => "regle", "txt" => "Escpèces"));
array_push($sortRegl, array("code" => "regle", "txt" => "Chèque"));
array_push($sortRegl, array("code" => "regle", "txt" => "prélèvement "));

$sortNull = array("code" => "regle", "txt" => "mode de règlement", "title" => "mode de règlement", "attr" => array("code"));

$html = "<div class='hd'>";
$html .= "<div class='left'>";
$html .= formBtn(array("key" => "retour", "ico" => "fa-solid fa-angles-left", "txt" => "Retour à la liste à valider", "title" => "retour à la liste", "href" => "fact_a_valider.php"));
$html .= formBtn(array("key" => "valider", "ico" => "fa-solid fa-check", "txt" => "Valider la facture", "title" => "valider la facture"));
$html .= "</div>";
$html .= "<div class='left'>";
$html .= formSelect(array("key" => "year", "label" => "AUD084700 - BITCHE FIXATIONS - Exercice client : ", "list" => $sortYear));
$html .= formDp(array("key" => "date", "label" => "Date de la facture : ", "value" => "27/06/2023"));
$html .= formDp(array("key" => "date", "label" => "Echèaonce de la facture : ", "value" => "27/06/2023"));
$html .= formSelect(array("key" => "regelement", "label" => "Mode de reglement : ", "list" => $sortRegl));
$html .= "</div>";
$html .= "</div>";


$html .= "<fieldset>";
$html .= "<div class='center'>";
$html .= "<div class='centerLeft'>";
$html .= formLabel(array("key" => "TRAVAUX COMMISARIAT AUX COMPTES"));
$html .= formLabel(array("key" => "0,00"));
$html .= "</div>";
$html .= "<div class='centerMid'>";
$html .= formLabel(array("key" => "Mission de commissariat aux comptes sur l'exercice clos le 00/00/0000 "));
$html .= formLabel(array("key" => "- Audit des comptes annuels delon règles et principes comptables français "));
$html .= formLabel(array("key" => "- Vérifications spécifiques "));
$html .= formLabel(array("key" => "- Rapports"));
$html .= formLabel(array("key" => "Selon lettre de mission 00/00/0000"));
$html .= "</div>";
$html .= "<div class='centerLeftTwo'>";
$html .= formLabel(array("key" => "TRAVAUX COMPTABLES ET FISCAUX "));
$html .= formLabel(array("key" => "0,00"));
$html .= "</div>";
$html .= "<div class='centerMidTwo'>";
$html .= formLabel(array("key" => "Taux : 0.5%"));
$html .= "</div>";
$html .= "<div class='centerFin'>";
$html .= formLabel(array("key" => ""));
$html .= formLabel(array("key" => "H.T : 0000,00"));
$html .= "</div>";
$html .= "<div class='centerFin'>";
$html .= formLabel(array("key" => ""));
$html .= formLabel(array("key" => "T.T.C. = 0000,00"));
$html .= "</div>";
$html .= "<div class='centerFin'>";
$html .= formLabel(array("key" => ""));
$html .= formLabel(array("key" => "Net à payer = 0000,00"));
$html .= "</div>";

$html .= "</div>";
$html .= "</fieldset>";


echo html(array("user" => $user, "main" => $html, "title" => "", "script" => "validation_recap", "type" => "filter", "filter" => $filter));

die();
?>
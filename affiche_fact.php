<?php
require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
 $cookie = cookieInit();


$sortList = array();
array_push($sortList, array("code" => "year", "txt" => "2021/05/08", "title" => "year"));
array_push($sortList, array("code" => "year", "txt" => "2023/08/15", "title" => "year"));
array_push($sortList, array("code" => "year", "txt" => "2023/08/01", "title" => "year"));
array_push($sortList, array("code" => "year", "txt" => "2023/07/18", "title" => "year"));
array_push($sortList, array("code" => "year", "txt" => "2022/02/25", "title" => "year"));
array_push($sortList, array("code" => "year", "txt" => "2022/05/30", "title" => "year"));

$sortNull = array("code" => "year", "txt" => "2023");

$k = isset($cookie["exercice"]["sortCol"]) ? array_search($cookie["exercice"]["sortCol"], array_column($sortList, "code")) : false;
if ($k === false) {
    $sortSelected = $sortNull;
} else {
    $k2 = array_search($sortList[$k]["parent"], array_column($sortList, "code"));
    $sortSelected = array("code" => $sortList[$k]["code"], "txt" => (($k2 === false) ? "" : ($sortList[$k2]["txt"] . " > ")) . $sortList[$k]["txt"], "title" => "Trier par : " . $sortList[$k]["title"]);
}
////////////////////////////////////////////
$sortlistcity = array();
array_push($sortlistcity, array("code" => "city", "txt" => "Tanger", "title" => "Tanger"));
array_push($sortlistcity, array("code" => "city", "txt" => "Paris", "title" => "Paris"));
array_push($sortlistcity, array("code" => "city", "txt" => "Fes", "title" => "Fes"));


$sortNullcity = array("code" => "city", "txt" => "Toulouse");

$k = isset($cookie["site"]["sortCol"]) ? array_search($cookie["site"]["sortCol"], array_column($sortlistcity, "code")) : false;
if ($k === false) {
    $sortSelectedcity = $sortNullcity;
} else {
    $k2 = array_search($sortlistcity[$k]["parent"], array_column($sortlistcity, "code"));
    $sortSelectedcity = array("code" => $sortlistcity[$k]["code"], "txt" => (($k2 === false) ? "" : ($sortlistcity[$k2]["txt"] . " > ")) . $sortlistcity[$k]["txt"], "title" => "Trier par : " . $sortlistcity[$k]["title"]);
}
//////////////////////////////////////////////
$sortSelecttable = array();
array_push($sortSelecttable, array("code" => "table", "txt" => "Travaux Sociaux", "title" => "Travaux Sociaux"));
array_push($sortSelecttable, array("code" => "table", "txt" => "Travaux Conseil", "title" => "Travaux Conseil"));
array_push($sortSelecttable, array("code" => "table", "txt" => "Travaux Juridiques", "title" => "Travaux Juridiques"));
array_push($sortSelecttable, array("code" => "table", "txt" => "Abonnements", "title" => "Abonnements"));


$sortNulltable = array("code" => "table", "txt" => "Travaux Comptable et fiscaux");

$k = isset($cookie["table"]["sortCol"]) ? array_search($cookie["table"]["sortCol"], array_column($sortSelecttable, "code")) : false;
if ($k === false) {
    $sortSelectedtable = $sortNulltable;
} else {
    $k2 = array_search($sortSelecttable[$k]["parent"], array_column($sortSelecttable, "code"));
    $sortSelectedtable = array("code" => $sortSelecttable[$k]["code"], "txt" => (($k2 === false) ? "" : ($sortSelecttable[$k2]["txt"] . " > ")) . $sortSelecttable[$k]["txt"], "title" => "Trier par : " . $sortSelecttable[$k]["title"]);
}

$bool = array();
array_push($bool, array("code" => "oui", "txt" => "Oui", "value" => ($cookie["index"]["sortDir"] == "oui")));
array_push($bool, array("code" => "non", "txt" => "Non", "value" => ($cookie["index"]["sortDir"] == "non")));


$html = "<div class='top'>";
$html .= "<div class='first-line'>";
$html .= formBtn(array("key" => "envoyer-valid", "txt" => "Envoyer â la validation"));
$html .= formBtn(array("key" => "inserer-ligne", "txt" => "Inserer nouvelles lignes"));
$html .= formBtn(array("key" => "enregistre-fac", "txt" => "Enregistrer cette facture sans envoyer"));
$html .= formBtn(array("key" => "supprimer-fac", "txt" => "Supprimer cette facture"));
$html .= formBtn(array("key" => "archiver-fac", "txt" => "Archiver la facture"));
$html .= formBtn(array("key" => "visualisation-fac", "txt" => "Visualisation de la facture", "href" => "visualisation.php"));
$html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier", "href" => "synthese.php"));
$html .= formBtn(array("key" => "modele-fac", "txt" => "Modèle facture autre dossier", "href" => "recup_model.php"));
$html .= formBtn(array("key" => "facture-fae", "txt" => "Facture FAE"));
$html .= formBtn(array("key" => "tarifs-soc", "txt" => "Tarifs Social", "href" => "tarifs_social.php"));
$html .= formLabel(array("key" => "Exercice client : "));
$html .= formSelect(array("key" => "selection_facture_list", "selected" => $sortSelected, "list" => $sortList));
$html .= formLabel(array("key" => "Site : "));
$html .= formSelect(array("key" => "selection_facture_list", "selected" => $sortSelectedcity, "list" => $sortlistcity));
$html .= formLabel(array("key" => "Date de la facture : "));
$html .= formBtn(array("key" => "year", "txt" => "28/06/2023", "readonly" => true));
$html .= formLabel(array("key" => "Prèsence d'une lettre de mission : "));
$html .= formCheckbox(array("key" => "bool", "list" => $bool));
$html .= "<div class='table-info'>";
$html .= "<table>";
$html .= "<tbody>";
$html .= "<tr>";
$html .= "<td>MONTANT DE LA FACTURE</td>";
$html .= "<td>H.T</td>";
$html .= "<td>78,50 €</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td class='non-style'></td>";
$html .= "<td>T.T.C</td>";
$html .= "<td>94,20 €</td>";
$html .= "</tr>";
$html .= "</tbody>";
$html .= "</table>";
$html .= "</div>";
$html .= "</div>";
$html .= "</div>";

$html .= "<div class='content'>";
$html .= "<fieldset class='first-field'>";
$html .= "<legend>";
$html .= formSelect(array("key" => "selection_facture_list", "selected" => $sortSelectedtable, "list" => $sortSelecttable));
$html .= "</legend>";
$html .= "  <div class='legend2'>";
$html .= formLabel(array("key" => "Total Gènèral = 78,50 / Total Facturer = "));
$html .= formInput(array("key" => "total-facture", "type" => "text", "value" => "0,00"));
$html .= "</div>";
$html .= "  <div class='legend3'>";
$html .= formBtn(array("key" => "categorie-remove", "ico" => "trash", "title" => "Supprimer une Catègorie"));
$html .= "</div>";
$html .= "<div class='heart'>";
$html .= "<div class='title'>";
$html .= "<div class='title-content'>";
$html .= formLabel(array("key" => "Titre : "));
$html .= formInput(array("key" => "titre-content", "type" => "text"));
$html .= "</div>";
$html .= "<div class='operation-remove'>";
$html .= formBtn(array("key" => "prestation", "ico" => "plus", "href" => "resultat.php"));
$html .= formBtn(array("key" => "operation", "ico" => "trash"));
$html .= "</div>";
$html .= "</div>";
$html .= "<table>";
$html .= "<tr>";
$html .= "<th class='operation'>" . formBtn(array("key" => "action", "ico" => "arrow-up"));
"</th>";
$html .= "<th>Date</th>";
$html .= "<th>Collab</th>";
$html .= "<th>Prest</th>";
$html .= "<th class='total' colspan='5'><div>" . formLabel(array("key" => "Total Gènèral = 78,50 / Total Facturer = ")) . formInput(array("key" => "total-facture", "type" => "text", "value" => "0,00")) . "</div></th>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td>" . formBtn(array("key" => "operation-delete", "ico" => "xmark"));
"</td>";
$html .= "<td>01/05/2021</td>";
$html .= "<td>JBC</td>";
$html .= "<td>291</td>";
$html .= "<td class='titre'>Saisie -Transfert bk \"512200\" CA du13-05-2023au19-05-2023 + R?®vision des comptes + affectation des comptes + apurement compte d\'attente + lettrage + contr??le de solde.</td>";
$html .= "<td>" . formBtn(array("key" => "operation", "ico" => "arrow-down"));
"</td>";
$html .= "<td>0,00</td>";
$html .= "<td>0,25</td>";
$html .= "<td>64,00</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td>" . formBtn(array("key" => "operation-delete", "ico" => "xmark"));
"</td>";
$html .= "<td>01/05/2022</td>";
$html .= "<td>DRC</td>";
$html .= "<td>100</td>";
$html .= "<td class='titre'>Saisie -Transfert bk \"512200\" CA du13-05-2023au19-05-2023 + R?®vision des comptes + affectation des comptes.</td>";
$html .= "<td>" . formBtn(array("key" => "operation", "ico" => "arrow-down"));
"</td>";
$html .= "<td>0,00</td>";
$html .= "<td>0,25</td>";
$html .= "<td>84,00</td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td>" . formBtn(array("key" => "operation-delete", "ico" => "xmark"));
"</td>";
$html .= "<td>01/05/2027</td>";
$html .= "<td>DRC</td>";
$html .= "<td>100</td>";
$html .= "<td class='titre'>Saisie -Transfert bk \"512200\" CA du13-05-2023au19-05-2023 + R?®vision des comptes + affectation des comptes.</td>";
$html .= "<td>" . formBtn(array("key" => "operation", "ico" => "arrow-down"));
"</td>";
$html .= "<td>0,00</td>";
$html .= "<td>0,25</td>";
$html .= "<td>84,00</td>";
$html .= "</tr>";

$html .= "<tr class='area'>";
$html .= "<td colspan='9'><div>" . formTextarea(array("key" => "textarea-container")) . formBtn(array("key" => "comment-add", "ico" => "comment-medical"));
"</div></td>";
$html .= "</tr>";
$html .= "</table>";


$html .= "<div class='title hide see'>";
$html .= "<div class='title-content'>";
$html .= formLabel(array("key" => "Titre : "));
$html .= formInput(array("key" => "titre-content", "type" => "text"));
$html .= "</div>";
$html .= "<div class='operation-remove'>";
$html .= formBtn(array("key" => "prestation", "ico" => "plus", "href" => "resultat.php"));
$html .= formBtn(array("key" => "operation", "ico" => "trash"));
$html .= "</div>";
$html .= "</div>";
$html .= "<table class='show'>";
$html .= "<tr>";
$html .= "<th class='operation'>" . formBtn(array("key" => "action", "ico" => "arrow-up"));
"</th>";
$html .= "<th>Date</th>";
$html .= "<th>Collab</th>";
$html .= "<th>Prest</th>";
$html .= "<th class='total' colspan='5'><div>" . formLabel(array("key" => "Total Gènèral = 78,50 / Total Facturer = ")) . formInput(array("key" => "total-facture", "type" => "text", "value" => "0,00")) . "</div></th>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td class='titre'></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";
$html .= "<tr class='area'>";
$html .= "<td colspan='9'><div>" . formTextarea(array("key" => "textarea-container")) . formBtn(array("key" => "comment-add", "ico" => "comment-medical"));
"</div></td>";
$html .= "</tr>";
$html .= "</table>";
$html .= "</div>";
$html .= "<div class='btn-out'>";
$html .= formBtn(array("key" => "categorie-add", "ico" => "plus", "title" => "Ajouter une Catègorie"));
$html .= "</div>";
$html .= "</fieldset>";

$html .= "<fieldset style='margin-top:20px;' class='second-field'>";
$html .= "<legend>";
$html .= formSelect(array("key" => "selection_facture_list", "selected" => $sortSelectedtable, "list" => $sortSelecttable));
$html .= "</legend>";
$html .= " <div class='legend2'>";
$html .= formLabel(array("key" => "Total Gènèral = 78,50 / Total Facturer = "));
$html .= formInput(array("key" => "total-facture", "type" => "text", "value" => "0,00"));
$html .= "</div>";
$html .= "  <div class='legend3'>";
$html .= formBtn(array("key" => "categorie-remove", "ico" => "trash", "title" => "Supprimer une Catègorie"));
$html .= "</div>";
$html .= "<div class='heart'>";
$html .= "<div class='title'>";
$html .= "<div class='title-content'>";
$html .= formLabel(array("key" => "Titre : "));
$html .= formInput(array("key" => "titre-content", "type" => "text"));
$html .= "</div>";
$html .= "<div class='operation-remove'>";
$html .= formBtn(array("key" => "prestation", "ico" => "plus", "href" => "resultat.php"));
$html .= formBtn(array("key" => "operation", "ico" => "trash"));
$html .= "</div>";
$html .= "</div>";
$html .= "<table>";
$html .= "<tr>";
$html .= "<th class='operation'>" . formBtn(array("key" => "action", "ico" => "arrow-up"));
"</th>";
$html .= "<th>Date</th>";
$html .= "<th>Collab</th>";
$html .= "<th>Prest</th>";
$html .= "<th class='total' colspan='5'><div>" . formLabel(array("key" => "Total Gènèral = 78,50 / Total Facturer = ")) . formInput(array("key" => "total-facture", "type" => "text", "value" => "0,00")) . "</div></th>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td class='titre'></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";
$html .= "<tr class='area'>";
$html .= "<td colspan='9'><div>" . formTextarea(array("key" => "textarea-container")) . formBtn(array("key" => "comment-add", "ico" => "comment-medical"));
"</div></td>";
$html .= "</tr>";
$html .= "</table>";


$html .= "<div class='title hide see'>";
$html .= "<div class='title-content'>";
$html .= formLabel(array("key" => "Titre : "));
$html .= formInput(array("key" => "titre-content", "type" => "text"));
$html .= "</div>";
$html .= "<div class='operation-remove'>";
$html .= formBtn(array("key" => "prestation", "ico" => "plus", "href" => "resultat.php"));
$html .= formBtn(array("key" => "operation", "ico" => "trash"));
$html .= "</div>";
$html .= "</div>";
$html .= "<table class='show'>";
$html .= "<tr>";
$html .= "<th class='operation'>" . formBtn(array("key" => "action", "ico" => "arrow-up"));
"</th>";
$html .= "<th class='date'>Date</th>";
$html .= "<th>Collab</th>";
$html .= "<th>Prest</th>";
$html .= "<th class='total' colspan='5'><div>" . formLabel(array("key" => "Total Gènèral = 78,50 / Total Facturer = ")) . formInput(array("key" => "total-facture", "type" => "text", "value" => "0,00")) . "</div></th>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td class='titre'></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "<td></td>";
$html .= "</tr>";
$html .= "<tr class='area'>";
$html .= "<td colspan='9'><div>" . formTextarea(array("key" => "textarea-container")) . formBtn(array("key" => "comment-add", "ico" => "comment-medical"));
"</div></td>";
$html .= "</tr>";
$html .= "</table>";
$html .= "</div>";
$html .= "<div class='btn-out'>";
$html .= formBtn(array("key" => "categorie-add", "ico" => "plus", "title" => "Ajouter une Catègorie"));
$html .= "</div>";
$html .= "</fieldset>";

$html .= "</div>";
$html .= "<div class='btn-last'>";
$html .= formBtn(array("key" => "ajoute-catgorie", "ico" => "plus", "title" => "Ajouter une nouvelle catègorie"));
$html .= formLabel(array("key" => "Ajouter une nouvelle catègorie"));
$html .= "</div>";


$cont = html(array_merge($opts, array("cont" => $html, "script" => "affiche_fact", "adr"=>false)));
die($cont);
?>

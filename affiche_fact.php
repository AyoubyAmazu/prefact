<?php
require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "fact", "prefact"))));
$factId = ((isset($_GET["f"]))? cryptDel($_GET["f"]) : false);
$getD = ((isset($_GET["d"]))? cryptDel($_GET["d"]) : false);
if($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
if($factId == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));

handleRequest();
$cookie = cookieInit();

// All queries of this page
$exerciceSql = "SELECT distinct exo from facture order by exo Asc";
$siteSql = "SELECT * from site order by Site Asc";
$travSql = "SELECT * FROM prefact.cat;";
// detail queries
$fact_cat = "SELECT * FROM facture_cat WHERE facture_id = $factId ORDER BY id ASC";
$fact_det = "SELECT * FROM facture_det WHERE fact_cat_id = ?";
$delete_detail = "DELETE FROM facture_det WHERE id = ?";
$prestSql = "SELECT * from facture_temps where fact_det_id = ?";
$select_tmps = "SELECT * FROM temps WHERE TEMPS_ID = ?";

$delete_prest_by_detail = "DELETE FROM prestation WHERE IdDetail = ?";
$delete_prest_by_id = "DELETE FROM prestation WHERE IdPrest = :idPrest";

/**
 * composes the page
 */
function composePage(): string
{

	$html = " ";
	$html .= "<div class='top'>";
	$html .= "<div class='first-line'>";
	$html .= composeFilters();
	$html .= composeHead();

	$html .= "</div>";
	$html .= "</div>";
	$html .= "<div class='content'>";
	$html .= composeFactCat();
	$html .= "</div>";
	$html .= "<div class='btn-last'>";
	$html .= formBtn(array("key" => "ajoute-catgorie", "ico" => "plus", "title" => "Ajouter une nouvelle catègorie"));
	$html .= formLabel(array("key" => "Ajouter une nouvelle catègorie"));
	$html .= "</div>";
	return $html;
}
/**
 * composes the head html items of the page
 */
function composeHead()
{
	$html = "";
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
	return $html;
}
/**
 * composes html filtres of the page
 */
function composeFilters()
{
	global $cookie;
	$siteList = fetchSitesList();
	// $exerList = fetchExerciceList();
	$html = "";

	$html .= formBtn(array("key" => "envoyer-valid", "txt" => "Envoyer â la validation", "href"=>"fact_a_valider.php?d=".$_GET["d"]));
	$html .= formBtn(array("key" => "inserer-ligne", "txt" => "Inserer nouvelles lignes", "href"=>"resultat.php?d=".$_GET["d"]));
	$html .= formBtn(array("key" => "supprimer-fac", "txt" => "Supprimer cette facture", "href"=>"resultat.php?d=".$_GET["d"]));
	$html .= formBtn(array("key" => "archiver-fac", "txt" => "Archiver la facture"));
	$html .= formBtn(array("key" => "visualisation-fac", "txt" => "Visualisation de la facture", "href" => "visualisation.php"));
	$html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier", "href" => "recap.php?d=".$_GET["d"]));
	$html .= formBtn(array("key" => "modele-fac", "txt" => "Modèle facture autre dossier", "href" => "recup_model.php"));
	$html .= formBtn(array("key" => "facture-fae", "txt" => "Facture FAE"));
	$html .= formBtn(array("key" => "tarifs-soc", "txt" => "Tarifs Social", "href" => "tarifs_social.php"));
	$html .= formLabel(array("key" => "Exercice client : "));
	// $html .= formSelect(array("key" => "selection_facture_list", "selected" => $exerList["cookie"], "list" => $exerList["list"]));
	$html .= formLabel(array("key" => "Site : "));
	$html .= formSelect(array("key" => "selection_facture_list", "selected" => $siteList["cookie"], "list" => $siteList['list']));
	$html .= formLabel(array("key" => "Date de la facture : "));
	$html .= formBtn(array("key" => "year", "txt" => "28/06/2023", "readonly" => true));
	$html .= formLabel(array("key" => "Prèsence d'une lettre de mission : "));
	$html .= formCheckbox(
		array(
			"key" => "bool",
			"list" => array(
				array("code" => "oui", "txt" => "Oui", "value" => ($cookie["index"]["sortDir"] == "oui")),
				array("code" => "non", "txt" => "Non", "value" => ($cookie["index"]["sortDir"] == "non"))
			)
		)
	);
	return $html;
}


/**
 * Summary of fetchInvoiceDetails
 * @return void
 */
function composeFactCat(): string
{
	global $fact_cat;
	$fact_cat = dbSelect($fact_cat, array("db" => "prefact"));
	$html = "";

	foreach ($fact_cat as $cat) {
		// $travDrvList = fetchTravDrvList($cat["Description"]);

		$html .= "<fieldset class='first-field' id=".$cat["id"]." >";
		$html .= "<legend>";
		// $html .= formSelect(array("key" => "selection_facture_list", "selected" => $travDrvList["cookie"], "list" => $travDrvList["list"]));
		$html .= "</legend>";
		$html .= "  <div class='legend2'>";
		$html .= formLabel(array("key" => "Total Gènèral = ".$cat["amount"]." / Total Facturer = "));
		$html .= formInput(array("key" => "total-facture", "type" => "text", "value" => $cat["amount"]));
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

		$html .= composeFactDet($cat["id"]);

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
		$html .= "</th>";
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
		$html .= "</div></td>";
		$html .= "</tr>";
		$html .= "</table>";
		$html .= "</div>";
		$html .= "<div class='btn-out'>";
		$html .= formBtn(array("key" => "categorie-add", "ico" => "plus", "title" => "Ajouter une Catègorie"));
		$html .= "</div>";
		$html .= "</fieldset>";
	}


	return $html;
}
// ! here
function composeFactDet($idTrav): string
{
	global $fact_det;

	$select = str_replace("?", $idTrav, $fact_det);
	$result = dbSelect($select, array("db" => "prefact"));
	$html = "";
	foreach ($result as $detail) {
		$html .= "<table detid=".$detail["id"].">";
		$html .= "<tr>";
		$html .= "<th class='operation'>" . formBtn(array("key" => "action", "ico" => "arrow-up"));
		$html .= "</th>";
		$html .= "<th>Date</th>";
		$html .= "<th>Collab</th>";
		$html .= "<th>Prest</th>";
		$html .= "<th class='total' colspan='5'><div>" . formLabel(array("key" => "Total Gènèral = " . $detail["amount"] . " / Total Facturer = ")) . formInput(array("key" => "total-facture", "type" => "text", "value" => $detail["amount"])) . "</div></th>";
		$html .= "</tr>";
		$html .= composePrest($detail["id"]);
		$html .= "<tr class='area'>";
		$html .= "<td colspan='9'><div>" . formTextarea(array("key" => "textarea-container")) . formBtn(array("key" => "comment-add", "ico" => "comment-medical"));
		$html .= "</div></td>";
		$html .= "</tr>";
		$html .= "</table>";
	}

	return $html;
}

/**
 * Show rows of
 * @return void
 */
function composePrest($factdet_id): string
{
	global $prestSql, $select_tmps;
	$select = str_replace("?", $factdet_id, $prestSql);
	$result = dbSelect($select, array("db" => "prefact"));
	$html = "";

	foreach ($result as $fact_tmp) {
		$_select = str_replace("?", $fact_tmp["temps_id"], $select_tmps);
		$temp = dbSelect($_select, array("db"=>"dia"))[0]; 
		$html .= "<tr id=".$fact_tmp["id"].">";
		$html .= "<td>" . formBtn(array("key" => "operation-delete", "ico" => "xmark"));
		$html .= "</td>";
		$html .= "<td>" . date("m/d/Y", strtotime($temp['TEMPS_DATE'])) . "</td>";
		$html .= "<td>" . $temp["COL_CODE"] . "</td>";
		$html .= "<td>" . $temp["PREST_CODE"] . "</td>";
		$html .= "<td class='titre'>" . $temp["TEMPS_MEMO"] . "</td>";
		$html .= "<td>" . formBtn(array("key" => "operation", "ico" => "arrow-down")) . "</td>";
		$html .= "<td>" . $temp["TEMPS_M_QTE"] . "</td>";
		$html .= "<td>" . $temp["TEMPS_DUREE"] . "</td>";
		$html .= "<td>" . $temp["TEMPS_M_PV"] . "</td>";
		$html .= "</tr>";
	}
	return $html;
}

/**
 * fetch from db travau drivers and selects from cookie if non return a defult item
 * @param string $travaux default travaux
 * @return array
 */
function fetchTravDrvList(string $travaux): array
{
	global $travSql, $cookie;

	$result = dbSelect($travSql, array("db" => "fact"));
	$list = array();
	// # IdDivers, Description, CodePresta, Classer
	foreach ($result as $trav) array_push($list, array("code" => $trav["Description"], "txt" => $trav["Description"], "title" => $trav["Description"]));
	if (!($travaux === "" | $travaux === "-")) return array("list" => $list, "cookie" => array("code" => "travaux", "txt" => $travaux));
	$sortNulltable = array("code" => "table", "txt" => "Travaux Comptable et fiscaux");

	$k = isset($cookie["table"]["sortCol"]) ? array_search($cookie["table"]["sortCol"], array_column($list, "code")) : false;
	if ($k === false) {
		$sortSelectedtable = $sortNulltable;
	} else {
		$k2 = array_search($list[$k]["parent"], array_column($list, "code"));
		$sortSelectedtable = array("code" => $list[$k]["code"], "txt" => (($k2 === false) ? "" : ($list[$k2]["txt"] . " > ")) . $list[$k]["txt"], "title" => "Trier par : " . $list[$k]["title"]);
	}
	return array("list" => $list, "cookie" => $sortSelectedtable);
}

/**
 * fetch from db sites or and selects from cookie if non return a default item
 * @return array
 */
function fetchSitesList(): array
{
	global $siteSql, $cookie;
	$siteResult = dbSelect($siteSql, array("db" => "fact"));
	$siteList = array();
	foreach ($siteResult as $site) array_push($siteList, array("code" => $site["Site"], "txt" => $site["Site"], "title" => $site["Site"]));

	$emptySite = array("code" => "city", "txt" => "Toulouse");

	$siteCookie = isset($cookie["site"]["sortCol"]) ? array_search($cookie["site"]["sortCol"], array_column($siteList, "code")) : false;
	if ($siteCookie === false) {
		$selectedSite = $emptySite;
	} else {
		$k2 = array_search($siteList[$siteCookie]["parent"], array_column($siteList, "code"));
		$selectedSite = array("code" => $siteList[$siteCookie]["code"], "txt" => (($k2 === false) ? "" : ($siteList[$k2]["txt"] . " > ")) . $siteList[$siteCookie]["txt"], "title" => "Trier par : " . $siteList[$siteCookie]["title"]);
	}
	return array("list" => $siteList, "cookie" => $selectedSite);
}
/**
 * fetch from db exercices or and selects from cookie if non return a default item
 * @return array
 */
function fetchExerciceList(): array
{
	global $exerciceSql, $cookie;
	// exercice selects data
	$result = dbSelect($exerciceSql, array("db" => "fact"));
	$exerciceList = array();
	foreach ($result as $ex) if ($ex[0] == 0) continue;
	else array_push($exerciceList, array("code" => $ex[0], "txt" => $ex[0], "title" => $ex[0]));

	$sortNull = array("code" => "year", "txt" => "2023");

	$k = isset($cookie["exercice"]["sortCol"]) ? array_search($cookie["exercice"]["sortCol"], array_column($exerciceList, "code")) : false;
	if ($k === false) {
		$sortSelected = $sortNull;
	} else {
		$k2 = array_search($exerciceList[$k]["parent"], haystack: array_column($exerciceList, "code"));
		$sortSelected = array("code" => $exerciceList[$k]["code"], "txt" => (($k2 === false) ? "" : ($exerciceList[$k2]["txt"] . " > ")) . $exerciceList[$k]["txt"], "title" => "Trier par : " . $exerciceList[$k]["title"]);
	}
	return array("list" => $exerciceList, "cookie" => $sortSelected);
}

/**
 * Handle ajax post requests
 * @return void
 */
function handleRequest()
{
	if(isset($_POST["delete_prest"]))deletePrestById($_POST["delete_prest"]);
	if(isset($_POST["delete_detail"]))deleteDetail($_POST["delete_detail"]);

}
/**
 * delete prestation by id
 * @param int $id
 * @return never
 */
function deletePrestById(int $id): never
{
	global $delete_prest_by_id;
	$delete_prest_by_id = str_replace(":idPrest", $id, $delete_prest_by_id);
	dbExec($delete_prest_by_id, opts: array("db"=>"fact"));
	die(json_encode(['success' => 200]));
}
/**
 * delete prestation whrever prest have detail id
 * @param int $detailId
 * @return never
 */
function deletePrestByDetail(int $detailId): void
{
	global $delete_prest_by_detail;
	$delete_prest_by_detail = str_replace("?", $detailId, $delete_prest_by_detail);
	dbExec($delete_prest_by_detail, opts: array("db"=>"fact"));
	die(json_encode(['success' => 200]));
}
/**
 * delete details by id
 * @param int $id
 * @return never
 */
function deleteDetail(int $id): never
{
	global $delete_detail;
	$delete_detail = str_replace("?", $id, $delete_detail);
	deletePrestByDetail($id);
	dbExec($delete_detail, opts: array("db"=>"fact"));
	die(json_encode(['success' => 200]));
}

if (isset($_POST["facture_id"])){
	$id = $_POST["facture_id"];
	$sql = "UPDATE z_fact.factures SET EnCours = 3 WHERE IdFact = '$id'";
	dbExec($sql, opts: array("db"=>"fact"));
	die(json_encode(['code'=>200]));
}

$cont = html(array_merge($opts, array("cont" => composePage(), "script" => "affiche_fact", "adr" => $getD)));

die($cont);

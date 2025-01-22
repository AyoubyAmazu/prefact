<?php
require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "fact", "prefact"))));
$factId = ((isset($_GET["f"])) ? cryptDel($_GET["f"]) : false);
$getD = ((isset($_GET["d"])) ? cryptDel($_GET["d"]) : false);
if ($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
if ($factId == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
$cookie = cookieInit();

// All queries of this page
$exerciceSql = "SELECT distinct exo from facture order by exo Asc";
$siteSql = "SELECT * from site order by Site Asc";
$travSql = "SELECT * FROM prefact.cat;";
// detail queries
$fact_cat = "SELECT * FROM facture_cat  WHERE facture_id = $factId ORDER BY id ASC";
$select_cat = "SELECT * FROM cat";
$fact_det = "SELECT * FROM facture_det WHERE fact_cat_id = ?";
$delete_detail = "DELETE FROM facture_det WHERE id = ?";
$prestSql = "SELECT * from facture_temps where fact_det_id = ?";
$select_tmps = "SELECT * FROM temps WHERE TEMPS_ID = ?";

$delete_prest_by_detail = "DELETE FROM prestation WHERE IdDetail = ?";
$deleteTemp = "DELETE FROM facture_temps WHERE id = ?";
handleRequest();

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
	global $cookie, $getD;
	$siteList = fetchSitesList();
	$exerList = fetchExerciceList();
	$html = "";
	$html .= formBtn(array("key" => "envoyer-valid", "txt" => "Envoyer â la validation"));
	$html .= formBtn(array("key" => "inserer-ligne", "txt" => "Inserer nouvelles lignes", "href" => "resultat.php?d=" . $_GET["d"]));
	$html .= formBtn(array("key" => "supprimer-fac", "txt" => "Supprimer cette facture"));
	$html .= formBtn(array("key" => "archiver-fac", "txt" => "Archiver la facture"));
	$html .= formBtn(array("key" => "visualisation-fac", "txt" => "Visualisation de la facture", "href" => "visualisation.php?d=".$_GET["d"]));
	$html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier", "href" => "recap.php?d=" . $_GET["d"]));
	$html .= formBtn(array("key" => "modele-fac", "txt" => "Modèle facture autre dossier", "href" => "recup_model.php?d=".$_GET["d"]));
	$html .= formBtn(array("key" => "facture-fae", "txt" => "Facture FAE"));
	$html .= formBtn(array("key" => "tarifs-soc", "txt" => "Tarifs Social", "href" => "tarifs_social.php?d=".$_GET["d"]));
	// $html .= formLabel(array("key" => "Exercice client : "));
	$html .= formSelect(array("key" => "selection_facture_list", "selected" => $exerList["cookie"], "list" => $exerList["list"], "label"=>"Exercice client : "));
	// $html .= formLabel(array("key" => "Site : "));
	$html .= formSelect(array("key" => "selection_facture_list", "selected" => $siteList["cookie"], "list" => $siteList['list'], "label"=>"Site:"));
	$html .= "<div>";
	$html .= formLabel(array("key" => "Date de la facture : "));
	$html .= formBtn(array("key" => "year", "txt" => "28/06/2023"));
	$html .= "</div>";
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
		$html .= displayField($cat, true);
	}
	return $html;
}
function composeFactDet($idTrav): string
{
	global $fact_det;

	$select = str_replace("?", $idTrav, $fact_det);
	$result = dbSelect($select, array("db" => "prefact"));
	$html = "";
	foreach ($result as $detail) {
		$html .= displayDet(true, $detail);
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
		$temp = dbSelect($_select, array("db" => "dia"))[0];
		$html .= "<tr id=" . cryptSave($fact_tmp["id"]) . ">";
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
 * Creates html for categorie fieldset
 * @param int $cat
 * @param bool $composeDet
 * @return string
 */
function displayField($cat, $composeDet = false)
{
	$html = "";
	$travDrvList = fetchTravDrvList($cat["cat_id"]);

	$html .= "<fieldset id=" . cryptSave($cat["id"]) . " >";
	$html .= "<legend>";
	$html .= formSelect(array("key" => "selection_facture_list", "selected" => $travDrvList["cookie"], "list" => $travDrvList["list"]));
	$html .= "</legend>";
	$html .= "  <div class='legend2'>";
	$html .= formLabel(array("key" => "Total Gènèral = " . $cat["amount"] . " / Total Facturer = "));
	$html .= formInput(array("key" => "total-facture", "type" => "text", "value" => $cat["amount"]));
	$html .= "</div>";
	$html .= "  <div class='legend3'>";
	$html .= formBtn(array("key" => "categorie-remove", "ico" => "trash", "title" => "Supprimer une Catègorie"));
	$html .= "</div>";
	if ($composeDet == true) $html .= composeFactDet($cat["id"]);
	$html .= "<div class='btn-out'>";
	$html .= formBtn(array("key" => "categorie-add", "ico" => "plus", "title" => "Ajouter une Catègorie"));
	$html .= "</div>";
	$html .= "</fieldset>";
	return $html;
}
/**
 * Create Detail for a categorie
 * @param bool $composePrest
 * @return string
 */
function displayDet($composeTemp = false, $detail)
{
	global $getD;
	$html = "<div class='heart'>";
		$html .= "<div class='title'>";
		$html .= "<div class='title-content'>";
		$html .= formLabel(array("key" => "Titre : "));
		$html .= formInput(array("key" => "titre-content", "type" => "text"));
		$html .= "</div>";
		$html .= "<div class='operation-remove'>";
		$html .= formBtn(array("key" => "prestation", "ico" => "plus", "href"=>"./resultat.php?d=".$_GET["d"]."&t=".cryptSave($detail["id"])));
		$html .= formBtn(array("key" => "operation", "ico" => "trash"));
		$html .= "</div>";
		$html .= "</div>";
		$html .= "<table id=" . cryptSave($detail["id"]) . ">";
		$html .= "<tr>";
		$html .= "<th class='operation'>" . formBtn(array("key" => "action", "ico" => "arrow-up"));
		$html .= "</th>";
		$html .= "<th>Date</th>";
		$html .= "<th>Collab</th>";
		$html .= "<th>Prest</th>";
		$html .= "<th class='total' colspan='5'><div>" . formLabel(array("key" => "Total Gènèral = ".$detail["amount"]." / Total Facturer = ")) . formInput(array("key" => "total-facture", "type" => "text", "value" => $detail["amount"])) . "</div></th>";
		$html .= "</tr>";
		if($composeTemp == true) $html .= composePrest($detail["id"]);
		$html .= "<tr class='area'>";
		$html .= "<td colspan='9'><div>" . formTextarea(array("key" => "textarea-container")) . formBtn(array("key" => "comment-add", "ico" => "comment-medical"));
		$html .= "</div></td>";
		$html .= "</tr>";
		$html .= "</table>";
		$html .= "</div>";
		return $html;
}
/**
 * fetch from db travau drivers and selects from cookie if non return a defult item
 * @param int $cat_id default travaux
 * @return array
 */
function fetchTravDrvList(int $cat_id = null): array
{
	global $select_cat, $cookie;
	$result = dbSelect($select_cat, array("db" => "prefact"));
	$selected = null;
	$list = array();

	foreach ($result as $trav) {
		array_push($list, array("code" => $trav["id"], "txt" => $trav["nom"], "title" => $trav["nom"] , ));
		if ($cat_id != null && $trav["id"] == $cat_id) $selected =  array("code" => $trav["id"], "txt" => $trav["nom"] , );
	}
	$sortNulltable = array("code" => "table", "txt" => "Travaux Comptable et fiscaux");

	$k = isset($cookie["table"]["sortCol"]) ? array_search($cookie["table"]["sortCol"], array_column($list, "code")) : false;
	if ($k === false && $selected == null) {
		$selected = $sortNulltable;
	} else if ($k !== false && $selected == null) {
		$k2 = array_search($list[$k], array_column($list, "code"));
		$selected = array("code" => $list[$k]["code"], "txt" => (($k2 === false) ? "" : ($list[$k2]["txt"] . " > ")) . $list[$k]["txt"], "title" => "Trier par : " . $list[$k]["title"]);
	}
	return array("list" => $list, "cookie" => $selected);
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
	$result = dbSelect($exerciceSql, array("db" => "prefact"));
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
 * Handle ajax requests
 * @return void
 */
function handleRequest()
{
	if (isset($_POST["validate"])) validate();
	if (isset($_POST["create_cat"])) createCat();
	if (isset($_POST["delete_cat"])) deleteCat(cryptDel($_POST["delete_cat"]));
	if (isset($_POST["create_det"])) createDet(cryptDel($_POST["cat_id"]));
	if (isset($_POST["delete_det"])) deleteDet(cryptDel($_POST["det_id"]));
	if (isset($_POST["archiverFact"])) archiverFact();
	if (isset($_POST["fact_fae"])) factFae();
	if (isset($_POST["delete_prest"])) deletePrestById(cryptDel($_POST["delete_prest"]));
	if (isset($_POST["delete_detail"])) deleteDetail($_POST["delete_detail"]);
	if (isset($_POST["delete_fact"])) deleteFact();
	if (isset($_POST["change_travaux"])) change_travaux();
}
/**
 * Validate facture
 * @return never
 */
function validate()
{
	global $getD, $factId, $opts;
	$rd = dbSelect("SELECT rd FROM adr WHERE code = '$getD'", array("db"=>"prefact"))[0]["rd"];
	if($rd == $opts["user"]["id"]){
		dbExec("UPDATE `prefact`.`facture` SET `status` = '3' WHERE (`id` = '$factId')", array("db"=>"prefact"));
	} else{ 
		dbExec("UPDATE `prefact`.`facture` SET `status` = '2' WHERE (`id` = '$factId')", array("db"=>"prefact"));
	}
	die(json_encode(["code"=>200]));
}
/**
 * Deletes the current facture
 * @return never
 */
function deleteFact()
{
	global $fact_cat, $factId;
	$factCatsIds = array_column(dbSelect($fact_cat, array("db" => "prefact")), "id");
	if (sizeof($factCatsIds) > 0) {
		$factDetails = array_column(dbSelect("SELECT id FROM facture_det WHERE fact_cat_id in (" . implode(", ", $factCatsIds) . ")", array("db" => "prefact")), "id");
		if (sizeof($factDetails) > 0) {
			// delete temps
			dbExec("DELETE FROM `facture_temps` WHERE fact_det_id in (" . implode(", ", $factDetails) . ");", array("db" => "prefact"));
			// delete fact details
			dbExec("DELETE FROM `facture_det` WHERE fact_cat_id in (" . implode(", ", $factCatsIds) . ");", array("db" => "prefact"));
		}
		// delete fact categories
		dbExec("DELETE FROM `facture_cat` WHERE facture_id = $factId;", array("db" => "prefact"));
	}
	// delete facture
	dbExec("DELETE FROM `facture` WHERE id = $factId;", array("db" => "prefact"));
	die(json_encode(['code' => 200]));
}
/**
 * creates a category field for this fact
 * @return void
 */
function createCat()
{
	global $factId;
	$factCatId = dbSelect("SELECT max(id) as id from facture_cat", array("db" => "prefact"));
	if (empty($factCatId)) $factCatId = 1;
	else $factCatId = $factCatId[0]["id"] + 1;
	dbExec("insert into facture_cat (id,facture_id, cat_id , amount) values ($factCatId,$factId, 1 , 0)", array("db" => "prefact"));
	$cat = dbSelect("SELECT * FROM facture_cat WHERE id = $factCatId");
	$html = displayField($cat[0]);
	die(json_encode(["code"=>200, "html"=>$html]));
}
/**
 * Deletes a category
 * @return void
 */
function deleteCat($catId)
{
	$details = array_column(dbSelect("SELECT id FROM facture_det WHERE fact_cat_id = $catId"), "id");
	if(!empty($details)){
		dbExec("DELETE FROM `prefact`.`facture_temps` WHERE fact_det_id in (".implode(", ",$details).");", array("db"=>"prefact"));
	}
	dbExec("DELETE FROM facture_cat WHERE id = $catId", array("db"=>"prefact"));
	die(json_encode(["code"=>200]));
}
function createDet($catId)
{
	$detId = dbSelect("SELECT max(id) as id FROM facture_det");
	if(empty($detId))$detId = 1;
	else $detId = $detId[0]["id"]+1;
	dbExec("INSERT INTO `prefact`.`facture_det` (`id`, fact_cat_id, titre, obs, amount) VALUES ($detId, $catId, '', '', 0.00);");
	die(json_encode(["code"=>200, "html"=>displayDet(false, array("id"=>$detId, "titre"=>"", "obs"=>"", "amount"=>0.00))]));
}
function deleteDet($detId)
{
	dbExec("DELETE from facture_det WHERE id = $detId");
	die(json_encode(["code"=>200]));
}
function archiverFact()
{
	global $factId;
	dbExec("UPDATE `prefact`.`facture` SET `archiver` = '1' WHERE (`id` = $factId);");
	die(json_encode(["code"=>200]));
}
function factFae()
{
	global $factId;
	dbExec("UPDATE `prefact`.`facture` SET `fae` = '1' WHERE (`id` = $factId);");
	die(json_encode(["code"=>200]));
}
/**
 * delete prestation by id
 * @param int $id
 * @return never
 */
function deletePrestById(int $id): never
{
	global $deleteTemp;
	$sql = str_replace("?", $id, $deleteTemp);
	dbExec($sql, opts: array("db" => "prefact"));
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
	dbExec($delete_prest_by_detail, opts: array("db" => "fact"));
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
	dbExec($delete_detail, opts: array("db" => "fact"));
	die(json_encode(['success' => 200]));
}

function change_travaux()
{
	var_dump($_POST["cat_id"]);
	$catId = $_POST["cat_id"];
	$fact_cat = cryptDel($_POST["fact_cat"]);
	$sql = "UPDATE facture_cat SET cat_id = $catId WHERE id = $fact_cat ";
	dbExec($sql, array("db"=>"prefact"));
	die(json_encode(['code' => 200,'txt' => $sql]));
}
$cont = html(array_merge($opts, array("cont" => composePage(), "script" => "affiche_fact", "adr" => $getD)));

die($cont);

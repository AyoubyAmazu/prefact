<?php

require_once("config.php");
session_start();
$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$getD = ((isset($_GET["d"])) ? cryptDel($_GET["d"]) : false);
if ($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));

// dossier id
$id = dbSelect("SELECT `id` FROM `adr` WHERE `code` = '$getD'", array("db" => "prefact"))[0]["id"];
// all queries of this page
$temps_sql = "SELECT * FROM expert_fidsud.temps WHERE ADR_ID =$id AND PREST_CODE LIKE ':p%' AND `LFACT_ID` = 0";
$select_fact = "SELECT * FROM `facture` WHERE `adr_id` =$id AND `status`=1 and archiver = 0  ORDER BY `id` ASC";
$select_cat = "SELECT * FROM prefact.cat;";
$select_non_fact = "SELECT temps_id FROM temps_non_fact";
$select_checked_tmps = "SELECT temps_id from facture_temps WHERE fact_det_id in
(SELECT id FROM facture_det WHERE fact_cat_id in (SELECT id from facture_cat WHERE facture_id in
(SELECT id FROM facture WHERE status in (1, 2, 3))))";

// unavailable temps
$tmps_non_fact = dbSelect($select_non_fact, array("db" => "prefact"));
$tmps_used = dbSelect($select_checked_tmps, array("db" => "prefact"));

if(isset($_POST["temps"])){
    foreach($_POST["temps"] as $temp){
        dbExec("INSERT into facture_temps (fact_det_id , temps_id) values (".cryptDel($_GET["t"]).",".cryptDel($temp).")", array("db"=>"prefact"));
    }
    die(json_encode(["code"=>200]));
}

/**
 * creates pages html
 */
function composePage(): string
{
    global $select_cat, $temps_sql, $tmps_non_fact, $tmps_used;
    $cats = dbSelect($select_cat, array("db" => "prefact"));
    $factsList = factsSelectOptions();

    $html = "<div class='popup facts-check hide'><div>";
    $html .= "<div class='label'>Modifier Segmentation</div>";
    $html .= formCheckbox(array("key" => "col", "list" => $factsList["list"], "code"=>$factsList["selected"]));
    $html .= "<div class='op'>";
    $html .= formBtn(array("key" => "cancel", "txt" => "Annuler"));
    $html .= formBtn(array("key" => "save", "txt" => "Continue"));
    $html .= "</div>";
    $html .= "</div></div>";
    $html .= composeHead();
    foreach ($cats as $cat) {
        $sql = str_replace(":p", $cat["prest_start"], $temps_sql);
        $tmp_list = dbSelect($sql, array("db" => "dia"));
        // print_r(array_column($tmps_non_fact, "temps_id"));
        $tmp_list = array_filter($tmp_list, function ($item) use ($tmps_non_fact, $tmps_used) {
            return !in_array($item["TEMPS_ID"], array_column($tmps_non_fact, "temps_id")) && !in_array($item["TEMPS_ID"], array_column($tmps_used, "temps_id"));
        });
        $html .= formTable(array("legend" => $cat["nom"], "id" => cryptSave($cat["id"]), "list" => $tmp_list));
    }
    return $html;
}
/**
 * Creates filters part of the page
 * @return string
 */
function composeHead(): string
{
    $html = "<div class='all'>";
    $html .= "<div class='left-div'>";
    $html .= formBtn(array("key" => "Ajouter-facture green", "ico" => "plus", "txt" => "Ajouter â la facture"));
    $html .= formBtn(array("key" => "unfact red", "ico" => "ban", "txt" => "Ne pas facturer"));
    $html .= "<div>";
    $html .= "</div>";
    $html .= formBtn(array("key" => "affiche_pre_facture", "ico" => "eye", "txt" => "Afficher la pré-facture"));
    $html .= "</div>";
    $html .= "<div class='right-div'>";
    $html .= formBtn(array("key" => "affiche-exep", "txt" => "Afficher l'exceptionnel"));
    // $html .= formBtn(array("key" => "presentation", "txt" => "Prestations facturèes"));
    $html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier", "href" => "recap.php?d=" . $_GET["d"]));
    $html .= "</div>";
    $html .= "</div>";
    return $html;
}

/**
 * Form Factures tables html
 * @param mixed $opts [string $legend] lagend of the fieldset, [array $list] list of factures
 * @param callable $con condition of the displaied facts
 * @return string
 */
function formTable($opts = array()): string
{
    $html = "<fieldset class='field'>";
    $html .= "<legend>" . $opts['legend'] . "</legend>";
    $html .= "<table id ='" . $opts['id'] . "' class='customers'>";
    $html .= "<thead>";
    $html .= "<tr>";
    $html .= "<th class='first-1'>" . formBtn(array("key" => "first-check", "ico" => "check-double"));
    $html .= "</th>";
    $html .= "<th class='second-2 date'>Date</th>";

    $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('" . $opts["id"] . "')\">Collab</p></th>";
    $html .= "<th class='second-2 prest-header'><p onclick=\"sortPrest('" . $opts["id"] . "')\">Prest</p></th>";

    $html .= "<th class='no-line exercice'><div class='all-year'><p class='exerciceLabel'>Exercice</p></div></th>";
    $html .= "<th>Titre</th>";
    $html .= "<th class='last-3'>Qte</th>";
    $html .= "<th class='last-3'>Duree</th>";
    $html .= "<th class='last-3'>PV</th>";
    $html .= "</tr>";
    $html .= "</thead>";
    foreach ($opts["list"] as $row) {
        $html .= "<tr class='rw' rw-id=" . cryptSave($row["TEMPS_ID"]) . ">";
        $formattedDate = date("m/d/Y", strtotime($row['TEMPS_DATE']));
        // added unvalide icon on porpuse to give the chape of box to the button
        $html .= "<td>" . formBtn(array("key" => "first-check row-check", "ico" => "fa-circle")) . "</td>";
        $html .= "<td class='centered-td date'>" . $formattedDate . "</td>";
        $html .= "<td class='centered-td code-row'><a href='#'>" . $row['COL_CODE'] . "</a></td>";
        $html .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" . formInput(array("key" => "prest_code", "align" => "c", "value" => $row['PREST_CODE'])) . "</span></p></td>";
        $html .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . formInput(array("key" => "exo_code", "align" => "c", "value" => $row['EXO_CODE'])) . "</span></p></td>";
        $html .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
        $html .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
        $html .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
        $html .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    $html .= "</fieldset>";
    return $html;
}

/**
 * Creates facts select options
 * @return array
 */
function factsSelectOptions(): array
{
    global $cookie, $select_fact;
    $facts = dbSelect($select_fact, array("db" => "prefact"));
    $list = array();
    foreach ($facts as $fact) {
        array_push($list, array("code" => cryptSave($fact["id"]), "txt" => $fact["date"], "title" => "date de facture"));
    }
    array_push($list, array("code" => "nouvelle_facture", "txt" => "Nouvelle facture"));
    if (sizeof($list) > 1) $_selected = $list[0];
    else $_selected = array("code" => "nouvelle_facture", "txt" => "Nouvelle facture");

    $cookie = array_search($cookie["index"]["sortCol"], array_column($list, "code"));
    if ($cookie !== false)  $_selected = array("code" => $list[$cookie]["code"]);
    return array("list" => $list, "selected" => $_selected);
}


$cont = html(array_merge($opts, array("cont" => composePage(), "script" => "resultat", "adr" => $getD)));
die($cont);

<?php

require_once("config.php");
session_start();
$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));
$cookie = cookieInit();
$getD = ((isset($_GET["d"]))? cryptDel($_GET["d"]) : false);
if($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
$getD = 14557;
// all queries of this page
//$select = "select distinct EXO_CODE from expert_fidsud.temps where TEMPS_DATE>='" . $date_deb . "' and TEMPS_DATE<='" . $date_fin . "' and ADR_ID = (select ADR_ID from expert_fidsud.adresse where ADR_CODE='" . $_SESSION['code_actuel'] . "') order by EXO_CODE asc";
$select_fact = "select * from factures where Code=(select ADR_CODE from expert_fidsud.adresse where ADR_ID = '$getD') and EnCours=1 and Provision=0 order by IdFact Asc";


// $temps_sql = "SELECT t.* FROM expert_fidsud.temps t LEFT JOIN z_fact.prestations p ON t.Temps_Id = p.Temps_Id ";
$temps_sql = "SELECT t.*
FROM expert_fidsud.temps t
WHERE t.ADR_ID ='$getD' AND NOT EXISTS (
    SELECT 1
    FROM z_fact.prestations p
    WHERE t.Temps_Id = p.Temps_Id 
);";


// $list = dbSelect($temps_sql, array("db" => "dia"));


/**
 * creates pages html
 */
function composePage(): string
{


    $html = composeHead();
    // Travaux compta
    $html .= formTable(array("legend" => "Travaux Comptable et fiscaux", "id" => "trav-com"), con: function ($opts): string {
        if ((($opts["prest"] >= 200) && ($opts["prest"] < 400)) || (strpos($opts["prest"], 'T2') !== false && strpos($opts["prest"], 'T2', 0) == 0)) {
            return $opts['rw'];
        }
        return "";
    });

// Travaux social

    $html .= formTable(array("legend" => "Travaux Sociaux", "id" => "trav-soc"), con: function ($opts): string {
        if ((($opts["prest"] >= 400) && ($opts["prest"] < 500))) {
            return $opts['rw'];
        }
        return "";
    });

// Travaux Conseil 

    $html .= formTable(array("legend" => "Travaux Conseil", "id" => "trav-cons"), con: function ($opts): string {
        if ((($opts["prest"] >= 500) && ($opts["prest"] < 600)) || (($opts["prest"] >= 700) && ($opts["prest"] < 900))) {
            return $opts['rw'];
        }
        return "";
    });

// Travaux Juridiques 

    $html .= formTable(array("legend" => "Travaux Juridiques", "id" => "trav-juri"), con: function ($opts): string {
        if ((($opts["prest"] >= 600) && ($opts["prest"] < 700))) {
            return $opts['rw'];
        }
        return "";
    });

// Travaux Abonnements 

    $html .= formTable(array("legend" => "Abonnements", "id" => "trav-abon"), con: function ($opts): string {
        if ((($opts["prest"] >= 900) && ($opts["prest"] < 999))) {
            return $opts['rw'];
        }
        return "";
    });
    return $html;
}

/**
 * Creates filters part of the page
 * @return string
 */
function composeHead(): string
{
    $factsList = factsSelectOptions();

    $html = "<div class='all'>";
    $html .= "<div class='left-div'>";
    $html .= "<div>";
    $html .= formLabel(array(
        "key" => "Sèlection d'une facture non terminèe : ",
    ));

    $html .= formSelect(array("key" => "sortAnalyse", "selected" => $factsList["cookie"], "list" => $factsList["list"]));
    $html .= "</div>";
    $html .= formBtn(array("key" => "affiche_pre_facture", "ico" => "eye", "txt" => "Afficher la pré-facture", "href" => "affiche_fact.php"));
    $html .= "</div>";
    $html .= "<div class='right-div'>";
    $html .= formBtn(array("key" => "affiche-exep", "txt" => "Afficher l'exceptionnel"));
    // $html .= formBtn(array("key" => "presentation", "txt" => "Prestations facturèes"));
    $html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier", "href" => "recap.php?d=".$_GET["d"]));
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
function formTable($opts = array(), $con): string
{
    global $temps_sql;
    $temps_list = dbSelect($temps_sql, array("db" => "dia"));
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
    foreach ($temps_list as $row) {
        $prest = (strpos($row['PREST_CODE'], '@', 0) == 0) ? str_replace("@", "", $row['PREST_CODE']) : $row['PREST_CODE'];

        $tblRow = "<tr class='rw' rw-id=" . $row["TEMPS_ID"] . ">";
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

/**
 * Creates facts select options
 * @return array
 */
function factsSelectOptions(): array
{
    global $cookie, $select_fact;
    // var_dump($select_fact);
    $facts = dbSelect($select_fact, array("db"=>"fact"));
    $list = array(array("code" => "nouvelle_facture", "txt" => "Nouvelle facture"));
    foreach($facts as $fact){
        array_push($list, array("code" => $fact["IdFact"], "txt" => $fact["Date"], "title" => "date de facture"));
    }
    $_selected = array("code" => "nouvelle_facture", "txt" => "Nouvelle facture");

    $cookie = array_search($cookie["index"]["sortCol"], array_column($list, "code"));
    if ($cookie !== false)  $_selected = array("code" => $list[$cookie]["code"]);
    return array("list" => $list, "cookie" => $_selected);
}

$cont = html(array_merge($opts, array("cont" => composePage(), "script" => "resultat", "adr" => $getD)));
die($cont);

<?php

require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$getD = ((isset($_GET["d"]))? cryptDel($_GET["d"]) : false);
if($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));

$html = "";

// queries of the page
$select_facts = "SELECT * FROM facture WHERE `status` ";
$select_facts_collab = "select CodeCollab from collab where IdFact=? order by IdCollab asc";
$select_rd = "select rd from adr where code ='?'";
//verifie s il y a des debours
$select_total_trav = "select * from prestations where IdFact='?' and IdDetail in (select IdDetail from detail where IdFact='?')";

/**
 * compose html of the page
 * @return string
 */
function composePage(): string
{
   global $html, $select_facts;
   $facts = dbSelect(buildFactsQury($select_facts), array("db" => "prefact"));
   displayHead();
   $html .= "<div class='centre'>";

   $html .= "<table>";

   $html .= "<tr>";
   $html .= "<th class='rd'> RD</th>";
   $html .= "<th>Crée par</th>";
   $html .= "<th>Code de Dossier</th>";
   $html .= "<th>Nom de Dossier</th>";
   $html .= "<th>Date de facture</th>";
   $html .= "<th>Date de validation</th>";
   $html .= "<th>Date d'envoi dans dia</th>";
   $html .= "<th>Modalités paiment</th>";
   $html .= "<th>Montant</th>";
   $html .= "<th>Débours</th>";
   $html .= "<th>Verrou factures</th>";
   $html .= "<th>  </th>";
   $html .= "</tr>";
   $html .= "<tr class='comm' >";
   $html .= "<td colspan='12'>";
   $html .= "<div class='recherch'>";
   $html .= formInput(array("key" => "rech", "label" => "Rechercher un dossier", "placeholder" => "rechercher un dossier"));
   $html .= "</div>";
   $html .= "</td>";
   $html .= "</tr>";
   foreach ($facts as $fact) {
      if ($fact["status"] == 2 || $fact["status"] == 3) composeFactRow($fact);
   }

   $html .= "</table>";
   $html .= "</div>";

   return $html;
}

/**
 * displays head part of the page
 * @return void
 */
function displayHead(): void
{
   global $html;
   $html .= "<div class='hd'>";
   $html .= "<div class='left'>";
   $html .= formBtn(array("key" => "refresh", "ico" => "fa-solid fa-arrows-rotate", "title" => "refresh", "href" => "fact_a_valider.php?d=".$_GET["d"]));
   $html .= formBtn(array("key" => "facturesTerminé", "ico" => "fa-solid fa-file-contract", "txt" => "Factures terminées", "href" => "fact_a_valider.php?term=true&d=".$_GET["d"]));
   $html .= formBtn(array("key" => "facturesAvalider", "ico" => "fa-solid fa-file-signature", "txt" => "Factures à valider", "href" => "fact_a_valider.php?a_valid=true&d=".$_GET["d"]));
   $html .= formBtn(array("key" => "lesFactures", "ico" => "fa-solid fa-file-arrow-up", "txt" => "Toutes les factures", "href" => "fact_a_valider.php?all_facts=true&d=".$_GET["d"]));
   $html .= "</div>";
   $html .= "<div class='left'>";
   $html .= formBtn(array("key" => "print", "ico" => "fa-solid fa-print", "title" => "imprimé"));
   //  $html .= formSelect(array("key" => "print" ));
   $html .= "</div>";
   $html .= "</div>";
}

/**
 * compose the table that displays facts
 * @param array $fact
 * @return void
 */
function composeFactRow(array $fact): void
{
   global $html, $select_rd, $select_facts_collab, $getD;
   $select_rd = str_replace("?", $fact["adr_id"], $select_rd);
   $select_facts_collab = str_replace("?", $fact["id"], $select_facts_collab);
   $rd = dbSelect($select_rd, array("db" => "prefact"));
   $collab = dbSelect($select_facts_collab, array("db" => "fact"));
   if(!empty($collab))$collab = $collab[0];
   if(isset($_GET["a_valid"]) && ($fact["status"]!=2 || $fact["status"]!=3)) return;

   if(empty($collab)){
      $collab["CodeCollab"] = "-";
   }

   $html .= "<tr id='".cryptSave($fact["id"])."' class = 'row'>";
   $html .= "<td class='left'>" . $rd[0]["rd"] . "</td>";
   $html .= "<td>" . $collab["CodeCollab"] . "</td>";
   $html .= "<td class = 'code_dossier'>" . $fact["adr_id"] . "</td>";
   $html .= "<td class = 'nom_dossier' >" . $getD . "</td>";
   $html .= "<td>";
   $html .= "<div class='date' style='position:relative;'>";
   $html .= formDp(array("key" => "date", "readonly"=>true, "value" => formatFactureDate($fact["date"], $fact["date"])));
   $html .= "<input id='date' type='date' value='".$fact["date"]."' style='opacity:0; position:absolute;'>";
   $html .= "</div>";
   $html .= "</td>";
   $html .= "<td>-</td>" /*. formateValiDte($fact["DateValidationRD"]) . "-</td>"*/;
   $html .= "<td>-</td>" /*. formateDateDIa($fact['DateInsertDIA']) . "-</td>"*/;
   $html .= "<td>";
   $html .= $fact["fae"] == 1 ? "FAE" : "" ;
   $html .= "</td>";
   $html .= "<td>" . $fact['amount'] . "</td>";
   $html .= "<td>";
   $html .= $fact["debours"] == 1 ? "OUI" : "NO" ;
   $html .= "</td>";
   $html .= "<td>";
   $html .= "<div class='checkbox'>";
   $html .= formCheckbox(array("key" => "verr", "list" => array(array("code" => "check"))));
   $html .= "</div>";
   $html .= "</td>";
   $html .= "<td>";
   $html .= "<div class='verticalB'>";
   $html .= formBtn(array("key" => "icoVertica", "ico" => "fa-solid fa-ellipsis-vertical"));
   $html .= "<div class='list off' factId='".$fact["id"]."'>";
   // $html .= "<input class='comment' hidden='true' value='".$fact["obs"]."'/>";
   $html .= formBtn(array("key" => "open", "txt" => "ouvrir la facture", "ico" => "fa-solid fa-envelope-open-text", "href" => "validation_recap.php?d=".$_GET['d']));
   $html .= formBtn(array("key" => "validate", "txt" => "Validate Facture", "ico" => "fa-solid fa-check"));
   $html .= formBtn(array("key" => "close", "txt" => "Annuler la facture", "ico" => "fa-solid fa-ban"));
   $html .= "</div>";
   $html .= "</div>";
   $html .= "</td>";
   $html .= "</tr>";
}

/**
 * build query of that fetches fact based on several conditions
 * @return string
 */
function buildFactsQury(string $select_facts): string
{
   
   global $opts;
   $isNotSite = $opts["user"]["soc"] == "" || $opts["user"]["soc"] == "vide";
   if (isset($_GET["all_facts"])) {
      $isNotSite ? $select_facts .= " IN (2, 3) AND debours = 0" . socListQueris() :
         $select_facts .= " IN (2, 3) debours = 0" . socListQueris();
   } else if (isset($_GET["term"])) {
      $isNotSite ? $select_facts .= "=4 AND debours = 0 " . socListQueris() :
         $select_facts .= "=4 AND debours = 0" . socListQueris();
   } else {
      $isNotSite ? $select_facts = "(" . $select_facts . "=2 AND debours = 0 " . socListQueris() . ") " :
         $select_facts = "(" . $select_facts . "=2 and debours = 0 " .  socListQueris() . ")";

   }
   return $select_facts;
}

/**
 * adds to the query soc list condtion depending on the user id
 * @return string
 */
function socListQueris(): string
{
   global $opts;
   $str = " and adr_id in (select CODE from adr";
   $condition = array("AULAB", "JUCAR", "AGB", "LUV");

   if (!empty($opts["user"]["socList"]))
      $str .= " where SOC in ( '" . implode("', '", $opts["user"]["socList"]) . "' ))";
   else $str .= ")";

   if (in_array($opts["user"]["id"], $condition))
      $str .= " or Code in (select ADR_CODE from fssecteurpublic.adresse where SOC_CODE='FSSECTEURP'))";

   return $str;
}

/**
 * Formate date of facture
 * @param string $factDate
 * @param string $date
 */
function formatFactureDate(string $factDate, string $date): string
{
   if ($factDate == "-") {
      $annee = substr($date, -4);
      $mois = substr($date, -7, 2);
      $jour = substr($date, 0, 2);
      return $annee . $mois . $jour;
   } else return date('Y-m-d', strtotime($factDate));
}

/**
 * Formate date of validation
 * @param string $valiDte
 */
function formateValiDte(string $valiDte): string
{
   if ($valiDte == 0) return '-';
   else {
      $annee = substr($valiDte, 0, 4);
      $mois = substr($valiDte, 4, 2);
      $jour = substr($valiDte, 6, 2);
      return $jour . '/' . $mois . '/' . $annee;
   }
}

/**
 * Formate Date Dia
 * @param string $dateDia
 */
function formateDateDIa(string $dateDia): string
{
   if ($dateDia == 0) return '-';
   else {
      $annee = substr($dateDia, 0, 4);
      $mois = substr($dateDia, 4, 2);
      $jour = substr($dateDia, 6, 2);
      return $jour . '/' . $mois . '/' . $annee;
   }
}

if(isset($_POST["facture_id"])) {
   $id = $_POST["facture_id"];
   $sql = "UPDATE z_fact.factures
           SET Encours = 2
           WHERE idFact = '$id'";
   dbExec($sql, array_merge($opts, array("db" => "fact")));
  die(json_encode(['code'=>200]));
}
echo html(array("user" => $user, "cont" => composePage(), "title" => "", "script" => "fact_a_valider", "adr" => $getD));



die();

<?php

require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
//print_r($opts);
$cookie = cookieInit();
$html = "";

// queries of the page
$select_facts = "SELECT * FROM factures WHERE EnCours";
$select_facts_collab = "select CodeCollab from collab where IdFact=? order by IdCollab asc";
// TODO: need database titre in dia: line 537
$select_rd = "select A.COL_CODE_N1 from adresse A where ADR_CODE='?'";
//$titre=$row3['TITRE_COURT'];/ $titre='';
//verifie s il y a des debours
$select_total_trav = "select * from prestations where IdFact='?' and IdDetail in (select IdDetail from detail where IdFact='?')";

/**
 * compose html of the page
 * @return string
 */
function composePage(): string
{
   global $html, $select_facts;

//   print_r(buildFactsQury($select_facts));
//   $facts = array();
   $facts = dbSelect(buildFactsQury($select_facts), array("db" => "fact"));
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
      if ($fact["EnCours"] == 3) composeFactRow($fact);
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
   $html .= formBtn(array("key" => "refresh", "ico" => "fa-solid fa-arrows-rotate", "title" => "refresh", "href" => "fact_a_valider.php"));
   $html .= formBtn(array("key" => "facturesTerminé", "ico" => "fa-solid fa-file-contract", "txt" => "Factures terminées", "href" => "fact_a_valider.php?term=true"));
   $html .= formBtn(array("key" => "facturesAvalider", "ico" => "fa-solid fa-file-signature", "txt" => "Factures à valider", "href" => "fact_a_valider.php?a_valid=true"));
   $html .= formBtn(array("key" => "lesFactures", "ico" => "fa-solid fa-file-arrow-up", "txt" => "Toutes les factures", "href" => "fact_a_valider.php?all_facts=true"));
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
   global $html, $select_rd, $select_facts_collab;
   $select_rd = str_replace("?", $fact["Code"], $select_rd);
   $select_facts_collab = str_replace("?", $fact["IdFact"], $select_facts_collab);
   $rd = dbSelect($select_rd, array("db" => "dia"))[0];
   $collab = dbSelect($select_facts_collab, array("db" => "fact"))[0];
   // unknown $presta_debours var
   //   if(in_array($row2['CodePrest'],$presta_debours)){
   //					$debours='Oui';
   //				}

   if(isset($_GET["a_valid"]) && $fact["EnCours"]!=3) return;

   $html .= "<tr class = 'row'>";
   $html .= "<td class='left'>" . $rd["COL_CODE_N1"] . "</td>";
   $html .= "<td>" . $collab["CodeCollab"] . "</td>";
   $html .= "<td class = 'code_dossier'>" . $fact["Code"] . "</td>";
   $html .= "<td class = 'nom_dossier' >" . $fact["Dossier"] . "</td>";
   $html .= "<td>";
   $html .= "<div class='date'>";
   $html .= formDp(array("key" => "date", "value" => formatFactureDate($fact["DateFacture"], $fact["Date"])));
   $html .= "</div>";
   $html .= "</td>";
   $html .= "<td>" . formateValiDte($fact["DateValidationRD"]) . "</td>";
   $html .= "<td>" . formateDateDIa($fact['DateInsertDIA']) . "</td>";
   $html .= "<td>";
   if ($fact["Modalite1"] == "") $html .= "---";
   else $html .= $fact["FAE"] == 1 ? "FAE" : "" . $fact["Modalite1"];
   $html .= "</td>";
   $html .= "<td>" . $fact['MontantFact'] . "</td>";
   $html .= "<td>---</td>";
   $html .= "<td>";
   $html .= "<div class='checkbox'>";
   $html .= formCheckbox(array("key" => "verr", "list" => array(array("code" => "check"))));
   $html .= "</div>";
   $html .= "</td>";
   $html .= "<td>";
   $html .= "<div class='verticalB'>";
   $html .= formBtn(array("key" => "icoVertica", "ico" => "fa-solid fa-ellipsis-vertical"));
   $html .= "<div class='list off' factId='".$fact["IdFact"]."'>";
   $html .= "<input class='comment' hidden='true' value='".$fact["CommentairesFacture"]."'/>";
   $html .= formBtn(array("key" => "open", "txt" => "ouvrir la facture", "ico" => "fa-solid fa-envelope-open-text", "href" => "validation_recap.php"));
   $html .= formBtn(array("key" => "commentaire", "txt" => "Ajouter un commentaire", "ico" => "fa-solid fa-pencil"));
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
      $isNotSite ? $select_facts .= " IN (3, 4) AND ValDebours=1 " . socListQueris() :
         $select_facts .= " IN (3, 4) AND ValDebours=1 " . "and Site='" . $opts["user"]["soc"] . "'" . socListQueris();
   } else if (isset($_GET["term"])) {
      $isNotSite ? $select_facts .= "=4 AND ValDebours=1 " . socListQueris() :
         $select_facts .= "=4 AND ValDebours=1 " . "and Site='" . $opts["user"]["soc"] . "'" . socListQueris();
   } else {
      $isNotSite ? $select_facts = "(" . $select_facts . "=3 AND ValDebours=1 " . socListQueris() . ") union (select * from factures where EnCours=4 and FactImprimee=0 and ValDebours=1" . socListQueris() . ")" :
         $select_facts = "(" . $select_facts . "=3 AND ValDebours=1 " . "and Site='" . $opts["user"]["soc"] . "'" . socListQueris() . ") union(select * from factures where EnCours=4 and FactImprimee=0 and ValDebours=1" . socListQueris() . ")";

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
   $str = " and (Code in (select ADR_CODE from expert_fidsud.adresse where SOC_CODE";
   $condition = array("AULAB", "JUCAR", "AGB", "LUV");

   if (!empty($opts["user"]["socList"]))
      $str .= " in ( '" . implode("', '", $opts["user"]["socList"]) . "' )))";
   else $str = "";

   if (in_array($opts["user"]["id"], $condition))
      $str .= ") or Code in (select ADR_CODE from fssecteurpublic.adresse where SOC_CODE='FSSECTEURP'))";

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
echo html(array("user" => $user, "cont" => composePage(), "title" => "", "script" => "fact_a_valider", "adr" => false));



die();


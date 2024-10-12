<?PHP
header('Content-Type: application/json');
require('config.php');
$user = auth(array('ajax'=>true));
$opts = array('ajax'=>true);
$isAnd = false;
$dataResp = json_decode(DATAresp, true);
//initiate the needed values
$annee = '';if(isset($_POST['annee'])) $annee = $_POST['annee'];
$grp = '';if(isset($_POST['grp']) and $_POST['grp'] != '-') $grp = $_POST['grp'];
$soc = '';if(isset($_POST['soc']) and $_POST['soc'] != '-') $soc = $_POST['soc'];
$txt = '';if(isset($_POST['txt']) and $_POST['txt'] != '-') $txt = $_POST['txt'];
$naf = '';if(isset($_POST['naf']) and $_POST['naf'] != '-') $naf = $_POST['naf'];
$segment = '';if(isset($_POST['segment']) and $_POST['segment'] != '-') $segment = $_POST['segment'];
$resp = '';if(isset($_POST['resp']) and $_POST['resp'] != '-') $resp = $_POST['resp'];
$resps = array();

foreach($dataResp as $v){if (isset($_POST[$v['code']]) and $_POST[$v['code']] != '' and $_POST[$v['code']] != '-') $resps[$v['code']] = $_POST[$v['code']];}

$select = "SELECT * FROM `synthese` WHERE annee = ? ";
if($annee != ''){$select=str_replace('?', $_POST['annee'], $select);}

if($soc != '' or $grp != '' or $txt != '' or $naf != '' or $segment != '' or $resp != '' or count($resps) > 0) $select .= 'AND`adr` IN ( SELECT `id` FROM `adr` WHERE ';

if($soc != ''){$select.=addSelect('soc', $soc);}
if($grp != "" ){$select.=addSelect('grp', $grp);}
if($txt != ""){$select.=addSelect('txt', $txt);}
if($naf != ""){$select.=addSelect('naf', $naf);}
if($segment != ""){$select.=addSelect('segment', $segment);}
if($resp != ""){$select.=addSelect('res', $resp);}

if(count($resps) > 0)foreach($dataResp as $v){if( isset($resps[$v['code']]) and $resps[$v['code']] != ""){$select.=addSelect($v['code'], $resps[$v['code']]);}}

$select .=$isAnd? " )": '';
$result = dbSelect($select, array_merge($opts, array('db'=>'prefact')));
$html = "";
foreach ($result as $v) {
    $html .= "<div  class='line'>";
     $html .= "<div class='col dossier'>";
    //     $html .= "<div class='sub code'>"  . $v["CodeDossier"] . "</a></div>";
    //     $html .= "<div class='sub nom'>"   . $v["NomDossier"] . "</div>";
    //     $html .= "<div class='sub groupe'>". $v["Groupe"] . "</div>";
     $html .= "</div>";
     $html .= "<div class='col temps'>";
        $html .= "<div class='sub'>";
        $html .= "<span class='label'>Durée</span> <span class='value'>" . $v["temps_dur"] . "</span>";
        $html .= "</div>";
        // $html .= "<div class='sub'>";
        // $html .= "<span class='label'>Coût de revient</span> <span class='value'>" . $v["ReportAntTemps"] . "</span>";
        // $html .= "</div>";
        $html .= "<div class='sub'>";
        $html .= "<span class='label'>Débours</span> <span class='value'>" . $v["temps_debours"] . "</span>";
        $html .= "</div>";
     $html .= "</div>";

     $html .= "<div class='col factures'>";
        $html .= "<div class='sub'>";
        $html .= "<span class='label'>Quantité</span> <span class='value'>" . $v["fact_qt"] . "</span>";
        $html .= "</div>";
        $html .= "<div class='sub'>";
        $html .= "<span class='label'>Emises</span> <span class='value'>" . $v["fact_emis"] . "</span>";
        $html .= "</div>";
        $html .= "<div class='sub'>";
        $html .= "<span class='label'>Débours</span> <span class='value'>" . $v["fact_debours"] . "</span>";
        $html .= "</div>";
     $html .= "</div>";

     $html .= "<div class='col Statut'>";
        // $html .= "<div class='sub segmentation'>";
        // $segmentation = $v["Segmentation"];
        // $letter = substr($segmentation, 0, 1);
        // $defaultColor = 'black';
        // $colors = array('A' => 'green', 'B' => 'green', 'C' => 'green', 'D' => 'gold', 'E' => 'red', 'Z' => 'black',);
        // $color = isset($colors[$letter]) ? $colors[$letter] : $defaultColor;
        // $html .= "<span class='label'>Segmentation</span> <span style='color: $color;'>  $letter </span>";
        // $html .= "</div>";
        $html .= "<div class='sub'>";
              if (strpos($v["plusmoins"], '-') === 0) 
                  {
             $html .= "<span class='label '>+/-value</span> <span class='value red'>" . $v["plusmoins"] . "</span>";
                  }  
           elseif (strpos($v["plusmoins"], '+') === 0) 
                  {
             $html .= "<span class='label'>+/-value</span> <span class='value green'>" . $v["plusmoins"] . "</span>";
                  }
        $html .= "</div>";
        // $html .= "<div class='sub'>";
        // $html .= "<span class='label'>Solde des créances</span> <span class='value'>". $v["ReportPostTemps"] . "</span>";
        // $html .= "</div>";
     $html .= "</div>";

     $html .= "<div class='col Operations'>";
    //     $html .= "<div class='sub'>";
    //     $html .= "<span class='label'>En cours</span> <span class='value'>" . $v["MoisCloture"] . "</span>";
    //     $html .= "</div>";
    //     $html .= "<div class='sub'>";
    //     $html .= "<span class='label'>Validation du RD</span> <span class='value'>" . $v["MoisCloture"] . "</span>";
    //     $html .= "</div>";
    //     $html .= "<div class='sub'>";
    //     $html .= "<span class='label'>Traitement administratif</span> <span class='value'>" . $v["MoisCloture"] . "</span>";
    //     $html .= "</div>";
     $html .= "</div>";

     $html .= "<div class='col Provisions'>";
    //     $html .= "<div class='sub'>";
    //     $html .= "<span class='label'>En cours</span> <span class='value'>" . $v["MoisCloture"] . "</span>";
    //     $html .= "</div>";
    //     $html .= "<div class='sub'>";
    //     $html .= "<span class='label'>Validation du RD</span> <span class='value'>" . $v["MoisCloture"] . "</span>";
    //     $html .= "</div>";
    //     $html .= "<div class='sub'>";
    //     $html .= "<span class='label'>Traitement administratif</span> <span class='value'>" . $v["MoisCloture"] . "</span>";
    //     $html .= "</div>";
     $html .= "</div>";

      $html .= "<div  class='col op'>";
         $html .= formBtn(array("key"=>"displayMenu" , "ico"=>"fa-solid fa-ellipsis-vertical" , "id"=>"popupMenu"));
         $html.="<div class='list off'>";
            $html .= formBtn(array("key" => "syntheseDossier", "txt" => "Synthèse du dossier  ","ico"=>"fa-solid fa-eye","href"=>"synthese.php" ));
            $html .= formBtn(array("key" => "crm", "txt" => "Ouvrir sur le CRM","ico"=>"fa-solid fa-arrow-up-right-from-square"));
            $html .= formBtn(array("key" => "displaySegme", "txt" => "Modifer le segmentation","ico"=>"fa-solid fa-layer-group"));
            $html .= formBtn(array("key" => "displayCommentaire", "txt" => "Modifer le commentaire","ico"=>"fa-solid fa-pencil"));
            $html .= formBtn(array("key" => "displayDéverrouiller", "txt" => "Déverrouiller le dossier","ico"=>"fa-solid fa-lock-open"));
            $html .= formBtn(array("key" => "displayInvalide", "txt" => "Invalider le solde","ico"=>"fa-solid fa-circle-xmark"));
        $html .= "</div>";
      $html .= "</div>";


    $html .= "</div>"; // Closing div for the "line"
    
    
    $html .= "<div  class='labels-section'>";
     $html .= "<div  class='lettre'>";
        $html .= formLabel(array(
        "key" => "Lettre de mission:",
        "value" => "00/00/0000",
        "title" => "Additional Information"));
     $html .= "</div>";

     $html .= "<div class='montant'>";
        $html .= formLabel(array(
        "key" => "Montant:",
        "value" => "0 000 000,00",
        "title" => "Additional Information"));
     $html .= "</div>";

     $html .= "<div class='solde'>";
        $html .= formLabel(array(
        "key" => "Solde validé",
        "icon" => "fa-solid fa-circle-check",
        "title" => "Additional Information"));
     $html .= "</div>";

     $html .= "<div class='ver'>";
        $html .= formLabel(array(
        "key" => "Dossier verrouillé",
        "icon" => "fa-solid fa-lock",
        "title" => "Additional Information"));
     $html .= "</div>";
    $html .= "</div>";
   }

try {
    echo json_encode(['success' => 200, 'data' => $html]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred.']);
}
/**
 * add condition to the query
 * @param string $column
 * @param mixed $value
 * @return string
 */
function addSelect(String $column, mixed $value): String
{
    global $isAnd;
    $select = $isAnd?' AND ':'';
    $select .= "`".$column."` = '";
    $select .= $value."'";
    $isAnd = true;
    return $select;
}

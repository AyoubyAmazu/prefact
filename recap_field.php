<?php
require_once("config.php");

$opts = array();
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact", "fact"))));
$opts["user"] = auth($opts);
$opts['user']['socList'] = array();
$cookie = cookieInit();
$getD = ((isset($_POST["adr"])) ? cryptDel($_POST["adr"]) : false);
if (!$getD) die(json_encode(array("code" => 400, "html" => "Invalid Id")));

if (isset($_POST["years"])) {
    $html = '';
    foreach ($_POST["years"] as $year) {
        // $select = "select *, (SELECT SUM(`t`.`Total_facture`) as total_fact FROM `travaux_detail` t WHERE `t`.`IdFact` = $getD) AS code from `rech_fact` where `Adr_Id`= '$getD' and `AnneeChoix` = '$year'";
        // $result = dbSelect($select, array_merge($opts, array("db" => "fact")));
        $html .=fieldHtml(array("year"=>$year));
    }
    die(json_encode(array("code" => 200, "html" => $html)));
}
function fieldHtml($opts = array())
{
    $html = "<div class='field'>";
    $html .= "<div class='all'>";
    $html .= "<div class='top'>";
    $html .= "<div class='bell'>";
    $html .= "</div>";
    $html .= "<div class='labl'>";
    $html .= formLabel(array("key"=>"<a href=''>".$opts["year"]."</a>" ,));
    $html .= "</div>";
    $html .= "<div class='vertica'>";
    $html .= formBtn(array("key"=>"vertica", "ico"=>"fa-solid fa-ellipsis-vertical", "id"=>"vertica"));
    $html.="<div class='list off'>";
    $html .= formBtn(array("key" => "bilan",      "txt" => "Fiche bilan"              ,"ico"=>"fa-solid fa-up-right-from-square"));
    $html .= formBtn(array("key" => "abonnement", "txt" => "Abonnement"               ,"ico"=>"fa-solid fa-plus"));
    $html .= formBtn(array("key" => "virement",   "txt" => "Virement CPTE à CPTE"     ,"ico"=>"fa-solid fa-plus"));
    $html .= formBtn(array("key" => "provision",  "txt" => "Provision année suivante" ,"ico"=>"fa-solid fa-plus"));
    $html .= formBtn(array("key" => "commentaire","txt" => "Ajouter un commentaire"   ,"ico"=>"fa-solid fa-pencil"));
    $html .= formBtn(array("key" => "recap",      "txt" => "Valider le récap"         ,"ico"=>"fa-solid fa-lock"));
    $html .= formBtn(array("key" => "rappel",     "txt" => "Ajouter un rappel"        ,"ico"=>"fa-solid fa-bell"));
    $html .= "</div>";
    $html .= "</div>";

    $html .= "</div>"; // closing top
    $html .= "<div class='chiffre'>";
    $html .=  formLabel(array("key"=>"chiffre d'affaire : 0,00 - Effectif : 0,00"));
    $html .= "</div>";
    $html .="<div class='table'>";
   
    $html .= "<div class='donne'>";
    $html .= "<div class='value facture'>";
    $html .= "<span class='labele fac'>FACTURE</span> <span class='val fac'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value juridique'>";
    $html .= "<span class='labele jur'>JURIDIQUE</span> <span class='val jur'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value paye'>";
    $html .= "<span class='labele pay'>PAYE / SOCIAL</span> <span class='val pay'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value debours'>";
    $html .= "<span class='labele deb'>DEBOURS</span> <span class='val deb'></span>";
    $html .= "</div>";
    $html .= "<div class='value frais'>";
    $html .= "<span class='labele frais'>FRAIS</span> <span class='val frais'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<div class='donneTrav'>";
    $html .= "<div class='value travaux'>";
    $html .= "<span class='labele trav'>TRAVAUX</span> <span class='val trav'><a href='synthese_trav.php?table=table2' target='blank'>  0,00 </a> </span>";
    $html .= "</div>";
    $html .="<div class='travaux-sublabels show'>";
       $html .= "<span class='travauxSublabel saisie'>SAISIE</span> <span class='val '>  0,00  </span>";
        $html .="<span class='travauxSublabel revision'>REVISION</span> <span class='val '>  0,00  </span>";
        $html .="<span class='travauxSublabel accomp'>ACCOMPAGNEMENT</span> <span class='val '>  0,00  </span>";
        $html .="<span class='travauxSublabel declaration'>DECLARATION</span> <span class='val '>  0,00  </span>";
        $html .="<span class='travauxSublabel divers'>DIVERS COMPTA</span> <span class='val '>  0,00  </span>";
        $html .="<span class='travauxSublabel cac'>CAC</span> <span class='val '>  0,00  </span>";
        $html .="<span class='travauxSublabel abonne'><a href='synthese_trav.php?table=table2' target='blank'>ABONNEMENTS</a></span> <span class='val'>  <a href='synthese_trav.php?table=table1' target='blank'>0,00</a>  </span>";
    $html .="</div>";
    $html .= "<div class='value juridique'>";
    $html .= "<span class='labele jur'>JURIDIQUE</span> <span class='val jur'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value paye'>";
    $html .= "<span class='labele pay'>PAYE / SOCIAL</span> <span class='val pay'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value conseil'>";
    $html .= "<span class='labele deb'>CONSEIL</span> <span class='val deb'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value debours'>";
    $html .= "<span class='labele frais'>DEBOURS</span> <span class='val frais'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value frais'>";
    $html .= "<span class='labele frais'>FRAIS</span> <span class='val frais'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";// closing donneTrav
  
    $html .="</div>";// closing table

    $html .= "<div class='tableX'>";
    $html .= "<div class='donneCompta'>";
    $html .= "<div class='value compta'>";
    $html .= "<span class='labele compta'>COMPTA / CAC EXC</span> <span class='val comp'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value juridique'>";
    $html .= "<span class='labele jur'>JURIDIQUE EXC</span> <span class='val jur'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value social'>";
    $html .= "<span class='labele social'>SOCIAL EXC</span> <span class='val soc'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<div class='donneComptaB'>";
    $html .= "<div class='value compta'>";
    $html .= "<span class='labele compta'>COMPTA / CAC EXC</span> <span class='val comp'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value juridique'>";
    $html .= "<span class='labele jur'>JURIDIQUE EXC</span> <span class='val jur'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value social'>";
    $html .= "<span class='labele social'>SOCIAL EXC</span> <span class='val soc'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value conseil'>";
    $html .= "<span class='labele conseil'>CONSEIL EXC</span> <span class='val cons'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<div class='letotal'>";
    $html .= "<div class='totalA'>";
    $html .= "<span class='labele totA'>TOTAL</span> <span class='val totA'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='totalB'>";
    $html .= "<span class='labele totB'>TOTAL</span> <span class='val totB'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";

    $html .= "<div class='tableY'>";
    $html .= "<div class='donneHono'>";
    $html .= "<div class='value hono'>";
    $html .= "<span class='labele hono'>HONO.RESTANT</span> <span class='val hono'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value attente'>";
    $html .= "<span class='labele attente'>ATTENTE VALIDE</span> <span class='val attente'><a href='synthese_trav.php'>  0,00  </a></span>";
    $html .= "</div>";
    $html .= "<div class='value solde'>";
    $html .= "<span class='labele solde' > <a href='synthese_trav.php'> SOLDE N-1 </a></span> <span class='val solde'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<div class='donneVirt'>";
    $html .= "<div class='value virt'>";
    $html .= "<span class='labele virt'>".formBtn(array("key" => "virement",   "txt" => "Virement CPTE à CPTE"))."</span> <span class='val virt'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value ant'>";
    $html .= "<span class='labele ant'>REPORT ANT</span> <span class='val ant'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value post'>";
    $html .= "<span class='labele post'>REPORT POST</span> <span class='val post'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='value debours'>";
    $html .= "<span class='labele debours'>REPORT DEBOURS</span> <span class='val debours'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<div class='letotalX'>";
    $html .= "<div class='totalY'>";
    $html .= "<span class='labele totA'>TOTAL</span> <span class='val totA'>  0,00  </span>";
    $html .= "</div>";
    $html .= "<div class='totalZ'>";
    $html .= "<span class='labele totB'>TOTAL</span> <span class='val totB'>  0,00  </span>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<div class='tota'>";
    $html .= " <span class='value tota'>  +0,00  </span>";
    $html .= "</div>";
    $html .= "<hr>";
    $html .= "<div class='bottom'>";
    $html .= "<div class='provs'>";
    $html .=  formLabel(array("key"=>"<a href=provision.php>Provision année suivante : 0,00</a>"));
    $html .= "</div>";
    $html .= "<div class='provs comment'>";
    $html .=  formLabel(array("key"=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla venenatis fringilla odio ac semper. Sed aliquet lorem sit amet quam varius mattis. In in aliquet sem. Morbi vehicula arcu ut lectus consectetur, in feugiat tortor ullamcorper."));
    $html .= "</div>";
    $html .= "<div class='provs valid'>";
    $html .=  formLabel(array("key"=>"Validé le 00/00/0000 par USERID"));
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</div>"; 
    $html .= "</div>";
    return $html;
}

die(json_encode(array("code" => 404, "html" => "Invalid Request")));
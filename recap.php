<?php

    require_once("config.php");

    $opts = array();
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));
    $opts["user"] = auth($opts);
    $opts['user']['socList'] = array();
    $cookie = cookieInit();
    $getD = ((isset($_GET["d"]))? cryptDel($_GET["d"]) : false);
    if($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));

    $anaList = array();
   array_push($anaList, array("code" => "analyseannuelle", "txt" => "Analyse annuelle", "title" => "analyse annuelle",));
   array_push($anaList, array("code" => "analysemensuelle", "txt" => "Analyse mensuelle", "title" => "analyse mensuelle","href"=>"syntheseFac.php"));
   array_push($anaList, array("code" => "analysemission", "txt" => "Analyse par mission", "title" => "Analyse par mission"));
   array_push($anaList, array("code" => "analysecollaborateur", "txt" => "Analyse par collaborateur", "title" => "Analyse par collaborateur" , "href"=>"synthese_collab.php"));
   $sortNull = array("code" => "Trier", "txt" => "analyse annuelle ", "title" => "analyse annuelle");

    $k = array_search($cookie["index"]["sortCol"], array_column($anaList, "code"));
    if($k === false) $sortSelected = $sortNull;
    else
    {
       $k2 = array_search($anaList[$k]["code"], array_column($anaList, "code"));
       $sortSelected = array("code" => $anaList[$k2]["code"]);
    }

    $cont = "<div class='op'>";
    $cont .= "<div class='side'>";
    $cont .= formBtn(array("key" => "facturation", "ico" => "fa-solid fa-file-invoice", "txt" => "Facturation", "href"=>"resultat.php"));
    $cont .= formBtn(array("key" => "tarifs", "ico" => "fa-solid fa-user", "txt" => "Tarifs social" , "href"=>"tarifs_social.php"));
    $cont .= formBtn(array("key" => "arret", "ico" => "fa-solid fa-square", "txt" => "Arrêt des travaux"));
    $cont .= "</div>";
    $cont .= "<div class='side'>";
    $cont .= formSelect(array("key" => "sortAnalyse", "label" => "Affichage", "title"=>"trier par : analyse" , "selected"=> $sortSelected , "list" => $anaList ));
    $cont .= "</div>";
    $cont .= "</div>";
    $cont .= "<div class='years'>";

    $visibleYears = 10; 
    $startingYear = 2023;

    for ($i = 0; $i < $visibleYears; $i++) {
        $year = $startingYear - $i;
        $cont .= formBtn(array("key"=>$year, "txt"=>$year . "<div>+0.00</div>", "title"=>"l'Année", "class"=>"sliderButton", "extra"=>array("year")));
    }
    $cont .= formBtn(array("key"=>"angleRight", "ico"=>"fa-solid fa-angle-right", "title"=>"scroll right"));
    $cont .= "</div>";


    $cont .= "<div class='fields'>";
    $cont .= "<fieldset class='field one'>";
    $cont .= "<div class='all'>";

    $cont .= "<div class='top'>";
    $cont .= "<div class='bell'>";
    $cont .= formBtn(array("key"=>"bell", "ico"=>"fa-solid fa-bell", "id"=>"bell"));
    $cont .= "</div>";
    $cont .= "<div class='labl'>";
    $cont .= formLabel(array("key"=>"<a href=synthese_collab.php>2023</a>" ,));
    $cont .= "</div>";
    $cont .= "<div class='vertica'>";
    $cont .= formBtn(array("key"=>"vertica", "ico"=>"fa-solid fa-ellipsis-vertical", "id"=>"vertica"));
    $cont.="<div class='list off'>";
    $cont .= formBtn(array("key" => "bilan",      "txt" => "Fiche bilan"              ,"ico"=>"fa-solid fa-up-right-from-square"));
    $cont .= formBtn(array("key" => "abonnement", "txt" => "Abonnement"               ,"ico"=>"fa-solid fa-plus"));
    $cont .= formBtn(array("key" => "virement",   "txt" => "Virement CPTE à CPTE"     ,"ico"=>"fa-solid fa-plus"));
    $cont .= formBtn(array("key" => "provision",  "txt" => "Provision année suivante" ,"ico"=>"fa-solid fa-plus"));
    $cont .= formBtn(array("key" => "commentaire","txt" => "Ajouter un commentaire"   ,"ico"=>"fa-solid fa-pencil"));
    $cont .= formBtn(array("key" => "recap",      "txt" => "Valider le récap"         ,"ico"=>"fa-solid fa-lock"));
    $cont .= formBtn(array("key" => "rappel",     "txt" => "Ajouter un rappel"        ,"ico"=>"fa-solid fa-bell"));
    $cont .= "</div>";
    $cont .= "</div>";

    $cont .= "</div>"; // closing top
    $cont .= "<div class='chiffre'>";
    $cont .=  formLabel(array("key"=>"chiffre d'affaire : 0,00 - Effectif : 0,00"));
    $cont .= "</div>";
    $cont .="<div class='table'>";
   
    $cont .= "<div class='donne'>";
    $cont .= "<div class='value facture'>";
    $cont .= "<span class='labele fac'>FACTURE</span> <span class='val fac'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value juridique'>";
    $cont .= "<span class='labele jur'>JURIDIQUE</span> <span class='val jur'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value paye'>";
    $cont .= "<span class='labele pay'>PAYE / SOCIAL</span> <span class='val pay'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value debours'>";
    $cont .= "<span class='labele deb'>DEBOURS</span> <span class='val deb'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value frais'>";
    $cont .= "<span class='labele frais'>FRAIS</span> <span class='val frais'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";

    $cont .= "<div class='donneTrav'>";
    $cont .= "<div class='value travaux'>";
    $cont .= "<span class='labele trav'>TRAVAUX</span> <span class='val trav'><a href='synthese_trav.php?table=table2' target='blank'>  0,00 </a> </span>";
    $cont .= "</div>";
    $cont .="<div class='travaux-sublabels show'>";
       $cont .= "<span class='travauxSublabel saisie'>SAISIE</span> <span class='val '>  0,00  </span>";
        $cont .="<span class='travauxSublabel revision'>REVISION</span> <span class='val '>  0,00  </span>";
        $cont .="<span class='travauxSublabel accomp'>ACCOMPAGNEMENT</span> <span class='val '>  0,00  </span>";
        $cont .="<span class='travauxSublabel declaration'>DECLARATION</span> <span class='val '>  0,00  </span>";
        $cont .="<span class='travauxSublabel divers'>DIVERS COMPTA</span> <span class='val '>  0,00  </span>";
        $cont .="<span class='travauxSublabel cac'>CAC</span> <span class='val '>  0,00  </span>";
        $cont .="<span class='travauxSublabel abonne'><a href='synthese_trav.php?table=table2' target='blank'>ABONNEMENTS</a></span> <span class='val'>  <a href='synthese_trav.php?table=table1' target='blank'>0,00</a>  </span>";
    $cont .="</div>";
    $cont .= "<div class='value juridique'>";
    $cont .= "<span class='labele jur'>JURIDIQUE</span> <span class='val jur'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value paye'>";
    $cont .= "<span class='labele pay'>PAYE / SOCIAL</span> <span class='val pay'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value conseil'>";
    $cont .= "<span class='labele deb'>CONSEIL</span> <span class='val deb'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value debours'>";
    $cont .= "<span class='labele frais'>DEBOURS</span> <span class='val frais'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value frais'>";
    $cont .= "<span class='labele frais'>FRAIS</span> <span class='val frais'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";// closing donneTrav
  
    $cont .="</div>";// closing table

    $cont .= "<div class='tableX'>";
    $cont .= "<div class='donneCompta'>";
    $cont .= "<div class='value compta'>";
    $cont .= "<span class='labele compta'>COMPTA / CAC EXC</span> <span class='val comp'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value juridique'>";
    $cont .= "<span class='labele jur'>JURIDIQUE EXC</span> <span class='val jur'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value social'>";
    $cont .= "<span class='labele social'>SOCIAL EXC</span> <span class='val soc'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";
    $cont .= "<div class='donneComptaB'>";
    $cont .= "<div class='value compta'>";
    $cont .= "<span class='labele compta'>COMPTA / CAC EXC</span> <span class='val comp'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value juridique'>";
    $cont .= "<span class='labele jur'>JURIDIQUE EXC</span> <span class='val jur'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value social'>";
    $cont .= "<span class='labele social'>SOCIAL EXC</span> <span class='val soc'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value conseil'>";
    $cont .= "<span class='labele conseil'>CONSEIL EXC</span> <span class='val cons'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";
    $cont .= "</div>";

    $cont .= "<div class='letotal'>";
    $cont .= "<div class='totalA'>";
    $cont .= "<span class='labele totA'>TOTAL</span> <span class='val totA'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='totalB'>";
    $cont .= "<span class='labele totB'>TOTAL</span> <span class='val totB'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";

    $cont .= "<div class='tableY'>";
    $cont .= "<div class='donneHono'>";
    $cont .= "<div class='value hono'>";
    $cont .= "<span class='labele hono'>HONO.RESTANT</span> <span class='val hono'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value attente'>";
    $cont .= "<span class='labele attente'>ATTENTE VALIDE</span> <span class='val attente'><a href='synthese_trav.php'>  0,00  </a></span>";
    $cont .= "</div>";
    $cont .= "<div class='value solde'>";
    $cont .= "<span class='labele solde' > <a href='synthese_trav.php'> SOLDE N-1 </a></span> <span class='val solde'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";
    $cont .= "<div class='donneVirt'>";
    $cont .= "<div class='value virt'>";
    $cont .= "<span class='labele virt'>".formBtn(array("key" => "virement",   "txt" => "Virement CPTE à CPTE"))."</span> <span class='val virt'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value ant'>";
    $cont .= "<span class='labele ant'>REPORT ANT</span> <span class='val ant'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value post'>";
    $cont .= "<span class='labele post'>REPORT POST</span> <span class='val post'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='value debours'>";
    $cont .= "<span class='labele debours'>REPORT DEBOURS</span> <span class='val debours'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";
    $cont .= "</div>";

    $cont .= "<div class='letotalX'>";
    $cont .= "<div class='totalY'>";
    $cont .= "<span class='labele totA'>TOTAL</span> <span class='val totA'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "<div class='totalZ'>";
    $cont .= "<span class='labele totB'>TOTAL</span> <span class='val totB'>  0,00  </span>";
    $cont .= "</div>";
    $cont .= "</div>";

    $cont .= "<div class='tota'>";
    $cont .= " <span class='value tota'>  +0,00  </span>";
    $cont .= "</div>";

    $cont .= "<hr>";
    $cont .= "<div class='bottom'>";
    
    $cont .= "<div class='provs'>";
    $cont .=  formLabel(array("key"=>"<a href=provision.php>Provision année suivante : 0,00</a>"));
    $cont .= "</div>";
    $cont .= "<div class='provs comment'>";
    $cont .=  formLabel(array("key"=>"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla venenatis fringilla odio ac semper. Sed aliquet lorem sit amet quam varius mattis. In in aliquet sem. Morbi vehicula arcu ut lectus consectetur, in feugiat tortor ullamcorper."));
    $cont .= "</div>";
    $cont .= "<div class='provs valid'>";
    $cont .=  formLabel(array("key"=>"Validé le 00/00/0000 par USERID"));
    $cont .= "</div>";
    $cont .= "</div>";
    
    $cont .= "</div>"; //closing ALL
    $cont .= "</fieldset>";
    $cont .= "</div>";

    $html = html(array_merge($opts, array("cont" => $cont, "script" => "recap", "adr" => $getD)));

    die($html);

?>
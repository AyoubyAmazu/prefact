<?php


require_once("config.php");
   
$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$getD = ((isset($_GET["d"])) ? cryptDel($_GET["d"]) : false);
if ($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
// $filter = htmlFilter($opts);



$html = "<div class='all'>";
    $html .= "<div class='left'>";
        $html .= formBtn(array("key" => "refresh", "ico" => "fa-solid fa-arrows-rotate","title"=>"refresh"));
        $html .= formBtn(array("key" => "retour", "txt" => "Retour vers la prè-facturation", "ico" => "fa-solid fa-angles-left","title"=>"Retour vers la prè-facturation" , "href"=>"affiche_fact.php"));
    $html .= "</div>";
    $html .= "<div class='right'>";
        $html .= formLabel(array("key" => "Recherche par dossier : "));
        $html .= formInput(array("key" => "recherche", "type" => "text" ,"placeholder" => "Recherche par dossier"));
    $html .= "</div>";
$html .= "</div>";

$html .= "<div class='content'>";
    $html .= "<div class='title'>";
        $html .= formLabel(array("key" => "<a href='#'> AVT104300 -- BOUF jèrome : </a>"));
        $html .= formBtn(array("key" => "copier", "txt" => "Copier cette facture","title"=>"Copier cette facture"));
    $html .= "</div>";
    $html .= "<div class='text'>";
        $html .= "<div class='text-info'>";
            $html .= "<div class='text-informations'>";
                $html .= "<div>Travaux Juridique</div>";
                $html .= "<div>4 100,00</div>";
            $html .= "</div>";
            $html .= "<div class='text-content'>";
                $html .= "<div class='lines one'> Nos travaux se rapportant à notre assistance dans le cadre de l'acquisition du fonds decommerce la sr ROTISSERIE ET COMPAGNIE dont notamment : </div>";
                $html .= "<div class='lines two'>  1/ concernant l'acquisition du fonds de commerce (2.500 017) : </div>";
                $html .= "<div class='lines'> - Etude des pièces et du dossier : - Assistance dans le cadre des discussion avec la mandataire judiciaire : </div>";
                $html .= "<div class='lines'> - assistance dans la réalisation de la formalité d'enregistrement</div>";
                $html .= "<div class='lines'> - assistance dans le cadre de la formalité auprès du Greffe de l'achat du fonds surrextralt Kbis de la société </div>";
            $html .= "</div>";
            $html .= "<div class='text-end'>";
                $html .= "<div>H.T : 4 100,00</div>";
                $html .= "<div>Net a payer : 4 920,00</div>";
            $html .= "</div>";
        $html .= "</div>";
    $html .= "</div>";
$html .= "</div>";



 
echo html(array("user" => $user, "cont" => $html, "script" => "recup_model", "adr"=>$getD));
die();

?>
<?php


require_once("config.php");
   
$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$cookie = cookieInit();
$getD = ((isset($_GET["d"])) ? cryptDel($_GET["d"]) : false);
if ($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));
$page = 0;
if (isset($_POST["get_page"]) && !isset($_POST['back'])) $page = $_POST['get_page'];
if(isset($_POST["get_page"]) && isset($_POST['back'])) $page = $_POST['get_page']-2;

$select_facts = "SELECT * FROM facture";
$factures = dbSelect($select_facts, array("db"=>"prefact"));

if(isset($_POST['page']))$fact = $factures[$_POST['page']];
else $fact = $factures[$page];

$select_cat = "SELECT * FROM facture_cat WHERE facture_id =".$fact["id"];
$cats = dbSelect($select_cat, array("db"=>"prefact"));

if (isset($_POST["get_page"])){
    die(json_encode(["code"=>200,"html"=>displayFact()]));
}

$html = "<div class='all'>";
    $html .= "<div class='left'>";
        $html .= formBtn(array("key" => "refresh", "ico" => "fa-solid fa-arrows-rotate","title"=>"refresh"));
        $html .= formBtn(array("key" => "retour", "txt" => "Retour vers la prè-facturation", "ico" => "fa-solid fa-angles-left","title"=>"Retour vers la prè-facturation"));
    $html .= "</div>";
    $html .= "<div class='right'>";
        $html .= formLabel(array("key" => "Recherche par dossier : "));
        $html .= formInput(array("key" => "recherche", "type" => "text" ,"placeholder" => "Recherche par dossier"));
    $html .= "</div>";
$html .= "</div>";

$html .= "<div class='content'>";
    $html .= displayFact();
$html .= "</div>";



/*
* Composes Facture Html
*/
function displayFact()
{
    global $cats, $fact, $page, $factures;
    $html = "<div class='title'>";
        $html .= formLabel(array("key" => "<a href='#'> ".$fact["adr_id"]." -- BOUF jèrome : </a>"));
        $html .= formBtn(array("key" => "copier", "txt" => "Copier cette facture","title"=>"Copier cette facture"));
    $html .= "</div>";
    $html .= "<div class='text'>";
        $html .= "<div class='text-info'>";
            $html .= "<div class='text-informations'>";
            foreach($cats as $cat){
                $trav = dbSelect("SELECT nom FROM cat WHERE id =".$cat["cat_id"]);

                $html .= "<div>".$trav[0]["nom"]."</div>";
                $html .= "<div>".$cat["amount"]."</div>";
            $html .= "</div>";
            $html .= "<div class='text-content'>";

            $dets = dbSelect("SELECT * FROM facture_det WHERE fact_cat_id =".$cat["id"], array("db"=>"prefact"));
            foreach($dets as $det){
                $html .= "<div class='lines one'>".$det["titre"]."</div>";
            $temps_id = array_column(dbSelect("SELECT temps_id FROM facture_temps WHERE fact_det_id =".$det["id"], array("db"=>"prefact")), "temps_id");
            if(!empty($temps_id)) $temps = dbSelect("SELECT * FROM temps WHERE TEMPS_ID in (".implode(", ", $temps_id).")", array("db"=>"dia"));
            else $temps = array();
            foreach($temps as $temp){
                $html .= "<div class='lines'> - ".$temp["TEMPS_MEMO"]."</div>";
            }
                $html .= "<div class='lines two'>".$det["obs"]."</div>";
            }   
            $html .= "</div>";
            }

            $html .= "<div class='text-end'>";
                $html .= "<div>H.T : ".$fact["amount"]."</div>";
                $html .= "<div>Net a payer : ".($fact["amount"]*1.20)."</div>";
            $html .= "</div>";
        $html .= "</div>";
        $html .= "<div class='pagination' style='display:flex; width:100%;justify-content:space-between;margin-top:30px;'>";
            $html .= formBtn(array("key"=>"prev", "txt"=>"Avenant", "readonly"=>$page==0));
            $html .="<div class='txt page'>".($page+1)."</div>";
            $html .= formBtn(array("key"=>"next", "txt"=>"Suivent", "readonly"=>sizeof($factures)==$page+1));
            $html .= "</div>";
        $html .= "</div>";
    return $html;
}

die(html(array("user" => $user, "cont" => $html, "script" => "recup_model", "adr"=>$getD)));
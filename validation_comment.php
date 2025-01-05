<?php
require_once("config.php");

$user = auth(array("ajax" => true));
$opts = array("ajax" => true, "user" => $user);
$cookie = cookieInit();

if(isset($_POST["saveComment"])){
    $update_query = "UPDATE factures SET CommentairesFacture = '".$_POST["saveComment"]."' WHERE IdFact = '".$_POST["idFact"];
    dbExec($update_query, array("db"=>"fact"));
    die(json_encode(['code'=>200]));
}

$html = "<div class='popup displayRappelList'><div>";
$html .= "<div class='label'>Commentaire</div>";
// Duplicate "contenue" class four times
    $html .= "<div class='contenue'>";
    $html .= formInput(array("key" => "comment","txt"=>"commentaire" , "placeholder"=>"saisir votre commentaire", "value"=>$_POST["comment"]));
    $html .= "</div>";
$html .= "<div class='op'>";
$html .= formBtn(array("key" => "cancel", "txt" => "Fermer"));
$html .= formBtn(array("key" => "save", "txt" => "Ajouter"));
$html .= "</div>";
$html .= "</div></div>";

die(json_encode(array("code" => 200, "html" => $html)));
?>

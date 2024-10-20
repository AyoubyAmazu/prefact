
<?php

require_once("config.php");

$user = auth(array("ajax" => true));
$opts = array("ajax" => true, "user" => $user);
$cookie = cookieInit();

if (isset($_POST["deverroui"])) {
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
    $sql = "UPDATE `synthese` SET `verrouil` = '0' WHERE `synthese`.`adr` = (SELECT `id` FROM `adr` WHERE `code` = ".$_POST["adr"].")";
    $html = "<div class='popup displayDéverrouiller'><div>";
    $html .= "<div class='label'>Déverouiller ce dossier</div>";
    $html .= "<div class='txt'>Etes-vous sur vouloir déverouiller ce dossier ?</div>";
    $html .= "<div class='op'>";
    $html .= formBtn(array("key" => "cancel", "txt" => "Fermer"));
    $html .= "</div>";
    $html .= "</div></div>";

    die(json_encode(array("code" => 200, "html" => $html)));
}


$html = "<div class='popup displayDéverrouiller'><div>";
$html .= "<div class='label'>Déverouiller ce dossier</div>";
$html .= "<div class='txt'>Etes-vous sur vouloir déverouiller ce dossier ?</div>";


$html .= "<div class='op'>";
$html .= formBtn(array("key" => "cancel", "txt" => "Non"));
$html .= formBtn(array("key" => "save", "txt" => "Oui"));
$html .= "</div>";
$html .= "</div></div>";

die(json_encode(array("code" => 200, "html" => $html)));




?>
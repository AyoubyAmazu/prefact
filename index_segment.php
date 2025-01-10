<?php

require_once("config.php");

if (isset($_POST["update_segment"])) {
    $sql = "UPDATE adr SET segment = '".strtoupper($_POST["segment"])."' WHERE adr.code = '".$_POST["adr"]."'";
    dbExec($sql,  array("db" => "prefact"));
    $html = "<div class='popup'>";
        $html.="<div>";
        $html .= "<div class='label'>Modifier Segmentation</div>";
        $html .="<div class='op'>";
        $html .= formBtn(array("key" => "cancel", "txt" => "Fermer"));
        $html .="</div>";
        $html .= "</div>";
    $html.="</div>";
    die(json_encode(array("code" => 200, "html" => $html)));
};

$user = auth(array("ajax" => true));
$opts = array("ajax" => true, "user" => $user);
$cookie = cookieInit();



$segments = json_decode(DATAsegment, true);
$segmeList = array();
foreach ($segments as $segment) {

    array_push($segmeList, array("code" => $segment["code"], "txt" => "<span style = color:" . $segment["color"] . ">" . $segment["abr"] . "</span >  - " . $segment["txt"], "title" => $segment["abr"], "color" => $segment["color"]));
}
$html = "<div class='popup displaySegme'><div>";
$html .= "<div class='label'>Modifier Segmentation</div>";
$html .= formCheckbox(array("key" => "col", "list" => $segmeList));
$html .= "<div class='op'>";
$html .= formBtn(array("key" => "cancel", "txt" => "Annuler"));
$html .= formBtn(array("key" => "save", "txt" => "Valider"));
$html .= "</div>";
$html .= "</div></div>";

die(json_encode(array("code" => 200, "html" => $html)));

<?php

    require_once("config.php");

    $opts = array();
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));
    $opts["user"] = auth($opts);
    $cookie = cookieInit();

    $getD = ((isset($_GET["d"]))? cryptDel($_GET["d"]) : false);
    if($getD == false) err(array_merge($opts, array("txt" => "Erreur d'accès", "btn" => APPurl)));

    $cont = "";

    $html = html(array_merge($opts, array("cont" => $cont, "script" => "recap", "adr" => $getD)));
    die($html);

?>
<?php

    require_once("config.php");

    $opts = array();
    $opts["ajax"] = true;
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia"))));
    $opts["user"] = auth($opts);
    $cookie = cookieInit();

    if(!isset($_POST["obj"])) err();
    $obj = json_decode($_POST["obj"], true);

    if(isset($obj["title"]["desc"])) $cookie["title"]["desc"] = $obj["title"]["desc"];
    if(isset($obj["title"]["resp"])) $cookie["title"]["resp"] = $obj["title"]["resp"];
    if(isset($obj["title"]["mission"])) $cookie["title"]["mission"] = $obj["title"]["mission"];

    cookieSave($cookie);

    die(json_encode(array("code" => 200)));
    
?>
<?php
require_once("config.php");

$self = APPurl;
$user = auth(array("script" => $self));
$opts = array("user" => $user);
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "fact", "prefact"))));

$select = "SELECT * FROM z_fact.divers_travaux;";
$drv = dbSelect($select, array("db" => "fact"));

// foreach ($drv as $d) {
//     $insert = "INSERT INTO `prefact`.`cat` (`txt`, `nom`, `prest_code`, `prest_start`)
//     VALUES ('txt', ':nom', ':pres', 0);";
//     $insert = str_replace(":nom", $d["Description"], $insert);
//     $insert = str_replace(":pres", $d["CodePresta"], $insert);
//     dbExec($insert, array("db" => "prefact"));
// }

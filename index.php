<?php

    require_once("config.php");

    $opts = array();
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));
    $opts["user"] = auth($opts);


    $opts["filter"] = htmlFilterData($opts);

    $where = array();
    array_push($where, "`id` IN (SELECT `adr` FROM `synthese` WHERE `annee` = " . $opts["filter"]["annee"] . ")");

    if($opts["filter"]["soc"]["selected"] == "") array_push($where, "`soc` IN ('" . implode("', '", array_column($opts["filter"]["soc"]["list"], "code")) . "')");
    else array_push($where, "`soc` = '" . $opts["filter"]["soc"]["selected"]["code"] . "'");

    

    $sql = "SELECT *";
    $sql .= ", (SELECT `temps_dur` FROM `synthese` x WHERE x.`adr` = z.`id` AND `annee` = " . $opts["filter"]["annee"] . " LIMIT 1) AS 'temps_dur'";
    $sql .= " FROM `adr` z" . ((count($where) == 0)? "" : (" WHERE " . implode(" AND ", $where)));
    $list = dbSelect($sql, array_merge($opts, array("db" => "prefact")));


    $cont = "";

    $html = html(array_merge($opts, array("cont" => $cont, "script" => "index")));
    die($html);

?>
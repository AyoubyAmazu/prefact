<?php

    function auth($opts = array())
    {
        if(!isset($opts["ajax"])) $opts["ajax"] = false;

        //$user = FSCauth(array("script" => APPself, "ajax" => $opts["ajax"]));

        $user = array("id" => "JBC", "first" => "Jean-Baptiste", "last" => "CAZAUX", "email" => "jb.cazaux@fidsud.fr", "admin" => false);
        // $user = array("id" => "MAOD", "first" => "Marie", "last" => "Onteiral-Diaz", "email" => "marie.onteiral-diaz@fidsud.fr", "admin" => false);
        // $user = array("id" => "SIM", "first" => "Siham", "last" => "Moufid", "email" => "siham.moufid@fidsud.fr", "admin" => false);
        //$user = array("id" => "CED", "first" => "Cédric", "last" => "DEVORA", "email" => "cedric.devora@fidsud.fr", "admin" => false);
        $user = array("id" => "RRO", "first" => "Rachel", "last" => "Rodrigez", "email" => "rachel.rodrigez@fidsud.fr", "admin" => false);

        $sql = "SELECT `SOC_CODE`, `COL_DATE_ENTREE`, `COL_DATE_SORTIE` FROM `collab` WHERE `COL_CODE` LIKE '" . $user["id"] . "' LIMIT 1";
        $result = dbSelect($sql, array_merge($opts, array("db" => "dia")));
        if(count($result) == 0) err(array_merge($opts, array("txt" => "Accès refusé", "det" => "Collaborateur non trouvé : " . $user["id"])));
        if($result[0]["COL_DATE_ENTREE"] != "" && $result[0]["COL_DATE_ENTREE"] > date("Ymd")) err(array_merge($opts, array("txt" => "Accès refusé", "det" => "Date d'entrée : " . dtDate($result[0]["COL_DATE_ENTREE"]))));
        if($result[0]["COL_DATE_SORTIE"] != "" && $result[0]["COL_DATE_SORTIE"] < date("Ymd")) err(array_merge($opts, array("txt" => "Accès refusé", "det" => "Date de sortie : " . dtDate($result[0]["COL_DATE_SORTIE"]))));
        $user["soc"] = $result[0]["SOC_CODE"];
        
        $sql = "SELECT `SOC_CODE` FROM `collab_soc` WHERE `COL_CODE` LIKE '" . $user["id"] . "'";
        $result = dbSelect($sql, array_merge($opts, array("db" => "dia")));
        $user["socList"] = array_filter(array_unique(array_column($result, "SOC_CODE")));
        return $user;
    }
        
?>
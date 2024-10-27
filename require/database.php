<?php

    function dbStart($opts = array())
    {
        $result = array();
        $db = json_decode(DB, true);

        if(!isset($opts["db"])) $opts["db"] = array_keys($db);

        foreach($opts["db"] as $v)
        {
            if(!isset($db[$v])) err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => "Base de données non identifiée : " . $v)));
            if(!isset($db[$v]["host"])) err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => "Serveur de la base de données non identifié : " . $v)));
            if(!isset($db[$v]["port"])) err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => "Port de la base de données non identifié : " . $v)));
            if(!isset($db[$v]["name"])) err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => "Nom de la base de données non identifié : " . $v)));
            if(!isset($db[$v]["username"])) err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => "Nom d'utilisateur de la base de données non identifié : " . $v)));
            if(!isset($db[$v]["password"])) err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => "Mot de passe de la base de données non identifié : " . $v)));
            
            try { $result[$v] = new PDO("mysql:host=" . $db[$v]["host"] . ";port=" . $db[$v]["port"] . ";dbname=" . $db[$v]["name"] . ";charset=utf8", $db[$v]["username"], $db[$v]["password"]); }
            catch(Exception $e) { err(array_merge($opts, array("txt" => "Erreur de connexion à une base de données", "det" => $v . " | " . $e -> getMessage()))); }
        }

        return $result;
    }

    function dbSelect($sql, $opts = array())
    {
        if(!isset($opts["db"])) $opts["db"] = "prefact";
        if(!isset($opts["conn"])) $opts["conn"] = dbStart(array_merge($opts, array("db" => array($opts["db"]))));

        try { $result = $opts["conn"][$opts["db"]] -> query($sql) -> fetchAll(); }
        catch(Exception $e) { err(array_merge($opts, array("txt" => "Erreur de d'execution d'une requête SQL", "det" => $opts["db"] . " | " . $sql . " | " . $e -> getMessage()))); }

        return $result;
    }
    
    function dbExec($sql, $opts = array())
    {
        if(!isset($opts["db"])) $opts["db"] = "prefact";
        if(!isset($opts["conn"])) $opts["conn"] = dbStart(array_merge($opts, array("db" => array($opts["db"]))));

        try { $opts["conn"][$opts["db"]] -> exec($sql); }
        catch(Exception $e) { err(array_merge($opts, array("txt" => "Erreur de d'execution d'une requête SQL", "det" => $opts["db"] . " | " . $sql . " | " . $e -> getMessage()))); }
    }

?>
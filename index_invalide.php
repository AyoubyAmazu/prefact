
<?php

    require_once("config.php");
    $user = auth(array("ajax" => true));
    $opts = array("ajax" => true, "user" => $user);
    $cookie = cookieInit();
    
    if(isset($_POST["valide"]))
    {
        $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
        $sql = "UPDATE `synthese` SET `solde_valid` = ".$_POST['valide']." WHERE `synthese`.`adr` = (SELECT `id` FROM `adr` WHERE `code` = '".$_POST["adr"]."')";
        dbExec($sql, array_merge($opts, array("db" => "prefact")));
        $html = "<div class='popup displayInvalide'><div>";
        $html .= "<div class='label'>Mis à jour avec succès.</div>";
        $html .= "<div class='op'>";
        $html .= formBtn(array("key" => "cancel", "txt" => "Fermer"));
        $html .= "</div>";
        $html .= "</div></div>";
    
        die(json_encode(array("code" => 200, "html" => $html)));
    }


    
    $html = "<div class='popup displayInvalide'><div>";
        $html .= "<div class='label'>Invalider le solde</div>";
        $html .= "<div class='txt'>Etes-vous sur vouloir invalider le solde de ce dossier ?</div>";
        $html .= "<div class='op'>";
            $html .= formBtn(array("key" => "cancel", "txt" => "Non"));
            $html .= formBtn(array("key" => "save", "txt" => "Oui"));
        $html .= "</div>";
    $html .= "</div></div>";
    
    die(json_encode(array("code" => 200, "html" => $html)));



    
?>
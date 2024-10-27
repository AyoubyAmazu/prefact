<?php

    require_once("config.php");

    $user = auth(array("ajax" => true));
    $opts = array("ajax" => true, "user" => $user);
    $cookie = cookieInit();
    
    if(isset($_POST['comment']))
    {
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
    $sql = "UPDATE adr SET obs = '".$_POST["comment"]."' WHERE adr.code = '".$_POST["adr"]."'";
    dbExec($sql, array_merge($opts, array("db" => "prefact")));
        $html = "<div class='popup displayCommentaire'><div>";
        $html .= "<div class='label'>Modifier la commentaire</div>";
        $html .= "<div class='op'>";
            $html .= formBtn(array("key" => "cancel", "txt" => "Fermer"));
        $html .= "</div>";
    $html .= "</div></div>";
    
    die(json_encode(array("code" => 200, "html" => $html)));
    }

    
    $html = "<div class='popup displayCommentaire'><div>";
        $html .= "<div class='label'>Modifier la commentaire</div>";
        $html .= formTextarea(array("key" => "comment", "placeholder" => "Modifier le commentaire"));


        $html .= "<div class='op'>";
            $html .= formBtn(array("key" => "cancel", "txt" => "Annuler"));
            $html .= formBtn(array("key" => "save", "txt" => "Valider"));
        $html .= "</div>";
    $html .= "</div></div>";
    
    die(json_encode(array("code" => 200, "html" => $html)));





    
?>
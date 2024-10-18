<?php

    require_once("config.php");

    $user = auth(array("ajax" => true));
    $opts = array("ajax" => true, "user" => $user);
    $cookie = cookieInit();
    
    if(isset($_POST['edit_comment']))
    {
        
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
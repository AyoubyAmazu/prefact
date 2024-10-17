
<?php

    require_once("config.php");

    $user = auth(array("ajax" => true));
    $opts = array("ajax" => true, "user" => $user);
    $cookie = cookieInit();
    


    
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
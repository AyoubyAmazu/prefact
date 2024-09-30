<?php
    
    require_once("config.php");

    $opts = array();
    $opts["ajax"] = true;
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia"))));
    //$opts["user"] = auth($opts);

    $html = "<div class='popup' id='help'><div>";
        $html .= "<div class='logo'><img src='" . APPurl . "media/fs50.png?v=" . APPversion . "'><div>" . APPname . "</div></div>";
        if(APPdesc != "") $html .= "<div class='desc'>" . APPdesc . "</div>";
        $html .= "<div class='op'>" . formBtn(array("key" => "cancel", "txt" => "Fermer")) . "</div>";
        $html .= "<div class='legal'>";
            $html .= "Cette application utilise des cookies pour vous reconnaître lors de vos prochaines visites et sécuriser votre navigation.";
            $html .= " <br>En utilisant cette application, vous acceptez l'utilisation des cookies et technologies similaires.";
            $html .= " <br>Vous acceptez aussi nos " . ((APPterms == "")? "Conditions d'utilisation" : ("<a title='Consulter' href='" . APPterms . "' target='_blank'>Conditions d'utilisation</a>"));
            $html .= " et notre " . ((APPprivacy == "")? "Politique de confidentialité" : ("<a title='Consulter' href='" . APPprivacy . "' target='_blank'>Politique de confidentialité</a>")) . ".";
        $html .= "</div>";
        $html .= "<div class='copy'><i class='fa-regular fa-copyright'></i> " . APPauthor . ", " . date("Y") . " - Tous droits reservés</div>";
    $html .= "</div></div>";

    die(json_encode(array("code" => 200, "html" => $html)));

?>
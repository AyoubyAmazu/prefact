<?php

    require_once("config.php");

    $user = auth(array("ajax" => true));
    $opts = array("ajax" => true, "user" => $user);
    $cookie = cookieInit();
    


    $segmeList = array();
    array_push($segmeList, array("code" => "dossierA", "txt" => "<span>A</span> - Rentable, pas d'impayés, acheteur, prescript", "title" => "Code du dossier"));
    array_push($segmeList, array("code" => "dossierB", "txt" => "<span >B</span>  - Rentable, pas d'impayés, à potentiel/prescript", "title" => "Nom du dossier"));
    array_push($segmeList, array("code" => "dossierc", "txt" => "<span >C</span>  - Rentable sans difficultés de paiement", "title" => "Groupe du dossier"));
    array_push($segmeList, array("code" => "dossierD", "txt" => "<div>D</div>  - Doit devenir rentable 1 an, a régulariser", "title" => "Groupe du dossier"));
    array_push($segmeList, array("code" => "dossierE", "txt" => "<aside>E</aside>  - Consommateur de ressources, créances impayées", "title" => "Groupe du dossier"));
    array_push($segmeList, array("code" => "dossierZ", "txt" => "Z  - Non qualifiable", "title" => "Groupe du dossier"));


    $html = "<div class='popup displaySegme'><div>";
    $html .= "<div class='label'>Modifier Segmentation</div>";
    $html .= formCheckbox(array("key" => "col", "list" => $segmeList));
    $html .= "<div class='op'>";
        $html .= formBtn(array("key" => "cancel", "txt" => "Annuler"));
        $html .= formBtn(array("key" => "save", "txt" => "Valider"));
    $html .= "</div>";
$html .= "</div></div>";

die(json_encode(array("code" => 200, "html" => $html)));
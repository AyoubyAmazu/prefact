<?php
    require_once("config.php");
    $user = auth(array("ajax" => true));
    $opts = array("ajax" => true, "user" => $user);
    $cookie = cookieInit();

    $sortList = array();
    array_push($sortList, array("code" => "dossier", "txt" => "Dossier", "readonly" => true));
    array_push($sortList, array("code" => "dossierCode", "txt" => "Code", "title" => "Code du dossier", "value" => in_array("dossierCode", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "dossierNom", "txt" => "Nom", "title" => "Nom du dossier", "value" => in_array("dossierNom", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "dossierGroupe", "txt" => "Groupe", "title" => "Groupe du dossier", "value" => in_array("dossierGroupe", $cookie["index"]["displayCol"])));
   
    array_push($sortList, array("code" => "temps", "txt" => "Temps", "readonly" => true));
    array_push($sortList, array("code" => "tempsdurée", "txt" => "Durée", "title" => "Code du dossier", "value" => in_array("tempsdurée", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "tempscout",  "txt" => "Coût de revient", "title" => "Nom du dossier", "value" => in_array("tempscout", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "tempsdebours", "txt" => "Débours", "title" => "Groupe du dossier", "value" => in_array("tempsdebours", $cookie["index"]["displayCol"])));
    
    array_push($sortList, array("code" => "factures", "txt" => "Factures", "readonly" => true));
    array_push($sortList, array("code" => "facturesQuantité", "txt" => "Quantité", "title" => "Code du dossier", "value" => in_array("facturesQuantité", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "facturesEmises", "txt" => "Emises", "title" => "Nom du dossier", "value" => in_array("facturesEmises", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "facturesDébours", "txt" => "Débours", "title" => "Groupe du dossier", "value" => in_array("facturesDébours", $cookie["index"]["displayCol"])));
   
    array_push($sortList, array("code" => "statut", "txt" => "Statut", "readonly" => true));
    array_push($sortList, array("code" => "statutSegmentation", "txt" => "Segmentation", "title" => "Code du dossier", "value" => in_array("statutSegmentation", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "statutValue", "txt" => "+/-values", "title" => "Nom du dossier", "value" => in_array("statutValue", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "statutCreance", "txt" => "Solde des créances", "title" => "Groupe du dossier", "value" => in_array("statutCreance", $cookie["index"]["displayCol"])));
    
    array_push($sortList, array("code" => "operations", "txt" => "Opérations", "readonly" => true));
    array_push($sortList, array("code" => "operationsEnCours", "txt" => "En cours", "title" => "Code du dossier", "value" => in_array("operationsEnCours", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "operationsValide", "txt" => "Validation du RD", "title" => "Nom du dossier", "value" => in_array("operationsValide", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "operationsAdministraftif", "txt" => "Traitement administratif", "title" => "Groupe du dossier", "value" => in_array("operationsAdministraftif", $cookie["index"]["displayCol"])));
   
    array_push($sortList, array("code" => "provisions", "txt" => "Provisions", "readonly" => true));
    array_push($sortList, array("code" => "provisionsEnCours", "txt" => "En cours", "title" => "Code du dossier", "value" => in_array("provisionsEnCours", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "provisionsValide", "txt" => "Validation du RD", "title" => "Nom du dossier", "value" => in_array("provisionsValide", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "provisionAdministratif", "txt" => "Traitement administratif", "title" => "Groupe du dossier", "value" => in_array("provisionAdministratif", $cookie["index"]["displayCol"])));
   
    array_push($sortList, array("code" => "autres", "txt" => "Autres", "readonly" => true));
    array_push($sortList, array("code" => "lettresMission", "txt" => "Lettre de mission", "title" => "Code du dossier", "value" => in_array("lettresMission", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "Montant", "txt" => "Montant", "title" => "Nom du dossier", "value" => in_array("Montant", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "SoldeValidé", "txt" => "Solde validé", "title" => "Groupe du dossier", "value" => in_array("SoldeValidé", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "DossierVerrouillé", "txt" => "Dossier verrouillé", "title" => "Code du dossier", "value" => in_array("DossierVerrouillé", $cookie["index"]["displayCol"])));
    array_push($sortList, array("code" => "Commentaire", "txt" => "Commentaire", "title" => "Code du dossier", "value" => in_array("Commentaire", $cookie["index"]["displayCol"])));

    $html = "<div class='popup displayParam'><div>";
        $html .= "<div class='label'>Paramètres du tableau</div>";
        $html .= formCheckbox(array("key" => "col", "label" => "Collones à afficher", "list" => $sortList));
        $html .= "<div class='op'>";
            $html .= formBtn(array("key" => "cancel", "txt" => "Annuler"));
            $html .= formBtn(array("key" => "save", "txt" => "Valider"));
        $html .= "</div>";
    $html .= "</div></div>";
    
    die(json_encode(array("code" => 200, "html" => $html)));
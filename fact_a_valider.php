<?php

   require_once("config.php");

   $self = APPurl;
   $user = auth(array("script" => $self));
   $opts = array("user" => $user);
   $cookie = cookieInit();
   $filter = htmlFilter($opts);

   $checkList = array();
   array_push($checkList, array("code"=>"check"));


    $html = "<div class='hd'>";
        $html .= "<div class='left'>";
        $html .= formBtn(array("key" => "refresh", "ico" => "fa-solid fa-arrows-rotate","title"=>"refresh"));
        $html .= formBtn(array("key" => "facturesTerminé", "ico" =>"fa-solid fa-file-contract", "txt" => "Factures terminées"));
        $html .= formBtn(array("key" => "facturesAvalider", "ico" => "fa-solid fa-file-signature", "txt" => "Factures à valider"));
        $html .= formBtn(array("key" => "lesFactures", "ico" => "fa-solid fa-file-arrow-up", "txt" => "Toutes les factures"));
    $html .= "</div>";
    $html .= "<div class='left'>";
    $html .= formBtn(array("key" => "print" , "ico" => "fa-solid fa-print" , "title"=>"imprimé"));
    $html .= "</div>";
    $html .= "</div>";


    $html .= "<div class='centre'>";
    $html .="<table>";
    $html .="<tr>";
    $html .= "<th class='rd'> RD</th>";
    $html .= "<th>Crée par</th>";
    $html .= "<th>Code de Dossier</th>";
    $html .= "<th>Nom de Dossier</th>";
    $html .= "<th>Date de facture</th>";
    $html .= "<th>Date de validation</th>";
    $html .= "<th>Date d'envoi dans dia</th>";
    $html .= "<th>Modalités paiment</th>";
    $html .= "<th>Montant</th>";
    $html .= "<th>Débours</th>";
    $html .= "<th>Verrou factures</th>";
    $html .= "<th>  </th>";

    $html .= "</tr>";
    $html .="<tr class='comm' >";

    $html .= "<td colspan='12'>";
    $html .="<div class='recherch'>";
    $html .= formInput(array("key"=>"rech","label"=>"Rechercher un dossier","placeholder"=>"rechercher un dossier" ));
    $html .= "</div>";

    $html .= "</td>";

    $html .= "</tr>";

    $html .="<tr>";
    $html .= "<td class='left'> DIE</td>";
    $html .= "<td>chpo</td>";
    $html .= "<td>ALB5446</td>";
    $html .= "<td>OPCO ATLAS</td>";
    $html .= "<td>";
    $html .= "<div class='date'>";
    $html .= formDp(array("key"=>"date","value"=>"27/06/2023"));
    $html .= "</div>";
    $html .= "</td>";
    $html .= "<td>22:33:1166</td>";
    $html .= "<td>22:33:1166</td>";
    $html .= "<td>---</td>";
    $html .= "<td>0,00</td>";
    $html .= "<td>---</td>";
    $html .= "<td>";
    $html .= "<div class='checkbox'>";
    $html .= formCheckbox(array("key"=>"verr",  "list" => $checkList));
    $html .= "</div>";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<div class='verticalB'>";
    $html .= formBtn(array("key"=>"icoVertica" , "ico"=>"fa-solid fa-ellipsis-vertical"));
    $html.="<div class='list off'>";
    $html .= formBtn(array("key" => "open",      "txt" => "ouvrir la facture"              ,"ico"=>"fa-solid fa-envelope-open-text" , "href"=>"validation_recap.php"));
    $html .= formBtn(array("key" => "commentaire", "txt" => "Ajouter un commentaire"               ,"ico"=>"fa-solid fa-pencil"));
    $html .= formBtn(array("key" => "close",   "txt" => "Annuler la facture"     ,"ico"=>"fa-solid fa-ban"));
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</td>";
    $html .= "</tr>";


    $html .="<tr>";
    $html .= "<td class='left'> DIE</td>";
    $html .= "<td>chpo</td>";
    $html .= "<td>ALB5446</td>";
    $html .= "<td>OPCO ATLAS</td>";
    $html .= "<td>";
    $html .= "<div class='date'>";
    $html .= formDp(array("key"=>"date","value"=>"27/06/2023"));
    $html .= "</div>";
    $html .= "</td>";
    $html .= "<td>22:33:1166</td>";
    $html .= "<td>22:33:1166</td>";
    $html .= "<td>---</td>";
    $html .= "<td>0,00</td>";
    $html .= "<td>---</td>";
    $html .= "<td>";
    $html .= "<div class='checkbox'>";
    $html .= formCheckbox(array("key"=>"verr",  "list" => $checkList));
    $html .= "</div>";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<div class='verticalB'>";
    $html .= formBtn(array("key"=>"icoVertica" , "ico"=>"fa-solid fa-ellipsis-vertical"));
    $html .= "<div class='list off'>";
    $html .= formBtn(array("key" => "open",      "txt" => "ouvrir la facture"              ,"ico"=>"fa-solid fa-envelope-open-text" , "href"=>"validation_recap.php"));
    $html .= formBtn(array("key" => "commentaire", "txt" => "Ajouter un commentaire"               ,"ico"=>"fa-solid fa-pencil"));
    $html .= formBtn(array("key" => "close",   "txt" => "Annuler la facture"     ,"ico"=>"fa-solid fa-ban"));
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</td>";
    $html .= "</tr>";

    $html .="<tr>";
    $html .= "<td class='left'> DIE</td>";
    $html .= "<td>chpo</td>";
    $html .= "<td>ALB5446</td>";
    $html .= "<td>OPCO ATLAS</td>";
    $html .= "<td>";
    $html .= "<div class='date'>";
    $html .= formDp(array("key"=>"date","value"=>"27/06/2023"));
    $html .= "</div>";
    $html .= "</td>";
    $html .= "<td>22:33:1166</td>";
    $html .= "<td>22:33:1166</td>";
    $html .= "<td>---</td>";
    $html .= "<td>0,00</td>";
    $html .= "<td>---</td>";
    $html .= "<td>";
    $html .= "<div class='checkbox'>";
    $html .= formCheckbox(array("key"=>"verr",  "list" => $checkList));
    $html .= "</div>";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<div class='verticalB'>";
    $html .= formBtn(array("key"=>"icoVertica" , "ico"=>"fa-solid fa-ellipsis-vertical"));
    $html .= "<div class='list off'>";
    $html .= formBtn(array("key" => "open",      "txt" => "ouvrir la facture"              ,"ico"=>"fa-solid fa-envelope-open-text" , "href"=>"validation_recap.php"));
    $html .= formBtn(array("key" => "commentaire", "txt" => "Ajouter un commentaire"               ,"ico"=>"fa-solid fa-pencil"));
    $html .= formBtn(array("key" => "close",   "txt" => "Annuler la facture"     ,"ico"=>"fa-solid fa-ban"));
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</td>";
    $html .= "</tr>";


    $html .="</table>";
    $html .= "</div>";




    echo html(array("user" => $user, "main" => $html, "title" => "", "script" => "fact_a_valider", "type" => "filter", "filter" => $filter));

    die();


 ?>

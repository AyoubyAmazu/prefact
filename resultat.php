<?php

    require_once("config.php");

    $self = APPurl;
    $user = auth(array("script" => $self));
    $opts = array("user" => $user);
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
    $cookie = cookieInit();
    // $opts['adr'] = "";
    // $filter = htmlFilter($opts);

   

    $youlist = array();
    array_push($youlist, array("code" => "year", "txt" => "2021/05/08", "title" => "2021/05/08"));
    array_push($youlist, array("code" => "year", "txt" => "2023/08/15", "title" => "2021/05/08"));
    array_push($youlist, array("code" => "year", "txt" => "2023/08/01", "title" => "2023/08/01"));

    $sortNull = array("code" => "nouvelle_facture", "txt" => "Nouvelle facture");

    $k = array_search($cookie["index"]["sortCol"], array_column($youlist, "code"));
    if ($k === false) {
        $sortSelected = $sortNull;
    } else {
     $k2 = array_search($youlist[$k]["code"], array_column($youlist, "code"));
        $sortSelected = array("code" => $youlist[$k]["code"]);
    }

  


    $sql = "SELECT TEMPS_DATE, COL_CODE, PREST_CODE, EXO_CODE, TEMPS_MEMO, TEMPS_M_QTE, TEMPS_DUREE, TEMPS_M_PV";
    $sql .= " FROM temps";
    $sql .= " WHERE EXO_CODE = 2022 AND ADR_ID = 2856";
    $sql .= " ORDER BY TEMPS_DATE DESC";
    $sql .= " LIMIT 50";
    $list = dbSelect($sql, array("db" => "dia"));
    



    $html = "<div class='all'>";
        $html .= "<div class='right-div'>";
        $html .= formBtn(array("key" => "affiche-exep", "txt" => "Afficher l'exceptionnel"));
        $html .= formBtn(array("key" => "presentation", "txt" => "Prestations facturèes"));
        $html .= formBtn(array("key" => "basculer", "txt" => "Basculer vers synthèse du dossier" , "href"=>"synthese.php"));
        $html .= "</div>";
        $html .= "<div class='left-div'>";
        $html .= formLabel(array(
            "key" => "Sèlection d'une facture non terminèe : ",));
        $html .= formSelect(array("key" => "sortAnalyse","selected" => $sortSelected,"list" => $youlist));
        $html .= formBtn(array("key" => "affiche_pre_facture", "ico" => "eye", "txt" => "Afficher la pré-facture" , "href"=>"affiche_fact.php"));
        $html .= "</div>";
    $html .= "</div>";






    //---------------- Travaux compta -------------------//
    $html .= "<fieldset class='fieldset1'>";
    $html .= "<legend>Travaux Comptable et fiscaux</legend>";
    $html .= "<table id='myTable'  class='customers '>";
        $html .= "<tr>";
        $html .= "<th class='first-1'>".formBtn(array("key" => "first-check", "ico" => "check"));"</th>"; 
        $html .= "<th class='second-2'>Date</th>";
        $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('myTable')\">Collab</p></th>";
        $html .= "<th class='second-2 prest-header'><p onclick=\"sortTableByPrest('myTable')\">Prest</p></th>";
        $html .= "<th class='no-line'><div class='all-year'><p class='exerciceLabel'>Exercice</p></div></th>";
        $html .= "<th>Titre</th>";
        $html .= "<th class='last-3'>Qte</th>";
        $html .= "<th class='last-3'>Duree</th>";
        $html .= "<th class='last-3'>PV</th>";
        $html .= "</tr>";
 

        foreach ($list as $row) {

         $prest=(strpos($row['PREST_CODE'], '@', 0)==0) ? str_replace("@","",$row['PREST_CODE']) : $row['PREST_CODE'];

          if ((( $prest>=200) && ( $prest<400)) || (strpos( $prest, 'T2')!==false && strpos( $prest, 'T2', 0)==0)){
                $html .= "<tr>";
                $formattedDate = date("d/m/Y", strtotime($row['TEMPS_DATE']));
        
                $html .= "<td>" .formCheckbox(array("key" => "sortDir", "list" => array(array("code" => "ASC", "txt" => "Ascendant", "value" => ($cookie["index"]["sortDir"] == "ASC"))))). "</td>";
                $html .= "<td class='centered-td'>" . $formattedDate . "</td>";
                $html .= "<td class='centered-td code-row'><a href='#'>" . $row['COL_CODE'] . "</a></td>";
                $html .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" .formInput(array("key" => "prest_code", "type" => "text", "align" => "c",  "value" =>  $row['PREST_CODE'])) . "</span></p></td>";
                $html .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . formInput(array("key" => "exo_code", "type" => "text", "align" => "c",  "value" =>  $row['EXO_CODE'])) . "</span></p></td>";
                $html .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
                $html .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
                $html .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
                $html .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";
                $html .= "</tr>";
            
        }
    }

    $html .= "</table>";
    $html .= "<div class='add-table-btn'>";
    $html .= formBtn(array("key" => "Ajouter-facture","ico" => "plus", "txt" => "Ajouter â la facture" , "href"=> "affiche_fact.php"));
    $html .= formBtn(array("key" => "ne-pas-facturer","ico" => "ban", "txt" => "Ne pas facturer"));
    $html .= "</div>";
    $html .= "</fieldset>";



    //--------------- Travaux social -----------------//

    $html .= "<fieldset class='fieldset2'>";
    $html .= "<legend>Travaux Sociaux</legend>";
    $html .= "<table id='myTable2' class='customers'>";
        $html .= "<tr>";
        $html .= "<th class='first-1'>".formBtn(array("key" => "first-check", "ico" => "check-double"));"</th>"; 
        $html .= "<th class='second-2'>Date</th>";
        $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('myTable2')\">Collab</p></th>";
        $html .= "<th class='second-2 prest-header'><p onclick=\"sortTableByPrest('myTable2')\">Prest</p></th>";
        $html .= "<th class='no-line'><div class='all-year'><p class='exerciceLabel'>Exercice</p></div></th>";
        $html .= "<th>Titre</th>";
        $html .= "<th class='last-3'>Qte</th>";
        $html .= "<th class='last-3'>Duree</th>";
        $html .= "<th class='last-3'>PV</th>";
        $html .= "</tr>";

        

        foreach ($list as $row) {
            $prest=(strpos($row['PREST_CODE'], '@', 0)==0) ? str_replace("@","",$row['PREST_CODE']) : $row['PREST_CODE'];

            if ((($prest>=400) && ($prest<500))){

                $html .= "<tr " . (($row['PREST_CODE'][0] === '@') ? 'class="hidden"' : '') . ">";
                $formattedDate = date("d/m/Y", strtotime($row['TEMPS_DATE']));
        
                $html .= "<td>" .formBtn(array("key" => "first-check", "ico" => "fa-circle")). "</td>";
                $html .= "<td class='centered-td'>" . $formattedDate . "</td>";
                $html .= "<td class='centered-td code-row'><a href='#'>" . formInput(array("key" => "col_code", "type" => "text", "align" => "c",  "value" =>  $row['COL_CODE'])). "</a></td>";
                $html .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" . formInput(array("key" => "prest_code", "type" => "text", "align" => "c",  "value" =>  $row['PREST_CODE'])) . "</span></p></td>";
                $html .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . formInput(array("key" => "exo_code", "type" => "text", "align" => "c",  "value" =>  $row['EXO_CODE'])). "</span></p></td>";
                $html .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
                $html .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
                $html .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
                $html .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";
        
                $html .= "</tr>";
           
        }
    }
    $html .= "</table>";
    $html .= "<div class='add-table-btn'>";
    $html .= formBtn(array("key" => "Ajouter-facture","ico" => "plus", "txt" => "Ajouter â la facture" , "href"=> "affiche_fact.php"));
    $html .= formBtn(array("key" => "ne-pas-facturer","ico" => "ban", "txt" => "Ne pas facturer"));
    $html .= "</div>";
    $html .= "</fieldset>";






    //--------------- Travaux Conseil -----------------//

    $html .= "<fieldset class='fieldset3'>";
    $html .= "<legend>Travaux Conseil</legend>";
    $html .= "<table id='myTable3' class='customers'>";
        $html .= "<tr>";
        $html .= "<th class='first-1'>".formBtn(array("key" => "first-check", "ico" => "check-double"));"</th>"; 
        $html .= "<th class='second-2'>Date</th>";
        $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('myTable3')\">Collab</p></th>";
        $html .= "<th class='second-2 prest-header'><p onclick=\"sortTableByPrest('myTable3')\">Prest</p></th>";
        $html .= "<th class='no-line'><div class='all-year'><p class='exerciceLabel'>Exercice</p></div></th>";
        $html .= "<th>Titre</th>";
        $html .= "<th class='last-3'>Qte</th>";
        $html .= "<th class='last-3'>Duree</th>";
        $html .= "<th class='last-3'>PV</th>";
        $html .= "</tr>";

        

        foreach ($list as $row) {
            $prest=(strpos($row['PREST_CODE'], '@', 0)==0) ? str_replace("@","",$row['PREST_CODE']) : $row['PREST_CODE'];

            if ((($prest>=500) && ($prest<600)) || (($prest>=700) && ($prest<900))){

                $html .= "<tr>";
                $formattedDate = date("d/m/Y", strtotime($row['TEMPS_DATE']));
        
                $html .= "<td>" .formBtn(array("key" => "first-check", "ico" => "fa-circle")). "</td>";
                $html .= "<td class='centered-td'>" . $formattedDate . "</td>";
                $html .= "<td class='centered-td code-row'><a href='#'>" . $row['COL_CODE'] . "</a></td>";
                $html .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" . formInput(array("key"=>"prest_code", "type"=>"text", "align"=>"c","value"=>$row['PREST_CODE'] )) . "</span><input class='popuptext' id='myPopup1' type='text' /></p></td>";
                $html .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . $row['EXO_CODE'] . "</span><input class='popuptext' id='myPopup2' type='text' /></p></td>";
                $html .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
                $html .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
                $html .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
                $html .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";
        
                $html .= "</tr>";
           
        }
    }
 
        

    $html .= "</table>";
    $html .= "<div class='add-table-btn'>";
    $html .= formBtn(array("key" => "Ajouter-facture","ico" => "plus", "txt" => "Ajouter â la facture" , "href"=> "affiche_fact.php"));
    $html .= formBtn(array("key" => "ne-pas-facturer","ico" => "ban", "txt" => "Ne pas facturer"));
    $html .= "</div>";
    $html .= "</fieldset>";


     //--------------- Travaux Juridiques -----------------//

     $html .= "<fieldset class='fieldset4'>";
     $html .= "<legend>Travaux Juridiques</legend>";
     $html .= "<table id='myTable4' class='customers'>";
         $html .= "<tr>";
         $html .= "<th class='first-1'>".formBtn(array("key" => "first-check", "ico" => "check-double"));"</th>"; 
         $html .= "<th class='second-2'>Date</th>";
         $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('myTable4')\">Collab</p></th>";
         $html .= "<th class='second-2 prest-header'><p onclick=\"sortTableByPrest('myTable4')\">Prest</p></th>";
         $html .= "<th class='no-line'><div class='all-year'><p class='exerciceLabel'>Exercice</p> </div></th>";
         $html .= "<th>Titre</th>";
         $html .= "<th class='last-3'>Qte</th>";
         $html .= "<th class='last-3'>Duree</th>";
         $html .= "<th class='last-3'>PV</th>";
         $html .= "</tr>";
 
         
 
         foreach ($list as $row) {
             $prest=(strpos($row['PREST_CODE'], '@', 0)==0) ? str_replace("@","",$row['PREST_CODE']) : $row['PREST_CODE'];
 
             if (($prest>=600) && ($prest<700)){
 
                 $html .= "<tr>";
                 $formattedDate = date("d/m/Y", strtotime($row['TEMPS_DATE']));
         
                 $html .= "<td>" .formBtn(array("key" => "first-check", "ico" => "fa-circle")). "</td>";
                 $html .= "<td class='centered-td'>" . $formattedDate . "</td>";
                 $html .= "<td class='centered-td code-row'><a href='#'>" . $row['COL_CODE'] . "</a></td>";
                 $html .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" . formInput(array("key"=>"prest_code", "align"=>"c", "value"=>$row['PREST_CODE'])) . "</span></p></td>";
                 $html .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . formInput(array("key"=>"exo_code", "align"=>"c", "value"=>$row['EXO_CODE'])) . "</span></p></td>";
                 $html .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
                 $html .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
                 $html .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
                 $html .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";
         
                 $html .= "</tr>";
            
         }
     }
  
         
 
     $html .= "</table>";
     $html .= "<div class='add-table-btn'>";
     $html .= formBtn(array("key" => "Ajouter-facture","ico" => "plus", "txt" => "Ajouter â la facture" , "href"=> "affiche_fact.php"));
     $html .= formBtn(array("key" => "ne-pas-facturer","ico" => "ban", "txt" => "Ne pas facturer"));
     $html .= "</div>";
     $html .= "</fieldset>";


     
     //--------------- Travaux Abonnements -----------------//

     $html .= "<fieldset class='fieldset5'>";
     $html .= "<legend>Abonnements</legend>";
     $html .= "<table id='myTable5' class='customers'>";
         $html .= "<tr>";
         $html .= "<th class='first-1'>".formBtn(array("key" => "first-check", "ico" => "check-double"));"</th>"; 
         $html .= "<th class='second-2'>Date</th>";
         $html .= "<th class='second-2 collab-header'><p onclick=\"sortTable('myTable5')\">Collab</p></th>";
         $html .= "<th class='second-2 prest-header'><p onclick=\"sortTableByPrest('myTable5')\">Prest</p></th>";
         $html .= "<th class='no-line'><div class='all-year'><p class='exerciceLabel'>Exercice</p></div></th>";
         $html .= "<th>Titre</th>";
         $html .= "<th class='last-3'>Qte</th>";
         $html .= "<th class='last-3'>Duree</th>";
         $html .= "<th class='last-3'>PV</th>";
         $html .= "</tr>";
 
         
 
         foreach ($list as $row) {
             $prest=(strpos($row['PREST_CODE'], '@', 0)==0) ? str_replace("@","",$row['PREST_CODE']) : $row['PREST_CODE'];
 
             if ((($prest>=900) && ($prest<=999))){
 
                 $html .= "<tr>";
                 $formattedDate = date("d/m/Y", strtotime($row['TEMPS_DATE']));
         
                 $html .= "<td>" .formBtn(array("key" => "first-check", "ico" => "fa-circle")). "</td>";
                 $html .= "<td class='centered-td'>" . $formattedDate . "</td>";
                 $html .= "<td class='centered-td code-row'><a href='#'>" . $row['COL_CODE'] . "</a></td>";
                 $html .= "<td class='centered-td prest-column'><p class='click' onclick='myFunction(this)'><span class='center_span' id='popupTextSpan1'>" . formInput(array("key"=>"prest_code", "align"=>"c", "value"=>$row['PREST_CODE'])) . "</span></p></td>";
                 $html .= "<td><p class='click' onclick='myFunction(this)'><span centered-span id='popupTextSpan1'>" . formInput(array("key"=>"exo_code", "align"=>"c", "value"=>$row['EXO_CODE'])) . "</span></p></td>";
                 $html .= "<td>" . $row['TEMPS_MEMO'] . "</td>";
                 $html .= "<td class='qnt'>" . $row['TEMPS_M_QTE'] . "</td>";
                 $html .= "<td class='duree'>" . $row['TEMPS_DUREE'] . "</td>";
                 $html .= "<td class='amount'>" . $row['TEMPS_M_PV'] . "</td>";
         
                 $html .= "</tr>";
            
         }
     }
  
         
 
     $html .= "</table>";
     $html .= "<div class='add-table-btn'>";
     $html .= formBtn(array("key" => "Ajouter-facture","ico" => "plus", "txt" => "Ajouter â la facture" , "href"=> "affiche_fact.php"));
     $html .= formBtn(array("key" => "ne-pas-facturer","ico" => "ban", "txt" => "Ne pas facturer"));
     $html .= "</div>";
     $html .= "</fieldset>";
  
    
    




     $cont = html(array_merge($opts, array("cont" => $html, "script" => "resultat")));
    //  echo html(array("user" => $user, "main" => $html, "title" => "", "script" => "resultat", "type" => "filter", "filter" => $filter));
        die($cont);
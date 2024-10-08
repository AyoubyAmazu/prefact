<?php

    require_once("config.php");

    $opts = array("ajax"=>true);
    $opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia","prefact"))));
    $opts["user"] = auth($opts);
    $opts['user']['socList'] = array();

    $opts["filter"] = htmlFilterData($opts);

    $where = array();
    array_push($where, "`id` IN (SELECT `adr` FROM `synthese` WHERE `annee` = " . $opts["filter"]["annee"] . ")");

    if($opts["filter"]["soc"]["selected"] == "") array_push($where, "`soc` IN ('" . implode("', '", array_column($opts["filter"]["soc"]["list"], "code")) . "')");
    else array_push($where, "`soc` = '" . $opts["filter"]["soc"]["selected"]["code"] . "'");


    $sql = "SELECT *";
    $sql .= ", (SELECT `temps_dur` FROM `synthese` x WHERE x.`adr` = z.`id` AND `annee` = " . $opts["filter"]["annee"] . " LIMIT 1) AS 'temps_dur'";
    $sql .= " FROM `adr` z" . ((count($where) == 0)? "" : (" WHERE " . implode(" AND ", $where)));
    $list = dbSelect($sql, array_merge($opts, array("db" => "prefact")));
    $cont ="";
    $cont.='<div>';
    $cont.= formSelect(array('key'=>'codeFilter',"label"=>'trier par :',"type"=>"pre"));
    $cont.= formCheckbox(array("key"=>"ascedent","op"=>[array("code"=>"ascendent","txt"=>"ascendent","type"=>"post"),array("code"=>"descendent","txt"=>"descendent","type"=>"post")]));
    $cont.= formBtn(array("key"=>"parametres","align"=>'l',"ico"=>"wrench",'type'=>'solid','txt'=>"Paramétres",'list'=>array()));
    $cont.= "</div>";
    $cont.="<div>";
    $cont.=formBtn(array("key"=>"statistiques","aling"=>"c","ico"=>"chart-pie","type"=>"solid","txt"=>"statistiques"));
    $cont.=formBtn(array("key"=>"fileExcel","aling"=>"c","ico"=>"file-excel","type"=>"solid"));
    $cont.="</div>";
    
    
    
    $cont.="</div>
    
    <table>
        <thead>
                <tr>
                    <td>Dossier</td>
                    <td class''>Temp</td>
                    <td>Factures</td>
                    <td class=''>Statu</td>
                    <td>Opiration</td>
                    <td class=''>Provisions</td>
                </tr>
            </thead>
        <tbody id=affichData>
    ";
    // foreach($result as $row){
    //     $cont.='<tr>
    //     <td>'.$row['adr'].'</td>
    //     <td>'.$row['temps_dur'].'</td>
    //     <td>'.$row['temps_dur'].'</td>
    //     <td>'.$row['temps_dur'].'</td>
    //     <td>'.$row['temps_dur'].'</td>
    //     <td>'.$row['temps_dur'].'</td>
    //     </tr>';
    // }
    $cont .="</tbody></table>";
    
    $html = html(array_merge($opts, array("cont" => $cont, "script" => "index")));
    die($html);

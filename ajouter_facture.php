<?php
    require_once("config.php");

    
if(isset($_POST["fact_id"])){
    if($_POST["fact_id"]=="nouvelle_facture" && !empty($_POST["temps"])){
        $code = cryptDel($_GET["d"]);
       
        $id_adr = dbSelect("SELECT `id` FROM `adr` WHERE `code` = '$code'", array("db"=>"prefact"))[0]["id"];
        $id_fact = ($id = dbSelect("SELECT max(id) as id FROM `facture` WHERE `adr_id` = $id_adr ", array("db"=>"prefact")))?$id[0]["id"]+1:1;
        $sql = "insert into facture (id, adr_id , date , amount , exo , status ) values ( $id_fact , $id_adr , now(), 0 , 0, 1)";
        dbExec($sql, array("db"=>"prefact"));
       
        $cat = dbSelect("select id from cat where id= 1", array("db"=>"prefact"))[0]["id"];
        $facture_cat_id =   ($cat_fact_id=dbSelect("SELECT max(id) as id from facture_cat", array("db"=>"prefact")))?$cat_fact_id[0]["id"]+1:1;
        $sql = "insert into facture_cat (id,facture_id, cat_id , amount) values ($facture_cat_id,$id_fact, $cat , 0)";
        dbExec($sql, array("db"=>"prefact"));
    
        $fact_det = dbSelect("select max(id) as id from facture_det ", array("db"=>"prefact"));
        if(!empty($fact_det)) $fact_det = $fact_det[0]["id"]+1; else $fact_det = 1;

        $sql = "insert into facture_det (id, fact_cat_id,titre,obs ,amount) values ($fact_det, $facture_cat_id, '-', '-',0)";
        dbExec($sql, array("db"=>"prefact"));
        foreach($_POST["temps"] as $temp){
            $id = dbSelect("select max(id) as id from facture_temps", array("db"=>"prefact"));
            if(!empty($id)) $id=$id[0]["id"]+1 ; else $id =1 ;
            $sql = "insert into facture_temps (id , fact_det_id , temps_id) values ($id, $fact_det, $temp)";
            dbExec($sql, array("db"=>"prefact"));
        }
        die(json_encode(["code"=>200,"id_fact"=>$id_fact]));
    }   

    
}

?>
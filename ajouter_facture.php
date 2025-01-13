<?php
require_once("config.php");

if (isset($_POST["fact_id"]) && isset($_POST["temps"])) {
    if ($_POST["fact_id"] == "nouvelle_facture" && isset($_POST["temps"])) {
        $code = cryptDel($_GET["d"]);
        $id_adr = dbSelect("SELECT `id` FROM `adr` WHERE `code` = '$code'", array("db" => "prefact"))[0]["id"];
        $factId = createFact($id_adr);
        foreach ($_POST["temps"] as $catId => $temps) {
            $catId = cryptDel($catId);
            $factCat = createFactCat($catId, $factId);
            $factDet = createFactDet($factCat);
            foreach ($temps as $temp) {
                createTemp($factDet, cryptDel($temp));
            }
        }
        die(json_encode(["code" => 200, "id_fact" => cryptSave($factId)]));
    }
    if ($_POST["fact_id"] !== "nouvelle_facture" && $_POST["fact_id"] !== "unfact") {
        // print_r($_POST);
        // die();
        $factId = cryptDel($_POST["fact_id"]);
        foreach ($_POST["temps"] as $catId => $temps) {
            $factCat = createFactCat(cryptDel($catId), $factId);
            $factDet = createFactDet($factCat);
            foreach ($temps as $temp) {
                createTemp($factDet, cryptDel($temp));
            }
        }
        die(json_encode(["code" => 200, "id_fact" => $_POST["fact_id"]]));
    }
    if ($_POST["fact_id"] == "unfact") {
        foreach ($_POST["temps"] as $temp) {
            $sql = "insert into temps_non_fact (temps_id) values ($temp)";
            dbExec($sql, array("db" => "prefact"));
        }
        die(json_encode(["code" => 200, "id_fact" => $_POST["fact_id"]]));
    }
}
/**
 * Creates a facture and return it's id
 * @param int $adrId 
 * @return int
 */
function createFact($adrId)
{
    $factId = dbSelect("SELECT max(id) as id FROM `facture` WHERE `adr_id` = $adrId ", array("db" => "prefact"));
    if (empty($factId)) $factId = 1;
    else $factId = $factId[0]["id"] + 1;
    $sql = "insert into facture (id, adr_id , date , amount , exo , status ) values ( $factId , $adrId , now(), 0 , 0, 1)";
    dbExec($sql, array("db" => "prefact"));
    return $factId;
}
/**
 * Search for categories of a facture if none found creates one
 * @param int $catId
 * @param int $factId
 * @return int
 */
function createFactCat($catId, $factId)
{
    $factCat = dbSelect("SELECT id from facture_cat WHERE cat_id = $catId and facture_id = $factId ", array("db" => "prefact"));
    $insert = "insert into facture_cat (id,facture_id, cat_id , amount) values (?,$factId, $catId , 0)";
    if (empty($factCat)) {
        $factCatId = dbSelect("SELECT max(id) as id from facture_cat", array("db" => "prefact"));
        if (empty($factCatId)) $factCatId = 1;
        else $factCatId = $factCatId[0]["id"] + 1;
        $sql = str_replace("?", $factCatId, $insert);
        dbExec($sql, array("db" => "prefact"));
        return $factCatId;
    }
    return $factCat[0]["id"];
}
/**
 * Search for facture detail in a categorie if not found creates one 
 * @param int $factCatId
 * @return int
 */
function createFactDet($factCatId)
{
    $factDet = dbSelect("SELECT id from facture_det WHERE fact_cat_id = $factCatId LIMIT 1", array("db" => "prefact"));
    if (empty($factDet)) {
        $factDetId = dbSelect("SELECT max(id) as id from facture_det ", array("db" => "prefact"));
        if (!empty($factDetId)) $factDetId = $factDetId[0]["id"] + 1;
        else $factDetId = 1;
        $sql = "insert into facture_det (id, fact_cat_id,titre,obs ,amount) values ($factDetId, $factCatId, '-', '-',0)";
        dbExec($sql, array("db" => "prefact"));
        return $factDetId;
    }
    return $factDet[0]["id"];
}
/**
 * Create temp of a facture
 * @param int $factDet
 * @param int $temp
 * @return void
 */
function createTemp($factDet, $temp)
{
    $sql = "INSERT into facture_temps (fact_det_id , temps_id) values ($factDet, $temp)";
    dbExec($sql, array("db" => "prefact"));
}
die(json_encode(["code" => 400, "err" => "no data provided"]));

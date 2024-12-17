<?php


header('Content-Type: application/json');
require('config.php');
$user = auth(array('ajax' => true));
$opts = array('ajax' => true);
$opts["conn"] = dbStart(array_merge($opts, array("db" => array("dia", "prefact"))));



if (isset($_POST["add_fact"])) {
    foreach ($_POST["data"] as $v) {
        $row = getRow($v);
        insertFact($row);
    }
    die();
}

/**
 * Gets all Data of an item
 * @param int $temps_id
 * @return array
 */
function getRow(int $temps_id): array
{
    global $opts;
    $select = "SELECT * FROM temps WHERE TEMPS_ID = $temps_id ";
    return dbSelect($select, array_merge($opts, array("db" => "dia")));
}

/**
 * Insert facture to Db
 * @return void
 */
function insertFact($data)
{
    global $opts;
    $query = "INSERT INTO facturation values( ";
    for ($i = 0; $i < sizeof($data); $i++) {
        print_r($data[$i]);
        if ($i == sizeof($data) - 1) $query .= $data[$i] . " )";
        else $query .= $data[$i];
        echo $query;
    
    }
    // dbExec($query, array_merge($opts, array("db" => "dia")));
}

<?PHP
header('Content-Type: application/json');
require('config.php');
$user = auth(array('ajax'=>true));
$opts = array('ajax'=>true);
$isAnd = false;
$dataResp = json_decode(DATAresp, true);
$annee = '';if(isset($_POST['annee'])) $annee = $_POST['annee'];
$soc = '';if(isset($_POST['soc'])) $annee = $_POST['soc'];
$grp = '';if(isset($_POST['grp'])) $grp = $_POST['grp'];
$txt = '';if(isset($_POST['txt'])) $txt = $_POST['txt'];
$naf = '';if(isset($_POST['naf'])) $naf = $_POST['naf'];
$segment = '';if(isset($_POST['segment'])) $segment = $_POST['segment'];
$resp = '';if(isset($_POST['resp'])) $resp = $_POST['resp'];
$resps = array();

foreach($dataResp as $v){if (isset($_POST[$v['code']]) and $_POST[$v['code']] != '') $resps[$v['code']] = $_POST[$v['code']];}

$select = "SELECT * FROM `synthese` WHERE annee = ? ";
if($annee != ''){$select=str_replace('?', $_POST['annee'], $select);}
if($soc != '' or $grp != '' or $txt != '' or $naf != '' or $segment != '' or $resp != '' or count($resps) > 0) $select .= 'AND`adr` IN ( SELECT `id` FROM `adr` WHERE ';
if($soc != ''){$select.=addSelect('soc', $soc);}
if($grp != "" ){$select.=addSelect('grp', $grp);}
if($txt != ""){$select.=addSelect('txt', $txt);}
if($naf != ""){$select.=addSelect('naf', $naf);}
if($segment != ""){$select.=addSelect('segment', $segment);}
if($resp != ""){$select.=addSelect('resp', $resp);}
if(count($resps) > 0)foreach($dataResp as $v){if($resps[$v['code']] != ""){$select.=addSelect($v['code'], $resps[$v['code']]);}}

// $select .=$isAnd? " )": '';
// $result = dbSelect($select, array_merge($opts, array('db'=>'prefact')));
try {
    echo json_encode(['success' => 200, 'data' => addSelect('soc', $soc)]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred.']);
}

function addSelect(String $column, mixed $value): String
{
    global $isAnd;
    $select = $isAnd?' AND ':'';
    $select .= "`".$column."` = '";
    $select .= $value."'";
    $isAnd = true;
    return $select;
}

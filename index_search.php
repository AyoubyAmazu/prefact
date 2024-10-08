<?PHP
header('Content-Type: application/json');
require('config.php');
$user = auth(array('ajax'=>true));
$opts = array('ajax'=>true);
$isAnd = false;
$dataResp = json_decode(DATAresp, true);
//initiate the needed values
$annee = '';if(isset($_POST['annee'])) $annee = $_POST['annee'];
$grp = '';if(isset($_POST['grp']) and $_POST['grp'] != '-') $grp = $_POST['grp'];
$soc = '';if(isset($_POST['soc']) and $_POST['soc'] != '-') $soc = $_POST['soc'];
$txt = '';if(isset($_POST['txt']) and $_POST['txt'] != '-') $txt = $_POST['txt'];
$naf = '';if(isset($_POST['naf']) and $_POST['naf'] != '-') $naf = $_POST['naf'];
$segment = '';if(isset($_POST['segment']) and $_POST['segment'] != '-') $segment = $_POST['segment'];
$resp = '';if(isset($_POST['resp']) and $_POST['resp'] != '-') $resp = $_POST['resp'];
$resps = array();

foreach($dataResp as $v){if (isset($_POST[$v['code']]) and $_POST[$v['code']] != '' and $_POST[$v['code']] != '-') $resps[$v['code']] = $_POST[$v['code']];}

$select = "SELECT * FROM `synthese` WHERE annee = ? ";
if($annee != ''){$select=str_replace('?', $_POST['annee'], $select);}

if($soc != '' or $grp != '' or $txt != '' or $naf != '' or $segment != '' or $resp != '' or count($resps) > 0) $select .= 'AND`adr` IN ( SELECT `id` FROM `adr` WHERE ';

if($soc != ''){$select.=addSelect('soc', $soc);}
if($grp != "" ){$select.=addSelect('grp', $grp);}
if($txt != ""){$select.=addSelect('txt', $txt);}
if($naf != ""){$select.=addSelect('naf', $naf);}
if($segment != ""){$select.=addSelect('segment', $segment);}
if($resp != ""){$select.=addSelect('res', $resp);}

if(count($resps) > 0)foreach($dataResp as $v){if( isset($resps[$v['code']]) and $resps[$v['code']] != ""){$select.=addSelect($v['code'], $resps[$v['code']]);}}

$select .=$isAnd? " )": '';
$result = dbSelect($select, array_merge($opts, array('db'=>'prefact')));
try {
    echo json_encode(['success' => 200, 'data' => $result]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred.']);
}
/**
 * add condition to the query
 * @param string $column
 * @param mixed $value
 * @return string
 */
function addSelect(String $column, mixed $value): String
{
    global $isAnd;
    $select = $isAnd?' AND ':'';
    $select .= "`".$column."` = '";
    $select .= $value."'";
    $isAnd = true;
    return $select;
}

<?PHP
require('config.php');
$user = auth(array('ajax'=>true));
$opts = array('ajax'=>true);
$dataResp = json_decode(DATAresp);
$annee = '';if(isset($_POST['annee'])) $annee = $_POST['annee'];
$soc = '';if(isset($_POST['soc'])) $annee = $_POST['soc'];
$grp = '';if(isset($_POST['grp'])) $grp = $_POST['grp'];
$txt = '';if(isset($_POST['txt'])) $txt = $_POST['txt'];
$naf = '';if(isset($_POST['naf'])) $naf = $_POST['naf'];
$segment = '';if(isset($_POST['segment'])) $segment = $_POST['segment'];
$resp = '';if(isset($_POST['resp'])) $resp = $_POST['resp'];
foreach($dataResp as $v){${$v} = ''; if (isset($_POST[$v])) ${$v} = $_POST[$v];}

$select = 'select * from synthese ';
if($annee != ''){$select.='';}
if($aoc != ''){$select.='';}
if($grp != ''){$select.='';}
if($txt != ''){$select.='';}
if($naf != ''){$select.='';}
if($segment != ''){$select.='and ';}
if($resp != ''){$select.='';}
foreach($dataResp as $v){if(${$v} != ''){$select.='and '.$v.' = '.${$v};}}
$select = ' ';
$result = dbSelect($select, array_merge($opts, array('db'=>'prefact')));

// else echo json_encode(array('code'=>400, 'err'=>'Unvalid Reaquest'));
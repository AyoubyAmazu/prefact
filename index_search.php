<?PHP
require('config.php');
$user = auth(array('ajax'=>true));
$opts = array('ajax'=>true);

$select = 'select * from synthese ';
$result = dbSelect($select, array_merge($opts, array('db'=>'prefact')));
if(isset($_POST['search'])){echo json_encode(array('code'=>200, 'data'=>json_encode($result)));}
else echo json_encode(array('code'=>400, 'err'=>'Unvalid Reaquest'));
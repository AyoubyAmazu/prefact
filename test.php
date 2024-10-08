<?php 
$user = 'hello ? test';
$user = str_replace('?', '1', $user);
echo json_encode(array('code'=>200, 'data'=>array(1,1,1,1,1,1)));
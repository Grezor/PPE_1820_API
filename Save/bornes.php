<?php 
header("Content*Type: application/json");
require_once('db.php');

$bornes = getAllBornes();
$json = json_encode($bornes);

echo $json;
?>
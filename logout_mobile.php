<?php 
header("Content-Type: text/plain");

require_once('db.php');

$token = null;

if (isset($_POST['token'])) {
  $login = $_POST['token'];
}else{
  http_response_code(400);
  return;
}

if(getUserFromToken($token) == false){
  http_response_code(401);
  return;
}

if(logout($token) == false) {
  http_response_code(500);
  return;
}

echo "success"
?>

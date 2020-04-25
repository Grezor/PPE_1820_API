<?php 
header("Content-Type: text/plain");

require_once('db_utils.php');

$token = null;

if (isset($_POST['token'])) {
  $token = $_POST['token'];
}else{
  http_response_code(400);
  return;
}

if(getUserIdWithToken($token) == false){
  http_response_code(401);
  return;
}
// echo 'bonjour';
if(logout($token) == false) {
  http_response_code(500);
  return;
}

echo "success"
?>

<?php 
header("Content-Type: text/plain");

require_once('db.php');

$token = null;

if (isset($_POST['token'])) {
  $token = $_POST['token'];
}else{
  http_response_code(400);
  return;
}
/* si le token n'est pas en base de donnée */
if(getUserIdWithToken($token) == false){
  http_response_code(401);
  return;
}

if(logout($token) == false) {
  http_response_code(500);
  return;
}

echo "success, le logout est ok"
?>

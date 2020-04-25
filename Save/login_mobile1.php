<?php 
header("Content-Type: text/plain");
require_once("db_utils.php");

$login = null;
$password = null;
// récupération du login du post
if (isset($_POST["nom"])) {
  $login = $_POST["nom"];
}else{
  http_response_code(400);
  return;
}
// récupération du mot de passe du post
if (isset($_POST["password"])) {
  $password = $_POST["password"];
}else{
  http_response_code(400);
  return;
}
// vérifie le couples login / password, si c'est trouvé, on donne un token 
$token = getToken($login, $password);

if ($token == false) {
  http_response_code(401);
  return;
}

echo $token;
<?php 
header("Content-Type: text/plain");

require_once 'db.php';

$login = null;
$assword = null;

// récupération du login du post 
if(isset($_POST["nickname"])){
  $login = $_POST["nickname"];
}else{
  http_response_code(400);
  return;
}

// récuperation du mot de passe du post
if(isset($_POST["password"])){
  $password = $_POST["password"];
}else{
  http_response_code(400);
  return;
}

// vérifie le couple, login mdp, si il est trouvé en bdd, on donne un token
$token = getToken($login, $password);

if ($token == false) {
  http_response_code(401);
  return;
}

echo $token;
?>
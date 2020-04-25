<?php 
require_once 'db.php';

$photoId = null; 
$token = null;
// récupération id de la photo
if (isset($_POST["photo_id"])) {
  $photoId = $_POST["photo_id"];
}else {
  http_response_code(400);
  return;
}
// récupération du token
if (isset($_POST["token"])) {
  $token = $_POST["token"];
}else{
  http_response_code(400);
  return;
}

$userId = getUserIdWithToken($token);

if ($userId === false) {
  http_response_code(401);
  return;
}
$result = toggleLikePhoto($photoId, $token);
$result = toggleLikePhoto($photoId, $userId);

if ($result === false) {
  http_response_code(500);
  return;
}

?>
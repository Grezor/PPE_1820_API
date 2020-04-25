<?php 
require_once('db_utils.php');
$photoId = null; 
$token = null;

if (isset($_POST["photo_id"])) {
  $photoId = $_POST["photo_id"];
}else {
  http_response_code(400);
  return;
}

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

$result = toggleLikePhoto($photoId, $userId);

if ($result === false) {
  http_response_code(500);
  return;
}

?>
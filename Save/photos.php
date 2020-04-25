<?php 

header("Content-Type: application/json");

require_once("db.php");

$code = null;

if (isset($_GET["code"])) {
  $code = $_GET["code"];
}else{
  http_response_code(400);
  return;
}

$photos = getPhotos($code);

for ($i=0; $i < count($photos); $i++) { 
  $photos[$i]["url"] = "photo/{$photos[$i]["id"]}.png";
  
}
$json = json_encode($photos);
echo $json;
<?php 

function getPDO(){
try{
  $db = new PDO();
  }catch(PDOException $e){
    die("ERREUR");
  }

}

function getAllBornes(){
  $statement = getPDO()->prepare("SELECT * FROM bornes");
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getPhotos($code){
$req = "SELECT photo.id, count(user_likes.id_photo) as likeCount, photo.url 
        FROM photo
        JOIN reservations ON reservations.id = photos.id_reservation AND reservations.code_evenement = :code_event
        LEFT JOIN user_likes ON photo.id = user_likes.id_photo 
        GROUP BY photo_id";

$statement = getPdo()->prepare($req);
$statement->execute([
    ":code_event" => $code
]);
return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getUserIdWithToken($token) {
  $req = "SELECT id_user FROM user_tokens WHERE token =:token";
  $statement = getPDO()->prepare($req);
  $statement->execute([":token" => $token]);
  $ids = $statement->fetchAll(PDO::FETCH_ASSOC);
  // si on ne trouve pas exactement une ligne, on arrete
  if(count($ids) != 1){
    return false;
  }
  return $ids[0]["id_user"];
}

function toogleLikePhoto($photoId, $userId){
  //Ajouter ou supprimer une ligne dans la table user_likes
  // si l'utilisateur avais liké -> on supprime la ligne
  // si l'utilisateur n'avais pas liké -> on crée une ligne
  $req = "SELECT id_user FROM user_likes WHERE id_user = :id_user AND id_photo = :id_photo";
  $statement = getPDO()->prepare($req);
  $statement->execute([":id_user" => $userId, ":id_photo" => $photoId]);
  $ids = $statement->fetchAll(PDO::FETCH_ASSOC);

  // si je retourne plus de 1 résultat c'est un problème
  if (count($ids) > 1) {
    return false;
  }

  //si count($ids) = 0, on fait le insert sinon on fait le delete
  $req = count($ids) === 0 ?
    "INSERT INTO user_likes (id_user, id_photo) VALUES (:id_user, :id_photo)"
    :"DELETE FROM user_likes WHERE id_user = :id_user AND id_photo = :id_photo";
    $statement = getPDO()->prepare($req);
    return $statement->execute([":id_user" => $userId, ":id_photo" => $photoId ]);
}

function getToken($login, $password){
  //trouver l'utilisateur en bdd
  $req = "SELECT id FROM users WHERE nickname = :login AND ";
}

function logout($token) {
  $req = "DELETE FROM user_tokens WHERE token = :token";
  $statement = getPdo()->prepare($req);
  return $statement->execute([
    ":token" => $token
  ]);
}
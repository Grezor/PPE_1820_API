<?php

require_once "db.php";

/**
 * Trouve un utilisateur à partir de son token
 *
 * @param [string] $token
 * @return l'identifiant de l'utilisateur ou false
 */
function findUserIdByToken($token)
{
    $pdo = getPdo();
    $req = $pdo->prepare("select id from users where current_token = :token");
    $res = $req->execute(array(":token" => $token));
    $ids = $req->fetchAll(PDO::FETCH_ASSOC);
    if (count($ids) == 1) {
        return $ids[0]["id"];
    } else {
        return false;
    }
}

function getUserIdWithToken($token) {
    $req = "SELECT id_user FROM users_tokens WHERE token =:token";
    $statement = getPDO()->prepare($req);
    $statement->execute([":token" => $token]);
    $ids = $statement->fetchAll(PDO::FETCH_ASSOC);
    // si on ne trouve pas exactement une ligne, on arrete
    if(count($ids) != 1){
      return false;
    }
    return $ids[0]["id_user"];
  }

function getAllBornes()
{
    $statement = getPdo()->prepare("SELECT * FROM bornes");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getUserInfoById($id)
{
    $statement = getPdo()->prepare("SELECT * FROM users where id = :id");
    $statement->execute(array(":id" => $id));
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($results) == 1) {
        return $results[0];
    }
    return false;
}

function getPhotosByCode($code)
{
    $req = <<<EOD
    SELECT p.id, estAime, url, date_prise FROM photos p
    INNER JOIN reservations AS r ON p.id_reservation = r.id
    where r.code_evenement = :code
EOD;

    $statement = getPdo()->prepare($req);
    $statement->execute(array(":code" => $code));
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getIsPhotoLikedById($id_photo):string 
{
    $req = <<<NANI
SELECT estAime
FROM photos
WHERE id = :id
NANI;

    $statement = getPdo()->prepare($req);
    $statement->execute(array(":id" => $id_photo));

    $result = $statement->fetch(PDO::FETCH_ASSOC);
    return $result["estAime"];
}

function toggleLikeForPhoto($id_photo): string
{
    $isLiked = getIsPhotoLikedById($id_photo);
    $isLiked = $isLiked == 0 ? 1 : 0;

    /*if($isLiked == 0){
    return 1;
    }else{
    return 0;
    }*/

    $req = <<<EOD
UPDATE photos
SET estAime = :isLiked
WHERE id = :id
EOD;

    $statement = getPdo()->prepare($req);
    $statement->execute(array(":isLiked" => $isLiked, ":id" => $id_photo));
    return $isLiked;
}

/**
 * Function qui permet de génerer un token pour la table users
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function logout($token) {
    $req = "DELETE FROM users_tokens WHERE token = :token";
    $statement = getPdo()->prepare($req);
    return $statement->execute([
      ":token" => $token
    ]);
  }
  function toogleLikePhoto($photoId, $userId){
    //Ajouter ou supprimer une ligne dans la table user_likes
    // si l'utilisateur avais liké -> on supprime la ligne
    // si l'utilisateur n'avais pas liké -> on crée une ligne
    $req = "SELECT id_user FROM users_likes WHERE id_user = :id_user AND id_photo = :id_photo";
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
    // trouver l'utilisateur en bdd
    $req = "SELECT id FROM users WHERE nom = :login AND password = :password";
    $statement = getPdo()->prepare($req);
    $statement->execute([
        ":login" => $login, 
        ":password" => $password
    ]);
    // on récupere le resultat 
    $ids = $statement->fetchAll(PDO::FETCH_ASSOC);
    //si on ne trouve pas exacttement une ligne, on arrete
    if (count($ids) != 1) {
        return false;
    }

    // permet de regéner un nouveaux token en cas d'entrée dupliquée
    // crée le token s'il est trouvé 
    // tant que l'insertion est en echec je genere un token aleatoire
    while(true) {
        $token = generateRandomString(42);
        // sauvegarde le token 
        $req = "INSERT INTO users_tokens (id_user, token, creation_date) VALUES (:id_user, :token, NOW())";
        $statement = getPdo()->prepare($req);
        try {
            $statement->execute([
                ":id_user" => $ids[0]["id"],
                ":token" => $token
            ]);
            // retourne la clé
            return $token;
        }catch(PDOException $e){
            // vérifie si c'est une clée dupliqué
            if ($e->errorInfo[1] == 1062) {
                // je refait une boucle
                continue;
            }
            // j'arrete la fonction
            return false;
        }
    }    
}

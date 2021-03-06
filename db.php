<?php 
/**
 * Connexion a la base de données
 */
function getPDO() {

      $dbHost = 'localhost';
      $dbName = 'ppe1820';
      $dbUser = 'root';
      $dbPass = '';

  try {
      $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
      return $pdo;
  } catch (PDOException $e) { 
      die('IMPOSSIBLE DE SE CONNECTER A LA BASE DE DONNEE');
  }
}


/**
 * Affiche le nombres de bornes
 * @return array
 */
function getAllBornes()
{
    $statement = getPDO()->prepare("SELECT * FROM bornes");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param $code
 * @return array
 */
function getPhotos($code){
  // $req = "SELECT photos.id FROM photos
  // JOIN reservations ON reservations.id = photos.id_reservation
  // WHERE reservations.code_evenement = '{$code}'";

  $req = "SELECT photos.id, count(user_likes.id_photo) AS likeCount, photos.url
          FROM photos
          JOIN reservations ON reservations.id = photos.id_reservation
            AND reservations.code_evenement = :code_event
          LEFT JOIN user_likes ON photos.id = user_likes.id_photo 
          GROUP BY photos.id";
          
  $statement = getPDO()->prepare($req);
  $statement->execute([":code_event" => $code]);
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère un user id a partir d'un token
 * 
 * Soit obtient le user id si le token est valide
 * Soit retourne false si le token n'est pas valide
 * 
 * @param string $token (jeton de l'utilisateur)
 * @param int|bool (l'identifiant de l'utilisateur)
 */
function getUserIdWithToken($token){
  $req = "SELECT id_user FROM user_tokens WHERE token = :token";
  $statement = getPDO()->prepare($req);
  $statement->execute([":token" => $token]);
  $ids = $statement->fetchAll(PDO::FETCH_ASSOC);
  // si on retrouve pas exactements un ligne, on arrete
  if (count($ids) != 1) {
    return false;
  }
  return $ids[0]["id_user"];
}
/**
 * @param $photoId
 * @param $userId
 * @return bool
 */
function toggleLikePhoto($photoId, $userId){
  // ajouter ou supprimer une ligne dans la table use_likes
  // si l'utilisateur avais like, on supprime la ligne
  // si l'utilisateur n'avais pas liké, on crée la ligne
  $req = "SELECT id_user 
          FROM user_likes 
          WHERE id_user = :id_user AND id_photo = :id_photo";
  $statement = getPDO()->prepare($req);
  $statement->execute([":id_user" => $userId, ":id_photo" => $photoId]);
  $ids = $statement->fetchAll(PDO::FETCH_ASSOC);
  // si je retourn  plus de 1 résultat c'est un problème
  if (count($ids) > 1) {
    return false;
  } 
  // si le test est vrai insert sinon DELETE
  $req = count($ids) == 0 ? 
        "INSERT into user_likes (id_user, id_photo) VALUES (:id_user, :id_photo)" 
        : "DELETE FROM user_likes WHERE id_user = :id_user AND id_photo = :id_photo";
  $statement = getPDO()->prepare($req);
  $insertResult = $statement->execute([
    ":id_user" => $userId,
    ":id_photo" => $photoId
    ]);
    // si cela echoue
  if($insertResult == false){
    return false;
  }
  // return le nombre de like d'une photo
  $req = "SELECT count(id_photo) as likeCount
          FROM user_likes 
          WHERE id_photo = :id_photo";
  $statement = getPDO()->prepare($req);
  $statement->execute([":id_photo" => $photoId]);
  $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  if (count($result) != 1) {
    return false;
  }
  return $result[0]["likeCount"];
}

/**
 * Function qui permet de génerer un token pour la table users
 * @return string
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

/**
 * @param $login
 * @param $password
 * @return bool|string
 */
function getToken($login, $password){
  // trouver l'utilisateur en bdd
  $req = "SELECT id FROM users WHERE nickname = :login AND password = :password";
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
      $req = "INSERT INTO user_tokens (id_user, token, creation_date) VALUES (:id_user, :token, NOW())";
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

/**
 * fonction qui prend en parametre le token
 */
function logout($token) {
// vérifier
  $req = "DELETE FROM user_tokens WHERE token = :token";
  $statement = getPdo()->prepare($req);
  $statement->execute([
    ":token" => $token
  ]);
}
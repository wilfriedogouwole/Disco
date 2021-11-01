<?php


/**
 * Class DatabaseLocal connexion à la base de données locale
 */
class DatabaseLocal
{
    /**
     * @var $pdo variable où sera initialisé notre base de données
     */
    protected $pdo;




    /**
     * Database constructor.
     */
    public function __construct()
    {
       $hostname =  'mysql.info.unicaen.fr';
       $dbname   =  '21814023_bd';
       $username =  '21814023';
       $password =  'oa6ojacohbahKeih';

        /**
         * définition des constantes d'ideniants de connexion
         */
        try {
            $this->pdo = new PDO("mysql:host=".$hostname.";dbname=".$dbname.";charset=utf8mb4", $username, $password);
        } catch (Exception $e) {
            echo "erreur de connexion\n";
            die('Erreur '.$e->getMessage());
        }

        /** Configure un attribut PDO : gestion d'erreur par exception
         * PDO::ATTR_ERRMODE ; avoir un rapport d'erreur
         * PDO::ERRMODE_EXCEPTION : emettre une exception */
        $this->pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }





    /** Fonction pour échapper aux char spéciaux de html */
    public function htmlEchap($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
    }


    /**
     * méthode qui renvoie tous nos utilisateurs inscrit dans notre BD
     * @return array : un tableau contenant les pseudo, nom et avatar de chaque utilisateur
     */
    public function getAllUsers()
    {
        $requete = $this->pdo->prepare("SELECT pseudo, nom, avatar FROM utilisateurs");
        $requete->execute();
        $result  = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function getUserStatus($id, $username){
        $requete = $this->pdo->prepare("SELECT status FROM utilisateurs WHERE pseudo='".$username."' AND id='".$id."';");
        $requete->execute();
        $resultat = $requete->fetch();

        return $resultat['status'];
    }


    /**
     * méthode permettant de créer une nouveau profil dans notre BD
     * @param Account id
     * @return int id du dernier profil ajouté
     */
    public function createAccount(Account $account)
    {

         $requete = $this->pdo->prepare("INSERT INTO utilisateurs (id,nom, prenom, pseudo, password, status) VALUES ('0',:firstname,:name,:username,:password,:status);");
         $data = array(':firstname' => $account->getFirstname(),
                       ':name' => $account->getName(),
                       ':username' => $account->getPseudo(),
                       ':password' => password_hash($account->getPassword(), PASSWORD_BCRYPT),
                       ':status' => $account->getStatus()
          );

         $requete->execute($data);


       return $this->pdo->lastInsertId();
    }


    /**
     * méthode permettant de récupérer le profil d'un utilisateur
     * @param $username utilisateur dont on veut avoir les infos
     * @return mixed les informations de l'utilisateur
     */
    public function getProfileByUsername($username)
    {
        $requete  = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo='".$username."';");
        $requete->execute();
        $profile = $requete->fetchAll(PDO::FETCH_ASSOC)[0];

        return $profile;
    }


    /**
     * méthode qui récupère le mot de passe d'un utilisateur
     * @param $username : utilisateur dont on veut récupérer le password
     * @return string le mot de passe crypté
     */
    public function getUserPassword($username)
    {
        $requete  = $this->pdo->prepare("SELECT password FROM utilisateurs WHERE pseudo = '".$username."';");
        $requete->execute();
        $password = $requete->fetch()[0];

        return $password;
    }


    /**
     * méthode récupérant quelques données d'un disque (nom de l'artiste, de l'abum et la cover)
     * @return mixed
     */
    public function getDiscInfoById($id)
    {
        $requete = $this->pdo->prepare("SELECT * FROM `album` WHERE id_album='".$id."';");
        $requete->execute();
        $infos   = $requete->fetch();

        return $infos;
    }


    /*   public function getArtistByYearRelease($year){
           $requete = $this->pdo->prepare("SELECT DISTINCT artist FROM `album` WHERE releaseYear='".$year."' ORDER BY artist;");
           $requete->execute();
           $allArtist = $requete->fetchAll(PDO::FETCH_ASSOC);

           return $allArtist;
       }*/

    public function getAlbumByYearRelease($year)
    {
        $requete = $this->pdo->prepare("SELECT id_album,discname FROM `album` WHERE releaseYear='".$year."' ORDER BY discname DESC;");
        $requete->execute();
        $allAlbum = $requete->fetchAll(PDO::FETCH_ASSOC);

        return $allAlbum;
    }


    public function getDiscYear()
    {
        $requete = $this->pdo->prepare("SELECT DISTINCT releaseYear FROM `album` ORDER BY releaseYear DESC;");
        $requete->execute();
        $allYear = $requete->fetchAll(PDO::FETCH_ASSOC);

        return $allYear;
    }



    /**
     * méthode vérifiant via le pseudo l'existence ou non d'un compte dans la BD
     * @param $username : pseudo du compte dont on veut vérifier l'existence
     * @return bool true si le compte existe dans la BD et false sinon
     */
    public function accountExist($username)
    {
        $requete = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = '".$username."';");
        $requete->execute();
        $account = $requete->fetch();
        if (!empty($account)) {
            return true;
        }
        return false;
    }


    /**
     * méthode vérifiant l'existence d'un prénom dans la BD
     * @param $name prénom de l'utilisateur dont on veut vérifier l'existence
     * @return bool true si le prénom existe et false sinon
     */
    public function nameExist($name)
    {
        $requete = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE nom = '".$name."';");
        $requete->execute();
        $name = $requete->fetch();
        if (!empty($name)) {
            return true;
        }
        return false;
    }


    /**
     * méthode vérifiant l'existence d'un nom dans la BD
     * @param $firstname prénom de l'utilisateur dont on veut vérifier l'existence
     * @return bool true si le nom existe et false sinon
     */
    public function firstnameExist($firstname)
    {
        $requete = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE nom = '".$firstname."';");
        $requete->execute();
        $firstname = $requete->fetch();
        if (!empty($firstname)) {
            return true;
        }
        return false;
    }


    /**
     * méthode vérifiant l'existence d'un pseudo dans la BD
     * @param $username prénom de l'utilisateur dont on veut vérifier l'existence
     * @return bool true si le nom existe et false sinon
     */
    public function pseudoExist($username)
    {
        $requete = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = '".$username."';");
        $requete->execute();
        $username = $requete->fetch();
        if (!empty($username)) {
            return true;
        }
        return false;
    }


    /**
     * méthode permettant de mettre à jour les informations de l'utilisateur
     * @param $username
     * @param $account
     * @return Disc
     */
    public function updateProfileInformation($username, $account)
    {
        /**
         * on récupère quelques anciennes infos
         */
        $oldUsername = $_SESSION['username'];
        $oldAvatar   = $_SESSION['avatar'];
        $status      = $_SESSION['status'];

        if (isset($_POST['edit'])) {
            $username  = $_POST['username'];
            $firstname = $_POST['firstname'];
            $name      = $_POST['name'];
            $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);

            if (empty($_FILES['avatar']['name'])) {
                $newAvatar = $oldAvatar;
            } else {
                $newAvatar = 'images/users/'.$_FILES['avatar']['name'];
                copy($_FILES['avatar']['tmp_name'], $newAvatar);
            }

            /**
             * on prépare la requete pour mettre à jour les informations
             */
            $requete = $this->pdo->prepare("UPDATE utilisateurs SET nom=:firstname, prenom=:prenom, pseudo=:username, password=:password, avatar=:newAvatar, status=:status WHERE username='".$oldUsername."';");

            /**
             * on change le nom et l'avatar de session
             */
            $_SESSION['username'] = $username;
            $_SESSION['avatar']   = $newAvatar;

            $requete->bindParam(':username', $username);
            $requete->bindParam(':firstname', $firstname);
            $requete->bindParam(':prenom', $name);
            $requete->bindParam(':password', $password);
            $requete->bindParam(':newAvatar', $newAvatar);
            $requete->bindParam(':status', $status);


            /**
             * on exécute notre requete
             */
            $requete->execute();

            $account = new Disc($firstname, $name, $username, $password, $newAvatar, $status);
        }
        return $account;
    }


    /**
     * méthode permettant la suppression d'un profil de la BD
     * @param $username profil dont on veut supprimer le compte
     */
    public function deleteUserProfil($username)
    {
        $requete = $this->pdo->prepare("DELETE FROM utilisateurs WHERE pseudo='".$username."';");
        $requete->execute();
    }



    /**
     * méthode permettant de récupérer l'identifiant d'un profil de la BD
     * @param $username profil dont on veut récupérer l'id
     */
    public function getUserIdByUsername($username){
      $requete = $this->pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo='".$username."';");
      $requete->execute();
      $id = $requete->fetch();
      return $id;
    }


    /**
     * méthode créant d'un disque dans la BD
     * @param $disc
     * @return Disc le disque crée
     */
    public function createDiscInDatabase($disc, array $session)
    {

            $username = $this->getUserIdByUsername($session['user']);
            $userID   = $username['id'];

            $requete  = $this->pdo->prepare("INSERT INTO album (id_album, artist, discname, cover, releaseYear, label, user_id) VALUES ('0',:artist, :discname , :cover, :annee , :label, '$userID'); ");
            $data = array(':artist'   => $disc->getArtist(),
                          ':discname' => $disc->getName(),
                          ':cover'    => $disc->getCover(),
                          ':annee'    => $disc->getYear(),
                          ':label'    => $disc->getLabel(),
                          //'user_id'    => $userID
             );

            $requete->execute($data);
          //  $disc   = new Disc($artist, $name, $year, $label, $cover);

      //  return $disc;
    }


    /**
     * méthode vérifiant l'existence d'un album dans notre BD
     * @param $albumName : nom de l'album à vérifier
     * @param $artist
     * @return bool true si l'album existe dans notre BD et false sinon
     */
    public function discExist($albumName, $artist)
    {
        $requete = $this->pdo->prepare("SELECT * FROM album WHERE discname='".$albumName."' AND artist='".$artist."';");
        $requete->execute();
        $disc = $requete->fetch();
        if (!empty($disc)) {
            return true;
        }
        return false;
    }


    /**
     * méthode retournant tous les disque présent sur le site
     * @return array liste de tous les disques de la BD
     */
    public function getAllDisc()
    {
        $requete = $this->pdo->prepare("SELECT * FROM album INNER JOIN utilisateurs ON album.user_id=users.id");
        $requete->execute();
        $allDisc = $requete->fetchAll();

        return $allDisc;
    }


    /**
     * méthode renvoyant la liste des disques pour un utilisateur
     * @param $username : utilisateur dont on veut avoir les disques
     * @return array une liste de disque
     */
    public function getAllUserDisc($username)
    {
        $requete = $this->pdo->prepare("SELECT id_album,artist,discname,cover FROM album INNER JOIN utilisateurs ON album.user_id=utilisateurs.id WHERE pseudo='".$username."';");
        $requete->execute();
        $allDisc = $requete->fetchAll(PDO::FETCH_ASSOC);

        return $allDisc;
    }


    /**
     * méthode qui renvoie un disc spécifique selon le nom entrer
     * @param $albumName : nom de l'album
     * @return mixed : les informations sur l'album
     */
    public function getOneDisc($albumName)
    {
        $requete = $this->pdo->prepare("SELECT * FROM album INNER JOIN utilisateurs ON album.user_id=utilisateur.id WHERE albumName='".$albumName."';");
        $requete->execute();
        $album   = $requete->fetchAll(PDO::FETCH_ASSOC)[O];

        return $album;
    }


    /**
     * méthode permettant de mettre à jour les données du disque
     * @param $albumName
     * @param $disc
     * @return Disc le disque mis à jour
     */
    public function updateDiscInformation($disc, $id)
    {
            $requete = $this->pdo->prepare("UPDATE album SET artist=:artist, discname=:discname, cover=:cover, releaseYear=:release_year, label=:label WHERE id_album='".$id."';");
            $data = array(
                ':artist'         => $disc->getArtist(),
                ':discname'       => $disc->getName(),
                ':cover'          => $disc->getCover(),
                ':release_year'   => $disc->getYear(),
                ':label'          => $disc->getLabel(),
            );
            $requete->execute($data);
    }


    /**
     * méthode supprimant un disque de la BD
     * @param $discName
     */
    public function deleteDisc($id)
    {
        $requete = $this->pdo->prepare("DELETE FROM album WHERE id_album='".$id."';");
        $requete->execute();
    }


    /***
     * méthode permettant la déconnexion de l'utilisateur sur le serveur
     * @param $username
     * @return bool
     */
    public function disconnectUser($username)
    {
        $requete = $this->pdo->prepare("SELECT pseudo FROM utilisateurs WHERE pseudo='".$username."';");
        $requete->execute();
        if (session_destroy()) {
            return true;
        }
        return false;
    }


    /**
     * méthode permettant de déterminer le nombre total d'articles dans la BD
     * @return int le nombre total d'articles
     */
    public function countAllArticles()
    {
        $requete    = $this->pdo->prepare("SELECT COUNT(*) AS nb_articles FROM `album`;");
        $requete->execute();
        $result     = $requete->fetch(); // on récupère le nombre d'article
        $nbArticles = (int) $result['nb_articles'];

        return $nbArticles;
    }


    /**
     * méthode permettant de déterminer le nombre total d'articles d'un utilisateur dans la BD
     * @return int le nombre total d'articles
     */
    public function countAllUserArticles($username)
    {
        $requete    = $this->pdo->prepare("SELECT COUNT(*) AS nb_articles FROM `album` INNER JOIN utilisateurs ON album.user_id=utilisateurs.id WHERE pseudo='".$username."';");
        $requete->execute();
        $result     = $requete->fetch(); // on récupère le nombre d'article
        $nbArticles = (int) $result['nb_articles'];

        return $nbArticles;
    }


    /**
     * méthode récupérant les données des disques
     * @param $start
     * @param $articlePerPage
     * @return array
     */
    public function getDiscValues($start, $articlePerPage)
    {
        $requete = $this->pdo->prepare("SELECT * FROM `album` ORDER BY `releaseYear` DESC LIMIT :premier, :parpage;");

        $requete->bindParam(':premier', $start, PDO::PARAM_INT);
        $requete->bindParam(':parpage', $articlePerPage, PDO::PARAM_INT);
        $requete->execute();
        $articles = $requete->fetchAll(PDO::FETCH_ASSOC);

        return $articles;
    }

    /**
     * @return variable
     */
    public function getPdo()
    {
        return $this->pdo;
    }


    /*   /**
        * @param $expression
        * @return bool|PDOStatement
        */
 /*   public function getSearchRequest($expression){
        $requete = $this->pdo->prepare("SELECT artist,albumName,cover FROM `album` WHERE artist LIKE ? OR albumName LIKE ?");
        $requete->execute(array("%".$expression."%", "%".$expression."%"));
        return $requete;
    }


    /**
     * @param $expression
     */
/*    public function getResultSearch($expression){
        $requete = $this->pdo->prepare("SELECT artist,albumName,cover FROM `album` WHERE artist LIKE ? OR albumName LIKE ?");
        $requete->execute(array("%".$expression."%", "%".$expression."%"));
        $result = $requete->fetch();
    }*/
}

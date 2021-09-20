<?php
require_once('AccountStorage.php');




class AccountStorageMySQL implements AccountStorage{


		public $base;


		public function __construct(PDO $db){
			$this->base = $db;
		}







		public function checkAuth($login, $password){
			$requete='SELECT * FROM utilisateurs';
			$stmt=$this->base->prepare($requete);
			$stmt->execute();
			$theRequestResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($theRequestResult as $ligne) {

				if($login == $ligne['pseudo'] && password_verify($password,$ligne['password'])){
					return  new Account($ligne['id'],$ligne['pseudo'],$ligne['password'],$ligne['status'],$ligne['nom']);
				}
			}

			return null;
		}




	   public function createAccount($account)
    {
        if (isset($_POST['register'])) {
            $username  = $_POST['username'];
            $name      = $_POST['name'];
            $firstname = $_POST['id'];
            $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $status    = "user";

            /* if (empty($_FILES['avatar']['name'])){
                 $avatarPath = "resources/users/user.png";
             }else{
                 $avatarPath = 'resources/users/'.$_FILES['avatar']['name'];
                 copy($_FILES['avatar']['tmp_name'], $avatarPath);
             }*/

            /**
             * on définit le nom et l'avatar de la session
             */
            $_SESSION['username'] = $username;
            // $_SESSION['avatar']   = $avatarPath;

            $requete = $this->pdo->prepare("INSERT INTO utilisateurs (nom, prenom, pseudo, password, status) VALUES (:firstname, :prenom , :username, :password, :status)");

            /**
             * on lie chaque paramètre à sa variable
             */
            $requete->bindParam(':id', $firstname);
            $requete->bindParam(':prenom', $name);
            $requete->bindParam(':username', $username);
            $requete->bindParam(':password', $password);
            $requete->bindParam('status', $status);

            /**
             * on execute la requete
             */
            $requete->execute();

            /**
             * on crée notre profil
             */
            $account = new Account($firstname, $name, $username, $password, $status);
        }
        return $account;
    }





	}

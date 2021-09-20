<?php
/**
 * importation des fichiers necessaires
 */
require_once("AccountStorage.php");
require_once("Account.php");
//require_once("src/lib/DatabaseLocal.php");


/**
 * Class AccountStorageStub permet d'authentifier un compte sur le site
 */

class AccountStorageStub 
{
    private $db;


    /**
     * AccountStorageStub constructor.
     * @param DatabaseLocal $db
     */
    public function __construct(DatabaseLocal $db)
    {
        $this->db = $db;
    }



    /*********************************************************************
     * MÉTHODES UTILES POUR LES DIFFÉRENTES OPÉRATIONS DU COMPTE AVEC LA BD
     *********************************************************************/





    /**
     * méthode pour insérer un nouveau compte dans la BD
     * @param Account $account
     * @return mixed le compte crée
     */
    public function createAccountInDatabase(Account $account)
    {
        return $this->db->createAccount($account);
    }


    /**
     * méthode vérifiant l'existence d'un compte dans la BD
     * @param $account : le compte à vérifier
     * @return bool|mixed true si le compte existe et false sinon
     */
    public function exists($account)
    {
        if ($this->db->accountExist($account)){
            return true;
        }
        return false;
    }


    /**
     * méthode renvoyant un array contenant les infos du profil $id
     * @param $id
     * @return mixed|void
     */
    public function readUser($id)
    {
        if (self::exists($id)){
            return $this->db->getProfileByUsername($id);
        }
        return;
    }


    /**
     * méthode renvoyant un array contenant les infos pour tous les profils
     * @return array|mixed
     */
    public function readAllUser()
    {
        return $this->db->getAllUsers();
    }





    /**
     * méthode mettant à jour les infos du profil $id sur son disque $disc
     * @param $id
     * @param Account $account
     * @return mixed
     */
    public function update($id, Account $account)
    {
        if (self::exists($id)){
            $this->db->updateProfileInformation($id, $account);
        }
    }




    /**
     * @param $id
     * @return mixed
     */
    public function deleteAccountInDatabase($id)
    {
        if (self::exists($id)){
            $this->db->deleteUserProfil($id);
            return true;
        }
        return false;
    }


    /**
     * méthode vérifiant si les informations de connexion entrées sont valides ou pas
     * @param $array
     * @return bool|mixed true si les infos de connexion sont valides, false sinon
     */
    public function loginVerify($array)
    {
        if (self::exists($array['username'])){
            $passwordCheck = $this->db->getUserPassword($array['username']);

            if (!password_verify($array['password'], $passwordCheck)){
                return false;
            }
            return true;
        }
        return true;
    }


    /**
     * méthode renvoyant un array de tous les disques
     * @return array|mixed
     */
    public function readAllDisc()
    {
        return $this->db->getAllDisc();
    }


    /**
     * méthode renvoyant tous les disques d'un utilisateur
     * @param $profilId
     * @return array|mixed
     */
    public function readDiscOwner($profilId)
    {
        return $this->db->getAllUserDisc($profilId);
    }




    /**
     * @param $id
     * @return mixed
     */
    public function disconnect($id)
    {
        return $this->db->disconnectUser($id);
    }


    /**
     * méthode vérifiant si un pseudo existe dans la BD
     * @param $username
     * @return bool|mixed true si le pseudo existe déjà et false sinon
     */
    public function pseudoExist($username)
    {
        if ($this->db->pseudoExist($username)){
            return true;
        }
        return false;
    }

    /**
     * @param $firstname
     * @return mixed
     */
    public function firstnameExist($firstname)
    {
        if ($this->db->firstnameExist($firstname)){
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function nameExist($name)
    {
       if ($this->db->nameExist($name)){
           return true;
       }
       return false;
    }
}





?>
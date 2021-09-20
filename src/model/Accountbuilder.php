<?php

require_once("src/model/Account.php");


/**
 * Class AccountBuilder permet la création d'un compte sur le site en respectant certaines contraintes
 * sur les différentes données saisies
 */

 class AccountBuilder
 {
     private $data;
     private $error;
     private $storage;



     /**
      * AccountBuilder constructor.
      * @param null $data $_POST data
      * @param null $avatarData $_FILE data
      */
     public function __construct($data=null, $avatarData=null)
     {
         if ($data === null){
             $data = array(
                 "id"     => "",
                 "firstname"    => "",
                 "name" => "",
                 "username" => "",
                 "password" => "",
                 "status" => "",
             );
         }
         $this->data=$data ;
         $this->error=array();

     }





     /**
      * méthode renvoyant une nouvelle instance de AccountBuilder avec les données modifiables du profil passées
      * @param Disc $account
      * @return AccountBuilder
      */
     public static function buildFromAccount(Account $account){
        return new AccountBuilder(
            array( "firstname"       => $account->getFirstname(),
                   "name"            => $account->getName(),
                   "username"        => $account->getPseudo(),
                   "password"        => $account->getPassword(),
                   "status"          => $account->getStatus(),
            ) );
     }


     /**
      * méthode vérifier la validité des données envoyées par le client et renvoie un tableau d'erreurs à corriger.
      * @return bool true si les informations entrées sont correctes, false sinon
      */
     public function isLoginIsValid(){
         $this->error = array();

         if (!key_exists("username", $this->data) || $this->data["pseudo"] === ""){
             $this->error["username"] = "Entrez un pseudo.";
         }else if (mb_strlen($this->data["pseudo"], 'UTF-8') >= 25){
             $this->error["username"] = "Le pseudo doit comporter moins de 25 caractères.";
         }else if ($this->data["username"] === NULL){
             $this->error["username"] = "Ce compte n'existe pas.";
         }

         if (!key_exists("password", $this->data) || $this->data["password"] === ""){
             $this->error["password"] = "Vous devez entrer un mot de passe.";
         }
         return count($this->error) === 0;
     }


     /***
      * méthode vérifiant la validité des données d'inscriptions entrées
      * @return bool true si ils sont valides et false sinon
      */
     public function isDataInscriptionValid(){
         $this->error = array();

             if (!key_exists("username", $this->data) || $this->data["username"] === ""){
                 $this->error["username"] = "Entrez un pseudo.";
             }else if (mb_strlen($this->data["username"], 'UTF-8') >= 25){
                 $this->error["username"] = "Le pseudo doit comporter moins de 25 caractères.";
             }else if ($this->data["username"] === NULL){
                 $this->error["username"] = "Ce pseudo existe déjà.";
             }

             if (!key_exists("firstname", $this->data) || $this->data["firstname"] === ""){
                 $this->error["firstname"] = "Vous devez entrer un nom.";
             }

             if (!key_exists("name", $this->data) || $this->data["name"] === ""){
                 $this->error["name"] = "Vous devez entrer un prénom.";
             }

             if (!key_exists("password", $this->data) || $this->data["password"] === ""){
                 $this->error["password"] = "Vous devez entrer un mot de passe.";
             }

             if (!key_exists("confirmPassword", $this->data) || $this->data["confirmPassword"] === ""){
                 $this->error["confirmPassword"] = "Vous devez entrer un mot de passe.";
             }

         return count($this->error) === 0;
     }


     /***
      * méthode créant une nouvelle instance de Profile avec les données fournies.
      * @return Account
      * @throws Exception
      */
     public function createProfile(){
        $status = 'user';

         if (!key_exists("username", $this->data)){
             throw new Exception("Champ manquant pour la création de profil");
         }
         if (!key_exists("firstname", $this->data)){
             throw new Exception("Champ manquant pour la création de profil");
         }
         if (!key_exists("name", $this->data)){
             throw new Exception("Champ manquant pour la création de profil");
         }
         if (!key_exists("password", $this->data)){
             throw new Exception("Champ manquant pour la création de profil");
         }

         return new Account($this->data["firstname"], $this->data["name"], $this->data["username"], $this->data["password"], $status);
     }


     /**
      * méthode mettant à jour une instance du compte avec les données fournies
      * @param Account $account
      * @throws Exception
      */
     public function updateAccount(Account $account){
         if (key_exists("nom", $this->data)){
             $account ->setFirstname($this->data['nom']);
         }
         if (key_exists("prenom", $this->data)){
             $account ->setName($this->data['prenom']);
         }
         if (key_exists("username", $this->data)){
             $account ->setPseudo($this->data["username"]);
         }
         if (key_exists("password", $this->data)){
             $account ->setPassword($this->data["password"]);
         }
         if (key_exists("confirmPassword", $this->data)){
             $account -> setPassword($this->data["password"]);
         }
     }

     /**
      * méthode renvoyant la référence du champ représentant le nom d'un utilisateur.
      * @return string
      */
     public function getFisrtnameRef(){
         return "nom";
     }


     /**
      * méthode renvoyant la référence du champ représentant le prénom d'un utilisateur.
      * @return string
      */
     public function getNameRef(){
         return "prenom";
     }


     /**
      * méthode renvoyant la référence du champ représentant le nom d'un utilisateur.
      * @return string
      */
     public function getUsernameRef(){
         return "username";
     }


     /**
      * méthode renvoyant la référence du champ représentant le mot de passe d'un utilisateur.
      * @return string
      */
     public function getPasswordRef() {
         return "password";
     }


     /**
      * méthode renvoyant la référence du champ représentant le mot de passe confirmé d'un utilisateur.
      * @return string
      */
     public function getConfirmPasswordRef() {
         return "confirmPassword";
     }


     /**
      * méthode renvoyant la référence du champ représentant l'image d'un utilisateur.
      * @return string
      */
     public function getAvatarRef() {
         return "avatar";
     }


     /**
      * méthode renvoyant la valeur d'un champ en fonction de la référence passée en argument
      * @param $ref
      * @return string
      */
     public function getData($ref) {
         return key_exists($ref, $this->data)? $this->data[$ref]: '';
     }


     /**
      * méthode renvoyant la valeur d'un champ en fonction de la référence passée en argument
      * @param $ref
      * @return mixed|string
      */
     public function getAvatarData($ref) {
         return key_exists($ref, $this->avatarData)? $this->avatarData[$ref]: '';
     }


     /**
      * renvoie les erreurs associées au champ de la référence passée en argument, ou nul s'il n'y a pas d'erreur. Nécéssite d'appeler isDataInscriptionIsValid() avant.
      * @param $ref
      * @return mixed|null
      */
     public function getErrors($ref) {
         return key_exists($ref, $this->errors)? $this->errors[$ref]: null;
     }



 }

 ?>

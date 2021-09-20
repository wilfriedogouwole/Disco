<?php


/**
 * Class Account représente les données nécessaires pour la création d'un compte
 */

 class Account
 {
     private $firstname;
     private $name;
     private $pseudo;
     private $password;
     private $status;


     /**
      * Account constructor.
      * @param $firstname : nom de l'utilisateur
      * @param $name : prénom de l'utilisateur
      * @param $pseudo : pseudo de l'utilisateur
      * @param $password : mot de passe de l'utilisateur
      * @param $status : statut de l'utilisateur
      */
     public function __construct($firstname, $name, $pseudo, $password,$status)
     {
         /**
          * on vérifie que le nom entré est valide
          */
         if (self::isFirstNameIsValid($firstname)){
             $this->firstname = $firstname;
         }

         /**
          * on vérifie que le prénom entré est valide
          */
         if (self::isNameIsValid($name)){
             $this->name      = $name;
         }

         /**
          * on vérifie que le pseudo entré est valide
          */
         if (self::isUsernameIsValid($pseudo)){
             $this->pseudo    = $pseudo;
         }

         /**
          * on vérifie que le mot de passe est valide
          */
         if (self::isPasswordIsValid($password)){
             $this->password  = $password;
         }

       //  $this->avatar    = $avatar;
         $this->status    = $status;
     }



     /**
      * @return mixed
      */
     public function getFirstname()
     {
         return $this->firstname;
     }


     /**
      * @return nom de l'utilisateur
      */
     public function getName()
     {
         return $this->name;
     }


     /**
      * @return pseudo de l'utilisateur
      */
     public function getPseudo()
     {
         return $this->pseudo;
     }

     /**
      * @return mot de passe de l'utilisateur
      */
     public function getPassword()
     {
         return $this->password;
     }



     /**
      * @return statut de l'utilisateur
      */
     public function getStatus()
     {
         return $this->status;
     }


     /**
      * Mutateur pour le nom
      * @param $firstname
      * @throws Exception
      */
     public function setFirstname($firstname): void
     {
         if (!self::isFirstNameIsValid($firstname)){
             throw new Exception("Nom incorrect");
         }
         $this->firstname = $firstname;
     }


     /**
      * Mutateur pour le prénom
      * @param $name
      * @throws Exception
      */
     public function setName($name){
        if (!self::isNameIsValid($name)){
            throw new Exception("Prénom incorrect");
        }
        $this->name = $name;
     }

     /**
      * mutateur pour le pseudo
      * @param $username
      * @throws Exception
      */
     public function setPseudo($username){
         if (!self::isUsernameIsValid($username)){
             throw new Exception("Pseudo invalide");
         }
         $this->pseudo = $username;
     }


     public function setPassword($password){
        if (self::isPasswordIsValid($password)){
            throw new Exception("Mot de passe invalide");
        }
        $this->password = $password;
     }


     /**
      * méthode vérifiant la validité du pseudo
      * @param $username : pseudo à vérifier
      * @return bool
      */
     public static function isUsernameIsValid($username){
         return (mb_strlen($username, 'UTF-8') < 25) && ($username !== "") && ($username !== NULL);
     }


     /**
      * méthode vérifiant la validité d'un nom
      * @param $name prénom à vérifier
      * @return bool
      */
     public static function isNameIsValid($name){
         $namesize = 40;
         return (mb_strlen($name, 'UTF-8') < $namesize) && ($name !== "") && ($name !== NULL);
     }


     /**
      * méthode vérifiant la validité d'un nom
      * @param $firstname : nom à vérifier
      * @return bool
      */
     public static function isFirstNameIsValid($firstname){
        $firstnamesize = 40;
       // return (mb_strlen($firstname, 'UTF-8') < $firstnamesize) && ($firstname !== "") && ($firstname !== NULL);
        return mb_strlen($firstname, 'UTF-8') < $firstnamesize && ($firstname !== "") && $firstname !== NULL;
     }


     /**
      * méthode vérifiant la validité d'un mot de passe
      * @param $password
      * @return bool
      */
     public static function isPasswordIsValid($password){
         return (mb_strlen($password, 'UTF-8')) && ($password !== "");
     }

 }


 ?>

<?php
    class AuthentificationManager
    {
        public $lesComptes;

        public function __construct(AccountStorage $listeDeComptes)
        {
            $this->lesComptes = $listeDeComptes;
        }



        public function connectUser($login, $password)
        {
            $account = $this->lesComptes->checkAuth($login, $password);
            if ($account !== null) {
                $_SESSION['user']= $account;
                return true;
            }
            return false;
        }



        public function disconnectUser()
        {
            unset($_SESSION['user']);
        }



        public function isUserConnected()
        {
            if (key_exists('user', $_SESSION)) {
                return true;
            }
            return false;
        }
    }

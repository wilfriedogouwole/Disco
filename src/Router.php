<?php
require_once('model/DiscStorageFile.php');
require_once('model/DiscStorageStub.php');
require_once('view/View.php');
require_once('control/Controller.php');
require_once('model/Disc.php');
require_once('model/AuthentificationManager.php');
require_once('model/AccountStorageStub.php');
//require_once('lib/DatabaseLocal.php');

class Router
{


    /**
     * méthode principale du routeur
     */
    public function main(AccountStorage $accountStorage)
    {
        session_start();
        session_set_cookie_params(3600);

        $feedback = key_exists("feedback", $_SESSION)? $_SESSION["feedback"] : null;
        $vue = new View($this, $feedback);


        $controller = new Controller($vue, $accountStorage);

        $sup = key_exists('sup', $_GET)?$_GET['sup']:null;


        if (!key_exists("id", $_GET) and (!key_exists("liste", $_GET))&&(!key_exists("action", $_GET))) {
            $vue->makeHomePage();
        }

        if (key_exists("action", $_GET)) {
            if ($_GET["action"]=="about") {
                $vue->makeAboutPage();
            }
        }

        /*if (isset($_POST['submit-log'])){
            echo"ok exist";
        }*/
        if (key_exists("action", $_GET)){
            if ($_GET['action'] == 'login'){
                 $vue->makeLoginPage();
                  if (key_exists('username-log', $_POST) && key_exists('password-log', $_POST)) {
                        $controller->connection($_POST['username-log'], $_POST['password-log']);
                    }
                }

            }

        if (key_exists("action", $_GET)){
            if ($_GET['action'] == 'createAccount'){
                $vue->makeRegisterPage();
            }
        }
        if (key_exists("action", $_GET)){
              if($_GET['action'] == 'sauverInscription'){

             $controller->saveNewAccounts($_POST);
                    }
                }

        if (key_exists("action", $_GET)){
            if ($_GET['action'] == 'deconnexion'){
                 session_destroy();
                $vue->displayAccountDisconnectedPageFeedback();
            }
        }

        if (key_exists("action", $_GET)){
            if ($_GET['action'] == 'createDisc'){
                $vue->makeDiscCreationPage();
            }
        }

        if (key_exists("action", $_GET)){
              if($_GET['action'] == 'saveDisc'){
                $controller->createYourDisc($_POST, $_FILES, $_SESSION);
              }
        }


        if (key_exists("discography", $_GET)) {
            $controller->showDiscographyList();
        }

        if (key_exists("userDisc", $_GET)) {
               $controller->showUserDiscList($_SESSION['user']);
        }

        if (key_exists("id", $_GET)) {
            if (isset($_GET['id']) && $_GET['id'] !== ""){
                $controller->showInformation($_GET['id'], $_SESSION);
            }
            else{
                $vue->makeUnexpectedErrorPage();
            }
        }


        if (key_exists("search", $_GET)){
            if (($_GET['search']=='Valider') && (!empty($_POST))){
                $vue->makeSearchResultPage($_GET);
            }
        }

        if (key_exists("sup", $_GET)) {
            if ($_GET["action"]=="demandesuppression") {
                $vue->makeDeleteDiscButtonPage($sup);
            }
        }

        if (key_exists("sup", $_GET)) {
            if ($_GET["action"]=="suppression") {
                $controller->deleteDisque($sup);
            }
        }

        if (key_exists("sup", $_GET)) {
            if ($_GET["action"]=="demandeMAJ") {
                $vue->makeUpdateDiscPage($sup);
            }
        }

        if (key_exists("action", $_GET)){
            if($_GET['action'] === 'modifier'){
                $controller->updateDisque($sup, $_POST, $_FILES);
            }
        }









  /*      if (key_exists("action", $_GET)) {
            if ($_GET["action"]=="nouveau") {
                $controller->newDisque();
            }
        }



        if (key_exists("action", $_GET)) {
            if ($_GET["action"]=="sauverNouveau") {
                if (!empty($_POST)) {
                    $controller->saveNewDisque($_POST);
                }
            }
        }





        if (key_exists("sup", $_GET)) {
            if ($_GET["action"]=="suppression") {
                $controller->deleteDisque($sup);
            }
        }
        if (key_exists("sup", $_GET)) {
            if ($_GET["action"]=="demandeMAJ") {
                $controller->askDisquemodif($sup);
            }
        }

        */
        $vue->render();
    }





    /**
     * méthode permettant de rediriger un lien vers une autre page
     * @param $url : url vers lequel on veut etre rediriger
     * @param $feedback
     */
    public function POSTredirect($url, $feedback)
    {
        $_SESSION["feedback"] = $feedback;
        header("Location: ".htmlspecialchars_decode($url), true, 303);
        exit;
    }


    public function getDetailDisc($id)
    {
        return "index.php?id=$id";
    }

    /**
     * méthode qui retourne le lien concernant la création d'un disque
     * @return string
     */
    public function getDiscCreationURL()
    {
        return "index.php?action=createDisc";
    }


    /**
     * méthode qui retourne le lien concernant la sauvegarde d'un disque
     * @return string
     */
    public function getDiscSaveURL()
    {
        return "index.php?action=saveDisc";
    }


    /**
     * méthode qui retourne le lien concernant la page A propos
     * @return string
     */
    public function getAboutPageURL()
    {
        return " index.php?action=about";
    }


    /**
     * méthode qui retourne le lien concernant la page d'acceuil
     * @return string
     */
    public function getHomePageURL()
    {
        return "index.php";
    }


    /**
     * @return string
     */
    public function getDiscographyURL()
    {
        return "index.php?discography";
    }


    public function getAllUserDiscURL(){
      return "index.php?userDisc";
    }


    public function getResearchURL(){
        return "index.php?research";
    }

    public function getDisqueAskDeletionURL($id)
    {
        return "index.php?sup=".$id."&action=demandesuppression";
    }

    public function getDisqueDeletionURL($id)
    {
        return "index.php?sup=".$id."&action=suppression";
    }
    public function getDisquemofifURL($id)
    {
        return "index.php?sup=".$id."&action=modifier";
    }
    public function getDisqueAskmofifURL($id)
    {
        return "index.php?sup=".$id."&action=demandeMAJ";
    }

    public function getLoginURL()
    {
        return "index.php?action=login";
    }

    public function getRegisterURL(){
        return "index.php?action=createAccount";
    }

    public function getsaveRegisterURL(){
        return "index.php?action=sauverInscription";
}
    public function getdeconnexion()
    {
        return "index.php?action=deconnexion";
    }
}

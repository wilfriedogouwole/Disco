<?php
require_once('view/View.php');
require_once('model/Disc.php');
require_once('model/DiscBuilder.php');
require_once('model/DiscStorage.php');
require_once('model/AuthentificationManager.php');
require_once("src/Router.php");




class Controller
{
    private $view;
    private $disqueStorage;
    private $errors;
    private $authManager;
    private $accountStorage1;
    private $database;
    private $router;


    /**
     * Controller constructor.
     * @param View $view
     * @param DiscStorage $newDisqueStorage
     * @param AuthentificationManager $authManager
     */
    public function __construct(View $view, AccountStorage $newAccountStorage)
    {

        $this->view = $view;
        //$this->disqueStorage = $newDisqueStorage;
        $this->router = new Router();
        $this->accountStorage1 =  $newAccountStorage;
        $this->database = new DatabaseLocal();
        $this->authManager = new AuthentificationManager($this->accountStorage1);
        //$this->errors="erreur d'entrer";
    }


    /**
     * affichage de la discographie
     */
    public function showDiscographyList()
    {
        $this->view->makeListPage();
    }


    /**
     * affiche les informations sur un article selectionné
     * @param $id identifiant de l'article
     * @return
     */
    public function showInformation($id, array $session)
    {
        $this->view->makeDetailDiscPage($id, $session);
    }


    public function showUserDiscList($username){
      $this->view->makeUserDiscListPage($username);
    }



    public function connection($login,$password){
        if($this->authManager->connectUser($login,$password)){
            $_SESSION['user'] = $login;
                 $this->view->displayLoginSuccessfullyPageFeedback();
            }else{
                 $this->view->displayLoginFailledPageFeedback();
            }
      }






    public function saveNewAccounts(array $data)
    {
        $accBuilder = new AccountBuilder($data);

        if (password_verify($data['confirmPassword'],password_hash($accBuilder->getData('password'), PASSWORD_BCRYPT))) {
            if ($accBuilder->isDataInscriptionValid()) {
                $nouveauProfil = $accBuilder->createProfile();
                $this->database->createAccount($nouveauProfil);
                $this->view->displayAccountCreatedFeedback();
            } else {
                $this->view->displayAccountNotCreatedFeedback();
            }
        }else {
          $this->view->displayErrorFieldPasswordFeedback('Les mots de passe ne correspondent pas');
        }
    }





    public function createYourDisc(array $datadisc, array $picdisc, array $session){
        // $datadisc = POST , $picdisc = FILES
        // on commence par récupérer l'extension du fichier et on crée un tableau des extensions autorisées
        $infosfichier        = pathinfo($picdisc['monfichier']['name']); // pathinfo() renvoie un array contenant entre autres l'extension du fichier
        $extension_upload    = $infosfichier['extension']; // exetension du fichier uploadFileTreatment
        $extension_acceptees = array('jpg', 'jpeg', 'png');


      // on teste si le fichier a bien été envoyé et qu'il n'y a pas d'erreur
        if (isset($picdisc['monfichier']) AND $picdisc['monfichier']['error'] == 0) {
          // dans un second temps on teste la taille de notre fichier qu'on limite à 1Mo soit 1000000 octets
          if ($picdisc['monfichier']['size'] <= 1000000) {
            // une fois la taille du fichier vérifiée, on vérifie à présent son extension
              if (in_array($extension_upload, $extension_acceptees)) {
                // on valide l'upload du fichier et on le stocke définitivement
                move_uploaded_file($picdisc['monfichier']['tmp_name'], 'resources/album_thumb/resize-pic/'.basename($picdisc['monfichier']['name']));
                //echo "Upload réussie";
              }else{
                  $erreur = 'Vous devez uploader un fichier de type png, jpg, jpeg';
              }
          }
        }


        // récupération des données et création du disque
        $artist = $datadisc["artist-newdisc"];
        $album  = $datadisc["album-newdisc"];
        $year   = $datadisc["year-newdisc"];
        $label  = $datadisc["label-newdisc"];
        $cover  = "resources/album_thumb/resize-pic/" . basename($picdisc['monfichier']['name']);

        $discBuilder = new DiscBuilder($datadisc, $picdisc);

        if ($discBuilder->isDataDiscValid()) {
            if (!($this->database->discExist($album, $artist))) {
              $nouveauDisc = $discBuilder->createDisc();
              $this->database->createDiscInDatabase($nouveauDisc, $session);
              $this->router->POSTredirect($this->router->getAllUserDiscURL(), "Votre disque a été crée");
              //$this->view->displayDiscCreatedPageFeedback();
            }
        }else {
          $feedb = "Votre disque n'a pu etre crée";
          $this->router->POSTredirect($this->router->getDiscCreationURL(), $feedb);
        }
    }






    public function newAccounts()
    {
        if (!key_exists('currentNewDisque', $_SESSION)) {
            $_SESSION['currentNewDisque']=new DiscBuilder();
        } else {
            $this->view->makeDisqueCreationPage($_SESSION['currentNewDisque']);
        }
    }


    public function newDisque()
    {
        if (!key_exists('currentNewDisque', $_SESSION)) {
            $_SESSION['currentNewDisque']=new DiscBuilder();
        } else {
            $this->view->makeDisqueCreationPage($_SESSION['currentNewDisque']);
        }
    }



    public function deleteDisque($id)
    {
        $this->database->deleteDisc($id);
        $this->view->displayDisqueSuppressionSuccess($id);
    }



    public function updateDisque($id, array $datadisc, array $picdisc)
    {
        if (isset($datadisc['update'])){
            $lastdata = $this->database->getDiscInfoById($id);
            $last_artiste  = $lastdata['artist'];
            $last_album    = $lastdata['discname'];
            $last_year     = $lastdata['releaseYear'];
            $last_label    = $lastdata['label'];
            $last_cover    = $lastdata['cover'];
            $cov           = "";
           /* $new_artiste   = "";
            $new_album     = "";
            $new_year      = "";
            $new_label     = "";*/

            if (key_exists("monfichier", $picdisc)){
                $infosfichier        = pathinfo($picdisc['monfichier']['name']);
                $extension_upload    = (isset($infosfichier['extension'])) ? $infosfichier['extension']:"";
                $extension_acceptees = array('jpg', 'jpeg', 'png');
            }

            if (isset($picdisc['monfichier']) AND $picdisc['monfichier']['error'] == 0) {
                if ($picdisc['monfichier']['size'] <= 1000000) {
                    if (in_array($extension_upload, $extension_acceptees)) {
                        move_uploaded_file($picdisc['monfichier']['tmp_name'], 'resources/album_thumb/resize-pic/'.basename($picdisc['monfichier']['name']));
                    }else{
                        $erreur = 'Vous devez uploader un fichier de type png, jpg, jpeg';
                    }
                }
            }


            $new_artiste  = ($datadisc['artist-updisc'] === "") ? $last_artiste:$datadisc['artist-updisc'];
            $new_album    = ($datadisc['album-updisc']  === "") ? $last_album:$datadisc['album-updisc'];
            $new_year     = ($datadisc['year-updisc']   === "") ? $last_year:$datadisc['year-updisc'];
            $new_label    = ($datadisc['label-updisc']  === "") ? $last_label:$datadisc['label-updisc'];
            $new_cover    = "resources/album_thumb/resize-pic/" . basename($picdisc['monfichier']['name']);


           if(basename($picdisc['monfichier']['name']) === NULL OR basename($picdisc['monfichier']['name'])===""){
                $cov = $last_cover;
            }else{
               $cov = $new_cover;
           }


            $datadisc['artist-updisc'] = $new_artiste;
            $datadisc['album-updisc']  = $new_album;
            $datadisc['year-updisc']   = $new_year;
            $datadisc['label-updisc']  = $new_label;

            $discBuilder  = new DiscBuilder($datadisc, $picdisc);
            $lastdiscdata = new Disc($new_artiste, $new_album, $new_year, $new_label, $cov);

            if (!$discBuilder->isUpdatedDataDiscValid()) {
                $this->database->updateDiscInformation($lastdiscdata,$id);
                $this->router->POSTredirect($this->router->getDetailDisc($id), "Votre disque a bien été modifié");
            } else {
                $this->view->makeUpdateDiscPage($id, $discBuilder);
                $this->view->displayDiscNotEditedFeedback($id);
            }
        }
    }






}



?>

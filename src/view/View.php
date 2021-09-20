<?php

/**
 * import des fichiers nécessaires
 */
require_once('control/Controller.php');
require_once('model/Disc.php');
require_once('model/DiscBuilder.php');
require_once("src/model/Accountbuilder.php");



/**
 * Class View
 */
class View
{
    private $title;
    private $content;
    private $stylePage;
    private $router;
    private $feedback;
    private $dbLocal;


    /**
     * View constructor.
     * @param Router $newRouter
     * @param $feedback
     */
    public function __construct(Router $newRouter, $feedback)
    {
        $this->title     = null;
        $this->content   = null;
        $this->stylePage = null;
        $this->router    = $newRouter;
        $this->menu      = array(
             /*vue utilisateur non connecteur*/
             "Acceuil"              => $this->router->getHomePageURL(),
             "Liste des Disques"    => $this->router->getDiscographyURL(),
             "Connexion"            =>  $this->router->getLoginURL(),
             "inscription"          =>  $this->router->getRegisterURL(),
             "A propos"             =>  $this->router->getAboutPageURL(),

        );

         /*vue utilisateur*/
        $this->menu2     = array(
            "Acceuil"              => $this->router->getHomePageURL(),
            "Liste des Disques"    => $this->router->getDiscographyURL(),
            "Création des Disques" =>  $this->router->getDiscCreationURL(),
            "Deconnexion"          =>  $this->router->getdeconnexion(),
            "Mes disques"          => $this->router->getAllUserDiscURL(),
            "A propos"             =>  $this->router->getAboutPageURL(),

       );
        /*vue admin*/
        $this->menu3    = array(
        "Acceuil"              => $this->router->getHomePageURL(),
        "Liste des Disques"    => $this->router->getDiscographyURL(),
        "Création des Disques" =>  $this->router->getDiscCreationURL(),
        "Deconnexion"          =>  $this->router->getdeconnexion(),
        "A propos"             =>  $this->router->getAboutPageURL(),

   );
        $this->feedback = $feedback;
        $this->dbLocal  = new DatabaseLocal();
    }


    /**
     * méthode de rendu des différentes pages de notre site web
     */
    public function render()
    {
        if ($this->title === null  || $this->title === "" || $this->content === null || $this->content === ""){
            $this->makeUnexpectedErrorPage();
        }
        $title   = $this->title;
        $content = $this->content;
        require_once('squelette.php');
    }




    /************************************************************************************************
     *                              MÉTHODE DE GÉNÉRATION DE PAGE
     ************************************************************************************************/







    /**
     * Méthode de création de la page d'acceuil
     */
    public function makeHomePage()
    {
        $this->stylePage = 'home.css';
        $this->title     = "DiscoDingo, le meilleur du disque";


        // on récupère le nombre total d'articles
        $nbArticles = $this->dbLocal->countAllArticles();
        $perPage      = 12; // nombre d'articles par page
        $nbTotalPage  = ceil($nbArticles / $perPage); // calcul du nombre total de pages


        // On détermine sur quelle page on se trouve
        if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page']>0 && $_GET['page']<=$nbTotalPage){
            $currentPage = (int) strip_tags($_GET['page']); //Supprime les balises HTML et PHP d'une chaîne
            //$currentPage = $_GET['page'];
        }else{
            $currentPage = 1;
        }


        $firstArticle = ($currentPage * $perPage) - $perPage; // calcul du 1er élément de la page

       // $images = $this->dbLocal->getDiscInfo();
        $images   = $this->dbLocal->getDiscValues($firstArticle, $perPage);

            /**
             * Contenu de la page
             */
        $str             = '<h1>Bienvenue sur discoDINGO</h1>';
        $str            .= '<ul class="gallery">';
            foreach ($images as $im) {
                /* On ne va faire qu'afficher le contenu du tableau $image:
                  * pour des raisons de sécurité et de clarté, on commence par tout échapper */
                $imEsc = array();
                foreach($im as $k => $v) {
                    $imEsc[$k] = $this->dbLocal->htmlEchap($v);
                }
                $str .= '<li><a href="index.php?id='.$imEsc['id_album'].'"> <figure id="fig">';
                $str .= '<img src="'.$imEsc['cover'].'" alt="'.$imEsc['discname'] .'" title="'.$imEsc['discname'].'"/>';
                $disc = isset($imEsc["discname"]) ? $imEsc["discname"] : null;
                $str .= '<figcaption> <span id="artist"> '.$imEsc['artist'].' </span> <br> <span id="album"> '.$disc.' </span> </figcaption>';
                $str .= '</figure></a></li>';
                $str .= "\n";
            }
            $str .= '</ul>';

                /**
                 * affichage de la pagination
                 */
            $str .= '<nav id="navPagination">';
            $str .= '<ul class="pagination">';
            $str .= '<li class="page-item">';

            /* Si on est sur la première page, on n'a pas besoin d'afficher de lien
*               vers la précédente. On va donc ne l'afficher que si on est sur une autre
*               page que la première */
            if ($currentPage > 1){
                    $str .= '<a class="page-link" href="./?page='.($currentPage - 1).'"> &laquo; </a>';
                }
            $str .= '</li>';
                for ($page = 1; $page <= $nbTotalPage; $page++){
                    $str .= '<li class="page-item">';
                    $str .= '<a href="./?page= '.$page.' " class="page-link"> '.$page.' </a>';
                    $str .= '</li>';
                }
            $str .= '<li class="page-item">';


                    /* Avec le nombre total de pages, on peut aussi masquer le lien
                     * vers la page suivante quand on est sur la dernière */
                if ($currentPage < $nbTotalPage){
                    $str .= '<a href="./?page= '.($currentPage + 1).' " class="page-link">&raquo;</a>';
                    $str .= '</li>';
                }
            $str .= '</ul>';
            $str .= '</nav>';

        $this->content   = $str;
    }




    /**
     * méthode générant la page de résultat des recherches des internautes
     */
   /* public function makeSearchResultPage(array $get){
        $this->stylePage = "search.css";
        $this->title     = "Résultats de la recherche";
        $str = "";

            /**
             * TRAITEMENT DU FORMULAIRE
             */
      // $articles = $this->dbLocal->getPdo()->query("SELECT artist,discname FROM `album` ORDER BY artist DESC");

        /*if (isset($get['research']) && !empty($get['research'])){
            $word     = $this->dbLocal->htmlEchap($get['research']);
            $articles = $this->dbLocal->getPdo()->query( 'SELECT artist,discname,cover FROM `album` WHERE artist LIKE "%'.$word.'%" OR discname LIKE "%'.$word.'%"');

            if ($articles->rowCount() == 0){
                $articles = $this->dbLocal->getPdo()->query('SELECT artist,discname FROM `album` FROM WHERE CONCAT(artist,discname) LIKE "%'.$word.'%" ORDER BY id DESC');
            }
        }*/

        // affichage des résultats
      /*  if ($articles->rowCount() > 0){
            $str .= '<ul>';

            while ($a = $articles->fetch(PDO::FETCH_ASSOC)){
                $identifiant = (isset($a['id_album'])) ? $a['id_album']:"";
                $cover       = (isset($a['cover'])) ? $a['cover']:"";

                $str .= '<li> <a href="index.php?id=<?=$identifiant?>"> <figure id="fig">';
                $str .= '<img src="'.$cover.'" alt="'.$a["discname"].'" title="'.$a["discname"].'">';
                $str .= '<figcaption> <span id="artist"> '.$a['artist'].' </span> <br> <span id="album"> '.$a['discname'].' </span> </figcaption>';
                $str .= '</figure> </a> </li>';
                $str .= "\n";
            }
            $str .= '</ul>';
        }else{
            $feedb = "Aucun résultat trouvé pour : " . $word;
            $this->router->POSTredirect($this->router->getHomePageURL(), $feedb);
        }


        $this->content = $str;
    }


    /**
     * méthode générant la liste par année des disques de notre site
     */
    public function makeListPage()
    {
        $this->stylePage = "discography.css";
        $this->title     = "Discographie";
        $allYear         = $this->dbLocal->getDiscYear();

        $str             = '<h1> Discographie </h1>';
        $str            .= '<article class="discography">';
        $str            .= '<div class="divHeader"> Année </div>';
        $str            .= '<div class="divHeader"> Album </div>';
        $str            .= '</article>';

        $str          .= '<section class="discList"> ';
        foreach ($allYear as $disc){
            $discEsc = array();

            foreach ($disc as $key => $value){
                $discEsc[$key] = $this->dbLocal->htmlEchap($value);

                $str          .= '<div class="year"> '.$discEsc["releaseYear"].' </div>';

                $allAlbum     = $this->dbLocal->getAlbumByYearRelease($discEsc["releaseYear"]);

                $str  .= '<div class="album">';
                foreach ($allAlbum as $album){
                    $albumEsc = array();

                    foreach ($album as $cle => $valeur){
                        $albumEsc[$cle] = $this->dbLocal->htmlEchap($valeur);
                        $str  .= '<ul class="listing">';
                        $nom_disc = isset($albumEsc['discname']) ? $albumEsc['discname'] : null;
                        $str  .= '<li> <a href="'.$this->router->getDetailDisc($albumEsc['id_album']).'"> '.$nom_disc.' </a> </li> ';
                        $str  .= '</ul>';
                    }
                }
                $str  .= '</div>';
            }
        }
        $str  .= '</section>';


        $this->content   = $str;
    }


    /**
     * méthode générant le formulaire de connexion
     */
    public function makeLoginPage(){
        $this->stylePage = "login-register.css";
        $this->title     = "Connectez-vous à votre compte";
        $str             = '<form class="box" action="'.$this->router->getLoginURL().'" name="login" method="post" >';
        $str            .= '<h1>Connexion à discoDINGO</h1>';
        $str            .= '<input type="text" class="box-input" name="username-log" placeholder="Pseudo">';
        $str            .= '<input type="password" class="box-input" name="password-log" placeholder="Mot de passe">';
        $str            .= '<input type="submit" value="Connexion " name="submit-log" class="box-button">';
        $str            .= '<p class="box-register">Vous êtes nouveau ici? <a href=" '.$this->router->getRegisterURL().' ">Inscrivez-vous ici</a> </p>';
        $str            .= '</form>';

        $this->content   = $str;
    }


    /**
     * méthode générant le formulaire d'inscription
     */
    public function makeRegisterPage(){
        $this->stylePage = "login-register.css";
        $this->title     = "Création de votre compte";
        $str             = '<form class="box" action="'.$this->router->getsaveRegisterURL().'" name="register" method="post">';
        $str            .= '<h1>S\'inscrire</h1>';
        $str            .= '<input type="text" class="box-input" name="username" placeholder="Pseudo" required />';
        $str            .= '<input type="text" class="box-input" name="firstname" placeholder="Nom" required />';
        $str            .= '<input type="text" class="box-input" name="name" placeholder="Prénom" required />';
         $str           .= '<input type="password" class="box-input" name="password" placeholder="Mot de passe" required />';
        $str            .= '<input type="password" class="box-input" name="confirmPassword" placeholder="Mot de passe" required />';
        $str            .= '<input type="submit" name="submit" value="inscription" class="box-button" />';
        $str            .= '<p class="box-register">Déjà inscrit ? <a href=" '.$this->router->getLoginURL().' ">Connectez-vous ici</a> </p>';
        $this->content   = $str;
    }


    /**
     * méthode générant le formulaire de création du disque
     */
    public function makeDiscCreationPage(){
        $this->stylePage = "login-register.css";
        $this->title     = "Créer votre disque";
        $str             = '<form class="box" action="'.$this->router->getDiscSaveURL().'" method="post" enctype="multipart/form-data"> ';
        $str            .= '<h2> Crée ton album </h2>';
        $str            .= '<input type="text" class="box-input" name="artist-newdisc" placeholder="Nom de l\'artiste" required/> ';
        $str            .= '<input type="text" class="box-input" name="album-newdisc" placeholder="Titre de l\'album" required/> ';
        $str            .= '<input type="text" class="box-input" name="year-newdisc" placeholder="Année de sortie" required/> ';
        $str            .= '<input type="text" class="box-input" name="label-newdisc" placeholder="Label"/> ';
        $str            .= '<label> Cover </label><input type="file" class="box-input" name="monfichier" title="charger votre cover"/> ';
        $str            .= '<input type="submit" name="submit" value="Créer" class="box-button" />';
        $str            .= '</form>';

        $this->content = $str;
    }


    /**
     * méthode d'affichage de la page à propos
     */
    public function makeAboutPage()
    {
        $this->stylePage = "about.css";
        $this->title     = "À Propos du Groupe 47";
        $str             = "";
        $str            .= "<section>  <h1 id='group'>GROUPE 47</h1> <p style='color: whitesmoke'>Site web conçu par 4 étudiants de Licence Informatique, pour un devoir de maison du module <span id='module'>(INF5C1) Technologies Web 3 </span>
                            <br> <span id='univ'> <a href='http://www.unicaen.fr' style='color: whitesmoke'> UNIVERSITÉ DE CAEN </a> &copy; Novembre 2020 </span> </p> </section> <br>";
        $str            .= file_get_contents("contents/aboutPage.txt"); // on récupère le contenu pour notre page
        $this->content   = $str;
    }




    public function makeUnexpectedErrorPage(){
        $this->stylePage = "error404.css";
        $this->title     = "Erreur de page";
        $str             = '<div id="notfound">';
        $str            .= '<div class="notfound">';
        $str            .= '<div class="notfound-404"> <h1>Oops!</h1> </div>';
        $str            .= '<h2>404 - Page non trouvée</h2>';
        $str            .= '<p>La page que vous recherchez a peut-être été supprimée ou son nom a été modifiée ou est temporairement indisponible.</p>';
        $str            .= '<a href=" '.$this->router->getHomePageURL().' ">Aller à la page d\'accueil</a>';
        $str            .= '</div> </div>';

        $this->content   = $str;
    }


    public function makeUnknownAccountPage(){
        $this->stylePage = "errorPage.css";
        $this->title     = "This person escaped our custody";
        $this->content   = "<img class='wrong_profile' src='resources/Pages/error/wrong_profile.png' alt='wrong_profile'>";
    }



    public function makeUnauthorisedAccessPage() {
        $this->stylePage = "unauthoriseAccess.css";
        $this->title     = "VOUS N'AVEZ PAS ACCÈS À CETTE PAGE";
        $this->content   = "<p>GET OFF MY LAWN!</p>
		<img class='notAllowed' src='../../resources/Pages/noAccess/notAllowed.png' alt='notAllowed'>";

    }


    /**
     * méthode présentant les détails pour chaque disque
     * @param $id
     * @param array $session
     */
    public function makeDetailDiscPage($id,array $session){
  // récupération des infos du disque numéro $id
    $allDiscData        = $this->dbLocal->getDiscInfoById($id);
    $discUserId         = $allDiscData['user_id'];
    $sessionName        = (isset($session['user'])) ? $session['user']:"";
    $userIdTab          = $this->dbLocal->getUserIdByUsername($sessionName);
    $userId             = $userIdTab['id'];
    $loginStatus        = $this->dbLocal->getUserStatus($userId,$sessionName);


        if (key_exists("user", $session)) {
            if ($loginStatus === 'admin'){
                $this->stylePage = "detail.css";
                $this->title     = " ".$allDiscData['discname']." " . "de" . " ".$allDiscData['artist']." ";
                $str             = '<h2> <span id="artist-name"> '.$allDiscData["discname"].' </span> ';
                $str            .= ' de  <span id="artist-name"> '.$allDiscData['artist'].' </span> </h2> ';
                $str            .= '<section class="detail">';
                $str            .= '<figure id="fig-detail"> <img src="'.$allDiscData['cover'].'" alt="'.$allDiscData['discname'] .'" title="'.$allDiscData['discname'].'"/>';
                $str            .= '</figure>';
                $str            .= '<div class="div-disc"> <article> <ul>';
                $str            .= '<li> <span id="disc-name"> '.$allDiscData["discname"].' </span> </li> ';
                $str            .= '<li> Artiste         : <span id="disc-artist"> '.$allDiscData["artist"].' </span> </li> ';
                $str            .= '<li> Année de sortie : <span id="disc-year"> '.$allDiscData["releaseYear"].' </span> </li> ';
                $str            .= '<li> Label           : <span id="disc-label"> '.$allDiscData["label"].' </span> </li> ';
                $str            .= ' </ul> </article>  ';
                $str            .= '<article id="deleteupdate">';
                $str            .= '<a href=" '.$this->router->getDisqueAskDeletionURL($id).' " style="text-decoration: none;color: antiquewhite;background-color: red"> Supprimer </a> <br>';
                $str            .= '<a href=" '.$this->router->getDisqueAskmofifURL($id).' " id="lien-modif" style="text-decoration: none;color: antiquewhite;background-color: #1c242d"> Modifier </a>';
                $str            .= '</article>';
                $str            .=  '</div></section>';

                $this->content   = $str;
            }elseif ($loginStatus === 'user'){
                $this->stylePage = "detail.css";
                $this->title     = " ".$allDiscData['discname']." " . "de" . " ".$allDiscData['artist']." ";
                $str             = '<h2> <span id="artist-name"> '.$allDiscData["discname"].' </span> ';
                $str            .= ' de  <span id="artist-name"> '.$allDiscData['artist'].' </span> </h2> ';
                $str            .= '<section class="detail">';
                $str            .= '<figure id="fig-detail"> <img src="'.$allDiscData['cover'].'" alt="'.$allDiscData['discname'] .'" title="'.$allDiscData['discname'].'"/>';
                $str            .= '</figure>';
                $str            .= '<div class="div-disc"> <article> <ul>';
                $str            .= '<li> Nom de l\'album : <span id="disc-name"> '.$allDiscData["discname"].' </span> </li> ';
                $str            .= '<li> Artiste         : <span id="disc-artist"> '.$allDiscData["artist"].' </span> </li> ';
                $str            .= '<li> Année de sortie : <span id="disc-year"> '.$allDiscData["releaseYear"].' </span> </li> ';
                $str            .= '<li> Label           : <span id="disc-label"> '.$allDiscData["label"].' </span> </li> ';
                $str            .= ' </ul> </article> ';

                if ($discUserId === $userId){
                    $str            .= '<article class="deleteupdate">';
                    $str            .= '<a href=" '.$this->router->getDisqueAskDeletionURL($id).' " style="text-decoration: none;color: antiquewhite;background-color: red"> Supprimer </a> <br>';
                    $str            .= '<a href=" '.$this->router->getDisqueAskmofifURL($id).' " id="lien-modif" style="text-decoration: none;color: antiquewhite;background-color: #1c242d"> Modifier </a>';
                    $str            .= '</article>';
                }
                $str            .= '</div> </section>';

            }

            $this->content   = $str;
        }else {
            $feedback = "Vous devez vous connecter pour voir plus d'information sur le disque";
            $this->router->POSTredirect($this->router->getLoginURL(), $feedback);
        }





    }


    /**
     * méthode créant la liste des disques de l'utilisateur
     * @param $username
     */
    public function makeUserDiscListPage($username){
        $this->stylePage = "userdisc.css";
        $this->title     = "Mes disques";
        $alluserdisc     = $this->dbLocal->getAllUserDisc($username);

                /**
                  *   PAGINATION
                */
                // on récupère le nombre total d'articles
                $nbArticles = $this->dbLocal->countAllUserArticles($username);
                $perPage      = 8; // nombre d'articles par page
                $nbTotalPage  = ceil($nbArticles / $perPage); // calcul du nombre total de pages


                // On détermine sur quelle page on se trouve
                if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page']>0 && $_GET['page']<=$nbTotalPage){
                    $currentPage = (int) strip_tags($_GET['page']); //Supprime les balises HTML et PHP d'une chaîne
                    //$currentPage = $_GET['page'];
                }else{
                    $currentPage = 1;
                }


                $firstArticle = ($currentPage * $perPage) - $perPage; // calcul du 1er élément de la page


        $str             = '<h1> Liste de vos disques </h1>' ;
        $str            .= '<section class="userdisc">';
            if ($nbArticles === 0){
                $str    .= '<img src="resources/Pages/error/error_user_content.jpg" alt="Vous n\'avez pas encore créer ou ajouter un contenu " class="errorContentPic"/>';
            }else{
                $str            .= '<ul class="disclist">';
                foreach ($alluserdisc as $userdisc) {
                    $userdiscEsc = array();
                    foreach ($userdisc as $key => $value) {
                        $userdiscEsc[$key] = $this->dbLocal->htmlEchap($value);
                    }
                    $disc = isset($userdiscEsc["discname"]) ? $userdiscEsc["discname"] : null;
                    $str   .= '<li class="element"> <article id="bloc1"> <a href="index.php?id='.$userdiscEsc['id_album'].'"> <p class="coverdisc">';
                    $str   .= '<img src="'.$userdiscEsc['cover'].'" alt="'.$userdiscEsc['discname'] .'" title="'.$userdiscEsc['discname'].'"/> </p> </a> </article>';
                    $str   .= '</article>';
                    $str   .= ' </li>';
                }
                $str            .= '</ul> </section>';
            }

        //$str .= '</ul>';


        $str .= '<nav id="navPagination">';
        $str .= '<ul class="pagination">';
        $str .= '<li class="page-item">';

        /* Si on est sur la première page, on n'a pas besoin d'afficher de lien
*               vers la précédente. On va donc ne l'afficher que si on est sur une autre
*               page que la première */
        if ($currentPage > 1){
                $str .= '<a class="page-link" href="./?userDisc&page='.($currentPage - 1).'"> &laquo; </a>';
            }
        $str .= '</li>';

        for ($page = 1; $page <= $nbTotalPage; $page++){
            $str .= '<li class="page-item">';
            $str .= '<a href="./?userDisc&page= '.$page.' " class="page-link"> '.$page.' </a>';
            $str .= '</li>';
        }

        $str .= '<li class="page-item">';


                /* Avec le nombre total de pages, on peut aussi masquer le lien
                 * vers la page suivante quand on est sur la dernière */
            if ($currentPage < $nbTotalPage){
                $str .= '<a href=" '.$_SERVER['PHP_SELF'].'?userDisc&page= '.($currentPage + 1).' " class="page-link">&raquo;</a>';
                $str .= '</li>';
            }
        $str .= '</ul>';
        $str .= '</nav>';

        $this->content   = $str;
    }


    /**
     * méthode de demande de confirmation de suppresion du disque
     * @param $id
     */
    public function makeDeleteDiscButtonPage($id)
    {
        $this->stylePage = "login-register.css";
        $this->title     = " Confirmez la Suppression ";
        $str             = '<form action=" '.$this->router->getDisqueDeletionURL($id).' "method="POST">';
        $str            .= '<button type="submit" name="delete" id="delete-button"> supprimer </button>';
        $str            .= '</form>';

        $this->content   = $str;
    }


    /**
     * méthode de mise à jour des informations du disque
     * @param $id
     */
    public function makeUpdateDiscPage($id)
    {
        $this->stylePage = "login-register.css";
        $this->title     = " Modifier votre objet ";
        $str             = '<form class="box" name="register" method="post" action=" '.$this->router->getDisquemofifURL($id).' " enctype="multipart/form-data">';
        $str            .= '<h1>Entrez vos nouvelles données</h1>';
        $str            .= '<input type="text" class="box-input" name="artist-updisc" placeholder="Nom de l\'artiste" /> ';
        $str            .= '<input type="text" class="box-input" name="album-updisc" placeholder="Titre de l\'album" /> ';
        $str            .= '<input type="text" class="box-input" name="year-updisc" placeholder="Année de sortie" /> ';
        $str            .= '<input type="text" class="box-input" name="label-updisc" placeholder="Label"/> ';
        $str            .= '<label> Cover </label><input type="file" class="box-input" name="monfichier"/> ';
        $str            .= '<input type="submit" name="update" value="modifier" class="box-button" />';
        $str            .= '</form>';

        $this->content   = $str;
    }






    public function displayErrorFieldPasswordFeedback($feedback){
        $this->router->POSTredirect($this->router->getRegisterURL(),$feedback);
    }

    public function displayAuthenticatedFeedback($feedback){
        $this->router->POSTredirect("index.php?discography",$feedback);
    }

    public function displayAccountCreatedFeedback(){
        $feedback = "Votre compte a été crée avec succès";
        $this->router->POSTredirect("index.php?action=login", $feedback);
    }

    public function displayAccountNotCreatedFeedback(){
        $feedback = "Votre compte n'a pu etre crée";
        $this->router->POSTredirect("index.php?action=createAccount", $feedback);
    }

    public function displayLoginSuccessfullyPageFeedback(){
        $feedback = "Bienvenue" . " " . $_SESSION['user'] . " sur discoDINGO";
        $this->router->POSTredirect($this->router->getHomePageURL(), $feedback);
    }

  public function displayLoginFailledPageFeedback(){
        $feedback = "<font color=red>Echec de Connexion ,Reessayer</font>";
        $this->router->POSTredirect($this->router->getLoginURL(), $feedback);
    }


    public function displayUnsuccessfulLoginPageFeedback(){
        $feedback = " Mot de passe ou pseudo invalide ";
        $this->router->POSTredirect($this->router->getLoginURL(), $feedback);
    }

    public function displayAccountDisconnectedPageFeedback(){
        $feedback = "Déconnexion réussie";
        $this->router->POSTredirect($this->router->getHomePageURL(), $feedback);
    }

    public function displayAccountCreatedPageFeedback($username){
        $feedback    = "Votre compte a été crée avec succès";
        $this->router->POSTredirect($this->router->getProfilPage(), $feedback);
    }

    public function displayAccountNotCreatedPageFeedback(){
        $feedback = "Votre n'a pu etre crée";
        $this->router->POSTredirect($this->router->getRegisterURL(), $feedback);
    }

    public function displayDiscCreatedPageFeedback(){
        $feedback = "Votre disque a été crée";
        $this->router->POSTredirect($this->router->getRegisterURL(), $feedback);
    }

    public function displayDiscNotCreatedPageFeedback(){
        $feedback = "Votre disque n'a pu etre crée";
        $this->router->POSTredirect($this->router->getRegisterURL(), $feedback);
    }

    public function displayDiscEditedFeedback($id)
    {
        $this->router->POSTredirect($this->router->getDisquemofifURL($id), "Votre disque a été modifié");
    }


    public function displayDiscNotEditedFeedback($id)
    {
        $this->router->POSTredirect($this->router->getDisquemofifURL($id), "Votre disque n'a pas été modifié");
    }


    public function displayDisqueSuppressionSuccess()
    {
        $this->router->POSTredirect($this->router->getAllUserDiscURL(), "Votre disque a été supprimé");
    }













}

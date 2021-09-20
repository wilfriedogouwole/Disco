<?php
require_once('model/Disc.php');
require_once('control/Controller.php');
require_once('view/View.php');
require_once("src/lib/DatabaseLocal.php");
?>



<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <title><?php echo $this->title?></title>

        <!-- feuille de style -->
    <link rel="stylesheet" href="stylesheet/menu.css" type="text/css">
    <link rel="stylesheet" href="stylesheet/footer.css" type="text/css">
    <link rel="stylesheet" href="stylesheet/<?php echo  $this->stylePage; ?>" type="text/css">



</head>



<body>



    <header>
        <?php
         // var_dump($_SESSION);


          //var_dump($_POST);
          //var_dump($_GET);





            if (!($this->title === "Erreur de page")){
                require_once("src/model/includes/searchbar.php");
            }

        ?>




        <h3 id="feedback">
            <?php if (key_exists("feedback", $_SESSION)) {
                echo $_SESSION["feedback"] ;
                unset($_SESSION["feedback"]);
            }
            ?>

        </h3>

        <!-- insertion du menu -->
        <?php
            if (!($this->title === "Erreur de page")){
          if(key_exists("admin", $_SESSION)) {
            echo "<P>Vous etre Admin";
            require_once("src/model/includes/navbar3.php");
        }
        elseif(key_exists("user", $_SESSION)) {
        require_once("src/model/includes/navbar2.php");

        }else{
            require_once("src/model/includes/navbar.php");
        }
    }
        ?>
    </header>




    <main>
            <?php
            if ($this->content!==null) {
                echo $this->content;
            }
            ?>
    </main>


        <!-- insertion du footer -->
        <?php
            if (!($this->title === "Erreur de page")){
                require_once("src/model/includes/footer.php");
            }
        ?>
</body>

</html>

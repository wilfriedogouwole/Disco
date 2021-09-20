<?php

require_once("src/Router.php");


$router = new Router();

/**
 * @return string : retourne l'année courante usr 4 digits
 */
function getCurrentYear(){
    setlocale(LC_TIME,"fr_FR");
    $currentYear = strftime('%Y');
    return $currentYear;
}


function htmlEsc($string){
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE | ENT_COMPAT);
}
?>


<footer>
    <p> Site réalisé pour un projet scolaire dans le cadre de l'apprentissage du langage PHP et des technologies du web en troisième année de Licence informatique par
        <ul>
            <li>  21812350 VOUVOU Brandon   </li>
            <li>  21814023 OGOUWELE Derrick </li>
            <li>  21910271 TOUBAKILA Naslie  </li>
            <li>  21911445 KEITA Lansana  </li>
        </ul>
    </p>

    <p id="para2"> Copyright &copy; discoDINGO <?php echo getCurrentYear(); ?> </p>

</footer>
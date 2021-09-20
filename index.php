<?php

/*²²²²²²²²²²²²²²²²²²
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");
require_once('view/View.php');
require_once("lib/DatabaseLocal.php");
require_once('model/Disc.php');
require_once('model/DiscStorage.php');
require_once('model/DiscStorageStub.php');
require_once('model/DiscBuilder.php');
require_once('model/DiscStorageFile.php');
require_once('model/AccountStorageMySQL.php');




/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 *
 * */



$router   = new Router();
$database = new DatabaseLocal();

$anAccountStorageMySQL = new AccountStorageMySQL($database->getPdo());

$router->main($anAccountStorageMySQL);


/*$db = new DatabaseLocal();


$amdb=new DiscStorageStub($db);
$router = new Router($amdb);
$router->main();
*/

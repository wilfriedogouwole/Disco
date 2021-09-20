<?php
    require_once("src/lib/DatabaseLocal.php");


    $db = new DatabaseLocal();

    $articles = $db->getPdo()->query("SELECT id_album,artist,discname,cover FROM `album` ORDER BY artist DESC");

    if (isset($_GET['research']) && !empty($_GET['research'])){
        $word     = $db->htmlEchap($_GET['research']);
        $articles = $db->getPdo()->query( 'SELECT id_album,artist,discname,cover FROM album WHERE artist LIKE "%'.$word.'%" OR discname LIKE "%'.$word.'%"');

        if ($articles->rowCount() == 0){
            $articles = $db->getPdo()->query('SELECT id_album,artist,discname,cover FROM `album` FROM WHERE CONCAT(artist,discname) LIKE "%'.$word.'%" ORDER BY id DESC');
        }
    }
?>

<form action = "index.php?search" method="GET">
    <input type="search" name="research" placeholder="Recherche..." />
    <input type="submit" name="search" value="Valider" />
</form>
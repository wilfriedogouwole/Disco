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


<div class="searchbar">
    <form action = "index.php?search" method="GET" >
        <input type="search" name="research" placeholder="Recherche..." />
        <input type="submit" value="Valider" />
    </form>
</div>


    <!-- Affichage des résulats-->

<?php
    if (key_exists("research", $_GET)) {
?>
<?php if ($articles->rowCount() > 0){ ?>

    <ul>
        <?php while ($a = $articles->fetch(PDO::FETCH_ASSOC)){ ?>
                <?php $identifiant = (isset($a['id_album'])) ? $a['id_album']:"";
                      $cover       = (isset($a['cover'])) ? $a['cover']:"";
                      
            ?>
           <li> <a href="index.php?id=<?=$identifiant?>">
                   <figure id="fig">';
                        <img src="<?=$cover?>" alt="<?=$a['discname']?>" title="<?=$a['discname']?>"/>
                            <figcaption> <span id="artist"> <?=$a['artist']?> </span> <br> <span id="album"> <?=$a['discname']?> </span> </figcaption>';
                    </figure> </a> </li>
      <?php  } ?>
    </ul>
    <?php }else{ ?>
        <h2> Aucun résultat trouvé pour : <span> <?= $word; ?> </span> </h2>
   <?php } }?>
<?php

/**
 * import des fichiers nécessaires
 */
require_once('model/Disc.php');


/**
 * Interface DiscStorage
 */
interface DiscStorage
{
    public function createDiscInDatabase(Disc $disc);

    public function discExist($disc);

    public function readDiscById($id);

    public function readAllDisc();

    public function updateDiscData($id, Disc $disc);

    public function deleteDisc($id) ;
}


?>
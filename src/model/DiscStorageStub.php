<?php

/**
 * import des fichiers nécessaires
 */
require_once('model/Disc.php');
require_once('DiscStorage.php');
require_once("src/lib/DatabaseLocal.php");


/**
 * Class DiscStorageStub
 */
class DiscStorageStub extends DiscStoragefile
{
    private $db;

    /**
     * DiscStorageStub constructor.
     * @param DatabaseLocal $db
     */
    public function __construct(DatabaseLocal $db)
    {
        $this->db = $db;
    }






    /**
     * méthode vérifiant si un disque existe dans notre BD
     * @param $disc
     * @return mixed
     */
    public function discExist($disc)
    {
        if ($this->db->discExist($disc)){
            return true;
        }
        return false;
    }

    /**
     * méthode renvoyant un array d'infos du disque $id
     * @param $id disque dont on veut avoir les infos
     * @return mixed
     */
    public function readDiscById($id)
    {
        if (self::discExist($id)){
            return $this->db->getAllUserDisc($id);
        }
        return;
    }




    /**
     * méthode renvoyant une liste de tous les disques
     * @return mixed
     */
    public function readAllDisc()
    {
        return $this->db->getAllDisc();
    }




    /**
     * méthode mettant à jour les infos du disque $disc
     * @param $id
     * @param Disc $disc
     * @return mixed
     */
    public function updateDiscData($id, Disc $disc)
    {
        if (self::discExist($id)){
            $this->db->updateDiscInformation($id, $disc);
            return true;
        }
        return false;
    }



    /**
     * méthode supprimant un album
     * @param $id
     * @return mixed
     */
    public function deleteDisc($id)
    {
       if (self::discExist($id)){
           $this->db->deleteDisc($id);
           return true;
       }
       return false;
    }
}


?>

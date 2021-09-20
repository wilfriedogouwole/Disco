<?php

/**
 * import des fichiers nécessaires
 */
require_once('model/DiscStorage.php');
require_once('model/Disc.php');


/**
 * Class DiscStoragefile :
 */
class DiscStoragefile implements DiscStorage
{
    private $database;

    /**
     * DiscStoragefile constructor.
     * @param ObjectFileDB $database : base de données du site
     */
    public function __construct(ObjectFileDB $database)
    {
        $this->database=$database;
    }


    /**
     * méthode permettant la création d'un disque dans la base de données
     * @param Disc $disc : disque à ajouter
     * @return string
     */
    public function createDiscInDatabase(Disc $disc)
    {
        return $this->database->insert($disc);
    }


    /**
     * méthode permettant de réinitialiser les données de la base
     */
    public function reinit()
    {
        $this->database->deleteAll();
        /*$this->database->insert(new Disc('Ceinture Noir', 'Maitre gims', 2017));
        $this->database->insert(new Disc('Amour', 'Celine dion', 2016));
        $this->database->insert(new Disc('Italia', 'Jul', 2019));*/
    }


    /**
     * méthode qui permet de lire une ligne correspondant à l'identifiant
     * @param $id : identifiant dont on veut récupérer l'object
     * @return mixed|null
     * @throws Exception
     */
    public function read($id)
    {
        if ($this->database->exists($id)) {
            return $this->database->fetch($id);
        } else {
            return null;
        }
    }


    /**
     * méthode permettant de retourner toutes les lignes de notre tableau de données
     * @return array
     */
    public function readAll()
    {
        return $this->database->fetchAll();
    }


    /**
     * méthode permettant la suppression de l'objet d'identifiant $id dans la base de données
     * @param $id
     * @throws Exception
     */
    public function delete($id)
    {
        return $this->database->delete($id);
    }

    /**
     * méthode permettant de mettre à jour un disque correspondant à l'identifiant $ids
     * @param $id
     * @param Disc $disc
     * @throws Exception
     */
    public function update($id, Disc $disc)
    {
        return $this->database->update($id, $disc);
    }

    /**
     * @param $disc
     * @return mixed
     */
    public function discExist($disc)
    {
        // TODO: Implement discExist() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function readDiscById($id)
    {
        // TODO: Implement readDiscById() method.
    }

    /**
     * @return mixed
     */
    public function readAllDisc()
    {
        // TODO: Implement readAllDisc() method.
    }

    /**
     * @param $id
     * @param Disc $disc
     * @return mixed
     */
    public function updateDiscData($id, Disc $disc)
    {
        // TODO: Implement updateDiscData() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteDisc($id)
    {
        // TODO: Implement deleteDisc() method.
    }
}

?>

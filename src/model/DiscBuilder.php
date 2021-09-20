<?php

/**
 * import des fichiers nécessaires
 */
//require_once('view/View.php');
require_once('Disc.php');


/**
 * Class DiscBuilder permet de créer un disque en respectant certaines contraintes
 */
class DiscBuilder
{

    private $data;
    private $error;
    private $coverData;
    private $storage;
    private $coverFormat;


    /**
     * DiscBuilder constructor.
     * @param null $data
     */
    public function __construct($data=null, $coverData=null)
    {
        if ($data === null || $coverData === null){
            $data = array(
                "name" => "",
                "artist" => "",
                "year" => "",
                "label" => ""
            );

            $coverData = array(
                "cover" => ""
            );
        }
        $this->data      = $data;
        $this->coverData = $coverData;
        $this->error     = array();
    }




    public function checkImageFormat($format){
        if(in_array($format , $this->coverFormat)) {
            return true;
        }
        return false;
    }



    public static function buildFromDisc(Disc $disc){
        return new DiscBuilder(
            array(
                "name" => $disc->getName(),
                "artist" => $disc->getArtist(),
                "year" => $disc->getYear(),
                "label" => $disc->getLabel()
            ),
            $coverData = array(
                "cover" => $disc->getCover()
            )
        );
    }


    public function isUpdatedDataDiscValid() {

        if (!key_exists("album-updisc", $this->data) || $this->data["album-updisc"] === ""){
            $this->error["album-updisc"] = "Vous devez entrer un nom d'album.";
        }else if (mb_strlen($this->data["album-updisc"], 'UTF-8') >= 255){
            $this->error["album-updisc"] = "Le nom de l'album doit avoir 255 caractères.";
        }

        if (!key_exists("artist-updisc", $this->data) || $this->data["artist-updisc"] === ""){
            $this->error["artist-updisc"] = "Vous devez entrer un nom d'album.";
        }else if (mb_strlen($this->data["artist-updisc"], 'UTF-8') >= 40){
            $this->error["artist-updisc"] = "Le nom de l'album doit avoir 40 caractères.";
        }

        if (mb_strlen($this->data["year-updisc"], 'UTF-8') !== 4){
            $this->error["year-updisc"] = "L'année doit etre ecrit sur 4 chiffres.";
        }

        if (!key_exists("monfichierAjour", $this->coverData) || $this->coverData["monfichierAjour"]['name'] === ""){
            $this->error["monfichierAjour"] = "You must choose an image for your planet.";
        }else if($this->coverData["monfichierAjour"]['size'] >= 1000000){
            $this->error["image"] = "Taille de l'image trop grande.";
        }
        return count($this->error) === 0;
    }


    public function isDataDiscValid() {

        if (!key_exists("album-newdisc", $this->data) || $this->data["album-newdisc"] === ""){
            $this->error["album-newdisc"] = "Vous devez entrer un nom d'album.";
        }else if (mb_strlen($this->data["album-newdisc"], 'UTF-8') >= 255){
            $this->error["album-newdisc"] = "Le nom de l'album doit avoir 255 caractères.";
        }

        if (!key_exists("artist-newdisc", $this->data) || $this->data["artist-newdisc"] === ""){
            $this->error["artist-newdisc"] = "Vous devez entrer un nom d'album.";
        }else if (mb_strlen($this->data["artist-newdisc"], 'UTF-8') >= 40){
            $this->error["artist-newdisc"] = "Le nom de l'album doit avoir 40 caractères.";
        }

        if (mb_strlen($this->data["year-newdisc"], 'UTF-8') !== 4){
            $this->error["year-newdisc"] = "L'année doit etre ecrit sur 4 chiffres.";
        }

        if (!key_exists("monfichier", $this->coverData) || $this->coverData["monfichier"]['name'] === ""){
            $this->error["monfichier"] = "You must choose an image for your planet.";
        }else if($this->coverData["monfichier"]['size'] >= 1000000){
            $this->error["image"] = "Taille de l'image trop grande.";
        }
        return count($this->error) === 0;
    }



    public function getNameRef() {
        return "name";
    }

    public function getArtistRef() {
        return "artist";
    }

    public function getLabelRef() {
        return "label";
    }

    public function getCoverRef() {
        return "cover";
    }


    public function getData($ref) {
        return key_exists($ref, $this->data)? $this->data[$ref]: '';
    }

/*    public function getCoverData($ref) {
        return key_exists($ref, $this->coverData)? $this->coverData[$ref]: '';
    }*/


    public function getErrors($ref) {
        return key_exists($ref, $this->error)? $this->error[$ref]: null;
    }


    /**
     * @return Disc
     * @throws Exception
     */
    public function createDisc()
    {
        if (!key_exists("album-newdisc", $this->data)){
            throw new Exception("Champ manquant pour la création du disque");
        }
        if (!key_exists("artist-newdisc", $this->data)){
            throw new Exception("Champ manquant pour la création du disque");
        }
        if (!key_exists("label-newdisc", $this->data)){
            throw new Exception("Champ manquant pour la création du disque");
        }
        if (!key_exists("year-newdisc", $this->data)){
            throw new Exception("Champ manquant pour la création du disque");
        }
        if (!key_exists("monfichier", $this->coverData)){
            throw new Exception("Champ manquant pour la création du disque");
        }

        return new Disc($this->data["artist-newdisc"], $this->data["album-newdisc"], $this->data["year-newdisc"], $this->data["label-newdisc"], "resources/album_thumb/resize-pic/" . basename($this->coverData['monfichier']['name']));
    }



    /**
     * méthode permettant de modifier une info du disque
     * @param Disc $disc : disque dont on veut modifier les infos
     */
    public function updateDisc(Disc $disc)
    {
        if (key_exists("album-updisc", $this->data)) {
           $disc->setName($this->data["album-updisc"]);
        }
        if (key_exists("artist-updisc", $this->data)) {
            $disc->setArtist($this->data["artist-updisc"]);
        }
        if (key_exists("year-updisc", $this->data)) {
            $disc->setYear($this->data["year-updisc"]);
        }
        if (key_exists("label-updisc", $this->data)) {
            $disc->setLabel($this->data["label-updisc"]);
        }

        return new Disc($this->data["artist-updisc"], $this->data["album-updisc"], $this->data["year-updisc"], $this->data["label-updisc"], "resources/album_thumb/resize-pic/" . basename($this->coverData['monfichier']['name']));
    }



    /**
     * @return mixed|null
     */
    public function getDiscData()
    {
        return $this->data;
    }


    public function getError()
    {
        return $this->error;
    }


}


?>

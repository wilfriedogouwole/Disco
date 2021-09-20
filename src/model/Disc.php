<?php


/**
 * Class Disc permet de creer un objet disque
 */


class Disc
{
    private $name;
    private $artist;
    private $year;
    private $label;
    private $cover;


    /**
     * Disc constructor.
     * @param $artist
     * @param $name
     * @param $year
     * @param $label
     */
    public function __construct($artist, $name, $year, $label, $cover)
    {
        $this->name   = $name;
        $this->year   = $year;
        $this->artist = $artist;
        $this->label  = ($label !== " ") ? $label : "N/A";
        $this->cover  = $cover;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name)
    {
        if (!self::isNameDiscValid($name)){
            throw new Exception("Nom de disque invalide pour .$name");
        }
        $this->name = $name;
    }


    /**
     * @param $year
     * @return mixed
     */
    public function setYear($year)
    {
        if (self::isYearValid($year)){
            throw new Exception("Format invalide pour l'année de sortie");
        }
        $this->year = $year;
    }


    /**
     * @param $artist
     * @return mixed
     */
    public function setArtist($artist)
    {
        if (!self::isNameArtistValid($artist)){
            throw new Exception("Nom $artist invalide pour l'artiste");
        }
        $this->artist =$artist;
    }




    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        if (!self::isLabelValid($label)){
            throw new Exception("Nom de label invalide pour .$label");
        }
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getCover(){
        return $this->cover;
    }


    public static function isNameDiscValid($name){
        return mb_strlen($name, 'UTF-8') < 25 && $name!== "" && $name!== NULL;
    }

    public static function isNameArtistValid($artist){
        return mb_strlen($artist, 'UTF-8') < 25 && $artist!== "" && $artist!== NULL;
    }

    public static function isLabelValid($label){
        return mb_strlen($label, 'UTF-8') && $label!== "" && $label!== NULL;
    }

    public static function isYearValid($year){
        return mb_strlen($year, 'UTF-8') === 4 && $year !=="" && $year!==NULL;
    }


}


?>
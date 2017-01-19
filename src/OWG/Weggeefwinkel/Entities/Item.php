<?php
namespace OWG\Weggeefwinkel\Entities;
/**
 * Description of Item
 *
 * @author steven.jespers
 */
class Item {
    private static $idMap = array();
    private $id;
    private $title;
    private $description;
    private $user;
    private $section;
    private $img;
    private $date;
    private $postcode;
    
    private function __construct($id, $title, $description, $img, $date,  $user, $section) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->section = $section;
        $this->img = $img;
        $this->date = $date;
    }
    
    public static function create($id, $title, $description, $img, $date, $user, $section) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Item($id, $title, $description, $img, $date, $user, $section);
        }
        return self::$idMap[$id];
    }
    
   
    
    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getUser() {
        return $this->user;
    }

    function getSection() {
        return $this->section;
    }

    function getImg() {
        return $this->img;
    }

    function getDate() {
        return $this->date;
    }

    function getPostcode() {
        return $this->postcode;
    }

    
    function setId($id) {
        $this->id = $id;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setSection($section) {
        $this->section = $section;
    }

    function setImg($img) {
        $this->img = $img;
    }








    
    

}

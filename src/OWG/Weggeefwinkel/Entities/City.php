<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Entities;

/**
 * Description of City
 *
 * @author steven.jespers
 */
class City {
    private static $idMap = array();
    private $id;
    private $name;
    private $postcode;
    
    private function __construct($id, $postcode, $cityname) {
        $this->id = $id;
        $this->postcode = $postcode;
        $this->name = $cityname;
    }
    
    public static function create($id, $postcode, $cityname) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new City($id, $postcode, $cityname);
        }
        return self::$idMap[$id];
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getPostcode() {
        return $this->postcode;
    }


}

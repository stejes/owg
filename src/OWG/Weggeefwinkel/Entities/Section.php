<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace OWG\Weggeefwinkel\Entities;

/**
 * Description of Section
 *
 * @author steven.jespers
 */
class Section {
    //put your code here
    private static $idMap = array();
    private $id;
    private $name;
    
    private function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public static function create($id, $name) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Section($id, $name);
        }
        return self::$idMap[$id];
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }


}

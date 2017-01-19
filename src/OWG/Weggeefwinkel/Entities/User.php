<?php

namespace OWG\Weggeefwinkel\Entities;
/*
 * Description of User
 *
 * @author steven.jespers
 */
class User {
    private static $idMap = array();
    private $id;
    private $username;
    private $city;
    private $email;
    private $password;
    
    private function __construct($id, $username, $city, $email, $password){
        $this->id = $id;
        $this->username = $username;
        $this->city = $city;
        $this->email = $email;
        $this->password = $password;
    }
    
    public static function create($id, $username, $city, $email, $password){
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new User($id, $username, $city, $email, $password);
        }
        return self::$idMap[$id];
    }
    
    function getId() {
        return $this->id;
    }

        
    function getUsername() {
        return $this->username;
    }
    function getPassword() {
        return $this->password;
    }

        
    
    function getCity() {
        return $this->city;
    }

    function getEmail() {
        return $this->email;
    }
    
    function setCity($city) {
        $this->city = $city;
    }

    function setEmail($email) {
        $this->email = $email;
    }
    
    function setPassword($password) {
        $this->password = $password;
    }








}

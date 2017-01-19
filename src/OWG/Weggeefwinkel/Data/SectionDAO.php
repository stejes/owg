<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Data;
use OWG\Weggeefwinkel\Entities\Section;
use PDO;

/**
 * Description of SectionDAO
 *
 * @author steven.jespers
 */
class SectionDAO {

    //put your code here
    public function getAll() {

        $sql = "select id, name from sections";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $section = Section::create($rij["id"], $rij["name"]);
            array_push($lijst, $section);
        }
        $dbh = null;
       
        return $lijst;
    }
    
    public function getById($id){
        $sql="select id, name from sections where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $section = Section::create($rij["id"], $rij["name"]);
        $dbh=null;
        return $section;
        
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Data;

use OWG\Weggeefwinkel\Data\DBConfig;
use OWG\Weggeefwinkel\Entities\City;
use PDO;

/**
 * Description of CityDAO
 *
 * @author steven.jespers
 */
class CityDAO {

    public function getAll() {
        $sql = "select id, postcode, name from cities order by postcode asc";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);

        $lijst = array();
        foreach ($resultSet as $rij) {
            $city = City::create($rij["id"], $rij["postcode"], $rij["name"]);
            array_push($lijst, $city);
        }
        $dbh = null;
        return $lijst;
    }

    public function getById($id) {

        $sql = "select id, postcode, name from cities where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);        
        $dbh = null;
        if($rij){
        $city = City::create($rij["id"], $rij["postcode"], $rij["name"]);
        return $city;
        }else{
            return null;
        }
    }

}

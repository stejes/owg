<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Business;
use OWG\Weggeefwinkel\Data\CityDAO;

/**
 * Description of CityService
 *
 * @author steven.jespers
 */
class CityService {
    //put your code here
    public function getAll(){
        $cityDao = new CityDAO();
        return $cityDao->getAll();
    }
}

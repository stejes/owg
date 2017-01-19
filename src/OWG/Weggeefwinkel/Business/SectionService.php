<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OWG\Weggeefwinkel\Business;
use OWG\Weggeefwinkel\Data\SectionDAO;

/**
 * Description of SectionService
 *
 * @author steven.jespers
 */
class SectionService {
    //put your code here
    public function getAll(){
        $sectionDAO = new SectionDAO();
        $sectionList = $sectionDAO->getAll();
        return $sectionList;
    }
}

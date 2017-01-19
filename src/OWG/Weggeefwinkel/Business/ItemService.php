<?php

//business/BoekService.php
//require_once("data/BoekDAO.php");

namespace OWG\Weggeefwinkel\Business;

use OWG\Weggeefwinkel\Data\ItemDAO;
use OWG\Weggeefwinkel\Data\SectionDAO;

class ItemService {

    public function getLastItems() {
        $itemDAO = new ItemDAO();
        $itemList = $itemDAO->getLast();
        return $itemList;
    }

    public function getByUser($username) {
        $itemDAO = new ItemDAO();
        $itemList = $itemDAO->getByUser($username);
        return $itemList;
    }

    public function getById($id) {
        $itemDAO = new ItemDAO();
        $item = $itemDAO->getById($id);
        return $item;
    }

    public function getUser($id) {
        $itemDAO = new ItemDAO();
        return $itemDAO->getById($id)->getUser();
    }

    public function updateItem($id, $title, $description, $img, $sectionId) {
        $itemDAO = new ItemDAO();
        $sectionDAO = new SectionDAO();
        $item = $itemDAO->getById($id);
        $section = $sectionDAO->getById($sectionId);        
        $item->setTitle($title);
        $item->setDescription($description);
        $item->setImg($img);
        $item->setSection($section);
        $itemDAO->update($item);
    }

    public function addItem($title, $description, $img, $sectionId, $userId) {
        $itemDAO = new ItemDAO();
        $itemDAO->create($title, $description, $img, $sectionId, $userId);
    }
    
    public function deleteItem($id){
        $itemDAO = new ItemDAO();
        $itemDAO->delete($id);
    }
    
    public function search($keywordArray, $postcode, $section){
        $itemDAO = new ItemDAO();
        //$keywordArray = explode(',', $keywords);
        //print_r($keywordArray);
        $itemList = $itemDAO->getByConditions($keywordArray, $postcode, $section);
        return $itemList;
    }

}

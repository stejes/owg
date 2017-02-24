<?php

session_start();
require_once 'bootstrap.php';


use OWG\Weggeefwinkel\Business\ItemService;
use OWG\Weggeefwinkel\Business\UserService;
use OWG\Weggeefwinkel\Business\CityService;

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit(0);
} else {
    $itemSvc = new ItemService();
    $itemList = $itemSvc->getByUser($_SESSION["username"]);
    $userSvc = new UserService();
    
    //$citySvc = new CityService();
    //$cityList = $citySvc->getAll();
    
    $user = $userSvc->getByUsername($_SESSION["username"]);
    
    //print_r($user);
    $view = $twig->render("items.twig", array("itemList" => $itemList, "username" =>$_SESSION["username"], "user" => $user, "itemsActive" => "active"));
    print($view);
}


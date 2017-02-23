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
    
    $citySvc = new CityService();
    $cityList = $citySvc->getAll();
    if(isset($_GET["action"])){
        if($_GET["action"] == "editData"){
           
            $userSvc->update($_POST["email"], $_POST["city"]);
        }
        if($_GET["action"] == "editPass"){
           
            $userSvc->updatePass($_POST["oldPass"], $_POST["pass"], $_POST["pass2"]);
        }
    }
    $user = $userSvc->getByUsername($_SESSION["username"]);
    
    //print_r($user);
    $view = $twig->render("account.twig", array("itemList" => $itemList, "username" =>$_SESSION["username"], "user" => $user, "cityList" => $cityList, "page" => "account"));
    print($view);
}


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
    $dataErrors = array();
    $passErrors = array();
    $error = "";
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "editData") {
            if (isset($_POST["email"]) && $_POST["email"] != "" && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error = "Geef een geldig (of geen) e-mailadres op";
                array_push($dataErrors, $error);
            }

            if (!isset($_POST["city"]) || !ctype_digit($_POST["city"]) || $_POST["city"] > 100000) {
                //header("location:login.php?error=invalidcity");

                $error = "Ongeldige gemeente";
                array_push($dataErrors, $error);
            }

            if (sizeof($dataErrors) === 0) {
                try {
                    $userSvc->update($_POST["email"], $_POST["city"]);
                } catch (InvalidCityException $ex) {
                    header("location:login.php?error=invalidinput");
                    exit(0);
                }
            }
        }
        if ($_GET["action"] == "editPass") {
            if ($_POST["password"] != $_POST["password2"]) {
                //header("location:")
                $error = "Paswoorden zijn niet gelijk.";
                array_push($passErrors, $error);
            }
            if (strlen($_POST["password"]) < 6) {
                $error = "Gelieve een paswoord van minstens 6 karakters op te geven";
                array_push($passErrors, $error);
            }
            if (sizeof($passErrors) === 0) {
                $userSvc->updatePass($_POST["oldPass"], $_POST["pass"], $_POST["pass2"]);
            }
        }
    }
    $user = $userSvc->getByUsername($_SESSION["username"]);
    if (isset($_GET["error"])) {
        $error = $_GET["error"];
    }
    //print_r($user);
    $view = $twig->render("account.twig", array("itemList" => $itemList, "username" => $_SESSION["username"], "user" => $user, "cityList" => $cityList, "error" => $error, "dataErrors" => $dataErrors, "passErrors" => $passErrors, "accountActive" => "active"));
    print($view);
}


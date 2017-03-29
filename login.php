<?php

session_start();
require_once 'bootstrap.php';

use OWG\Weggeefwinkel\Business\UserService;
use OWG\Weggeefwinkel\Business\CityService;
use OWG\Weggeefwinkel\Business\ItemService;
use OWG\Weggeefwinkel\Business\InputValidator;
use OWG\Weggeefwinkel\Exceptions\InvalidCityException;
use OWG\Weggeefwinkel\Exceptions\UsernameExistsException;

/*
 * login en registratiepagina
 * 
 */
$registererrors = array();
$validator = new InputValidator();
if (isset($_POST["login"])) {
    //check of het over een geldige login gaat, zet sessie of breek af
    
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        //echo "hier zijn we";
        try {
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
            //$username = $_POST["username"];
            $userSvc = new UserService();
            $validUser = $userSvc->checkLogin($username, $_POST["password"]);
        } catch (LoginFailedException $ex) {
            header("location:login.php?error=loginfailed");
            exit(0);
        }
        if (!$validUser) {
            header("location:login.php?error=loginfailed");
            exit(0);
        } else {
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $validUser->getId();
            header("location: items.php");
            exit(0);
        }
    }
} elseif (isset($_POST["register"])) {
    //geeft ingevoerde input aan de service voor validatie, log onmiddellijk in als registratie gelukt is
    //check op lege velden, city = int en paswoorden zijn gelijk, voeg errors toe aan errors-array


    if (!(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["city"]))) {
        //header("location:login.php?error=emptyfields");
        $error = "Alle velden zijn verplicht.";
        array_push($registererrors, $error);
    }

    if (strlen($_POST["username"]) < 3 || strlen($_POST["username"]) > 40) {
        $error = "Gebruikersnaam moet tussen 3 en 20 karakters zijn.";
        array_push($registererrors, $error);
    }

    if (!isset($_POST["city"]) || !ctype_digit($_POST["city"]) || $_POST["city"] > 100000) {
        //header("location:login.php?error=invalidcity");
        $error = "Ongeldige gemeente";
        array_push($registererrors, $error);
    }

    if ($_POST["password"] != $_POST["password2"]) {
        //header("location:")
        $error = "Paswoorden zijn niet gelijk.";
        array_push($registererrors, $error);
    } else if (strlen($_POST["password"]) < 6) {
        $error = "Gelieve een paswoord van minstens 6 karakters op te geven";
        array_push($registererrors, $error);
    }

    if (sizeof($registererrors) == 0) {
        //indien geen errors, stuur naar de userservice
        try {
            $userSvc = new UserService();
            
            $newId = $userSvc->registerUser($_POST["username"], $_POST["password"], $_POST["password2"], $_POST["city"]);

            if ($newId) {
                $_SESSION["username"] = $_POST["username"];
                $_SESSION["id"] = $newId;
                header("location: items.php");
                exit(0);
            } else {
                header("location:login.php?error=unknown");
            }
        } catch (UsernameExistsException $ex) {
            header("location:login.php?error=userexists");
            exit(0);
        } catch (InvalidCityException $ex) {
            header("location:login.php?error=invalidinput");
            exit(0);
        }
    }
} elseif (isset($_GET["action"])) {
    //bij logout, destroy de session en redirect naar de index

    if ($_GET["action"] == "logout") {
        session_destroy();
        header("location: index.php");
        exit(0);
    }
}






if (!isset($_SESSION["username"])) {
    $citySvc = new CityService();
    $cityList = $citySvc->getAll();
    $error = "";
    if (isset($_GET["error"])) {
        $error = $_GET["error"];
        //array_push($registererrors, $_GET["error"]);
    }
    $view = $twig->render("loginForm.twig", array("cityList" => $cityList, "regerrors" => $registererrors, "error" => $error, "loginActive" => "active"));
    print($view);
} else {
    $itemSvc = new ItemService();
    $itemList = $itemSvc->getByUser($_SESSION["username"]);
    //$view = $twig->render("account.twig", array("itemList" => $itemList, "username" => $_SESSION["username"]));
    //print($view);
    header("location:items.php");
    exit(0);
    //print "jeuj: " . $_SESSION["username"];
}


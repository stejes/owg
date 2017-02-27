<?php

session_start();
require_once 'bootstrap.php';

//use OWG\Weggeefwinkel\Business\UserService;
//use OWG\Weggeefwinkel\Business\CityService;
use OWG\Weggeefwinkel\Business\ItemService;
use OWG\Weggeefwinkel\Business\SectionService;
use OWG\Weggeefwinkel\Business\UserService;
use OWG\Weggeefwinkel\Business\MessageService;
use OWG\Weggeefwinkel\Entities\Item;
use OWG\Weggeefwinkel\Business\PhotoService;

if (!isset($_SESSION["username"])) {

    header("location: login.php");
    exit(0);
}

$sectionSvc = new SectionService();
$sectionList = $sectionSvc->getAll();
$itemSvc = new ItemService();
$userSvc = new UserService();
$item = null;
if (isset($_GET["id"])) {
    $item = $itemSvc->getById($_GET["id"]);
}
$itemErrors = array();
if (isset($_GET["action"])) {
    if ($_GET["action"] == "add") {
        if (isset($_POST["addItem"])) {

            if (!(isset($_POST["title"]) && isset($_POST["description"])) || $_POST["title"] == "" || $_POST["description"] == "") {
                $error = "Titel en omschrijving zijn verplicht";
                array_push($itemErrors, $error);
            }
            if (strlen($_POST["title"]) > 50) {
                $error = "Titel mag maximaal 50 karakters bevatten";
                array_push($itemErrors, $error);
            }
            if (strlen($_POST["description"]) > 500) {
                $error = "Omschrijving mag maximaal 500 karakters bevatten";
                array_push($itemErrors, $error);
            }

            if (strlen($_FILES["img"]["name"]) > 200) {
                $error = "Bestandsnaam mag maximaal 200 karakters bevatten";
                array_push($itemErrors, $error);
            }
            if (sizeof($itemErrors) == 0) {
                if (!empty($_FILES["img"]["name"])) {
                    $photoSvc = new PhotoService();

                    $photoName = $photoSvc->handlePhoto($_FILES["img"]);
                } else {
                    $photoName = "no-image.png";
                }
                $user = $userSvc->getByUsername($_SESSION["username"]);
                $itemSvc->addItem($_POST["title"], $_POST["description"], $photoName, $_POST["section"], $user->getId());
                //header("location: items.php");
                //exit(0);
            }
        }
        $view = $twig->render("addItem.twig", array("sectionList" => $sectionList, "username" => $_SESSION["username"], "itemErrors" => $itemErrors));
        print($view);
    }


    //print_r($item);
    elseif ($_GET["action"] == "edit") {


        if (isset($_POST["submit"])) {
            if ($item->getUser()->getUsername() == $_SESSION["username"]) {
                if(isset($_POST["imgRemove"])){
                    $photoName = "no-image.png";
                }
                elseif (!empty($_FILES["img"]["name"])) {
                    $photoSvc = new PhotoService();

                    $photoName = $photoSvc->handlePhoto($_FILES["img"]);
                } else {
                    $photoName = "no-image.png";
                }
                $itemSvc->updateItem($_GET["id"], $_POST["title"], $_POST["description"], $photoName, $_POST["section"]);

                header("location: items.php");
                exit(0);
            } else {
                print "ni van u eh";
            }
        }
        $view = $twig->render("editItem.twig", array("item" => $item, "sectionList" => $sectionList, "username" => $_SESSION["username"]));
        print($view);
    } elseif ($_GET["action"] == "delete") {
        $itemSvc->deleteItem($_GET["id"]);
        header("location: items.php");
    } elseif ($_GET["action"] == "show") {
        if (isset($_POST['send'])) {

            //print ("jaja");

            $messageSvc = new MessageService();
            $messageSvc->writeMessage($_POST['title'], $_POST['text'], $item->getUser(), null);
        }
        $view = $twig->render("showItem.twig", array("item" => $item, "sectionList" => $sectionList, "username" => $_SESSION["username"]));
        print($view);
    }
}


//print "jeuj: " . $_SESSION["username"];
else {
    $view = $twig->render("showItem.twig", array("item" => $item, "username" => $_SESSION["username"]));
    print($view);
}


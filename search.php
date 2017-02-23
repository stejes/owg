<?php

session_start();
require_once 'bootstrap.php';

use OWG\Weggeefwinkel\Business\SectionService;
use OWG\Weggeefwinkel\Business\ItemService;
use OWG\Weggeefwinkel\Business\CityService;

$sectionSvc = new SectionService();
$sectionList = $sectionSvc->getAll();
$citySvc = new CityService();
$cityList = $citySvc->getAll();
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
} else {
    $username = "";
}

if (isset($_GET["search"])) {
    //print "wel";
    $itemSvc = new ItemService();
    $keywords = "";
    $postcode = "";
    $section = "";
    if (isset($_GET["keywords"]) && $_GET["keywords"] != "") {
        $keywords = $_GET["keywords"];
    }
    if (isset($_GET["postcode"]) && $_GET["postcode"] != "") {
        $postcode = $_GET["postcode"];
    }
    if (isset($_GET["section"]) && $_GET["section"] != "") {
        $section = $_GET["section"];
    }
    $keywords = explode(" ", $_GET["keywords"]);

    $itemList = $itemSvc->search($keywords, $postcode, $section);
    // print_r($keywords);
    $view = $twig->render("search.twig", array("sectionList" => $sectionList, "itemList" => $itemList, "keywords" => $keywords, "username" => $username, "cityList" => $cityList, "page" => "search"));
    print($view);
} else {
    $view = $twig->render("search.twig", array("sectionList" => $sectionList, "username" => $username, "cityList" => $cityList, "page" => "search"));
    print($view);
}

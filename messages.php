<?php

session_start();
require_once 'bootstrap.php';

use OWG\Weggeefwinkel\Business\ItemService;
use OWG\Weggeefwinkel\Business\UserService;
use OWG\Weggeefwinkel\Business\CityService;
use OWG\Weggeefwinkel\Business\MessageService;

if (!isset($_SESSION["username"])) {
    header("location: login.php");
    exit(0);
} else {
     $username = $_SESSION["username"];
     $userid = $_SESSION["id"];
     //print $userid;
     $messageSvc = new MessageService();

    if (isset($_POST['send'])) {
        print ("jaja");
        
        
        $messageSvc->writeMessage($_POST['title'], $_POST['text'], $_SESSION['username'], null);
    }
    
    $messageList = $messageSvc->getUserMessages($userid);


    $view = $twig->render("messages.twig", array("username" => $username, "messageList" => $messageList, "messagesActive" => "active"));
    print($view);
}


<?php
require_once 'bootstrap.php';
$view = $twig->render("index.twig", array());
print($view);
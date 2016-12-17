<?php
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once ('main_page_controller.php');

$possiblePages = array('home', 'organizace', 'articles');

if (isset($_GET["web"]) && in_array($_GET["web"], $possiblePages)) {

} else {
    $mainPageController = new MainPageController();
    echo $mainPageController->getHTML();
}

?>

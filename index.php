<?php

/*
 *  Main controller.
 */

require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once ('main_page_controller.php');
require_once ('articles_page_controller.php');
require_once ('display_article_controller.php');
require_once ('login_page_controller.php');



// map a controller to each page
$controllersForPages = array(
    'home' => 'MainPageController',
    'organizace' => 'MainPageController',
    'articles' => 'ArticlesPageController',
    'article' => 'DisplayArticleController',
    'login' => 'LoginPageController'
);


// choose the right controller
if (isset($_GET["web"]) && array_key_exists($_GET["web"], $controllersForPages)) {
    $page = $_GET["web"];
    $controller = new $controllersForPages[$page];
    echo $controller->getHTML();
} else {

    // no web -> show main page
    $mainPageController = new MainPageController();
    echo $mainPageController->getHTML();
}

?>

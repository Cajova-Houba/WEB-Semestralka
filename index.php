<?php

/*
 *  Main controller.
 */

require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();

require_once ('core/code/utils.php');

// map a controller to each page
$controllersForPages = array(
    'home' => 'MainPageController',
    'organizace' => 'MainPageController',
    'articles' => 'ArticlesPageController',
    'article' => 'DisplayArticleController',
    'login' => 'LoginPageController',
    'register' => 'RegisterPageController',
    'users' => 'ManageUsersPageController',
    'reviews' => 'ArticlesToReviewController',
    'myarticles' => 'MyArticlesPageController',
    'logout' => 'LogoutController',
    'new_article' => 'NewArticlePageController',
    'edit_article' => 'NewArticlePageController',
    'download' => 'DownloadFileController'
);


// choose the right controller
if (isset($_GET["web"]) && array_key_exists($_GET["web"], $controllersForPages)) {
    $page = escapechars($_GET["web"]);
    require_once ($controllersForPages[$page].'.php');
    if($page == 'organizace') {
        $controller = new MainPageController(MainPageController::ABOUT_PAGE);
        echo $controller->getHTML();
    } else {
        $controller = new $controllersForPages[$page];
        echo $controller->getHTML();
    }
} else {

    // no web -> show main page
    require_once ('MainPageController.php');
    $mainPageController = new MainPageController(MainPageController::MAIN_PAGE);
    echo $mainPageController->getHTML();
}

?>

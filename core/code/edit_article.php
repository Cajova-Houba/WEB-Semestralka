<?php
/*
 * This script will fetch editing/deleting an article
 */
require_once ('dao/user_dao.php');
require_once ('dao/article_dao.php');
require_once ('utils.php');
require_once ('classes/Login.class.php');

$articleDao = new ArticleDao();
$userDao = new UserDao();

// user has to be logged in and must be author
$login = new Login();
if(!$login->isUserLogged()) {
    redirHome();
}

$user = $userDao->getUserByUsername($login->getUsername());
if(!$user->isAuthor()) {
    redirHome();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["action"])) {
        redirHome();
    }

    if (!isset($_POST["aid"])) {
        redirHome();
    }
    $articleId = $_POST["aid"];

    $action = escapechars($_POST["action"]);
    if($action === "delete") {
        // delete article
        if($articleDao->canDelete($user->getId(), $articleId)) {
            $articleDao->remove($articleId);
        }
    } else if ($action === "edit") {
        // edit article
        redirTo('new_article.php?aid='.escapechars($articleId));
    }
}
redirHome();
?>
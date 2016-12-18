<?php
/*
 * This script will fetch publishing a reviewed article.
 */
require_once('dao/UserDao.php');
require_once('dao/ReviewDao.php');
require_once('dao/ArticleDao.php');
require_once ('utils.php');
require_once ('classes/Login.class.php');

$userDao = new UserDao();
$reviewDao = new ReviewDao();
$articleDao = new ArticleDao();
$login = new Login();

//accept only post requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // user has to be admin
    if(!$login->isUserLogged()) {
        redirHome();
    }
    $user = $userDao->getUserByUsername($login->getUsername());
    if(!$user->isAdmin()) {
        redirHome();
    }

    if(!isset($_POST["article_id"])) {
        // no article id
        redirHome();
    }

    $aId = $_POST["article_id"];
    // check that the article has been reviewed three times
    $reviews = $reviewDao->getReviewsForArticle($aId);
    if(sizeof($reviews) != 3) {
        // error
        redirHome();
    }

    // publish article, reject the article
    if(!isset($_POST["action"])) {
        // no action
        redirHome();
    }

    if($_POST["action"] === "publish") {
        $rowCount = $articleDao->publishArticle($aId);
        if ($rowCount != 1) {
            // error
            redirHome();
        }
    } else if ($_POST["action"] === "reject") {
        // reject
        $res = $articleDao->rejectArticle($aId);
        if($res != 1) {
            //error
            echo "error";
            redirHome();
        }
    }
}

redirHome();

?>
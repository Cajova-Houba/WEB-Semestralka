<?php
/**
 * This script will fetch assigning a new article to reviewers.
 *
 * User must be logged in and must be admin.
 */
require_once ('utils.php');
require_once('dao/UserDao.php');
require_once('dao/ReviewDao.php');
require_once('dao/ArticleDao.php');
require_once ('classes/Login.class.php');

$login = new Login();
$userDao = new UserDao();
$reviewDao = new ReviewDao();
$articleDao = new ArticleDao();
if(!$login->isUserLogged()) {
    redirHome();
}
$user = $userDao->getUserByUsername($login->getUsername());
if(!$user->isAdmin()) {
    redirHome();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rev1 = isset($_POST["r1"]) ? intval(escapechars($_POST["r1"])) : -1;
    $rev2 = isset($_POST["r2"]) ? intval(escapechars($_POST["r2"])) : -1;
    $rev3 = isset($_POST["r3"]) ? intval(escapechars($_POST["r3"])) : -1;
    $articleId = isset($_POST["article_id"]) ? escapechars($_POST["article_id"]) : -1;

    //verify that the reviewers exist and don't allow duplicities
    $rev1Ok = $userDao->reviewerExists($rev1);
    $rev2Ok = $userDao->reviewerExists($rev2) && $rev2 != $rev1;
    $rev3Ok = $userDao->reviewerExists($rev3) && $rev3 != $rev2 && $rev3 != $rev1;


    //check that the article exists
    if(!$articleDao->exists($articleId)) {
        redirHome();
    }

    //assign to reviewers
    //this script may be reused when reassigning reviewers so it's better to do it like this
    if($rev1Ok) {
        $res = $reviewDao->assignReview($articleId, $rev1, $user->getId());
        if($res != 1) {
            //error
        }
    }
    if($rev2Ok) {
        $res = $reviewDao->assignReview($articleId, $rev2, $user->getId());
        if($res != 1) {
            //error
        }
    }
    if($rev3Ok) {
        $res = $reviewDao->assignReview($articleId, $rev3, $user->getId());
        if($res != 1) {
            //error
        }
    }
}

redirHome();


?>
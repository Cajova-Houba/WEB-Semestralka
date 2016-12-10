<?php

/*

    This script will fetch saving a new review of an article.

*/
require_once('dao/review_dao.php');
require_once('dao/user_dao.php');
require_once('utils.php');
require_once('classes/Login.class.php');

$userDao = new UserDao();
$reviewDao = new ReviewDao();

// first, check is the user is logged in and is reviewer
$login = new Login();
if(!$login->isUserLogged()) {
    redirHome();
}
$user = $userDao->getUserByUsername($login->getUsername());
if($user == null ||!$user->isReviewer()) {
    redirHome();   
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $reviewId = isset($_POST["review_id"]) ? escapechars($_POST["review_id"]) : -1;
    $crit1 = isset($_POST["c1"]) ? escapechars($_POST["c1"]) : 0;
    $crit2 = isset($_POST["c2"]) ? escapechars($_POST["c2"]) : 0;
    $crit3 = isset($_POST["c3"]) ? escapechars($_POST["c3"]) : 0;
    $crit4 = isset($_POST["c4"]) ? escapechars($_POST["c4"]) : 0;
    
    //check if the review belongs to this reviewer
    if(!$reviewDao->correctReviewer($reviewId, $user->getId())) {
        redirHome();
    }
    
    $reviewRes = ReviewResult::newResult($crit1, $crit2, $crit3, $crit4);
    $res = $reviewDao->reviewArticle($reviewId, $reviewRes);
    
    if($res == 1) {
        //ok
        redirHome();
    } else {
        //error
        redirHome();
    }
    
} else {
    redirHome();
}

?>
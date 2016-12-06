<?php
/*

This script will fetch the creation of the new article.

*/
require_once('article_dao.php');
require_once('user_dao.php');
require_once('utils.php');
require_once('classes/Login.class.php');

$userDao = new UserDao();
$articleDao = new ArticleDao();

// first, check is the user is logged in
$login = new Login();
if(!$login->isUserLogged()) {
    redirHome();
}
$user = $userDao->getUserByUsername($login->getUsername());
if($user == null ||!$user->isAuthor()) {
    redirHome();   
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST["title"]) ? escapechars($_POST["title"]) : "";
    $content = isset($_POST["content"]) ? escapechars($_POST["content"]) : "";
    $created = date("Y-m-d H:i:s"); 
    
    // validate
    $titleOk = checkTitle($title);
    $contentOk = checkContent($content);
    
     // check for errors
    if(!$titleOk) {
        header('Location: http://localhost/kiv-web/new_article.php?err='.Errors::TITLE_NOT_OK);
        die('Chyba při registraci, zkuste to znovu...');
    }
    if(!$contentOk) {
        header('Location: http://localhost/kiv-web/new_article.php?err='.Errors::CONTENT_NOT_OK);
        die('Chyba při registraci, zkuste to znovu...');
    }
    
    // save the article
    $article = new Article();
    $article->setTitle($title);
    $article->setContent($content);
    $article->setCreated($created);
    $authors = [];
    $authors[] = $user->getId();
    $article->setAuthors($authors);
    
//    print_r($article);
//    echo("Title: ".$article->getTitle());
    $res = $articleDao->newArticle($article);
    if($res != 1) {
        /*error*/
//        echo("Error");
        redirHome();
    } else {
        /*success*/
//        echo("Success");
        redirHome();
    }
} else {
    redirHome();
}

?>
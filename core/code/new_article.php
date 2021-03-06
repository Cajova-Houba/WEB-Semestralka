<?php
/*

This script will fetch the creation of the new article.
Also editing an existing one.

*/
require_once('dao/ArticleDao.php');
require_once('dao/UserDao.php');
require_once('dao/AttachmentDao.php');
require_once('utils.php');
require_once('classes/Login.class.php');

$userDao = new UserDao();
$articleDao = new ArticleDao();
$attachmentDao = new AttachmentDao();

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
    $attachment = isset($_FILES["attachment"]) ? $_FILES["attachment"] : null;

    // validate
    $titleOk = checkTitle($title);
    $contentOk = checkContent($content);
    
     // check for errors
    if(!$titleOk) {
        redirToPageWithParams('new_article', array("err" => Errors::TITLE_NOT_OK));
    }
    if(!$contentOk) {
        redirToPageWithParams('new_article', array("err" => Errors::CONTENT_NOT_OK));
    }

    /* file too big */
    if($attachment["size"] > 500000) {
        redirToPageWithParams('new_article', array("err" => Errors::FILE_NOT_OK));
    }

    // save the article
    $aid = null;
    if(isset($_POST["aid"])) {
        // aid is set => editing existing article
        $aid = escapechars($_POST["aid"]);
        if(!$articleDao->isAuthor($aid, $user->getId())) {
            echo "Not author.";
            redirHome();
        }

        $article = $articleDao->get($aid);
        $article->setTitle($title);
        $article->setContent($content);
        $articleId = $articleDao->updateArticle($article);
        if ($articleId == 0) {
            /*error*/
            echo "Article not saved";
            redirHome();
        } else {
            $articleId = $aid;
        }
    } else {
        $article = new Article();
        $article->setTitle($title);
        $article->setContent($content);
        $article->setCreated($created);
        $authors = [];
        $authors[] = $user->getId();
        $article->setAuthors($authors);
        $articleId = $articleDao->newArticle($article);
    }

    if($articleId == 0) {
        /*error*/
        echo("Error");
        redirHome();
    } else {
        /*success*/

        /* save the attachment */
        if($attachment != null && !empty($attachment["name"])) {
            // remove the previous article
            if($aid != null) {
                $attachmentDao->removeByArticle($aid);
            }

            // save the new attachment
            $res = $attachmentDao->saveFile($attachment, $articleId);
            if($res != 1) {
                /*error => remove article */

                echo "Error while saving the file";
                $articleDao->remove($articleId);
//                var_dump($_FILES);
//                var_dump($_POST);
            }
        } else {
            echo "file is null.";
//            var_dump($_FILES);
//            var_dump($_POST);
        }
        echo("Success");
        redirHome();
    }

} else {
    redirHome();
}

?>
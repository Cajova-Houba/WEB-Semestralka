<?php
/*
 * This file will fetch downloading a file.
 * fid get parameter specifies the file id.
 */
require_once ('core/code/dao/attachment_dao.php');
require_once ('core/code/dao/article_dao.php');
require_once ('core/code/utils.php');
require_once ('core/code/dao/attachment_dao.php');
require_once ('core/code/dao/user_dao.php');
require_once ('core/code/classes/Login.class.php');

if(!isset($_GET["fid"])) {
    redirHome();
}

$fid = escapechars($_GET["fid"]);
$attachmentDao = new AttachmentDao();
$articleDao = new ArticleDao();

// check that the file exists
if(!$attachmentDao->exists($fid)) {
    redirHome();
}
$attachment = $attachmentDao->get($fid);

// check that the article has been already published
// only author can download the attachment of an unpublished article
if (!$articleDao->isPublished($attachment->getArticleId())) {
    $login = new Login();
    $userDao = new UserDao();

    if($login->isUserLogged()) {
        $user = $userDao->getUserByUsername($login->getUsername());
    } else {
        redirHome();
    }

    if(!$articleDao->isAuthor($attachment->getArticleId(), $user->getId())) {
        redirHome();
    }
}

$file = $attachment->getPath().$attachment->getName();
//var_dump($file);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
readfile($file);
exit;

?>
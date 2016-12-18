<?php

require_once('core/code/dao/AttachmentDao.php');
require_once('core/code/dao/ArticleDao.php');
require_once ('core/code/utils.php');
require_once('core/code/dao/AttachmentDao.php');
require_once ('core/code/manager/UserManager.php');

/**
 * Controller for downloading files.
 */
class DownloadFileController {

    private $file;

    function __construct() {
        if(!isset($_GET["fid"])) {
            return;
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
            $userManager = new UserManager();
            $user = $userManager->authenticate();
            if($user == null) {
                return;
            }

            if(!$articleDao->isAuthor($attachment->getArticleId(), $user->getId())) {
                return;
            }
        }

        $this->file = $attachment->getPath().$attachment->getName();
    }

    function getHTML() {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($this->file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($this->file));
        readfile($this->file);
        exit;
    }
}
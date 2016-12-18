<?php

/**
 * Controller for displaying one artilce.
 */
require_once('ui/MainPageView.php');
require_once('ui/misc/NavbarView.php');
require_once('ui/DisplayArticleView.php');

require_once('core/code/manager/UserManager.php');
require_once('core/code/dao/ArticleDao.php');
require_once('core/code/dao/AttachmentDao.php');
require_once ('core/code/utils.php');

class DisplayArticleController
{
    private $navbar = NavbarView::DEFAULT_NAVBAR;
    private $data = [];

    function __construct()
    {
        $articleDao = new ArticleDao();
        $userManager = new UserManager();
        $attachmentDao = new AttachmentDao();

        // check that the article id is specified and article exists
        if(!isset($_GET["aId"]) || !$articleDao->exists($_GET["aId"])) {
            redirHome();
        }
        $article = $articleDao->get($_GET["aId"]);

        // check if the user is logged in - author can view his unpublished articles
        $user = $userManager->authenticate();

        // article is unpublished and no user is logged in or isn't author
        $isAuthor = $user == null ? false : $articleDao->isAuthor($article->getId(), $user->getId());
        if(!$article->isPublished() && !$isAuthor) {
            redirHome();
        }

        // stuff for article
        $this->data["article"] = $article;
        $this->data["authors"] = authorsToString($articleDao->getAuthorsForArticle($article->getId()));
        $this->data["attachments"] = $attachmentDao->getAttachmentsForArticle($article->getId());
        $this->data["showEdit"] = $isAuthor;

        //stuff for navbar
        if($user != null) {
            $this->data["firstName"] = $user->getFirstName();
            $this->data["lastName"] = $user->getLastName();
            if($user->isAuthor()) {
                $this->navbar = NavbarView::AUTHOR_NAVBAR;
            } else if ($user->isAdmin()) {
                $this->navbar = NavbarView::ADMIN_NAVBAR;
            } else if ($user->isReviewer()) {
                $this->navbar = NavbarView::REVIEWER_NAVBAR;
            }
        }
    }

    function getHTML() {
        return DisplayArticleView::getHTML('Článek', MainMenuView::NOTHING_ACTIVE,$this->navbar, $this->data);
    }
}
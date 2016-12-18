<?php

/**
 * Controller for articles page.
 */
require_once('ui/MainPageView.php');
require_once('ui/misc/NavbarView.php');
require_once('ui/ArticlesPageView.php');

require_once('core/code/manager/UserManager.php');
require_once('core/code/dao/ArticleDao.php');
require_once ('core/code/utils.php');

class ArticlesPageController {
    private $info;
    private $navbar = NavbarView::DEFAULT_NAVBAR;
    private $data = [];


    function __construct()
    {
        // check the logged user
        $userManager = new UserManager();
        $user = $userManager->authenticate();
        if($user != null) {
            $this->data = array ("firstName" => $user->getFirstName(), "lastName" => $user->getLastName());
            if($user->isAuthor()) {
                $this->navbar = NavbarView::AUTHOR_NAVBAR;
            } else if ($user->isAdmin()) {
                $this->navbar = NavbarView::ADMIN_NAVBAR;
            } else if ($user->isReviewer()) {
                $this->navbar = NavbarView::REVIEWER_NAVBAR;
            }
        }

        // check the info parameter
        if (isset($_GET["info"])) {
            $this->info = Infos::getInfoMessage($_GET["info"]);
        }

        $this->getPublishedArticles();
    }

    /*
     * Format published articles for template.
     */
    private function getPublishedArticles() {
        $articleDao = new ArticleDao();
        $articles = $articleDao->getPublished();
        $articlesData = [];
        foreach ($articles as $article) {
            $authorsStr = authorsToString($articleDao->getAuthorsForArticle($article->getId()));
            $articlesData[] = array("article" => $article, "authors" => $authorsStr);
        }

        $this->data["articles"] = $articlesData;
    }

    function getHTML() {
        return ArticlesPageView::getHTML($this->info, $this->navbar, $this->data);
    }
}
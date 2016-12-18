<?php
require_once ('core/code/manager/UserManager.php');
require_once ('core/code/dao/ArticleDao.php');
require_once ('core/code/utils.php');
require_once ('ui/misc/NavbarView.php');
require_once ('ui/NewArticlePageView.php');
require_once ('ui/misc/MainMenuView.php');

/**
 * Controller for adding/editing new article.
 */
class NewArticlePageController {

    private $navbar = NavbarView::AUTHOR_NAVBAR;
    private $data = [];
    private $title;

    function __construct() {
        $userManager = new UserManager();
        $user = $userManager->authenticate();
        if($user == null || !$user->isAuthor()) {
            redirHome();
        }
        $this->data["firstName"] = $user->getFirstName();
        $this->data["lastName"] = $user->getLastName();
        $this->data["article"] = null;
        $this->data["errorMessage"] = null;
        $this->title = 'Nový článek';

        //if the aid is specified, author can edit his articles
        $articleDao = new ArticleDao();
        $article = null;
        if(isset($_GET["aid"])) {
            $aid = escapechars($_GET["aid"]);

            // if the user isn't author, or the article has been already published
            // editing is not possible
            if($articleDao->isAuthor($aid, $user->getId()) && !$articleDao->isPublished($aid)) {
                $article = $articleDao->get($aid);
                $this->title = 'Upravit článek';
                $this->data["article"] = $article;
            }
        }

        //possible error messages
        if(isset($_GET["err"])) {
            $this->data["errorMessage"] = Errors::getErrorMessage($_GET["err"]);
        }
    }

    function getHTML() {
        return NewArticlePageView::getHTML($this->title, MainMenuView::NOTHING_ACTIVE, $this->navbar, $this->data);
    }
}
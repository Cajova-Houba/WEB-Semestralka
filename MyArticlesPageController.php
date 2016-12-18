<?php
require_once ('core/code/manager/UserManager.php');
require_once ('core/code/utils.php');
require_once ('ui/misc/NavbarView.php');
require_once ('core/code/dao/ArticleDao.php');
require_once ('core/code/dao/ReviewDao.php');
require_once ('ui/MyArticlesPageView.php');

/**
 * Controller for page displaying author's articles.
 */
class MyArticlesPageController {

    private $navbar = NavbarView::AUTHOR_NAVBAR;
    private $data = [];

    function __construct() {
        $userManager = new UserManager();
        $user = $userManager->authenticate();
        if($user == null || !$user->isAuthor()) {
            redirHome();
        }
        $this->data["firstName"] = $user->getFirstName();
        $this->data["lastName"] = $user->getLastName();

        $articleDao = new ArticleDao();
        $reviewDao = new ReviewDao();
        $articles = $articleDao->getArticlesForAuthor($user->getId());


        // format data for template
        $formattedArticles = [];
        foreach ($articles as $article) {
            $formattedArticle = [];
            $authors = authorsToString($articleDao->getAuthorsForArticle($article->getId()));

            //prepare the review object if needed
            if($article->getState() === ArticleState::TO_BE_REVIEWED || $article->getState() === ArticleState::REVIEWED) {
                $reviewResults = $reviewDao->getReviewsForArticle($article->getId());

                // initialize the review object with empty values
                $review = array(1 => array('-','-','-','-'),
                    2 => array('-','-','-','-'),
                    3 => array('-','-','-','-'));

                // fill the array with actual review results
                for ($i = 0; $i < sizeof($reviewResults); $i++) {
                    if(!$reviewResults[$i]->isReviewed()) {
                        continue;
                    }

                    $revRes = $reviewDao->getReviewResult($reviewResults[$i]->getReviewResultId());
                    $review[$i+1]  = array(
                        $revRes->getCrit1(),
                        $revRes->getCrit2(),
                        $revRes->getCrit3(),
                        $revRes->getCrit4()
                    );
                }
                $formattedArticle["review"] = $review;
            }

            $formattedArticle["article"] = $article;
            $formattedArticle["authors"] = $authors;

            $formattedArticles[] = $formattedArticle;
        }

        $this->data["articles"] = $formattedArticles;
    }

    function getHTML() {
        return MyArticlesPageView::getHTML("Mé články", MainMenuView::NOTHING_ACTIVE,$this->navbar, $this->data);
    }

}
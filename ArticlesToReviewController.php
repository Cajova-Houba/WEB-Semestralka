<?php

require_once ('core/code/utils.php');
require_once ('core/code/manager/UserManager.php');
require_once ('core/code/dao/ReviewDao.php');
require_once ('ui/misc/NavbarView.php');
require_once ('ui/ArticlesToReviewView.php');

/**
 * New articles assigned to reviewer.
 */
class ArticlesToReviewController {

    private $navbar = NavbarView::REVIEWER_NAVBAR;
    private $data = [];

    function __construct() {

        // user has to be reviewer
        $userManager = new UserManager();
        $user = $userManager->authenticate();
        if($user == null || !$user->isReviewer()) {
            redirHome();
        }

        // get articles to review and format them for template
        $formatedReviews = [];
        $reviewDao = new ReviewDao();
        $articles = $reviewDao->getArticlesToReview($user->getId());
        foreach($articles as $article) {
            $reviewDetails = [];
            $reviewDetails["article"] = $article["article"];
            $reviewDetails["review"] = $article["review"]->getId();
            $assignedBy = "Assigned by: ";
            $assigner = $userManager->get($article["review"]->getAssignedById());
            if ($assigner == null) {
                $assignedBy = $assignedBy . "-";
            } else {
                $assignedBy = $assignedBy . $assigner->getUsername();
            }
            $reviewDetails["assigner"] = $assignedBy;
            $formatedReviews[] = $reviewDetails;
        }

        $this->data["firstName"] = $user->getFirstName();
        $this->data["lastName"] = $user->getLastName();
        $this->data["reviews"] = $formatedReviews;
    }

    function getHTML() {
        return ArticlesToReviewView::getHTML($this->navbar, $this->data);
    }
}
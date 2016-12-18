<?php
/*
 * Controller for page.
 */
require_once('ui/MainPageView.php');
require_once ('ui/AboutConferencePageView.php');
require_once('ui/misc/NavbarView.php');

require_once('core/code/manager/UserManager.php');
require_once ('core/code/utils.php');

class MainPageController {

    // main page and 'organizace' page
    const MAIN_PAGE = 1;
    const ABOUT_PAGE = 2;

    private $info;
    private $navbar = NavbarView::DEFAULT_NAVBAR;
    private $data;
    private $page = MainPageController::MAIN_PAGE;


    function __construct($page)
    {

        if (isset($page)) {
            $this->page = $page;
        }

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
    }


    function getHTML() {
        switch ($this->page) {
            case MainPageController::ABOUT_PAGE:
                return AboutConferencePageView::getHTML("Organizace", MainMenuView::ORGANIZACE_ACTIVE, $this->navbar, $this->data);
                break;
            default:
                return MainPageView::getHTML($this->info, $this->navbar, $this->data);
        }
    }
}

?>
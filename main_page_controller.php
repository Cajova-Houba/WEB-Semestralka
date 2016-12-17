<?php
/*
 * Controller for page.
 */
require_once ('ui/main_page.php');
require_once ('ui/misc/navbar.php');

require_once ('core/code/manager/user_manager.php');
require_once ('core/code/utils.php');

class MainPageController {

    private $info;
    private $navbar = NavbarView::DEFAULT_NAVBAR;
    private $data;


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
    }


    function getHTML() {
        return MainPageView::getHTML($this->info, $this->navbar, $this->data);
    }
}

?>
<?php
/*

    A script which will display a correct navbar.

*/
require_once('navbar/NavbarAdminView.php');
require_once('navbar/NavbarAuthorView.php');
require_once('navbar/NavbarDefaultView.php');
require_once('navbar/NavbarReviewerView.php');

class NavbarView {

    const DEFAULT_NAVBAR = 0;
    const ADMIN_NAVBAR = 1;
    const AUTHOR_NAVBAR = 2;
    const REVIEWER_NAVBAR = 3;

    /**
     * For the DEFAULT_NAVBAR no data are expected. For other navbars,
     * $data["firstName"] and $data["lastName"] are expected.
     */
    static function getHTML($navbarType, $data) {
        switch ($navbarType) {
            case NavbarView::DEFAULT_NAVBAR:
                return NavbarDefaultView::getHTML();
                break;
            case NavbarView::ADMIN_NAVBAR:
                return NavbarAdminView::getHTML($data);
                break;
            case NavbarView::AUTHOR_NAVBAR:
                return NavbarAuthorView::getHTML($data);
                break;
            case NavbarView::REVIEWER_NAVBAR:
                return NavbarReviewerView::getHTML($data);
                break;
            default:
                return NavbarDefaultView::getHTML();
                break;
        }
    }
}
?>
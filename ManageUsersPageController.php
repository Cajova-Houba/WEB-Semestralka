<?php

require_once ('core/code/manager/UserManager.php');
require_once ('core/code/utils.php');
require_once ('ui/misc/NavbarView.php');
require_once ('core/code/dao/RoleDao.php');
require_once ('ui/ManageUsersPageView.php');

/**
 * Controller for manage users page. Accessible only by admin.
 */
class ManageUsersPageController {

    private $navbar = NavbarView::ADMIN_NAVBAR;
    private $data = [];

    function __construct() {

        $userManager = new UserManager();
        $roleDao = new RoleDao();
        $user = $userManager->authenticate();
        if($user == null || !$user->isAdmin()) {
            redirHome();
        }

        $this->data["firstName"] = $user->getFirstName();
        $this->data["lastName"] = $user->getLastName();
        $this->data["roles"] = $roleDao->getAll();
        $this->data["users"] = $userManager->getAll();
    }

    function getHTML() {
        return ManageUsersPageView::getHTML($this->navbar, $this->data);
    }
}
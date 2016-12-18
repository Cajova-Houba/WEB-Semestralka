<?php

require_once ('core/code/manager/UserManager.php');
require_once ('core/code/utils.php');

/**
 *
 */
class LogoutController {

    function __construct() {
        $userManager = new UserManager();
        $userManager->logout();
    }

    // so that the convetion is same
    function getHTML() {
        redirHome();
    }

}
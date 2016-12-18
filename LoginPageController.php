<?php
require_once ('core/code/utils.php');
require_once('ui/LoginPageView.php');

/**
 * Controller for login page.
 */
class LoginPageController {
    private $errMessage;

    function __construct() {
        if(isset($_GET["err"])) {
            $this->errMessage = Errors::getErrorMessage($_GET["err"]);
        }
    }

    function getHTML() {
        return LoginPageView::getHTML($this->errMessage);
    }
}
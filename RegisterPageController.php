<?php

require_once ('core/code/utils.php');
require_once ('ui/RegisterPageView.php');

/**
 * Controller for register page.
 */
class RegisterPageController {

    private $errorMessage;

    function __construct() {
        if(isset($_GET["err"])) {
            $errCode = escapechars($_GET["err"]);
            $this->errorMessage = Errors::getErrorMessage($errCode);
        } else {
            $this->errorMessage = null;
        }
    }

    function getHTML() {
        return RegisterPageView::getHTML($this->errorMessage);
    }
}
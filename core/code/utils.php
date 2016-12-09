<?php

/*

Some basic utils.

*/
    /*
        ERRORS
        - it doesn't have to be in separate class, but the code will be more readable.
    */
    class Errors {
        const GENERAL_ERROR = -1;

        /* Errors during registration */
        const FIRST_NAME_NOT_OK = 1;
        const LAST_NAME_NOT_OK = 2;
        const USERNAME_NOT_OK = 3;
        const PASSWORD_NOT_OK = 4;
        const USER_ALREADY_EXISTS = 5;
        
        /* Errors during creating a new article */
        const TITLE_NOT_OK = 6;
        const CONTENT_NOT_OK = 7;
    }


    /*
        Username validation (true if ok).
    */
    function checkUsername($username) {
        return !empty($username) && strlen($username) <= 40;
    }

    /*
        Check unecrypted password.
    */
    function checkPassword($password) {
        return !empty($password) && strlen($password) <= 20;
    }
    
    /*
        Check the title of article
    */
    function checkTitle($title) {
        return !empty($title) && strlen($title) <= 40;
    }

    /*
        Check the content of the article.
    */
    function checkContent($content) {
        return !empty($content);
    }

    /*
        Sets the header to home and dies.
    */
    function redirHome() {
        header('Location: http://localhost/kiv-web/');
        die('http://localhost/kiv-web/');
    }

    /*
     * Coverts authors array to string.
     */
    function authorsToString($authorsArray) {
        $authorsStr = "";
        foreach($authorsArray as $author) {
            $authorsStr = $authorsStr.$author->getUsername()."; ";
        }

        // trim the last ';'
        $authorsStr = rtrim($authorsStr, "; ");

        return $authorsStr;
    }
    
    /*
    * Escape strings.
    */
    function escapechars($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

?>
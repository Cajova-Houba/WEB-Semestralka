<?php

/*

Some basic utils.

*/

class Infos {
    const REG_OK = 1;

    static function getInfoMessage($infoCode) {
        switch ($infoCode) {
            case Infos::REG_OK:
                return "Registrace proběhla v pořádku.";
                break;
            default:
                return "";
                break;
        }
    }
}
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
        const FILE_NOT_OK = 10;

        /* Errors during login */
        const BAD_USERNAME_PASSWORD = 8;

        /* Errors during article review */
        const BAD_CRITERIAS = 9;

        /*
        * Returns error message.
        */
        static function getErrorMessage($errCode) {
            switch ($errCode) {
                case Errors::FIRST_NAME_NOT_OK:
                    return "První jméno je chybné.";
                    break;
                case Errors::LAST_NAME_NOT_OK:
                    return "Chybné příjmení.";
                    break;
                case Errors::USERNAME_NOT_OK:
                    return "Chybé username.";
                    break;
                case Errors::PASSWORD_NOT_OK:
                    return "Chybné heslo.";
                    break;
                case Errors::USER_ALREADY_EXISTS:
                    return "Uživatel s tímto username již existuje.";
                    break;

                case Errors::TITLE_NOT_OK:
                    return "Chybný název článku.";
                    break;
                case Errors::CONTENT_NOT_OK:
                    return "Chybný obsah článku.";
                    break;
                case Errors::FILE_NOT_OK:
                    return "Chybný soubor.";
                    break;

                case Errors::BAD_USERNAME_PASSWORD:
                    return "Chybná kombinace jména a hesla.";
                    break;

                case Errors::BAD_CRITERIAS:
                    return "Kritéria hodnocení musí být v rozsahu 0-10";
                    break;

                default:
                    return "Neznámá chyba.";
                    break;

            }
        }
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
     * Returns true if the $crit is number between 0-10
     */
    function criteriaOk($crit) {
        $i = intval($crit);
        return $i >=0 && $i <= 10;
    }

    /*
        Sets the header to home and dies.
    */
    function redirHome() {
        redirTo('');
    }

    function redirTo($page) {
        header('Location: http://localhost/kiv-web/'.escapechars($page));
        die('http://localhost/kiv-web/'.escapeshellarg($page));
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

    function formatDate($dateStr) {
        return date_format(date_create($dateStr), "d.m. Y");
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
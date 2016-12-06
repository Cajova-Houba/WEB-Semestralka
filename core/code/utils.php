<?php

/*

Some basic utils.

*/

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

?>
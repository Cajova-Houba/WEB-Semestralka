<?php
/*

This script will fetch a new user registration.

*/

    require_once('dao/UserDao.php');
    require_once('utils.php');

    $userDao = new UserDao();

    // user fields
    $firstName = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
    $lastName = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $passwordConfirm = isset($_POST["password_check"]) ? $_POST["password_check"] : "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // get all the fields
        $firstName = escapechars($firstName);
        $lastName = escapechars($lastName);
        $username = escapechars($username);
        $password = escapechars($password);
        $passwordConfirm = escapechars($passwordConfirm);
        
        // verify the fields
        $firstNameOk = $lastNameOk = $usernameOk = $passwordOk  = false;
        
        $firstNameOk = !empty($firstName) && strlen($firstName) <= 40;
        $lastNameOk = !empty($lastName) && strlen($lastName) <= 40;
        $usernameOk = !empty($username) && strlen($username) <= 40;
        $passwordOk = !empty($password) && strlen($password) <= 20 && (strcmp($password, $passwordConfirm) == 0);
        
        
        // check for errors
        if(!$firstNameOk) {
            redirToPageWithParams('register', array("err" => Errors::FIRST_NAME_NOT_OK));
        }
        if(!$lastNameOk) {
            redirToPageWithParams('register', array("err" => Errors::LAST_NAME_NOT_OK));
        }
        if(!$usernameOk) {
            redirToPageWithParams('register', array("err" => Errors::USERNAMENAME_NOT_OK));
        }
        if(!$passwordOk) {
            redirToPageWithParams('register', array("err" => Errors::PASSWORD_NOT_OK));
        }
        
        // check for duplicities
        $user = $userDao->getUserByUsername($username);
        if($user != null) {
            redirToPageWithParams('register', array("err" => Errors::USER_ALREADY_EXISTS));
        }
        
        // save the new user
        $user = User::nameUsername($firstName, $lastName, $username);
        $user->setUnecryptedPassword($password);
        var_dump($user);
        $res = $userDao->saveUser($user);
        
        // redirect
        if($res == 1) {
            //success
            redirToPageWithParams('home', array("info" => Infos::REG_OK));
        } else {
            redirToPageWithParams('register', array("err" => Errors::GENERAL_ERROR));
        }
        
    } else {
        // not a post request
        redirHome();
    }
?>
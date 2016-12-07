<?php
/*

This script will fetch a new user registration.

*/

    require_once('dao/user_dao.php');
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
            header('Location: http://localhost/kiv-web/register.php?err='.Errors::FIRST_NAME_NOT_OK);
            die('Chyba při registraci, zkuste to znovu...');
        }
        if(!$lastNameOk) {
            header('Location: http://localhost/kiv-web/register.php?err='.Errors::LAST_NAME_NOT_OK);
            die('Chyba při registraci, zkuste to znovu...');
        }
        if(!$usernameOk) {
            header('Location: http://localhost/kiv-web/register.php?err='.Errors::USERNAMENAME_NOT_OK);
            die('Chyba při registraci, zkuste to znovu...');
        }
        if(!$passwordOk) {
            header('Location: http://localhost/kiv-web/register.php?err='.Errors::PASSWORD_NOT_OK);
            die('Chyba při registraci, zkuste to znovu...');
        }
        
        // check for duplicities
        $user = $userDao->getUserByUsername($username);
        if($user != null) {
            header('Location: http://localhost/kiv-web/register.php?err='.Errors::USER_ALREADY_EXISTS);
            die('Chyba při registraci, zkuste to znovu...');
        }
        
        // save the new user
        $user = new User($firstName, $lastName, $username);
        $user->setUnecryptedPassword($password);
        $res = $userDao->saveUser($user);
        
        // redirect
        if($res == 1) {
            //success
            redirHome();
        } else {
            header('Location: http://localhost/kiv-web/register.php?err='.Errors::GENERAL_ERROR);
            die('Chyba při registraci, zkuste to znovu...');
        }
        
    } else {
        // not a post request
        redirHome();
    }
?>
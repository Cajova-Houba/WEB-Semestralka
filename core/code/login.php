<?php
/*

This script will fetch login to the system.

*/
    require_once('dao/UserDao.php');
    require_once('classes/Login.class.php');
    require_once ('utils.php');

    $userDao = new UserDao();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // get values from request
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // escape chars
        $username = escapechars($username);
        $password = escapechars($password);
        
        // validate
        $usernameOk = !empty($username);
        $passwordOk = !empty($password);
        
        if(!$usernameOk) {
            redirTo('login.php?err='.Errors::BAD_USERNAME_PASSWORD);
        }
        
        if(!$passwordOk) {
            redirTo('login.php?err='.Errors::BAD_USERNAME_PASSWORD);
        }
        
        // authenticate
        $auth = $userDao->authenticate($username, hash('sha256', $password, false));
        if(!$auth) {
            redirTo('login.php?err='.Errors::BAD_USERNAME_PASSWORD);
        } else {
            // login
            $login = new Login();
            $login->login($username);
            redirHome();
        }
        
    } else {
        redirHome();
    }
?>
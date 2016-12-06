<?php
/*

This script will fetch login to the system.

*/
    require_once('user_dao.php');
    require_once('classes/Login.class.php');

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
            header('Location: http://localhost/kiv-web/login.php?err=1');
            die("Chyba při přihlášení...");
        }
        
        if(!$passwordOk) {
            header('Location: http://localhost/kiv-web/login.php?err=2');
            die("Chyba při přihlášení...");
        }
        
        // authenticate
        $auth = authenticate($username, hash('sha256', $password, false));
        if(!$auth) {
            header('Location: http://localhost/kiv-web/login.php?err=3');
            die("Nope...");
        } else {
            // login
            $login = new Login();
            $login->login($username);
            header('Location: http://localhost/kiv-web/');
            die('Login successful');
        }
        
    } else {
        header('Location: http://localhost/kiv-web/');
        die("http://localhost/kiv-web/");
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
<?php
    require_once('user_dao.php');

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
            header('Location: http://localhost/kiv-web/register.php?err=1');
            die('Chyba při registraci, zkuste to znovu...');
        }
        if(!$lastNameOk) {
            header('Location: http://localhost/kiv-web/register.php?err=2');
            die('Chyba při registraci, zkuste to znovu...');
        }
        if(!$usernameOk) {
            header('Location: http://localhost/kiv-web/register.php?err=3');
            die('Chyba při registraci, zkuste to znovu...');
        }
        if(!$passwordOk) {
            header('Location: http://localhost/kiv-web/register.php?err=4');
            die('Chyba při registraci, zkuste to znovu...');
        }
        
        // check for duplicities
        $user = getUserByUsername($username);
        if($user != null) {
            header('Location: http://localhost/kiv-web/');
            die('Chyba při registraci, zkuste to znovu...');
        }
        
        // save the new user
        $user = new User($firstName, $lastName, $username);
        $user->setUnecryptedPassword($password);
        $res = saveUser($user);
        
        // redirect
        if($res == 1) {
            echo("Success.");
        } else {
            echo("User not saved.");
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
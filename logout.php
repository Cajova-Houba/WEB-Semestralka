<?php
/*

This script will log the user out (if anyone is logged in) and then redirects back home.

*/
require_once('core/code/classes/Login.class.php');

$login = new Login();

if($login->isUserLogged()) {
    $login->logout();
}

header('Location: http://localhost/kiv-web/');
die('http://localhost/kiv-web/');

?>
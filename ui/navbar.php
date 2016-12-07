<?php
/*

    A script which will display a correct navbar.
    It is expected, that $login (Login.class.php) and $user (User.class.php) variables are set.

*/
if (!isset($login)) {
    include(__DIR__.'/navbar/navbar-default.php');
} else if (isset($login) && !isset($user)) {
    include(__DIR__.'/navbar/navbar-default.php');
} else {
    
    if(!$login->isUserLogged()) {
        include(__DIR__.'/navbar/navbar-default.php');
    } else {
        if($user->isAdmin()){
            include(__DIR__.'/navbar/navbar-admin.php');
        } else if($user->isReviewer()) {
            include(__DIR__.'/navbar/navbar-reviewer.php');
        } else if($user->isAuthor()) {
            include(__DIR__.'/navbar/navbar-author.php');
        } else {
            include(__DIR__.'/navbar/navbar-default.php');
        }
    }
    
}
?>
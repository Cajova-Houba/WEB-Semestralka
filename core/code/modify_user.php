<?php
/**
 * This script will fetch enabling/disabling, deleting and changing the role of the user.
 */
require_once ('utils.php');
require_once ('dao/user_dao.php');
require_once ('classes/Login.class.php');
require_once ('dao/role_dao.php');

$userDao = new UserDao();
$roleDao = new RoleDao();
$login = new Login();

if($_SERVER["REQUEST_METHOD"] == "POST") {

    //user has to be logged in and ahs to be admin
    if(!$login->isUserLogged()) {
        redirHome();
    }

    $user = $userDao->getUserByUsername($login->getUsername());
    if(!$user->isAdmin()) {
        redirHome();
    }

    // check the id and the action
    $userId = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
    $action = isset($_POST["action"]) ? $_POST["action"] : null;
    var_dump($userId);
    var_dump($action);

    if($userId == null || $action == null || !$userDao->exists($userId)) {
        //err
        redirHome();
    }

    if ($action === 'update') {
        //update role
        $roleId = isset($_POST["role_id"]) ? $_POST["role_id"] : null;
        if($roleId != null && $roleDao->exists($roleId)) {
            $res = $userDao->updateRole($userId, $roleId);
            if( $res !== 1) {
                //error
                echo "error while modifying";
            }
        }
    } else if ($action === 'enable') {
        //enable account
        $res = $userDao->enableUser($userId);
        if($res !== 1) {
            echo "Error while enabling.";
        }
    } else if ($action === 'disable') {
        //disable account
        //cannot disable myself...
        if($userId !== $user->getId()) {
            $res = $userDao->disableUser($userId);
            if($res !== 1) {
                echo "Error while disabling.";
            }
        }
    } else if ($action === 'delete') {
        //delete account
        //cannot disable myself...
        if($userId !== $user->getId()) {
            $res = $userDao->remove($userId);
            if($res !== 1) {
                echo "Error while deleting.";
            }
        }
    }

    // redir back to manage users page
    redirTo('users.php');
}

redirHome();

?>
<?php
/*
This file contains methods for database related stuff.
*/
require_once('base_dao.php');
if(!defined('__CORE_ROOT__')) {
    //get one dir up - use it when require_once classes
    define('__CORE_ROOT__', dirname(dirname(__FILE__))); 
}
require_once(__CORE_ROOT__.'/classes/User.class.php');

class UserDao extends BaseDao {
    
    function __construct() {
        parent::__construct(User::TABLE_NAME);
    }
    
    /*
        Returns the user with this id or null if not found.
    */
    function getUser($id) {
        $row = $this->get($id);
        if($row == null) {
            return null;
        }

        $user = new User();
        $user->fill($row);

        return $user;
    }

    /*
     Saves the new user.

     And returns the number of saved rows (=1).
    */
    function saveUser($user) {
        $db = getConnection();

        $query = "INSERT INTO ".User::TABLE_NAME."(username, password,first_name,last_name,role_id) VALUES(:username,:password,:first_name,:last_name,:role_id)";

        $stmt = $db->prepare($query);
        $stmt->execute(array(':username'=>$user->getUsername(),
                             ':password'=>$user->getPassword(),
                             ':first_name'=>$user->getFirstName(),
                             ':last_name'=>$user->getLastName(),
                             ':role_id'=>$user->getRoleId()));
        $rows = $stmt->rowCount();

        // close the sonnection
        $db = null;

        return $rows;
    }

    /*
     Returns the user by username or null if none is found.
    */
    function getUserByUsername($username) {
        $db = getConnection();

        $query = "SELECT * FROM ".User::TABLE_NAME." WHERE username=:username";

        $stmt = $db->prepare($query);
        $stmt->execute(array(':username'=>$username));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = null;
        $db = null;

        foreach($rows as $row) {
            $res = new User();
            $res->fill($row);
        }

        return $res;
    }

    /*
        Tries to authenticate the user and returns true if the authentication is successfull.
    */
    function authenticate($username, $encryptedPassword) {
        $db = getConnection();

        $query = "SELECT * FROM ".User::TABLE_NAME." WHERE username=:username AND password=:password";
        $stmt = $db->prepare($query);
        $stmt->execute(array(':username'=>$username, ':password'=>$encryptedPassword));
        $res = $stmt->rowCount() == 1;

        $db = null;

        return $res;
    }
}

?>
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

    function getAll()
    {
        $rows = parent::getAll();
        $users = [];

        foreach ($rows as $row) {
            $u = new User();
            $u->fill($row);
            $users[] = $u;
        }

        return $users;
    }

    /*
        Returns the user with this id or null if not found.
    */
    function get($id) {
        $row = parent::get($id);
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

    /*
     * Returns all registered reviewers.
     */
    function getReviewers() {
        $query = "SELECT * FROM ".User::TABLE_NAME." WHERE role_id=:revRole";
        $reviewers = [];

        $db = getConnection();

        $stmt = $db->prepare($query);
        $stmt->execute(array(":revRole" => User::REVIEWER_ROLE_ID));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $r = new User();
            $r->fill($row);
            $reviewers[] = $r;
        }

        $db = null;

        return $reviewers;
    }

    /*
     *  Returns true if the user with $revId exists and is reviewer.
     */
    function reviewerExists($revId) {
        $query = "SELECT id FROM ".User::TABLE_NAME." WHERE id=:id AND role_id=:revRole";

        $db = getConnection();

        $stmt = $db->prepare($query);
        $stmt->execute(array(":id" => $revId,
                             ":revRole" => User::REVIEWER_ROLE_ID));
        $rowCount = $stmt->rowCount();

        $db = null;

        return $rowCount == 1;
    }


    /*
     * Marks the user's account as enabled.
     * Returns 1 if ok, 0 if an error occurs.
     */
    function enableUser($userId) {
        $query = "UPDATE ".User::TABLE_NAME." SET enabled=true WHERE id=:userId";

        $db = getConnection();
        $rowCount =  $this->executeModifyStatement($db, $query, array(":userId" => $userId));
        $db = null;

        return $rowCount;
    }

    /*
     * Marks the user's account as disabled.
     * Returns 1 if ok, 0 if an error occurs.
     */
    function disableUser($userId) {
        $query = "UPDATE ".User::TABLE_NAME." SET enabled=false WHERE id=:userId";

        $db = getConnection();
        $rowCount =  $this->executeModifyStatement($db, $query, array(":userId" => $userId));
        $db = null;

        return $rowCount;
    }

    /*
     * Updates the users role and returns 1 if the update was successful.
     */
    function updateRole($userId, $roleId) {
        $query = "UPDATE ".User::TABLE_NAME." SET role_id=:roleId WHERE id=:userId";

        $db = getConnection();
        $rowCount = $this->executeModifyStatement($db, $query, array("roleId" => $roleId, "userId" => $userId));
        $db = null;

        return $rowCount;
    }
}

?>
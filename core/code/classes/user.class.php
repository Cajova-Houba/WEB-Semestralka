<?php
require_once('BaseObject.class.php');

/*
 Object representing a user.
*/

class User extends BaseObject {
        const TABLE_NAME = 'user';
        const AUTHOR_ROLE_ID = 1;
        const ADMIN_ROLE_ID = 2;
        const REVIEWER_ROLE_ID = 3;
        private $firstName = '';
        private $lastName = '';
        private $username = '';
        private $password = '';
        private $roleId;
        private $enabled = true;
        
        function __construct() {
            $this->roleId = User::AUTHOR_ROLE_ID;
        }
    
        /*
            Creates a new object with first name, last name and
            username with AUTHOR role.
        */
        static function nameUsername($firstName, $lastName, $username) {
            $user = new self();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setUsername($username);
            
            return $user;
        }

    static function nameUsernamePassword($firstName, $lastName, $username, $encPassword) {
            $user = User::nameUsername($firstName, $lastName, $username);
            $user->setPassword($encPassword);

            return $user;
        }

    static function allParams($id, $firstName, $lastName, $username, $encPassword, $roleId) {
            $user = User::nameUsernamePassword($firstName, $lastName, $username, $encPassword);
            $user->setId($id);
            $user->setRoleId($roleId);

            return $user;
        }

    function fill($row) {
            $this->setId($row["id"]);
            $this->firstName = $row["first_name"];
            $this->lastName = $row["last_name"];
            $this->username = $row["username"];
            $this->password = $row["password"];
            $this->roleId = intval($row["role_id"]);
            $this->enabled = $row["enabled"];
        }

    function getTableName() {
            return User::TABLE_NAME;
        }

    function getFirstName() {
            return $this->firstName;
        }

    function getLastName() {
            return $this->lastName;
        }

    function getUsername() {
            return $this->username;
        }

    function getPassword() {
            return $this->password;
        }

    function getRoleId() {
            return $this->roleId;
        }

    function isEnabled() {
            return $this->enabled;
    }

    function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setRoleId($roleId) {
        $this->roleId = $roleId;
    }

    function setEnabled($enabled) {
            $this->enabled = $enabled;
    }

    function isAdmin() {
        return $this->isEnabled() && $this->getRoleId() === User::ADMIN_ROLE_ID;
    }

    function isReviewer() {
        return $this->isEnabled() && $this->getRoleId() === User::REVIEWER_ROLE_ID;
    }

    function isAuthor() {
        return $this->isEnabled() && $this->getRoleId() === User::AUTHOR_ROLE_ID;
    }

    /**
    Takes the unecrypted password and uses the sha256 to encrypt it.
    */
    function setUnecryptedPassword($password) {
        $this->password = hash("sha256", $password, false);
    }
}
?>
<?php
require_once('BaseObject.class.php');

/*
 Object representing a user.
*/

class User extends BaseObject {
        const TABLE_NAME = 'user';
        const USER_ROLE_ID = 1;
        const ADMIN_ROLE_ID = 2;
        const REVIEWER_ROLE_ID = 3;
        private $firstName = '';
        private $lastName = '';
        private $username = '';
        private $password = '';
        private $roleId = '';
        
        function __construct($firstName, $lastName, $username) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->username = $username;
            $this->roleId = User::USER_ROLE_ID;
        }
        
        function __construct2($firstName, $lastName, $username, $encryptedPassword) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->username = $username;
            $this->password = $encryptedPassword;
            $this->roleId = User::USER_ROLE_ID;
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
    
        function isAdmin() {
            return $this->getRoleId() == User::ADMIN_ROLE_ID;
        }
    
        function isReviewer() {
            return $this->getRoleId() == User::REVIEWER_ROLE_ID;
        }
    
        /**
        Takes the unecrypted password and uses the sha256 to encrypt it.
        */
        function setUnecryptedPassword($password) {
            $this->password = hash("sha256", $password, false);
        }
}
?>
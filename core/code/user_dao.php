<?php
    /*
    This file contains methods for database related stuff.
    */
    require_once('db_connector.php');
    require_once('classes/user.class.php');

    /*
     Saves the new user.
     
     And returns the number of saved rows (=1).
    */
    function saveUser($user) {
        $db = getConnection();
        
        $query = "INSERT INTO user(username, password,first_name,last_name,role_id) VALUES(:username,:password,:first_name,:last_name,:role_id)";
        
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
        
        $query = "SELECT * FROM user WHERE username=:username";
        
        $stmt = $db->prepare($query);
        $stmt->execute(array(':username'=>$username));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $res = null;
        $db = null;
        
        foreach($rows as $row) {
            $res = new User($row["first_name"], $row["last_name"], $row["username"], $row["password"]);
        }
        
        return $res;
    }

?>
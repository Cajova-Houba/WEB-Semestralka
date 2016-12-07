<?php
/*

    A basic data access object.

*/
require_once('db_connector.php');

class BaseDao {
    
    private $tableName;
    
    function __construct($tableName) {
        $this->tableName = $tableName;
    }
    
    /*
        Returns a row with values from database or null if the record is not found in database.
    */
    function get($id) {
        $query = "SELECT * FROM `".$this->tableName."` WHERE id=:id";
        
        $db = getConnection();
        
        $stmt = $db->prepare($query);
        $stmt->execute(array(":id" => $id));
        $obj = null;
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $obj = $row;
        }
        
        $db = null;
        
        return $row;
    }
}

?>
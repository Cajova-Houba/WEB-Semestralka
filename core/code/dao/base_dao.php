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
     * Returns all objects in database.
     */
    function getAll() {
        $query = "SELECT * FROM ".$this->tableName;
        $db = getConnection();

        $rows = $this->executeSelectStatement($db, $query, array());
        $db = null;

        return $rows;
    }

    /*
        Returns a row with values from database or null if the record is not found in database.
    */
    function get($id) {
        $query = "SELECT * FROM `".$this->tableName."` WHERE id=:id";
        
        $db = getConnection();
        $obj = null;

        $rows = $this->executeSelectStatement($db, $query, array(":id" => $id));
        foreach($rows as $row) {
            $obj = $row;
        }
        
        $db = null;
        
        return $obj;
    }

    /*
     * Returns true if the object with id exist.
     */
    function exists($id) {
        $obj = $this->get($id);

        return $obj != null;
    }

    /*
     * Executes the SELECT * statement and returns fetchAll();
     */
    function executeSelectStatement($db, $query, $parameters) {
        $stmt = $db->prepare($query);
        $stmt->execute($parameters);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*
     * Executes the INSERT/UPDATE/DELETE statement and returns the number of
     * affected rows.
     */
    function executeModifyStatement($db, $query, $parameters) {
        $stmt = $db->prepare($query);
        $stmt->execute($parameters);
        return $stmt->rowCount();
    }
}

?>
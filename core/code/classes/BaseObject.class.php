<?php
    
abstract class BaseObject {
    private $id = -1;
    
    /*
        Fills fileds with data from row.
    */
    abstract function fill($dtbRow);
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
}
    
?>
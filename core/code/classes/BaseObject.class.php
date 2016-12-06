<?php
    
abstract class BaseObject {
    private $id = -1;
    
    abstract function getTableName();
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
}
    
?>
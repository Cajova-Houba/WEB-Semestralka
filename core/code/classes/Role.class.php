<?php
/*

Role objectt

*/

require_once('BaseObject.class.php');

class Role extends BaseObject {
    
    const TABLE_NAME = 'role';
    private $name = '';
    
    function getTitleName() {
        return Role::TABLE_NAME;
    }
    
    function getName() {
        return $this->name;
    }
    
    function setName($name) {
        $this->name = $name;
    }
    
}

?>

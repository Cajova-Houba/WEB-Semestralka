<?php
/*
 * DAO for roles.
 */
require_once('BaseDao.php');
if(!defined('__CORE_ROOT__')) {
    //get one dir up - use it when require_once classes
    define('__CORE_ROOT__', dirname(dirname(__FILE__)));
}
require_once(__CORE_ROOT__.'/classes/Role.class.php');

class RoleDao extends BaseDao {

    function __construct() {
        parent::__construct(Role::TABLE_NAME);
    }

    function getAll() {
        $rows = parent::getAll();
        $roles = [];

        foreach ($rows as $row) {
            $r = new Role();
            $r->fill($row);
            $roles[] = $r;
        }

        return $roles;
    }
}
?>
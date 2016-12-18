<?php

/**
 * Base class for managers.
 */
class BaseManager
{
    private $dao;

    function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * Returns the object with this id or null.
     *
     * @param $id
     */
    function get($id) {
        return $this->dao->get($id);
    }

    /**
     * Returns all rows from database.
     */
    function getAll() {
        return $this->dao->getAll();
    }

    /**
     * Returns true id the object with this id exists.
     *
     * @param $id
     */
    function exists($id) {
        return $this->dao->exists($id);
    }

    /**
     * Removes the object with this id from database.
     *
     * @param $id
     */
    function remove($id) {
        return $this->dao->remove($id);
    }

}
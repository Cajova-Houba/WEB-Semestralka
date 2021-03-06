<?php

/**
 * Manager for users and authentication.
 */
require_once(dirname(dirname(__FILE__)) . '/dao/UserDao.php');
require_once (dirname(dirname(__FILE__)).'/classes/Login.class.php');
require_once('BaseManager.php');


class UserManager extends BaseManager {

    private $userDao;

    function __construct()
    {
        $this->userDao = new UserDao();
        parent::__construct($this->userDao);
    }

    /**
     * Tries to authenticate the logged user and returns either null or logged user object.
     */
    function authenticate() {
        $login = new Login();
        if(!$login->isUserLogged()) {
            return null;
        }

        return $this->getByUsername($login->getUsername());
    }

    function getByUsername($username) {
        return $this->userDao->getUserByUsername($username);
    }

    /**
     * Logs the current user out.
     */
    function logout() {
        $login = new Login();
        if($login->isUserLogged()) {
            $login->logout();
        }
    }

}
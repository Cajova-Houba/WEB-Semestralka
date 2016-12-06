<?php 

require_once('Session.class.php');

/**
 *  The login object.
 */
class Login{
    
    private $ses; 
    private $dUser = "user"; // username attribute in session
    
    
    public function __construct(){
        $this->ses = new MySession;
    }
    
    /**
     Checks if the user is logged in.
     */
    public function isUserLoged(){
        return $this->ses->isSessionSet($this->dUser);
    }
    
    /**
     Sets the username in session.
     */
    public function login($userName){
        $this->ses->addSession($this->dUser,$userName); // jmeno
    }
    
    /**
     Unsets the username in session.
     */
    public function logout(){
        $this->ses->removeSession($this->dUser);
    }
    
    /*
    Returns the username or empty string if no user is logged in.
    */
    public function getUsername() {
        if(!$this->isUserLoged()) {
            return '';
        }
        return $this->ses->readSession($this->dUser);
    }
    
}

?>
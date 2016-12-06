<?php

/**
 *  Objekt pro praci se session.
 *  @author Michal Nykl
 */
class MySession{
    
    /**
     *  Pri vytvoreni objektu zahaji session.
     */
    public function __construct(){
        session_start(); // zahajim
    }
    
    /**
     *  Funkce pro ulozeni hodnoty do session.
     *  @param string $name Jmeno promenne.
     *  @param object $value Hodnota
     */
    public function addSession($name, $value){
        $_SESSION[$name] = $value;
    }
    
    /**
     *  Vrati hodnotu dane session nebo null, pokud session neni nastavena.
     *  @param string $name Jmeno promenne.
     *  @return object
     */
    public function readSession($name){
        if($this->isSessionSet($name)){ // ano
            return $_SESSION[$name];
        } else { // ne
            return null;
        }
    }
    
    /**
     *  Je session nastavena?
     *  @return boolean
     */
    public function isSessionSet($name){
        return isset($_SESSION[$name]);
    }
    
    
    /**
     *  Odstrani danou session.
     *  @param string $name Jmeno promenne.
     */
    public function removeSession($name){
        unset($_SESSION[$name]);
    }
    
}


?>
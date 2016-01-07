<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of safeSession
 *
 * @author Roman
 */
class safeSession {
    
    public function __constructor($asUser = false){
        if(session_status() != PHP_SESSION_ACTIVE ){
            session_start();
        }
    }
    
    public function destroy(){
        session_destroy();
    }
}

?>

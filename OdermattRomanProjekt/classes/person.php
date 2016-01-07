<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of person
 *
 * @author Roman
 */
class person {
	/**
	 * starts a Session
	 */
	public static function startSession(){
		if(session_status() != PHP_SESSION_ACTIVE ){
			$session_name = 'MKN151_Game_Session';   // Set a custom session name
			$secure = false; // TODO: Change this to true
			// This stops JavaScript being able to access the session id.
			$httponly = true;
			// Forces sessions to only use cookies.
			if (ini_set('session.use_only_cookies', 1) === FALSE) {
				header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
				exit();
			}
			// Gets current cookies params.
			$cookieParams = session_get_cookie_params();
			session_set_cookie_params($cookieParams["lifetime"],
					$cookieParams["path"],
					$cookieParams["domain"],
					$secure,
					$httponly);
			// Sets the session name to the one set above.
				
			session_name($session_name);
			session_start();            // Start the PHP session
			session_regenerate_id();
		}
	}

	/**
	 * Check de Login Data and saves them into the session
	 * @param String $username
	 * @param String $password
	 * @return boolean (=>false if Login failed, true if success)
	 */
	public static function logIn($username,$password){
		$success=false;
		if($password === config::GuestPW){
			$_SESSION['username']=$username;
			$_SESSION['permission']='gameuser';
			$success = true;
		}elseif($username === 'Admin' && $password === config::AdminPW){
			$_SESSION['username']='admin';
			$_SESSION['permission']='admin';
			$success = true;
		}
		return $success;
	}
	 
	/**
	 * Get User Permission
	 * @return boolean nullable (admin | gameuser | null if not logged in)
	 */
	public static function getPermissionName(){
		$permission = '';
		if(isset($_SESSION['permission'])){
			$permission = $_SESSION['permission'];
		}
		return $permission;
	}

}

?>

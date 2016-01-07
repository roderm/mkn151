<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of Login
 *
 * @author Roman
 */
class Login {

	/**
	 * Returns Login-Page as HTML-View
	 * @param unknown_type $succesLocation
	 */
	public function getMain($succesLocation = ''){
		require 'classes/view.php';
		$view = new view();
		$view->setTemplate('login');
		$model = new stdClass();
		$model->submitUrl = 'Admin';
		return $view->loadTemplate($model);
	}

	/**
	 * Checks user-Data for Login and Logg In
	 */
	public function loggin(){
		include_once 'classes/person.php';
		$success = false;
		$uri = '';
		if (isset($_POST['username']) && isset($_POST['passwd'])){
			$username = (string)htmlspecialchars($_POST['username']);
			$password = (string)htmlspecialchars($_POST['passwd']);
			if(person::logIn($username, $password)){
				$success = true;
				switch(person::getPermissionName()){
					case 'admin':
						$uri = Uri::getAction('Admin', 'getMain');
						break;
					case 'gameuser':
						$uri = Uri::getAction('Gamer', 'getMain');
						break;
				}
			}
		}

		return json_encode(array("success"=>$success, "uri"=>$uri));
	}

	/**
	 * Logout User and Send Url for redirect
	 */
	public function logout(){
		if(isset($_SESSION['gameID']) && $_SESSION['gameID'] != 0){
			include_once 'controller/Gamer.php';
			$gamer = new Gamer();
			$gamer->EndGame();
		}
		session_destroy();
		return json_encode(array("uri"=>Uri::getAction('Login', 'getMain')));
	}
}
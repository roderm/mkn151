<?php

/*
 * To change this template, choose Tools | Templates
* and open the template in the editor.
*/

/**
 * Description of Admin
 *
 * @author Roman
 */
class Admin {
	
	/**
	 * Returns Main-Page from the Admin-Section as HTML-View
	 * @return string
	 */
	public function getMain(){
		include_once 'classes/person.php';
		if(person::getPermissionName()!='admin'){
			$login = Uri::getAction('Login');
			header("Location: $login");
		}else{
			require 'classes/view.php';
			require './models/admin.php';
			$view = new view();
			return $view->loadTemplate(new admin_model());
		}
	}
	
	/**
	 * Return Tools-Page as HTML-View (only Logout)
	 */
	public function getTools(){
		require 'classes/view.php';
		$view = new view();
		$view->setTemplate('admin/tools');
		return $view->loadTemplate(new stdClass());
	}
}

?>

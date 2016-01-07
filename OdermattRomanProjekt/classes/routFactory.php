<?php 
/**
 * Description of SQL
 *
 * @author Roman
 */
class routFactory {
	
	public function __autoloud($controller){
		include('controller/'.$controller.'.php');
	}
	
	public function __construct($controller = 'main', $method = 'getMain'){
		$this->__autoloud($controller);
		$controller = new $controller;
		echo $controller->$method();
	}
}
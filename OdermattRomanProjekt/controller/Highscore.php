<?php

class Highscore{

	/**
	 * Return Highscore-Page as HTML-View
	 */
	public function getMain(){
	  require_once('classes/person.php');
		require_once('classes/view.php');
		person::startSession();
		$view = new view();
		return $view->loadTemplate($this->getModel());		
	}
	
	/**
	 * Delete Highscore Row in DB
	 * @return boolean
	 */
	public function deleteCol(){
		$success = false;
		require_once('classes/SQL.php');
	  $sql = new SQL();
	  $sql->connect();
		$gameID = mysql_real_escape_string($_POST['id']);
		if(person::getPermissionName()=='admin' && is_numeric($gameID)){
			$sql->doThat("DELETE FROM quizGames WHERE id=".$gameID);
		}
		return $success;
	}
	
	/**
	 * Return Data-Model for the View
	 */
	private function getModel(){
		require_once('classes/SQL.php');
		$model = new stdClass();
		$model->template = 'admin/HighscoreTable';
		$model->isAdmin=(person::getPermissionName()=='admin'?'true':'false');
		$model->gameID = $this->getGameId();
		$sql = new SQL();
		$sql->connect();
		$model->data = $sql->get("SELECT * FROM ViewHighscore ORDER BY GamerMainScore DESC");
		return $model;
	}
	
	/**
	 * Return Game-Id for The User for Mark
	 */
	private function getGameId(){
		if(isset($_SESSION['gameID'])){
			return $_SESSION['gameID'];
		}else{
			return 0;
		}
	}
}
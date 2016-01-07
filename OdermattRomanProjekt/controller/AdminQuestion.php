<?php
class AdminQuestion{

	/**
	 * Return Question-Page as HTML-View
	 */
	public function getMain(){
		require 'classes/SQL.php';
		require 'classes/view.php';
			
		$model = new stdClass();
		$model->template = 'admin/QuestionMain';
		$sql = new SQL();
		$sql->connect();
		$model->data = $sql->get("SELECT * FROM quizCategories");
		$view = new view();
		return $view->loadTemplate($model);
	}

	/**
	 * Return QuestionTable-Page as HTML-View
	 * parameters = POST: CategorieID
	 */
	public function getQuestionTable(){
		require 'classes/SQL.php';
		require 'classes/view.php';
		$categorie = htmlentities($_POST['katID']);
		$model = new stdClass();
		$model->template = 'admin/QuestionTable';
		$sql = new SQL();
		$sql->connect();
		$model->data = $sql->get("SELECT * FROM ViewQuestionQuote WHERE categorie='".$categorie."' ORDER BY question");
		$view = new view();
		return $view->loadTemplate($model);
	}

	/**
	 * Add or Edit Question in DB
	 * parameters = POST: QuestionID and Question-Properties
	 */
	public function saveQuestion(){
		require 'classes/SQL.php';
		$success = false;
		$sql = new SQL();
		$sql->connect();
		$kat = mysql_real_escape_string($_POST['katID']);
		$id = mysql_real_escape_string($_POST['questionID']);
		$question = mysql_real_escape_string(htmlentities($_POST['question']));
		$answer0 = mysql_real_escape_string(htmlentities($_POST['answer0']));
		$answer1 = mysql_real_escape_string(htmlentities($_POST['answer1']));
		$answer2 = mysql_real_escape_string(htmlentities($_POST['answer2']));
		$answer3 = mysql_real_escape_string(htmlentities($_POST['answer3']));
		if($id==0){
			$query = "INSERT INTO quizQuestions(categorie, question, answer0, answer1, answer2, answer3)";
			$query .=  "VALUES (".$kat.",'".$question."','".$answer0."','".$answer1."','".$answer2."','".$answer3."')";
			$id = $sql->doThat($query);
			$success = true;
		}elseif(is_numeric($id)){
			$query = "UPDATE quizQuestions SET categorie = ".$kat.",question='".$question."',answer0='".$answer0."',answer1='".$answer1."',answer2='".$answer2."',answer3='".$answer3."' ";
			$query .= "WHERE id=".$id;
			$sql->doThat($query);
			$success = true;
		}
		return json_encode(array("id"=>$id, "success"=>$success));
	}

	/**
	 * Remove Question in DB
	 * parameters = POST: QuestionID
	 */
	public function deleteQuestion(){
		require 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();
		$id = mysql_real_escape_string($_POST['id']);
		if(is_numeric($gameID)){
			$sql->doThat('DELETE FROM quizQuestions WHERE id='.mysql_real_escape_string($id));
		}
	}
}

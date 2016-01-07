<?php

class Gamer {

	/**
	 * Return Gamer-Main-Page as HTML-View
	 */
	public function getMain(){
		if(person::getPermissionName()!='admin' && person::getPermissionName()!='gameuser'){
			$login = Uri::getAction('Login');
			header("Location: $login");
		}else{
			require('classes/view.php');
			$model = new stdClass();
			$model->template = 'game/main';
			$model->hasFrage=(isset($_SESSION['tmpFrage']['id']));
			$model->user = $_SESSION['username'];
			$model->points=0;
			$model->message = $model->hasFrage?'Welcome back':'Welcome';
			$view = new view();
			return $view->loadTemplate($model);
		}
	}

	/**
	 * Returns Question from SESSION
	 * @return string
	 */
	public function backToGame(){
		$arr= array();
		array_push($arr, $_SESSION['tmpFrage']['answers'][0]);
		array_push($arr, $_SESSION['tmpFrage']['answers'][1]);
		array_push($arr, $_SESSION['tmpFrage']['answers'][2]);
		array_push($arr, $_SESSION['tmpFrage']['answers'][3]);
		shuffle($arr);
		return json_encode(array(
				"question"=>$_SESSION['tmpFrage']['question']." (".$_SESSION['tmpFrage']['prozStat']."% richtig Beantwortet)",
				"answer"=>$arr,
				"jokers"=>$_SESSION['jokers']
		));
	}

	/**
	 * Start new Game:
	 * - Set Game-Row in DB
	 * - Set Game-Defaults
	 * @return string
	 */
	public function newGame(){
		$arr;
		if(!isset($_SESSION['username']))
		{
			$arr = array("success"=> false);
		} else {
			require_once 'classes/SQL.php';
			$sql = new SQL();
			$sql->connect();
			$query = "INSERT INTO quizGames(guest, start) VALUES ('".$_SESSION['username']."', now())";
			$_SESSION['gameID'] = $sql->doThat($query);
			$_SESSION['jokers']=config::AnzJOKERS;
			$arr = array("success"=>true,
					"Kategorien"=>$this->getKategorien(false));
		}
		return json_encode($arr);
	}

	/**
	 * Get a 4 Randow Categories
	 * @param string $toJson
	 * @return string|multitype:
	 */
	public function getKategorien($toJson = true){
		require_once 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();

		$query = "SELECT quizCategories.* FROM quizQuestions ";
		$query .= "INNER JOIN quizCategories ON quizCategories.id = quizQuestions.categorie ";
		$query .= "LEFT JOIN quizTurns ON quizTurns.question = quizQuestions.id AND quizTurns.game = ".$_SESSION['gameID']." ";
		$query .= "WHERE quizTurns.id IS NULL ";
		$query .= "GROUP BY quizCategories.bezeichnung ";
		$query .= "ORDER BY RAND() LIMIT 4 ";
		$result = $sql->get($query);

		$arr = array();
		foreach($result as $key => $value){
			array_push($arr,
					array(
							"id"=>$value->id,
							"val"=>$value->bezeichnung,
							"descr"=>$value->beschreibung
					));
		}
		if($toJson){
			return json_encode($arr);
		}else{
			return $arr;
		}
	}

	/**
	 * Returns a Random Question
	 * @param = POST: CategorieID
	 * @return string|boolean
	 */
	public function getQuestion()
	{
		if(isset($_POST['katID'])){
			require_once 'classes/SQL.php';
			$sql = new SQL();
			$sql->connect();

			$query = "SELECT quizQuestions.*, round((100 / COUNT(stat.id))*SUM(CASE WHEN stat.selectedAnswer = 0 THEN 1 ELSE 0 END),0) as StatQ FROM quizQuestions ";
			$query .= "LEFT JOIN quizTurns ON quizTurns.question = quizQuestions.id AND quizTurns.game = ".$_SESSION['gameID']." ";
			$query .= "LEFT JOIN quizTurns as stat ON stat.question = quizQuestions.id ";
			$query .= "WHERE quizTurns.id IS NULL AND quizQuestions.categorie = ".$_POST['katID']." ";
			$query .= "ORDER BY RAND() LIMIT 1";
			$question = $sql->get($query);
			$_SESSION['tmpFrage'] = array("id"=>$question[0]->id,
					"question"=>$question[0]->question,
					"prozStat"=> $question[0]->StatQ,
					"joker"=>false,
					"answers"=>array(
							"0"=>array("id"=>rand(1000, 9999), "ans"=>$question[0]->answer0),
							"1"=>array("id"=>rand(1000, 9999), "ans"=>$question[0]->answer1),
							"2"=>array("id"=>rand(1000, 9999), "ans"=>$question[0]->answer2),
							"3"=>array("id"=>rand(1000, 9999), "ans"=>$question[0]->answer3)
					)
			);

			$arr= array();
			array_push($arr, $_SESSION['tmpFrage']['answers'][0]);
			array_push($arr, $_SESSION['tmpFrage']['answers'][1]);
			array_push($arr, $_SESSION['tmpFrage']['answers'][2]);
			array_push($arr, $_SESSION['tmpFrage']['answers'][3]);
			shuffle($arr);
			return json_encode(array(
					"question"=>$question[0]->question." (".$question[0]->StatQ."% richtig Beantwortet)",
					"answer"=>$arr,
					"jokers"=>$_SESSION['jokers']
			));
		}else{
			return false;
		}

	}

	/**
	 * Use Joker if it is available
	 * @return json with two ids with wrong answers
	 */
	public function useJoker(){
		$_SESSION['tmpFrage']['joker']=true;
		if($_SESSION['jokers']>0){
			$_SESSION['jokers'] -= 1;
			return json_encode(array("n0"=>$_SESSION['tmpFrage']['answers']['1']['id'], "n1"=>$_SESSION['tmpFrage']['answers']['2']['id'],"joker"=>$_SESSION['jokers']));
		}else{
			return json_encode(array("joker"=>0));
		}
	}

	/**
	 * Check if Selected Answer is Right else Make GameOver
	 * @return json
	 */
	public function checkAnswer(){
		$answerID = $_POST['answer'];
		$answer = -1;
		$complete = false;
		switch($answerID){
			case $_SESSION['tmpFrage']['answers']['0']['id']:
				$complete = true;
				$answer = 0;
				break;
			case $_SESSION['tmpFrage']['answers']['1']['id']:
				$answer = 1;
				break;
			case $_SESSION['tmpFrage']['answers']['2']['id']:
				$answer = 2;
				break;
			case $_SESSION['tmpFrage']['answers']['3']['id']:
				$answer = 3;
				break;
		}
		$query = 'INSERT INTO quizTurns(question, game, selectedAnswer, joker) ';
		$query .= 'VALUES ('.$_SESSION['tmpFrage']['id'].','.$_SESSION['gameID'].','.$answer.','.($_SESSION['tmpFrage']['joker']?1:0).')';
		require_once 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();
		$sql->doThat($query);
		if($answer === 0){
			$_SESSION['tmpFrage'] = null;
			return json_encode(array('success'=>true,
					"Kategorien"=>$this->getKategorien(false)
			));
		}else{
			return $this->GameOver();
		}
	}

	/**
	 * GameOver for The User
	 * @return
	 */
	public function GameOver()
	{
		$this->EndGame();
		$ret = array('success'=>false, "gameID"=>$_SESSION['gameID'], "answer"=>$_SESSION['tmpFrage']['answers']['0']['id']);
		$_SESSION['tmpFrage'] = null;
		$_SESSION['gameID']=0;
		return json_encode($ret);
	}

	/**
	 * End Game and get The Score !!!
	 */
	public function quitGame(){
		$this->EndGame();
		$ret = array('success'=>false, "gameID"=>$_SESSION['gameID'], "answer"=>$_SESSION['tmpFrage']['answers']['0']['id']);
		require_once 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();
		$query = "SELECT COUNT(id)*30 as points FROM quizTurns WHERE game=".$_SESSION['gameID']." GROUP BY game LIMIT 1";
		$SqlResult = $sql->get($query);
		$ret['score']=$SqlResult[0]->points;
		$_SESSION['tmpFrage'] = null;
		return json_encode($ret);
	}

	/**
	 * Sets The End-Date in DB-Row Game
	 */
	public function EndGame(){
		$query = "UPDATE quizGames SET stop=now() WHERE id=".$_SESSION['gameID'];
		require_once 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();
		$sql->doThat($query);
	}
}
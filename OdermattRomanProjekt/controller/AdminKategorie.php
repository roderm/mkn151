<?php 

/**
 *
 * @author roman
 *
 */
class AdminKategorie{

	/**
	 * Return Kategorien-Page as HTML-View
	 */
	public function getMain()
	{
		require 'classes/SQL.php';
		require 'classes/view.php';
			
		$model = new stdClass();
		$model->template = 'admin/KategorieTable';
		$sql = new SQL();
		$sql->connect();
		$model->data = $sql->get("SELECT * FROM quizCategories");
		$view = new view();
		return $view->loadTemplate($model);
	}

	/**
	 * Add new or edit Categories in DB
	 * paramters = POST: Categorie ID and Categorie-Properties
	 */
	public function saveKategorien(){
		require 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();
		$id = mysql_real_escape_string(htmlentities($_POST['katID']));
		$bez = mysql_real_escape_string(htmlentities($_POST['bezTxt']));
		$desc = mysql_real_escape_string(htmlentities($_POST['descTxt']));
		$returnData;
		$success = true;
		if($id == 0 && $bez!=""){
			$sql->doThat('INSERT INTO quizCategories(bezeichnung, beschreibung) VALUES ("'.$bez.'","'.$desc.'")');
			$returnData = $sql->get('SELECT * FROM quizCategories WHERE bezeichnung="'.$bez.'" AND beschreibung="'.$desc.'" LIMIT 1');
		}elseif($bez!=""){
			$sql->doThat('UPDATE quizCategories SET bezeichnung="'.$bez.'", beschreibung="'.$desc.'" WHERE id='.$id);
			$returnData = $sql->get('SELECT * FROM quizCategories WHERE id='.$id);
		}else{
			$success = false;
		}
		$arr=array(
				'success' => $success,
				'row'=>array(
						'id'=>$returnData[0]->id,
						'bez'=> $returnData[0]->bezeichnung,
						'desc'=> $returnData[0]->beschreibung
				)
		);
		return json_encode($arr);
	}

	/**
	 * Delete a Categorie in DB
	 * parameters = POST: Categorie ID
	 */
	public function deleteKategorien(){
		require 'classes/SQL.php';
		$sql = new SQL();
		$sql->connect();
			
		$id = mysql_real_escape_string($_POST['id']);
		if(is_numeric($id)){
		 $sql->doThat('DELETE FROM quizCategories WHERE id='.$id);
		}
	}
}
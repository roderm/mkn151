<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQL
 *
 * @author Roman
 */
class SQL {
    private $db_server = 'localhost:3306';
    private $db_name='MKN';
    private $db_user='Sql-Benutzer';
    private $db_passwd='Password';
    
    /**
     * Connect de DB
     * @throws Exception
     */
    public function connect(){
        try{
            mysql_connect($this->db_server, $this->db_user, $this->db_passwd);
            mysql_select_db($this->db_name);
        }catch(Exception $e){
            throw new Exception('Verbindung zum Datenbank server fehlgeschlagen: '.$e->getmessage());
        }
    }
    
    /**
     * Get a DB-Query and Returns as Object-Array
     * @param unknown_type $query
     */
    public function get($query){
        $result = mysql_query($query) OR die(mysql_error());
        $arr = array();
        
        while($row = mysql_fetch_object($result)){
        	$arr[]=$row;
        }
        return $arr;
    }
    
    /**
     * Execute a INSERT, DELETE or UPDATE-Statemen and Returns the RowID(s)
     * @param unknown_type $query
     * @return number
     */
	public function doThat($query){
		$result = mysql_query($query) OR die(mysql_error());
		return mysql_insert_id();
	}
}

?>

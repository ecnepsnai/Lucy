<?php

/* MYSQLi CLASS FOR CDA */

$connection = null;

class sql{

	private $cda_output = 0;

	/* Sets the output setting */
	function setOutput($output){
		$this->cda_output = $output;
	}


	/* Connects to the database */
	function connect($location, $name, $username, $password){
		$GLOBALS['connection'] = new mysqli($location, $username, $password, $name);
		if(!$GLOBALS['connection']){
			throw new Exception($GLOBALS['connection']->connect_error, 1);
		}
		return True;
	}

	/* Tests database connectivity */
	function testConnection($location, $username, $password, $name){
		/* We temporary disable error reporting here since mysqli doesn't throw an error, only a warning if connection failed */
		$cerl = error_reporting ();
		error_reporting (0);
		$conn = new mysqli($location, $username, $password, $name);
		error_reporting ($cerl);
		if(!$conn){
			return false;
		}
		return true;
	}

	/* Selects row(s) from the database */
	function select($columns, $table, $conditions){
		$sql = "SELECT ";
		if(isset($columns)){
			$sql.= implode(", ", $columns);
		} else {
			$sql.= "*";
		}
		$sql.= " FROM " . $table;
		if($conditions != null){
			$sql.= " WHERE ";
			foreach ($conditions as $variable => $value) {
				$sql.= $variable . " = '" . $GLOBALS['connection']->real_escape_string($value) . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}
		
		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sql_response = $GLOBALS['connection']->real_query($sql);
		$sqli_response = $GLOBALS['connection']->use_result();
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$data = array();

		while($row = $sqli_response->fetch_assoc()){			
			array_push($data, $row);			
		}		
		if(count($data) == 1){			
			$response = array("status" => true, "data" => $data[0]);
		} else {			
			$response = array("status" => true, "data" => $data);
		}
		return $response;
	}

	/* Searches row(s) in the database */
	function search($columns, $table, $query, $library){
		$sql = "SELECT ";
		if(isset($columns)){
			$sql.= implode(", ", $columns);
		} else {
			$sql.= "*";
		}
		$sql.= " FROM " . $table . " WHERE ( ";

		foreach($library as $column){
			$sql.= $column . " like '%" . $GLOBALS['connection']->real_escape_string($query) . "%' OR ";
		}
		$sql = rtrim($sql, " OR ");
		$sql.= " )";
		
		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sql_response = $GLOBALS['connection']->real_query($sql);
		$sqli_response = $GLOBALS['connection']->use_result();
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$data = array();

		while($row = $sqli_response->fetch_assoc()){			
			array_push($data, $row);			
		}		
		if(count($data) == 1){			
			$response = array("status" => true, "data" => $data[0]);
		} else {			
			$response = array("status" => true, "data" => $data);
		}
		return $response;
	}

	/* Inserts row(s) into the database */
	function insert($table, $columns, $values){
		$sql = "INSERT INTO `" . $table . "` (";
		foreach($columns as $column){
			$sql.= "`" . $column . "`, ";
		}
		$sql = rtrim($sql, ", ");
		$sql.= ") VALUES ( ";
		foreach ($values as $value) {
			$sql.="'" . $GLOBALS['connection']->real_escape_string($value) . "', ";
		}
		$sql = rtrim($sql, ", ");
		$sql.= ")";

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sql_response = $GLOBALS['connection']->real_query($sql);
		$sqli_response = $GLOBALS['connection']->use_result();
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$response = array("id" => $GLOBALS['connection']->insert_id, "num" => $GLOBALS['connection']->affected_rows);
		return $response;
	}

	/* Updates a row in the database */
	function update($table, $changes, $conditions){
		$sql = "UPDATE `" . $table . "` SET ";
		foreach($changes as $col => $value){
			$sql.= "`" . $col . "` = '" . $GLOBALS['connection']->real_escape_string($value) . "', ";
		}
		$sql = rtrim($sql, ", ");
		if($conditions != null){
			$sql.= " WHERE ";
			foreach ($conditions as $variable => $value) {
				$sql.= $variable . " = '" . $GLOBALS['connection']->real_escape_string($value) . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sql_response = $GLOBALS['connection']->real_query($sql);
		$sqli_response = $GLOBALS['connection']->use_result();
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$response = array("id" => $GLOBALS['connection']->insert_id, "num" => $GLOBALS['connection']->affected_rows);
		return $response;
	}

	/* Deletes row(s) from the database */
	function delete($table, $conditions){
		$sql = "DELETE FROM `" . $table . "`";
		if($conditions != null){
			$sql.= " WHERE ";
			foreach ($conditions as $variable => $value) {
				$sql.= $variable . " = '" . $GLOBALS['connection']->real_escape_string($value) . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}
		
		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sql_response = $GLOBALS['connection']->real_query($sql);
		$sqli_response = $GLOBALS['connection']->use_result();
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$response = array("id" => $GLOBALS['connection']->insert_id, "num" => $GLOBALS['connection']->affected_rows);
		return $response;
	}

	/* Creates a new Table */
	function createTable($table, $columns, $primary, $uniques){
		$is_AI = false;

		$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (";
		foreach($columns as $column){
			$sql.= "`" . $column['name'] . "` " . $column['type'];
			if($column['length'] != null){
				$sql.= "(" . $column['length'] . ")";
			}
			if($column['null'] === false){
				$sql.= " NOT NULL";
			} else {
				$sql.= " NULL";
			}
			if(!empty($column['ai'])){
				$sql.= " AUTO_INCREMENT";
				$is_AI = true;
			}
			$sql.=",";
		}
		if(!empty($primary)){
			$sql.= " PRIMARY KEY (`" . $primary . "`),";
		}
		foreach($uniques as $unique){
			$sql.= " UNIQUE KEY (`" . $unique . "`),";
		}
		$sql = rtrim($sql, ",");
		$sql.= ")";
		if($is_AI){
			$sql.= "AUTO_INCREMENT=1";
		}

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sql_response = $GLOBALS['connection']->real_query($sql);
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$response = array("id" => $GLOBALS['connection']->insert_id);
		return $response;
	}

	/* Drops a table from the database */
	function dropTable($table){
		$sql = "DROP TABLE " . $table;

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}
		
		$sql_response = $GLOBALS['connection']->real_query($sql);
		if(!$sql_response){
			throw new Exception($GLOBALS['connection']->error, 1);
		}

		$response = array("id" => $GLOBALS['connection']->insert_id);
		return $response;
	}
}
<?php

/* SQLITE3 CLASS FOR CDA */

$connection = null;

class sql{

	private $cda_output = 0;

	/* Sets the output setting */
	function setOutput($output){
		$this->cda_output = $output;
	}


	/* Connects to the database */
	/* NOTE: For consistency with other database types, we include this unused parameters.*/
	/* They are not required and can be null (except $name) */
	function connect($location, $name, $username, $password){
		$GLOBALS['connection'] = new SQLite3($name); 
		if(!$GLOBALS['connection']){
			throw new Exception("Error connecting to database: " . $name, 1);
		}
		return True;
	}

	/* Tests database connectivity */
	function testConnection($location, $username, $password, $name){
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
				$sql.= $variable . " = '" . $GLOBALS['connection']->escapeString($value) . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}
		

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}


		$sqlite_response = $GLOBALS['connection']->query($sql); 
		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}
		$data = array();
		while($row = $sqlite_response->fetchArray(SQLITE3_ASSOC)){
			array_push($data, $row);
		}
		if(count($data) == 1){			
			$response = array("status" => true, "data" => $data[0]);
		} else {			
			$response = array("status" => true, "data" => $data);
		}
		$GLOBALS['connection']->close();

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
			$sql.= $column . " like '%" . $query . "%' OR "; 
		}
		$sql = rtrim($sql, " OR ");
		$sql.= " )";
	

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sqlite_response = $GLOBALS['connection']->query($sql); 
		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}
		$data = array();
		while($row = $sqlite_response->fetchArray(SQLITE3_ASSOC)){
			array_push($data, $row);
		}
		if(count($data) == 1){			
			$response = array("status" => true, "data" => $data[0]);
		} else {			
			$response = array("status" => true, "data" => $data);
		}
		$GLOBALS['connection']->close();

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
			$sql.="'" . $GLOBALS['connection']->escapeString($value) . "', ";
		}
		$sql = rtrim($sql, ", ");
		$sql.= ")";

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sqlite_response = $GLOBALS['connection']->exec($sql); 
		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}

		$response = array("id" => $GLOBALS['connection']->lastInsertRowID(), "num" => $GLOBALS['connection']->changes());
		$GLOBALS['connection']->close();
		return $response;
	}


	/* Updates a row in the database */
	function update($table, $changes, $conditions){
		$sql = "UPDATE `" . $table . "` SET ";
		foreach($changes as $col => $value){
			$sql.= "`" . $col . "` = '" . $value . "', ";
		}
		$sql = rtrim($sql, ", ");
		if($conditions != null){
			$sql.= " WHERE ";
			foreach ($conditions as $variable => $value) {
				$sql.= $variable . " = '" . $GLOBALS['connection']->escapeString($value) . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}


		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sqlite_response = $GLOBALS['connection']->exec($sql); 
		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}

		$response = array("num" => $GLOBALS['connection']->changes());
		$GLOBALS['connection']->close();
		return $response;
	}

	/* Deletes row(s) from the database */
	function delete($table, $conditions){
		$sql = "DELETE FROM `" . $table . "`";
		if($conditions != null){
			$sql.= " WHERE ";
			foreach ($conditions as $variable => $value) {
				$sql.= $variable . " = '" . $GLOBALS['connection']->escapeString($value) . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}
		

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sqlite_response = $GLOBALS['connection']->exec($sql); 
		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}

		$response = array("num" => $GLOBALS['connection']->changes());
		$GLOBALS['connection']->close();
		return $response;
	}

	/* Creates a new Table */
	function createTable($table, $columns, $primary, $uniques){
		$is_AI = false;

		$sql = "CREATE TABLE IF NOT EXISTS `" . $table . "` (";
		foreach($columns as $column){
			if(!empty($column['ai']) && $column['name'] == $primary){
				$sql.= "`" . $column['name'] . "` INTEGER PRIMARY KEY";
			} else {
				$sql.= "`" . $column['name'] . "` " . $column['type'];
				if($column['length'] != null){
					$sql.= "(" . $column['length'] . ")";
				}
			}
			$sql.=",";
		}
		$sql = rtrim($sql, ",");
		$sql.= ")";

		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}

		$sqlite_response = $GLOBALS['connection']->exec($sql);

		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}

		$response = true;
		$GLOBALS['connection']->close();
		return $response;
	}

	/* Drops a table from the database */
	function dropTable($table){
		$sql = "DROP TABLE " . $table;


		if($this->cda_output != 0){
			echo("<code class=\"cda-output\">" . $sql . "</code><br/>");
		}
		
		$sqlite_response = $GLOBALS['connection']->exec($sql); 
		if(!$sqlite_response){
			throw new Exception($GLOBALS['connection']->lastErrorMsg(), 1);
		}

		$response = true;
		$GLOBALS['connection']->close();
		return $response;
	}
}
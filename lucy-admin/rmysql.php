<?php

/* RMYSQL CLASS FOR CDA */

$connection = null;

class sql{

	private $cda_output = 0;

	/* Sets the output setting */
	function setOutput($output){
		$this->cda_output = $output;
	}


	/* Connects to the database */
	function connect($location, $name, $username, $password){
		/* $GLOBALS['connection'] = mysql_connect($location, $username, $password);
		if(!$GLOBALS['connection']){
			throw new Exception(mysql_error(), 1);
		}
		$r = mysql_select_db($name);
		if(!$r){
			throw new Exception(mysql_error(), 1);
		} */
		return True;
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
				$sql.= $variable . " = '" . $value . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}
		
		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
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
		
		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
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
			$sql.="'" . $value . "', ";
		}
		$sql = rtrim($sql, ", ");
		$sql.= ")";

		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
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
				$sql.= $variable . " = '" . $value . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}

		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
	}

	/* Deletes row(s) from the database */
	function delete($table, $conditions){
		$sql = "DELETE FROM `" . $table . "`";
		if($conditions != null){
			$sql.= " WHERE ";
			foreach ($conditions as $variable => $value) {
				$sql.= $variable . " = '" . $value . "' AND ";
			}
			$sql = rtrim($sql, ", AND ");
		}
		
		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
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
		if($uniques != null){
			foreach($uniques as $unique){
				$sql.= " UNIQUE KEY (`" . $unique . "`),";
			}
			$sql = rtrim($sql, ",");
		}
		$sql.= ")";
		if($is_AI){
			$sql.= "AUTO_INCREMENT=1";
		}

		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
	}

	/* Drops a table from the database */
	function dropTable($table){
		$sql = "DROP TABLE " . $table;

		echo('<code class="cda_output" style="margin:1em 0; display:block;">' . $sql . '</code><br/>');
	}
}
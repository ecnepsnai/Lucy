<?php

	/* Database authentication variables.  Make sure to fill these in. */

	/* Database Location */
	define('dba_location', $GLOBALS['config']['Database']['Location']);
	
	/* Database Name */
	define('dba_name', $GLOBALS['config']['Database']['Name']);

	/* Database Username */
	define('dba_username', $GLOBALS['config']['Database']['Username']);

	/* Database Password */
	define('dba_password', $GLOBALS['config']['Database']['Password']);

	/* Debug Output */
	/* 0 = No output: production. */
	/* 1 = Outputs all SQL queries: development. */
	/* 2 = Outputs everything: debugging. */
	define("cda_output", 0);



	/* The database type, don't hard-code a value here. */
	$dba_type = "";

	/* The database connection status */
	$dba_isConnected = False;

	/* cda */
	class cda {

		/* Establishes a SQL connection */
		/* $type = String : The SQL Type. */
		function init($type){
			switch ($type) {
				case 'MSSQL':
					$dba_type = $type;
					require("mssql.php");
				break;

				case 'MYSQLI':
					$dba_type = $type;
					require("mysqli.php");
				break;

				case 'MYSQL':
					$dba_type = $type;
					require("mysql.php");
				break;

				case 'SQLITE':
					$dba_type = $type;
					require("sqlite.php");
				break;

				case 'RMYSQL':
					$dba_type = $type;
					require("rmysql.php");
				break;
				
				default:
					die("Unknown database type");
				break;

			}
		}

		/* Selects row(s) from the database */
		/* $columns = Array : A list of the column names to select. */
		/* $table = String : The table name. */
		/* $conditions = Array : A associative array of conditions to meet. */
		function select($columns, $table, $conditions){
			/* Testing that all variables passed */
			if($table == null || $table == ""){
				die("Missing table name.");
			}

			$sql = new sql;

			if(cda_output != 0){
				$sql->setOutput(cda_output);
			}

			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->select($columns, $table, $conditions);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}

		/* Searches row(s) in the database */
		/* $columns = Array : A list of the column names to select. */
		/* $table = String : The table name. */
		/* $query = String : The Search Query. */
		/* $library = Array : A list of the column names to test the query against. */
		function search($columns, $table, $query, $library){
			/* Testing that all variables passed */
			if($table == null || $table == ""){
				die("Missing table name.");
			}
			if($query == null || $query == ""){
				die("Missing query name.");
			}

			$sql = new sql;

			if(cda_output != 0){
				$sql->setOutput(cda_output);
			}

			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->search($columns, $table, $query, $library);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}

		/* Inserts a row into the database */
		/* $table = String : The table name. */
		/* $columns = Array : A list of the column names to select. */
		/* $values = Array : A list of all the values. */
		function insert($table, $columns, $values){
			/* Testing that all variables passed */
			if($columns == null){
				die("Missing column name(s).");
			}
			if($table == null || $table == ""){
				die("Missing table name.");
			}

			$sql = new sql;
			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->insert($table, $columns, $values);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}

		/* Updates a row in the database */
		/* $table = String : The table name. */
		/* $changes = Array : The column names and values */
		/* $conditions = Array : A associative array of conditions to meet. */
		function update($table, $changes, $conditions){
			/* Testing that all variables passed */
			if($changes == null){
				die("Missing column name(s).");
			}
			if($table == null || $table == ""){
				die("Missing table name.");
			}

			$sql = new sql;
			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->update($table, $changes, $conditions);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}

		/* Delete row(s) from the database */
		/* $table = String: The table name. */
		/* $conditions = Array : A associative array of conditions to meet. */
		function delete($table, $conditions){
			/* Testing that all variables passed */
			if($table == null || $table == ""){
				die("Missing table name.");
			}

			$sql = new sql;
			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->delete($table, $conditions);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}

		/* Creates a Table in the Database */
		/* $table = String: The table name. */
		/* $columns = Multi-Decisional Array that must follow strict formatting. */
		/* $primary = String : The column name for the primary key. */
		/* $unique = Array : The column names for the unique keys. */
		function createTable($table, $columns, $primary, $uniques){
			/* Testing that all variables passed */
			if($table == null || $table == ""){
				die("Missing table name.");
			}
			if($columns == null){
				die("Missing columns.");
			}

			$sql = new sql;
			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->createTable($table, $columns, $primary, $uniques);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}

		/* Drops a Table in the Database */
		/* $table = String: The table name. */
		function dropTable($table){
			/* Testing that all variables passed */
			if($table == null || $table == ""){
				die("Missing table name.");
			}

			$sql = new sql;
			$dba_isConnected = $sql->connect(dba_location, dba_name, dba_username, dba_password);

			$response = array();
			try{
				$response = $sql->dropTable($table);
			} catch(exception $e){
				die($e);
			}
			return $response;
		}
	}
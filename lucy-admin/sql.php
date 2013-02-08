<?php
	// Check to see if Lucy was properly set up.
	$DATABASE_SETUP = False;
	if($GLOBALS['config']['Database']['Type'] == '' || $GLOBALS['config']['Database']['Location'] == '' || $GLOBALS['config']['Database']['Username'] == '' || $GLOBALS['config']['Database']['Name'] == ''){
		$DATABASE_SETUP = False;
	} else {
		$DATABASE_SETUP = True;
	}

	if(!$GLOBALS['config']['Database']['nullpwd'] && $GLOBALS['config']['Database']['Password'] == ''){
		$DATABASE_SETUP = False;
	}


	// Performs a SQL query based off of the user settings.
	// $query 		= [string] The SQL query to perform.  Do not include more than one query.
	//
	// WARNING: Values must be escaped before entering this function.  They are not escaped here.
	//
	// $singleRow	= [optional /boolean] If the returned array should be a single row.
	// Returns the fetched array.
	function sqlQuery($query, $singleRow){

		// Checks for a missing query.
		if(empty($query)){
			throw new Exception("SQL Query not included.", 1);
			die();
		}

		// Checks for SQL configuration errors.
		if(DATABASE_SETUP == False){
			throw new Exception("The SQL Configuration is not complete.", 1);
			die();
		}

		// Connects, Performs, and Disconnects from the database.
		switch ($GLOBALS['config']['Database']['Type']) {

			// MySQL Database.
			case 'MYSQL':
				// Establishes a connection to the database.
				$connection = mysql_connect($GLOBALS['config']['Database']['Location'], $GLOBALS['config']['Database']['Username'], $GLOBALS['config']['Database']['Password']);
				if(!$connection){
					throw new Exception(mysql_error(), 1);
				}
				mysql_select_db($GLOBALS['config']['Database']['Name']);
				// Performs the query.
				$response = mysql_query($query);
				if(!$response){
					throw new Exception(mysql_error(), 1);
				}

				// If the result is only a single row, just return that one row.
				if($singleRow){
					return mysql_fetch_array($response);
					mysql_close();
					break;
				}

				// If the result is multiple rows, return an array of the rows.
				$rows = array();
				while($row = mysql_fetch_array($response)){
					array_push($rows, $row);
				}
				return $rows;
				mysql_close();
				break;
			
			// Microsoft SQL Server.
			// TODO: Finish this.
			case 'MSSQL':
				$connection = mssql_connect($GLOBALS['config']['Database']['Location'], $GLOBALS['config']['Database']['Username'], $GLOBALS['config']['Database']['Password']);
				if (!$connection) {
					throw new Exception(mssql_get_last_message(), 1);
				}
				mssql_select_db($GLOBALS['config']['Database']['Name']);
				break;

			// SQLite database.
			// TODO: Finish this.
			case 'SQLITE':
				# code...
				break;

			// MySQLI Database Type.
			// TODO: Finish this.
			case 'MYSQLI':
				$connection = mysqli_connect($GLOBALS['config']['Database']['Location'], $GLOBALS['config']['Database']['Username'], $GLOBALS['config']['Database']['Password'], $GLOBALS['config']['Database']['Name']);
				if(!$connection){
					throw new Exception(mysqli_error(), 1);
				}
				$response = mysqli_query($connection, $query);
				$rows = mysqli_fetch_array($response, MYSQLI_ASSOC);

				return $rows;
				break;
			// Unknown database type.
			default:
				throw new Exception("Unknown SQL Type.", 1);
				die();
		}
	}
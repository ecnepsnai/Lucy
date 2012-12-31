<?php

	// Hey you!  Congratulations, you are in the configuration files for Lucy, you will need to fill in the blanks below.  Don't worry, it isn't hard!



	// #1 This is the type of database you're using.  Lucy works for MySQL, Microsoft SQL Server, and SQLite.
	// MySQL is the most common, but if you're unsure check with your web hosting provider.
	// Please enter one of the following:
	// "MYSQL" for MySQL Servers.
	// "MSSQL" for Microsoft SQL Servers.
	// "SQLITE" for SQLite Servers.
	define('DATABASE_TYPE', 'MYSQL');


	// #2 This is the URL where your database is located.
	// When you create a database with your host, they will provide this url.
	define('DATABASE_LOCATION', 'localhost');


	// #3 The Username that Lucy will use to access the database.
	// Lucy will need Select, Insert, Alter, Create, and Drop permissions.
	// When you create a database with your hose, you may be asked to create a username.
	define('DATABASE_USERNAME', 'root');


	// #4 The password for the above user.
	// When you create a database with your hose, you may be asked to create a password.
	define('DATABASE_PASSWORD', '');
	// NOTE: If your user does not have a password (not recommended) alter the if statement below.


	// #5 The name of the database that Lucy will use.
	// You will have to pick a name when creating a database with your host.
	define('DATABASE_NAME', 'lucy');


	// That's it!  Save and upload this file and reload lucy-setup.php to continue!



	// Check to see if Lucy was properly set up.
	if(DATABASE_TYPE == '' || DATABASE_LOCATION == '' || DATABASE_USERNAME == '' || DATABASE_PASSWORD == 'aaa' || DATABASE_NAME == ''){
	// If you don't have a password Enter a random value between These literals.
		define('DATABASE_SETUP', False);
	} else {
		define('DATABASE_SETUP', True);
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
		switch (DATABASE_TYPE) {

			// MySQL Database.
			case 'MYSQL':
				// Establishes a connection to the database.
				$connection = mysql_connect(DATABASE_LOCATION, DATABASE_USERNAME, DATABASE_PASSWORD);
				if(!$connection){
					throw new Exception(mysql_error(), 1);
				}
				mysql_select_db(DATABASE_NAME);
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
				$connection = mssql_connect(DATABASE_LOCATION, DATABASE_USERNAME, DATABASE_PASSWORD);
				if (!$connection) {
					throw new Exception(mssql_get_last_message(), 1);
				}
				mssql_select_db(DATABASE_NAME);
				break;

			// SQLite database.
			// TODO: Finish this.
			case 'SQLITE':
				# code...
				break;

			// MySQLI Database Type.
			// TODO: Finish this.
			case 'MYSQLI':
				$connection = mysqli_connect(DATABASE_LOCATION, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
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
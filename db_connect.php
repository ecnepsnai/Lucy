<?php

	// Hey you!  Congratulations, you are in the configuration files for Lucy, you will need to fill in the blanks below.  Don't worry, it isn't hard!


	// #1 This is the URL where your database is located.
	//When you create a database with your host, they will provide this url.
	$database_location = "localhost";


	// #2 The Username that Lucy will use to access the database.
	//Lucy will need Select, Insert, Alter, Create, and Drop permissions.
	//When you create a database with your hose, you may be asked to create a username.
	$database_username = "root";


	// #3 The password for the above user.
	//When you create a database with your hose, you may be asked to create a password.
	$database_password = "";
	//NOTE: If your user does not have a password (not recommended) alter the if statement below.



	// #4 The name of the database that Lucy will use.
	//You will have to pick a name when creating a database with your host.
	$database_name = "lucy";


	// That's it!  Save and upload this file and reload lucy-setup.php to continue!




	//Check to see if Lucy was properly set up.
	if($database_location == "" || $database_username == "" || $database_name == ""){
		$db_IsSetup = False;
		require_once("lucy-setup.php");
	} else {
		$db_IsSetup = True;
	}
	$con = mysql_connect($database_location,$database_username,$database_password);
	if(!$con) {
		require("error_connect.php");
	}
	mysql_select_db($database_name);

	//unset the values after use.
	unset($database_location);
	unset($database_username);
	unset($database_password);
	unset($database_name);
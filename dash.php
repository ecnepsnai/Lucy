<?php
	require("lucy-admin/session.php");
	require("lucy-admin/sql.php");

	if(!$usr_IsSignedIn){
		header("Location: " . $GLOBALS['Config']['domain'] . "login.php");
	}

	$sql = "SELECT * FROM ticketlist WHERE email = '" . $usr_Email . "'";
	try {
		$tickets = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}

require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/dash.php');
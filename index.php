<?php
require('lucy-admin/session.php');
require('lucy-admin/sql.php');

// Show the Dash for users who are already signed in.
if($usr_IsSignedIn){
	$sql = "SELECT * FROM ticketlist WHERE email = '" . $usr_Email . "'";
	try {
		$tickets = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}
	require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/dash.php');
} else {
	require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/index.php');
}
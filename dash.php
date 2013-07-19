<?php
	require("lucy-admin/session.php");

	if(!$usr_IsSignedIn){
		header("Location: login.php");
	}

	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	try {
		$response = $cda->select(null,"ticketlist",array("owner"=>$usr_ID));
	} catch (Exception $e) {
		die($e);
	}
	$tickets = $response['data'];
	if(isset($tickets['id'])){
		$tickets = array($tickets);
	}
	require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/dash.php');
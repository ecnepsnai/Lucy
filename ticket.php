<?php
	require("lucy-admin/session.php");

	// If no id was supplied.
	$id = $_GET['id'];
	if(empty($id)){
		require("error_empty.php");
	}


	require("lucy-admin/cda.php");
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	$id = addslashes($id);


	try{
		$response = $cda->select(array("application","version","os","status","id","owner"),"ticketlist",array("id"=>$id));
	} catch (Exception $e) {
		die($e);
	}
	$ticket_info = $response['data'];

	// If no data was returned -- ticket does not exist.
	if(count($ticket_info) == 0){
		die("Ticket does not exist");
	}

	try{
		$response = $cda->select(null,$id,null);
	} catch (Exception $e) {
		die($e);
	}

	$ticket_messages = $response['data'];
	if(isset($ticket_messages['id'])){
		$ticket_messages = array($ticket_messages);
	}

	// Correcting issue if there is only one item in the database.
	if(isset($ticket_messages['From'])){
		$ticket_messages = array($ticket_messages);
	}
	
	// If somebody is trying to view the ticket without being signed in it will deny the request
	if($usr_Email == "" || $usr_Email == null){
		header("Location: login.php?notice=login");
	}

	// THIS SHOULD NOT RETURN TRUE
	// If the ticket table has no data in it.
	if(count($ticket_messages) == 0){
		die("No ticket information returned.");
	}

require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/ticket.php');
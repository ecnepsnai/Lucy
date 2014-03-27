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
	$id = $id;


	try{
		$response = $cda->select(null,"threads",array("id"=>$id));
	} catch (Exception $e) {
		header('location: index.php');
	}
	$thread_info = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($threads['id'])){
		$threads = array($threads);
	}

	// If no data was returned -- thread does not exist.
	if(count($thread_info) == 0){
		header('location: index.php');
	}



	$thread_messages = json_decode($thread_info['data']);
	
	// If somebody is trying to view the thread without being signed in it will deny the request
	if($usr_Email == "" || $usr_Email == null){
		header('location: index.php');
	}

	// THIS SHOULD NOT RETURN TRUE
	// If the thread table has no data in it.
	if(count($thread_messages) == 0){
		header('location: index.php');
	}

require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/thread.php');
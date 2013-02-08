<?php
	require("lucy-admin/session.php");

	// If no id was supplied.
	$id = $_GET['id'];
	if(empty($id)){
		require("error_empty.php");
	}


	require("lucy-admin/sql.php");
	$id = addslashes($id);


	// User chose to close the ticket.
	if(isset($_POST['close']) && $_POST['close'] == "CloseTicket"){

		// Updates the master ticketlist.
		$sql = "UPDATE ticketlist SET status = 'Closed' WHERE  id = '" . $id . "';";
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			require("error_db.php");
		}

		// Inserts a CLOSED message into the ticket table.
		$sql = "INSERT INTO " . $id . " (`Name`, `Email`, `Date`, `Message`, `From`) VALUES ('" . $usr_Name . "', '" . $usr_Email . "', '" . date("Y-m-d H:i:s")  . "', 'CLOSED', '";
		if($usr_Type == "Admin"){
			$sql.= "Agent');";
		} else {
			$sql.= "Client');";
		}
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			require("error_db.php");
		}
	}

	// User added a reply
	if(isset($_POST['reply']) && $_POST['reply'] == "ReplyToTicket"){

		// If no message was included.
		if(empty($_POST['message'])){
			require("error_empty.php");
		}
		$message = addslashes($_POST['message']);

		//Trims the message to the maximum length of MEDIUMTEXT.
		//IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
		$message = substr($message, 0, 16777216);

		$isFile = False;
		$filename = $_FILES['screenshot']['tmp_name'];


		// Tests to see if a screenshot was included.
		if (empty($filename)) {
			$img_hash = "";
		} elseif (isset($filename) && $GLOBALS['config']['Imgur']['Enable']) {

			// Getting the file information.
			$isFile = True;
			$handle = fopen($filename, "r");
			$data = fread($handle, filesize($filename));
			$pvars = array('image' => base64_encode($data), 'key' => $GLOBALS['config']['Imgur']['Key']);
			$timeout = 30;

			// Setting up the cUrl uploader.
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://api.imgur.com/2/upload.json');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);

			// Uploading to Imgur.
			$json = curl_exec($curl);
			curl_close ($curl);
			$data = json_decode($json,true);

			// Getting the image hash from the response.
			$img_hash = $data["upload"]["image"]["hash"];
		}

		// Inserts the new entry into the ticket table.
		$sql = "INSERT INTO " . $id . " (`Name`, `Email`, `Date`, `Message`, `File`, `From`) VALUES ('" . $usr_Name . "', '" . $usr_Email . "', '" . date("Y-m-d H:i:s")  . "', '" . $message . "', '" . $img_hash . "', '";
		if($usr_Type == "Admin"){
			$sql.= "Agent');";
		} else {
			$sql.= "Client');";
		}
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			require("error_db.php");
		}

		if($usr_Type == "Admin"){
			$sql = "UPDATE ticketlist SET lastreply = 'Agent' WHERE  id = '" . $id . "';";
		} else {
			$sql = "UPDATE ticketlist SET lastreply = 'Client' WHERE  id = '" . $id . "';";
		}
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			require("error_db.php");
		}

		
	}

	// Getting the ticket information from the master ticketlist.
	$sql = "SELECT application, version, os, status, id, name, email, date FROM ticketlist WHERE id = '" . $id . "'";

	// Administrator users can see all tickets.
	if($usr_Type != "Admin"){
		// $sql.= " AND email = '" . $usr_Email . "'";
	}
	try {
		$ticket_info = sqlQuery($sql, True);
	} catch (Exception $e) {
		require("error_db.php");
	}

	// If no data was returned -- no ticket does not exist.
	if(count($ticket_info) == 0){
		die();
	}



	// Getting everything from the ticket table.
	$sql = "SELECT * FROM " . $id;
	try {
		$ticket_messages = sqlQuery($sql, False);
	} catch (Exception $e) {
		require("error_db.php");
	}

	// THIS SHOULD NOT RETURN TRUE
	// If the ticket table has no data in it.
	if(count($ticket_messages) == 0){
		require("error_empty.php");
	}

require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/ticket.php');
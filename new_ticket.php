<?php
require('lucy-admin/session.php');
$val_error = null;
// Requires the captcha library if reCAP is enabled.
if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Ticket']){
	require("lucy-admin/recaptchalib.php");
}


require("lucy-admin/sql.php");
// User submitted a new ticket.
if(isset($_POST['submit'])){
	// Validate the form if Javascript failed
	if(empty($_POST['app']) || empty($_POST['version']) || empty($_POST['os']) || empty($_POST['message'])){
		$val_error = True;
		goto writeDOC;
	}
	// Validates the reCAPTICHA challenge if enabled.
	if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Ticket']){
		$resp = recaptcha_check_answer ($GLOBALS['config']['ReCaptcha']['Private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$cap_error = True;
			goto writeDOC;
		}
	}

	// Getting & Setting the ticket information.
	if($usr_IsSignedIn){
		$inp_name = $usr_Name;
		$inp_email = $usr_Email;
	} else {
		$inp_name = $_POST['name'];
		$inp_email = $_POST['email'];
	}

	$ticketid = "HP_" . rand(0, 9) . chr(97 + mt_rand(0, 25)) . rand(1000, 9999);
	$application = addslashes($_POST['app']);
	$version = addslashes($_POST['version']);
	$os = addslashes($_POST['os']);
	$message = addslashes($_POST['message']);
	$date = date("Y-m-d H:i:s"); 
	$isFile = False;
	$filename = $_FILES['screenshot']['tmp_name'];

	// Trims the message to the maximum length of MEDIUMTEXT.
	// IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
	$message = substr($message, 0, 16777216);


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

	// Inserting the ticket into the master ticket list.
	$sql = "INSERT INTO ticketlist (id, name, email, application, version, os, status, subject, date, lastreply) ";
	$sql.= "VALUES ('" . $ticketid . "','" . $inp_name . "','" . $inp_email . "','" . $application . "', '" . $version . "', '" . $os . "', 'Open', '" . substr($message, 0, 50) . "', '" . date("Y-m-d H:i:s") . "', 'Client')";
	try {
		sqlQuery($sql, False);
	} catch (Exception $e) {
		require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/error-db.php');
	}

	// Creating the specific table for the ticket.
	$sql = "CREATE  TABLE `" . $ticketid . "` (`UpdateID` INT NOT NULL AUTO_INCREMENT ,  `From` ENUM('Client','Agent') NULL , `Name` VARCHAR(45) NULL ,   
	`Email` VARCHAR(45) NULL ,  `Date` DATETIME NULL ,  `Message` MEDIUMTEXT NULL ,  `File` VARCHAR(25) NULL ,  PRIMARY KEY (`UpdateID`));";
	try {
		sqlQuery($sql, False);
	} catch (Exception $e) {
		require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/error-db.php');
	}

	// Inserting information into that table.
	$sql = "INSERT INTO `" . $ticketid . "` (`From`, `Name`, `Email`, `Date`, `Message`, `File`) VALUES ('Client', '" . $usr_Name . "', '" . $usr_Email . "', '" . $date . "', '" . $message . "', '" . $img_hash . "');";
	try {
		sqlQuery($sql, False);
	} catch (Exception $e) {
		require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/error-db.php');
	}

	// Dies when complete.
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . $GLOBALS['config']['Domain'] . "ticket.php?id=" . $ticketid . "&notice=new\">Redirecting...");
}
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/new_ticket.php');
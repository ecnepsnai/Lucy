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
		// Gathering the Email and Name.
		$inp_name = addslashes($_POST['name']);
		$inp_email = addslashes($_POST['email']);

		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($_POST['password']));

		$sql = "INSERT INTO  userlist (type, name, email, password, date_registered, salt) VALUES ('Client',  '" . $inp_name . "',  '" . $inp_email . "',  '" . $hashed_password . "',  '" . date("Y-m-d") . "', '". $salt ."');";
		try{
			sqlQuery($sql, True);
		} catch (Exception $e){
			die($e);
		}

		// Gets the id from the database.
		$sql = "SELECT id FROM userlist WHERE email = '" . $inp_email . "'";
		try{
			$user = sqlQuery($sql, True);
		} catch (Exception $e){
			die($e);
		}

		// Opens the session for the user.
		session_start();
		$_SESSION['id'] = $user['id'];
		$_SESSION['name'] = $inp_name;
		// Like before, we hard-code all users as Clients when signing up.
		$_SESSION['type'] = 'Client';
		$_SESSION['email'] = $inp_email;
		$_SESSION['LAST_ACTIVITY'] = time();
	}

	// Creating the Ticket ID based off on the setting
	$ticketid = "";
	foreach (str_split($GLOBALS['config']['Support']['ID']) as $char) {
		if($char == "#"){
			$ticketid.= rand(0, 9);
		} elseif($char == "%"){
			$ticketid.= chr(97 + mt_rand(0, 25));
		} else {
			$ticketid.= $char;
		}
	}
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
	$sql.= "VALUES ('" . $ticketid . "','" . $inp_name . "','" . $inp_email . "','" . $application . "', '" . $version . "', '" . $os . "', 'Pending', '" . substr($message, 0, 50) . "', '" . date("Y-m-d H:i:s") . "', 'Client')";
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

	header("Location: ticket.php?id=" . $ticketid . "&notice=new");
}
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/new_ticket.php');
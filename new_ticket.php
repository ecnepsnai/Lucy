<?php
require('lucy-admin/session.php');
$val_error = null;
// Requires the captcha library if reCAP is enabled.
if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Ticket']){
	require("lucy-admin/recaptchalib.php");
}

// User submitted a new ticket.
if(isset($_POST['submit'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

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
	$inp_name = null;
	$inp_email = null;

	// Getting & Setting the ticket information.
	if($usr_IsSignedIn){
		$inp_name = $usr_Name;
		$inp_email = $usr_Email;
	} else {
		// Gathering the Email and Name.
		$inp_name = $_POST['name'];
		$inp_email = $_POST['email'];

		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($_POST['password']));

		// Creating the SQL statment.
		// We hard-code in the user as a Client user with the assumption that there is already an admin.
		try{
			$response = $cda->insert("userlist",array("type","name","email","password","date_registered","salt"),array("Client",$inp_name,$inp_email,$hashed_password,date("Y-m-d"),$salt));
		} catch (Exception $e){
			$signup_error = $e;
			goto writeDOC;
		}

		// Opens the session for the user.
		session_start();
		$_SESSION['id'] = $response['id'];
		$_SESSION['name'] = $inp_name;
		// Like before, we hard-code all users as Clients when signing up.
		$_SESSION['type'] = 'Client';
		$_SESSION['email'] = $inp_email;
		$_SESSION['LAST_ACTIVITY'] = time();

		// Sends welcome message.
		mailer_welcomeMessage($inp_name, $inp_email);
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
	$application = $_POST['app'];
	$version = $_POST['version'];
	$os = $_POST['os'];
	$message = $_POST['message'];
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

	try{
		$response = $cda->insert("ticketlist",array("id","owner","email","application","version","os","status","subject","date","lastreply"),array($ticketid,$_SESSION['id'],$inp_email,$application,$version,$os,'Pending',substr($message, 0, 50),date("Y-m-d H:i:s"),'Client'));
	} catch (Exception $e){
		die($e);
	}

	

	try{
		$cols = array(
			array(
				"name"=>"UpdateID",
				"type"=>"int",
				"length"=>11,
				"null"=>false,
				"ai"=>true
			),
			array(
				"name"=>"From",
				"type"=>"varchar",
				"length"=>10,
				"null"=>false
			),
			array(
				"name"=>"Name",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"Email",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"Date",
				"type"=>"DATETIME",
				"length"=>null,
				"null"=>false
			),
			array(
				"name"=>"Message",
				"type"=>"mediumtext",
				"length"=>null,
				"null"=>false
			),
			array(
				"name"=>"File",
				"type"=>"varchar",
				"length"=>8,
				"null"=>false
			)
		);
		$response = $cda->createTable($ticketid, $cols, 'UpdateID', null);
	} catch (Exception $e){
		die($e);
	}

	try{
		$response = $cda->insert($ticketid,array('From','Name','Email','Date','Message','File'),array('Client',$_SESSION['name'],$_SESSION['email'],date("Y-m-d H:i:s"),$message,$img_hash));
	} catch (Exception $e){
		die($e);
	}
	header("Location: ticket.php?id=" . $ticketid . "&notice=new");
}
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/new_ticket.php');
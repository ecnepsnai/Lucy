<?php
require("lucy-admin/session.php");
$signup_error = null;

// Requires the captcha library if reCAP is enabled.
if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Signup']){
	require("lucy-admin/recaptchalib.php");
}

// Obviously if the user is already signed in, we don't let them sign in again.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// If the user chose to signup.
if(isset($_POST['submit'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Validates the reCAPTICHA challenge if enabled.
	if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Signup']){
		$resp = recaptcha_check_answer ($GLOBALS['config']['ReCaptcha']['Private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$cap_error = True;
			goto writeDoc;
		}
	}

	// Getting the name and email.
	$raw_name = trim($_POST['name']);
	$raw_email = trim($_POST['email']);

	// Validating the inputs.
	if(empty($raw_name) || empty($raw_email) || empty($_POST['pwd'])){
		$signup_error = "All of the fields are required.";
		goto writeDoc;
	}

	require("lucy-admin/validEmail.php");
	if(!validEmail($raw_email)){
		$signup_error = "The email address you provided was not valid.";
		goto writeDoc;
	}

	// Generating a random salt used for encryption.
	$salt = mt_rand(10, 99);

	// Encrypting the password.
	$hashed_password = md5($salt . md5($_POST['pwd']));
	$inp_name = $raw_name;
	$inp_email = $raw_email;

	// Creating the SQL statment.
	// We hard-code in the user as a Client user with the assumption that there is already an admin.
	try{
		$response = $cda->insert("userlist",array("type","name","email","password","date_registered","salt"),array("Client",$inp_name,$inp_email,$hashed_password,date("Y-m-d"),$salt));
	} catch (Exception $e){
		$signup_error = $e;
		goto writeDoc;
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
	// Dies is successful.
	header("Location: new_ticket.php");
}

writeDoc:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/signup.php');
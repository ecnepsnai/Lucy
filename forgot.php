<?php
require("lucy-admin/session.php");

// Obviously if the user is already signed in, we don't let them reset their own password.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

$change_password = false;


// User entered a PIN number
if(isset($_GET['p'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Verifying the PIN entered and the IP address used
	try{
		$response = $cda->select(array('expire_date','email'),'resetlist',array('pin'=>$_GET['p'],'ip'=>$_SERVER['REMOTE_ADDR']));
	} catch (Exception $e) {
		lucy_error('Database Error',$e, true);
		goto writeDOC;
	}

	// If nothing was returned, the PIN was not correct
	if(!isset($response['data']['expire_date'])){
		lucy_error('Invalid PIN','The PIN provided was not valid or you are trying to complete a reset from a different computer from where you started it.');
		goto writeDOC;
	}

	// Checking to see if the reset token has expired or not
	$date = new DateTime($response['data']['expire_date']);
	$now = new DateTime();
	if($now > $date){
		lucy_error('Expired PIN','The PIN provided has expired.');
		goto writeDOC;
	}

	// Allowing the user to change the password - the PIN was correct
	$user_email = $response['data']['email'];
	$change_password = true;
}

// User entered a new password
if(isset($_POST['password']) && isset($_POST['password_2']) && isset($_POST['email'])){

	// Matching the passwords
	if($_POST['password'] !== $_POST['password']){
		lucy_error('Passwords do not match','Try again');
		goto writeDOC;
	}

	// Creating the salted hash
	$salt = mt_rand(10,99);
	$salted_password = md5($salt . md5($_POST['password']));

	// Updating the password entry
	try{
		$response = $cda->update('userlist',array('salt'=>$salt,'password'=>$salted_password),array('email'=>$_POST['email']));
	} catch (Exception $e){
		lucy_error('Database error',$e, true);
		goto writeDOC;
	}

	// Removing the reset entry from the database
	try{
		$response = $cda->delete('resetlist',array('email'=>$_POST['email']));
	} catch (Exception $e){
		lucy_error('Database error',$e, true);
		goto writeDOC;
	}

	// Sending out reset notifications
	mailer_passwordResetNotice($_POST['email']);
	header('location: login.php');
}

writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/forgot.php');
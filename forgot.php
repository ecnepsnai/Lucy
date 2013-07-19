<?php
require("lucy-admin/session.php");

// Obviously if the user is already signed in, we don't let them reset their own password.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// User requested a password reset.
if(isset($_POST['submit'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	$inp_email = addslashes(trim($_POST['email']));

	try{
		$response = $cda->select(array("id","email"),"userlist",array("email"=>$inp_email));
	} catch (Exception $e) {
		die($e);
	}
	$user = $response['data'];

	if(isset($user['id']) && isset($user['email'])) {
		$salt1 = md5(rand(10,99));
		$salt2 = md5(rand(10,99) . $salt1);
		$sql = "INSERT INTO pwd_reset (`email`, `salt1`, `salt2`) VALUES ('" . $user['email'] . "', '" . $salt1 . "', '" . $salt2 . "')";
		try {
			sqlQuery($sql, False);
		} catch (Exception $e) {
			die($e);
		}

		// Emails password reset link.
		mailer_passwordReset($usr_Name, $usr_Email, "forgot.php?a=" . $salt1 . "&b=" . $salt2);
		die("Please check your email for a validation link to change your password.");
	}
}

// User clicked the request new password link emailed to them.
if(isset($_GET['a']) && isset($_GET['b'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	$salt1 = addslashes($_GET['a']);
	$salt2 = addslashes($_GET['b']);
	try{
		$response = $cda->select(array("email"),"pwd_reset",array("salt1"=>$salt1,"salt2"=>$salt2));
	} catch (Exception $e) {
		die($e);
	}
	$reset = $response['data'];

	// Checking for a returned email address.
	if(isset($reset['email'])) {
		// Generates a new salt and password for the user
		$salt = mt_rand(10, 99);
		$password = mt_rand(10, 99) . chr(97 + mt_rand(0, 25)) . mt_rand(1000, 9999) . chr(97 + mt_rand(0, 25));
		$hashed_password = md5($salt . md5($password));

		try{
			$response = $cda->update("userlist",array("password"=>$hashed_password,"salt"=>$salt),array("email"=>$reset['email']));
		} catch (Exception $e) {
			die($e);
		}


		// Deletes this set of password reset tokens.
		try {
			$response = $cda->delete("pwd_reset",array("salt1"=>$salt1,"salt2"=>$salt2));
		} catch (Exception $e) {
			die($e);
		}

		// Emails new password.
		mailer_generalMessage("You", $reset['email'], "Lucy Password", "Your new password is <b>" . $password . "</b> you probably should change it after signing in again...");
		die("A new password has been mailed to you.  $password");
	}

	// If no email address was returned = Invalid tokens.
	else { die("Invalid reset tokens."); }
}

writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/forgot.php');
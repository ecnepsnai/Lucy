<?php
require("lucy-admin/session.php");
require("lucy-admin/sql.php");

// Obviously if the user is already signed in, we don't let them reset their own password.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// User requested a password reset.
if(isset($_POST['submit'])){
	$inp_email = addslashes(trim($_POST['email']));

	// Check to see if that email even exists in the database.
	$sql = "SELECT id, email FROM userlist WHERE email = '". $inp_email ."'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}
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
	$salt1 = addslashes($_GET['a']);
	$salt2 = addslashes($_GET['b']);
	$sql = "SELECT email FROM pwd_reset WHERE salt1 = '" . $salt1 . "' AND salt2 = '" . $salt2 . "'";
	try {
		$reset = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	// Checking for a returned email address.
	if(isset($reset['email'])) {
		// Generates a new salt and password for the user
		$salt = mt_rand(10, 99);
		$password = mt_rand(10, 99) . chr(97 + mt_rand(0, 25)) . mt_rand(1000, 9999) . chr(97 + mt_rand(0, 25));
		$hashed_password = md5($salt . md5($password));

		// Updates the userlist to have the new password.
		$sql = "UPDATE userlist SET password = '" . $hashed_password . "', salt = '" . $salt . "' WHERE email = '" . $reset['email'] . "';";
		try {
			sqlQuery($sql);
		} catch (Exception $e) {
			die($e);
		}

		// Deletes this set of password reset tokens.
		try {
			sqlQuery("DELETE FROM pwd_reset WHERE salt1 = '" . $salt1 . "' AND salt2 = '" . $salt2 . "'");
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
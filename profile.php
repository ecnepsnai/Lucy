<?php
require("lucy-admin/session.php");

// Requiring the CDA library.
require("lucy-admin/cda.php");

// Creating the CDA class.
$cda = new cda;
// Initializing the CDA class.
$cda->init($GLOBALS['config']['Database']['Type']);

// Obviously if the user is not signed in, we don't let them see a profile.
if(!$usr_IsSignedIn){
	header("Location: login.php?notice=login");
}

// Pulling the user information for the theme file
try{
	$response = $cda->select(array("name","email","verified"),"userlist",array("id"=>$usr_ID));
} catch (Exception $e) {
	die($e);
}

$user = $response['data'];

// If the form was submitted
if(isset($_POST['submit'])){

	// If the user requested to change their password
	if(isset($_POST['cur_password'])){
		$current_password = $_POST['cur_password'];

		// Checking that the two passwords match
		$new_password = $_POST['new_password'];
		$new_password_rep = $_POST['new_password_rep'];
		if($new_password != $new_password_rep){
			die("Passwords do not match.");
		}

		// Pulling the salt information from the userlist
		try{
			$response = $cda->select(array("salt"),"userlist",array("id"=>$usr_ID));
		} catch (Exception $e) {
			die($e);
		}
		$user = $response['data'];
		$hashed_current_password = md5($user['salt'] . md5($current_password));
		$salt = mt_rand(10, 99);
		$hashed_new_password = md5($salt . md5($new_password));

		// Changing the password only if the current password was correct
		try{
			$response = $cda->update("userlist",array("password"=>$hashed_new_password,"salt"=>$salt),array("password"=>$hashed_current_password));
		} catch (Exception $e) {
			die($e);
		}
	}

	// User only wanted to change their email
	if($user['email'] != $_POST['email']){
		try{
			$response = $cda->update("userlist",array("name"=>$_POST['name'],"email"=>$_POST['email'],"verified"=>0),array("id"=>$usr_ID));
		} catch (Exception $e) {
			die($e);
		}

	// User only wanted to change their name
	} else if($user['name'] != $_POST['name']){
		try{
			$response = $cda->update("userlist",array("name"=>$_POST['name']),array("id"=>$usr_ID));
		} catch (Exception $e) {
			die($e);
		}
	}
	header("location: index.php");
	die();
}
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/profile.php');
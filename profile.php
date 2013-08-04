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

if(isset($_POST['submit'])){
	if(isset($_POST['cur_password'])){
		$current_password = $_POST['cur_password'];
		$new_password = $_POST['new_password'];
		$new_password_rep = $_POST['new_password_rep'];
		if($new_password != $new_password_rep){
			die("Passwords do not match.");
		}
		try{
			$response = $cda->select(array("salt"),"userlist",array("id"=>$usr_ID));
		} catch (Exception $e) {
			die($e);
		}
		$user = $response['data'];
		$hashed_current_password = md5($user['salt'] . md5($current_password));
		$salt = mt_rand(10, 99);
		$hashed_new_password = md5($salt . md5($new_password));
		try{
			$response = $cda->update("userlist",array("password"=>$hashed_new_password,"salt"=>$salt),array("password"=>$hashed_current_password));
		} catch (Exception $e) {
			die($e);
		}
	}
}

try{
	$response = $cda->select(array("name","email"),"userlist",array("id"=>$usr_ID));
} catch (Exception $e) {
	die($e);
}

$user = $response['data'];
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/profile.php');
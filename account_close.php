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

if(isset($_POST['password'])){
	try{
		$response = $cda->select(array("salt"),"userlist",array("id"=>$usr_ID));
	} catch (Exception $e) {
		die($e);
	}
	$user = $response['data'];
	$hashed_password = md5($user['salt'] . md5($_POST['password']));
	try{
		$response = $cda->select(array("id"),"userlist",array("password"=>$hashed_password,"id"=>$usr_ID));
	} catch (Exception $e) {
		die($e);
	}
	if(!isset($response['data']['id'])){
		lucy_error('Incorrect Password','Please try again');
	} else {
		try{
			$response = $cda->delete("userlist",array("password"=>$hashed_password,"id"=>$usr_ID));
		} catch (Exception $e) {
			die($e);
		}
		session_start();
		session_unset();
		session_destroy();
		header("Location: index.php");
		die();
	}
}

writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/account_close.php');
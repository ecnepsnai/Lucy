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


try {
	$response = $cda->select(array("tf_secret"),"userlist",array("id"=>$usr_ID));
} catch (Exception $e) {
	die($e);
}

$user = $response['data'];

require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/auth_setup.php');
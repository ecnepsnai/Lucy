<?php
require("lucy-admin/session.php");

$login_error = False;

// Obviously if the user is already signed in, we don't let them log in again.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// User chose to login.
if(isset($_POST['submit'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Getting the raw inputs.
	$raw_email = trim($_POST['email']);
	if(empty($raw_email) || empty($_POST['pwd'])){
		goto writeDOC;
	}

	// Preparing the first sql request.
	$inp_email = $raw_email;
	// Ask for user salt first, then verify, THEN get rest of data
	try {
		$response = $cda->select(array("email, salt"),"userlist",array("email"=>$inp_email));
	} catch (Exception $e) {
		die($e);
	}
	$pw = $response['data'];
	$pw_hash = md5($pw['salt'] . md5(trim($_POST['pwd'])));

	// Preparing the second sql request.
	try {
		$response = $cda->select(array("id","name","type","email","tf_enable","tf_secret"),"userlist",array("email"=>$inp_email,"password"=>$pw_hash));
	} catch (Exception $e) {
		die($e);
	}
	$user = $response['data'];
	if(empty($user['id'])){
		$login_error = True;
	} else {

		// Testing to see if Two-Factor Authentication is Enabled
		if($user['tf_enable'] && $user['tf_secret'] != ""){
			session_start();
			$_SESSION['tf_secret'] = $user['tf_secret'];
			header("Location: auth.php");
		} else {
			// Creating the session data for the user.
			session_start();
			$_SESSION['id'] = $user['id'];
			$_SESSION['name'] = $user['name'];
			$_SESSION['type'] = $user['type'];
			$_SESSION['email'] = $user['email'];
			$_SESSION['LAST_ACTIVITY'] = time();

			// If there was a redirect parameter set, navigate to that url.  Will only work for local urls.
			if($_GET['rdirect']){
				header("Location: " . $_GET['rdirect']);
			}

			// Moves the user to the administrator dashboard if they are an admin
			if($user['type'] == 'Admin' || $user['type'] == "Agent"){
				header("Location: lucy-admin/ui/");
			} else {
				header("Location: dash.php");
			}
		}
	}
}
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/login.php');
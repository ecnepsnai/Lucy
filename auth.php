<?php
require("lucy-admin/session.php");
require("lucy-admin/sql.php");
require("lucy-admin/auth.php");
$auth_error = False;

// Obviously if the user is already signed in, we don't let them log in again.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// User entered a code.
if(isset($_POST['submit'])){

	$tf = new tfa;

	$codeIsValid = False;

	try{
		$codeIsValid = $tf->verifyCode($_SESSION['tf_secret'], $_POST['pin'], 1);
	} catch (Exception $e){
		die($e);
	}

	if(!$codeIsValid){
		$auth_error = true;
		goto writeDOC;
	}

	$sql = "SELECT id, name, type, email FROM userlist WHERE tf_secret = '". $_SESSION['tf_secret'] ."'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	// Creating the session data for the user.
	unset($_SESSION['tf_secret']);
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
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/auth.php');
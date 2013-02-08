<?php
	require_once("defines.php");
	require_once("mailer.php");
	$usr_IsSignedIn = False;
	session_start();
	if(isset($_SESSION['id']) || isset($_SESSION['name']) || isset($_SESSION['type'])){
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $GLOBALS['config']['SessionExpire'])) {
			session_unset();
			session_destroy();
		}
		$_SESSION['LAST_ACTIVITY'] = time();

		$usr_ID = $_SESSION['id'];
		$usr_Name = $_SESSION['name'];
		$usr_Email = $_SESSION['email'];
		$usr_Type = $_SESSION['type'];
		$usr_IsSignedIn = True;
		$GLOBALS['usr_IsSignedIn'] = True;
	}
	if(isset($_GET['t'])){
		echo("User is signed in: " . $usr_IsSignedIn);
		echo("<br/>User ID: " . $_SESSION['id']);
		echo("<br/>User Name: " . $_SESSION['name']);
		echo("<br/>User Email: " . $_SESSION['email']);
		echo("<br/>User Type: " . $_SESSION['type']);
	}
?>